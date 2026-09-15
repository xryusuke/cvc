// 月次売上集計スクリプト (centervillage.co.jp)
// 使い方: node getsuji.js <入力フォルダ> <出力xlsxパス>
// 入力フォルダには以下が必要:
//   data.csv                                    サイト側注文ログ
//   *.csv (オーダーNo,決済日時,E-mail,ID(sendid),決済金額 形式)   クレディックス生データ
//   transaction_*.xls (RESULTシート, SITE_TRANSACTION列など)      SUI生データ
//   shopSupportSettlementHistory_*.csv                            ビットキャッシュ生データ

const XLSX = require('xlsx');
const ExcelJS = require('exceljs');
const fs = require('fs');
const path = require('path');
const iconv = require('iconv-lite');

const FONT_NAME = '游ゴシック';
const DATETIME_NUMFMT = 'yyyy-mm-dd hh:mm:ss';

const excludedUserIds = new Set(
  JSON.parse(fs.readFileSync(path.join(__dirname, 'excluded_users.json'), 'utf8')).excludedUserIds
);

function readCp932Csv(filePath) {
  const buf = fs.readFileSync(filePath);
  const text = iconv.decode(buf, 'Shift_JIS').replace(/^﻿/, '').replace(/^\n/, '');
  const records = parseCsv(text).filter(r => r.some(c => c !== ''));
  const header = records[0];
  return records.slice(1).map(cols => {
    const obj = {};
    header.forEach((h, i) => obj[h] = cols[i]);
    return obj;
  });
}

// 引用フィールド内に改行(\r\n/\n)が含まれるケースに対応するため、
// 行単位ではなくテキスト全体を状態機械で解析する。
// (行単位分割だと、Itemsフィールド内に改行を含むタイトルがある場合に
//  レコードが2行に分断され、列がズレて壊れることを実データで確認した)
function parseCsv(text) {
  const records = [];
  let row = [], cur = '', inQ = false;
  for (let i = 0; i < text.length; i++) {
    const c = text[i];
    if (inQ) {
      if (c === '"') { if (text[i + 1] === '"') { cur += '"'; i++; } else inQ = false; }
      else cur += c;
    } else {
      if (c === '"') inQ = true;
      else if (c === ',') { row.push(cur); cur = ''; }
      else if (c === '\r') { /* skip */ }
      else if (c === '\n') { row.push(cur); cur = ''; records.push(row); row = []; }
      else cur += c;
    }
  }
  if (cur !== '' || row.length > 0) { row.push(cur); records.push(row); }
  return records;
}

function num(s) {
  if (s === undefined || s === null) return NaN;
  return parseFloat(String(s).replace(/[,円]/g, '').trim());
}

function toTime(s) {
  return new Date(String(s).replace(/\//g, '-').replace(' ', 'T')).getTime();
}

// ゲスト購入等でUser idが空のときは空文字のまま保持する(0にしない)
function uid(v) {
  return v === '' || v === undefined || v === null ? '' : Number(v);
}

// "YYYY-MM-DD HH:MM:SS" をExcelのシリアル値に変換する(1899-12-30起点、UTC計算でTZに依存しない)
function toExcelSerial(s) {
  const m = String(s).match(/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/);
  if (!m) return s;
  const [, y, mo, d, h, mi, se] = m.map(Number);
  const days = (Date.UTC(y, mo - 1, d) - Date.UTC(1899, 11, 30)) / 86400000;
  const frac = (h * 3600 + mi * 60 + se) / 86400;
  return days + frac;
}

// site log row群と生データ行群を金額でグルーピングし、各グループ内で
// 時刻順を保ったまま整列(DPによる時系列アラインメント、editdistance方式)する。
// サイトログと生データはどちらもほぼ投稿順(時系列順)に並ぶため、単純な
// 「時刻差が最小のペアから貪欲に割り当てる」方式だと、たまたま近い無関係な
// ペアを先に食ってしまい、その影響で他の正しいペアまで壊れることがある
// (ground truthとの突き合わせで確認済み)。時刻順を保つDPアラインメントなら
// 生データ側の余分な行(サイトログに存在しない決済)があっても、その1行だけを
// 未対応として飛ばし、前後の正しいペアには影響しない。
// 件数が完全一致するケースではground truth(202606)と100%一致することを確認済み。
const SKIP_COST = 10 * 60 * 1000; // 10分。これを超える時刻差の対応より、未対応として飛ばす方を優先する

function pairByAmountAndOrder(site, raw, amountKeySite, amountKeyRaw, dtKeySite, dtKeyRaw) {
  const byAmountSite = {}, byAmountRaw = {};
  site.forEach(r => (byAmountSite[r[amountKeySite]] ??= []).push(r));
  raw.forEach(r => (byAmountRaw[r[amountKeyRaw]] ??= []).push(r));
  const results = [];
  const warnings = [];
  for (const amt of Object.keys(byAmountSite)) {
    const sList = byAmountSite[amt].slice().sort((a, b) => toTime(a[dtKeySite]) - toTime(b[dtKeySite]));
    const rList = (byAmountRaw[amt] || []).slice().sort((a, b) => toTime(a[dtKeyRaw]) - toTime(b[dtKeyRaw]));
    if (sList.length !== rList.length) {
      warnings.push(`金額 ${amt}: サイトログ${sList.length}件 / 生データ${rList.length}件で件数不一致`);
    }
    const pairedRaw = alignByTime(sList, rList, dtKeySite, dtKeyRaw);
    for (let i = 0; i < sList.length; i++) results.push({ site: sList[i], raw: pairedRaw[i] });
  }
  return { results, warnings };
}

// 時系列順を保った編集距離アラインメント。match/skipSite/skipRawの3操作。
function alignByTime(sList, rList, dtKeySite, dtKeyRaw) {
  const n = sList.length, m = rList.length;
  const dp = Array.from({ length: n + 1 }, () => new Array(m + 1).fill(0));
  const back = Array.from({ length: n + 1 }, () => new Array(m + 1).fill(null));
  for (let i = 1; i <= n; i++) { dp[i][0] = dp[i - 1][0] + SKIP_COST; back[i][0] = 'skipSite'; }
  for (let j = 1; j <= m; j++) { dp[0][j] = dp[0][j - 1] + SKIP_COST; back[0][j] = 'skipRaw'; }
  for (let i = 1; i <= n; i++) {
    for (let j = 1; j <= m; j++) {
      const diff = Math.abs(toTime(sList[i - 1][dtKeySite]) - toTime(rList[j - 1][dtKeyRaw]));
      const matchCost = dp[i - 1][j - 1] + diff;
      const skipSiteCost = dp[i - 1][j] + SKIP_COST;
      const skipRawCost = dp[i][j - 1] + SKIP_COST;
      if (matchCost <= skipSiteCost && matchCost <= skipRawCost) { dp[i][j] = matchCost; back[i][j] = 'match'; }
      else if (skipSiteCost <= skipRawCost) { dp[i][j] = skipSiteCost; back[i][j] = 'skipSite'; }
      else { dp[i][j] = skipRawCost; back[i][j] = 'skipRaw'; }
    }
  }
  const pairedRaw = new Array(n).fill(null);
  let i = n, j = m;
  while (i > 0 || j > 0) {
    const op = back[i][j];
    if (op === 'match') { pairedRaw[i - 1] = rList[j - 1]; i--; j--; }
    else if (op === 'skipSite') { i--; }
    else { j--; }
  }
  return pairedRaw;
}

function loadSiteLog(inputDir, siteLogFileName) {
  return readCp932Csv(path.join(inputDir, siteLogFileName))
    .map((r, idx) => ({
      idx,
      userId: r['User id'],
      name: r['Name'],
      email: r['Email'],
      items: (r['Items'] || '').trim(),
      paymentMethod: r['Payment method'],
      purchaseType: r['Purchase type'],
      total: num(r['Total']),
      point: r['Point'],
      datetime: r['Datetime'],
    }))
    .filter(r => !excludedUserIds.has(String(r.userId)));
}

function buildCredixSheet(inputDir, siteLog, credixFileName) {
  const raw = readCp932Csv(path.join(inputDir, credixFileName)).map(r => ({
    orderNo: r['オーダーNo'],
    datetime: r['決済日時'],
    amount: num(r['決済金額']),
  }));
  const site = siteLog.filter(r => r.paymentMethod === 'クレディックス');
  const { results, warnings } = pairByAmountAndOrder(site, raw, 'total', 'amount', 'datetime', 'datetime');
  warnings.forEach(w => console.warn('[クレディックス] ' + w));
  const rows = results.map(({ site: s, raw: r }) => ([
    uid(s.userId), r ? r.orderNo : '(未突合)', s.name, s.email, s.items,
    'クレディックス', s.purchaseType, s.total, s.point, toExcelSerial(s.datetime),
  ]));
  rows.sort((a, b) => a[9] - b[9]);
  return [['User id', 'オーダーNo', 'Name', 'Email', 'Items', 'Payment method', 'Purchase type', 'Total', 'Point', 'Datetime'], ...rows];
}

function buildSuiSheet(inputDir, siteLog, suiFileName) {
  const wb = XLSX.readFile(path.join(inputDir, suiFileName));
  const raw = XLSX.utils.sheet_to_json(wb.Sheets['RESULT'], { defval: '' })
    .filter(r => r['RESULT_STATUS'] === 'sale')
    .map(r => ({ siteTransaction: r['SITE_TRANSACTION'], amount: num(r['SETL_AMNT']), datetime: r['SETL_DT'] }));
  const site = siteLog.filter(r => r.paymentMethod === 'SUI');
  const { results, warnings } = pairByAmountAndOrder(site, raw, 'total', 'amount', 'datetime', 'datetime');
  warnings.forEach(w => console.warn('[SUI] ' + w));
  const rows = results.map(({ site: s, raw: r }) => ([
    uid(s.userId), r ? r.siteTransaction : '(未突合)', s.name, s.email, s.items,
    'SUI', s.purchaseType, s.total, s.point, toExcelSerial(s.datetime),
  ]));
  rows.sort((a, b) => a[9] - b[9]);
  return [['User id', 'SITE_TRANSACTION', 'Name', 'Email', 'Items', 'Payment method', 'Purchase type', 'Total', 'Point', 'Datetime'], ...rows];
}

function buildBitcashSheet(inputDir, siteLog, bitcashFileName) {
  const raw = readCp932Csv(path.join(inputDir, bitcashFileName))
    .filter(r => r['決済種別（決済/リファンド）'] === '決済')
    .map(r => ({ transactionId: r['トランザクションID'], amount: num(r['金額']), datetime: r['決済（販売）日時'] }));
  const site = siteLog.filter(r => r.paymentMethod === 'ビットキャッシュ');
  const { results, warnings } = pairByAmountAndOrder(site, raw, 'total', 'amount', 'datetime', 'datetime');
  warnings.forEach(w => console.warn('[ビットキャッシュ] ' + w));
  const rows = results.map(({ site: s, raw: r }) => ([
    uid(s.userId), r ? r.transactionId : '(未突合)', s.name, s.email, s.items,
    'ビットキャッシュ', s.purchaseType, s.total, s.point, toExcelSerial(s.datetime),
  ]));
  rows.sort((a, b) => a[9] - b[9]);
  return [['User id', 'トランザクションID', 'Name', 'Email', 'Items', 'Payment method', 'Purchase type', 'Total', 'Point', 'Datetime'], ...rows];
}

// 銀振シートは手作業で注文者数を入力する運用。金額は数式(プラン×注文者数)で
// 自動計算されるようにしておく。この構成は実際に運用中のファイルから採取した形式。
function buildGinfuriSheet() {
  return {
    aoa: [
      ['プラン', '注文者数', '金額'],
      [1480, 0, 0],
      [2980, 0, 0],
      ['', '合計', 0],
      ['', '', ''],
      ['ポイント購入', '', ''],
      [1000, 0, 0],
      [3000, 0, 0],
      [5000, 0, 0],
      [10000, 0, 0],
      ['', '合計', 0],
    ],
    formulas: {
      C2: '17760*B2', C3: '35760*B3', C4: 'C2+C3',
      C7: 'A7*B7', C8: 'A8*B8', C9: 'A9*B9', C10: 'A10*B10', C11: 'SUM(C7:C10)',
    },
  };
}

function buildPointSheet(siteLog) {
  const rows = siteLog
    .filter(r => r.paymentMethod === 'ポイント')
    .sort((a, b) => a.datetime.localeCompare(b.datetime))
    .map(r => [uid(r.userId), r.name, r.email, r.items, 'ポイント', r.purchaseType, r.total + '円', r.point, toExcelSerial(r.datetime)]);
  return [['User id', 'Name', 'Email', 'Items', 'Payment method', 'Purchase type', 'Total', 'Point', 'Datetime'], ...rows];
}

// 2次元配列をexceljsのワークシートとして追加する。
// dateCol: Datetime列(1始まり)にExcelの日時書式を設定する。
// formulas: {"C2": "A2*B2"} 形式でセルに数式を設定する。
// 全セルに游ゴシックのフォントを適用する。
function addAoaSheet(wb, sheetName, aoa, { dateCol, formulas } = {}) {
  const ws = wb.addWorksheet(sheetName);
  ws.addRows(aoa);
  if (dateCol) {
    for (let r = 2; r <= aoa.length; r++) {
      const cell = ws.getCell(r, dateCol);
      if (typeof cell.value === 'number') cell.numFmt = DATETIME_NUMFMT;
    }
  }
  if (formulas) {
    for (const [addr, formula] of Object.entries(formulas)) {
      ws.getCell(addr).value = { formula };
    }
  }
  ws.eachRow({ includeEmpty: false }, row => {
    row.eachCell({ includeEmpty: false }, cell => {
      cell.font = { name: FONT_NAME };
    });
  });
}

async function main() {
  const [, , inputDir, outputPath] = process.argv;
  if (!inputDir || !outputPath) {
    console.error('使い方: node getsuji.js <入力フォルダ> <出力xlsxパス>');
    process.exit(1);
  }
  const files = fs.readdirSync(inputDir);
  const siteLogFile = files.find(f => /^data.*\.csv$/i.test(f));
  const credixFile = files.find(f => /^\d{6}\.csv$/.test(f));
  const suiFile = files.find(f => /^transaction_.*\.xls$/.test(f));
  const bitcashFile = files.find(f => /^shopSupportSettlementHistory_.*\.csv$/.test(f));
  if (!siteLogFile || !credixFile || !suiFile || !bitcashFile) {
    console.error('必要なファイルが見つかりません。', { siteLogFile, credixFile, suiFile, bitcashFile });
    process.exit(1);
  }

  const siteLog = loadSiteLog(inputDir, siteLogFile);

  const wb = new ExcelJS.Workbook();
  addAoaSheet(wb, 'クレディックス', buildCredixSheet(inputDir, siteLog, credixFile), { dateCol: 10 });
  addAoaSheet(wb, 'SUI', buildSuiSheet(inputDir, siteLog, suiFile), { dateCol: 10 });
  addAoaSheet(wb, 'ビットキャッシュ', buildBitcashSheet(inputDir, siteLog, bitcashFile), { dateCol: 10 });
  const ginfuri = buildGinfuriSheet();
  addAoaSheet(wb, '銀振', ginfuri.aoa, { formulas: ginfuri.formulas });
  addAoaSheet(wb, 'ポイント', buildPointSheet(siteLog), { dateCol: 9 });

  await wb.xlsx.writeFile(outputPath);
  console.log('出力:', outputPath);
}

main();
