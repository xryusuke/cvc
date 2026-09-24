// DVD流入集計スクリプト (centervillage.co.jp)
// 使い方: node dvd.js <入力フォルダ> <出力xlsxパス>
// 入力フォルダには dvd.xlsx (アクセスログの1列テキスト、ヘッダー無し) が必要。
// 各行から "c=dvdNN" を含む行だけを抽出し、日付とパラメータを分けて出力する。
// 例: "01/Jul/2026:02:43:59 /r?c=dvd07" → No, "01/Jul/2026:02:43:59", "dvd07"
// (202607分の実データで、生ログ→出力が1行残らず突合できることを確認済みの方式)

const ExcelJS = require('exceljs');
const XLSX = require('xlsx');
const fs = require('fs');
const path = require('path');

const FONT_NAME = '游ゴシック';

function extractDvdRows(inputDir, dvdFileName) {
  const wb = XLSX.readFile(path.join(inputDir, dvdFileName));
  const lines = XLSX.utils.sheet_to_json(wb.Sheets[wb.SheetNames[0]], { header: 1, defval: '' })
    .map(r => String(r[0] || ''));

  const rows = [];
  for (const line of lines) {
    const m = line.match(/^(\S+)\s+.*c=(dvd\d+)/);
    if (!m) continue;
    rows.push([rows.length + 1, m[1], m[2]]);
  }
  return rows;
}

async function main() {
  const [, , inputDir, outputPath] = process.argv;
  if (!inputDir || !outputPath) {
    console.error('使い方: node dvd.js <入力フォルダ> <出力xlsxパス>');
    process.exit(1);
  }
  const files = fs.readdirSync(inputDir);
  const dvdFile = files.find(f => /^dvd\.xlsx$/i.test(f));
  if (!dvdFile) {
    console.error('dvd.xlsxが見つかりません。');
    process.exit(1);
  }

  const rows = extractDvdRows(inputDir, dvdFile);
  const aoa = [['No', '日付', 'パラメータ'], ...rows];

  const wb = new ExcelJS.Workbook();
  const ws = wb.addWorksheet('Sheet1');
  ws.addRows(aoa);
  ws.eachRow({ includeEmpty: false }, row => {
    row.eachCell({ includeEmpty: false }, cell => {
      cell.font = { name: FONT_NAME };
    });
  });

  await wb.xlsx.writeFile(outputPath);
  console.log('出力:', outputPath, `(${rows.length}件)`);
}

main();
