@extends('layouts.template_tv')

@section('style')
    <link rel="stylesheet" type="text/css" href="/css/tv/top.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css"/>
    <style>
        li#gn-2 {
            background-color: unset !important;
            border-bottom: none !important;
        }

        #gn-1 {
            background-color: #D07592 !important;
            border-bottom: solid 2px #d0366d !important;
        }

        #gn-1 a {
            opacity: 0.9 !important;
        }

        .bnr-onftr {
            display: block !important;
        }
        .pickup_wrap1 h1 {
            background: #cc3366;
            border-radius: 5px 5px 0 0;
            padding: 5px;
            color: #fff;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
        }
        .pickup_box1 {
            border: solid 1px #cc3366;
            padding: 10px;
        }
        .pickup_new li img {
            max-width: 100%;
        }
        .pickup_new li p img {
            width: 20px;
            padding-right: 2px;
            display: inline;
        }
        .pickup_new h3 {
            font-weight: bold;
        }
        .pickup_new p {
            font-size: 11px;
            letter-spacing: -1px;
        }
        .price_pickup {
            font-weight: bold;
            color: #FF0004;
            padding-left: 2px;
            font-size: 14px;
        }

        .slick-slide {
            margin: 0 5px;
            text-align: left;
        }
        .slick-prev {
            left: 10px;
            z-index: 2;
        }
        .slick-next {
            right: 10px;
        }
    </style>
@endsection

@section('content')
    @php
        $stt = 1;
    @endphp
    <div id="top-cvtv-lb">
        <div class="tclb-li"><a href="/tv/hd-list" target="_top" border="0"><img
                    src="/images/senbile/bnr-hd-20150420.png" width="768" height="64" alt="高画質HD配信" border="0"></a>
        </div>
    </div>
    <div id="side-r">
        <div class="ranking-head sprite">ダウンロードランキング</div>
        <div class="side-ranking" style="word-wrap: break-word;">
            <dl class="clearfix">
                @foreach($ranking as $list)
                    @if($list->product and $stt <= 10)
                        <dt class="ranking{{$stt}} sprite">ランキング {{$stt}}</dt>
                        @php
                            $stt = $stt + 1;
                            $img = cdn_image_path() . 'mihoudai/' . $list->product->product_code . '-m-thumb.jpg';
                        @endphp
                        <dd class="ranking-jacket"><a
                                href="/tv/{{clean($list->product->title)}}/de/{{$list->product->product_code}}/"
                                title="{{$list->product->title}} 白崎恭子"><img src="{{$img}}" width="148" height="212"
                                                                            alt="{{$list->product->title}} 白崎恭子"></a>
                        </dd>
                        <dd class="ranking-title"><a
                                href="/tv/{{clean($list->product->title)}}/de/{{$list->product->product_code}}/"
                                title="{{$list->product->title}} 白崎恭子">{{$list->product->title}}</a><br>
                            @foreach($list->product->actress as $act) <a href="/tv/{{$act->name}}/la/1/"
                                                                         title="「{{$act->name}}」が出演している作品を検索する"><strong>{{$act->name}}</strong></a> @endforeach
                        </dd>
                    @endif
                @endforeach
            </dl>
        </div>
        <div id="r-pickupact">
            <div class="side-title sprite-nohidden">新人女優</div>
            <div id="puact-content" class="clearfix">
                <ul class="side-act-img clearfix">
                    @for($j=0; $j < count($actress); $j ++)
                        <li class="li-01"><a href="/tv/{{$actress[$j]['name']}}/la/1/"> <img
                                    src="{{$actress[$j]['image']}}" alt="{{$actress[$j]['name']}}" width="100"
                                    height="100"><br>
                                {{$actress[$j]['name']}}<br>
                                <span class="puact-namekana">（{{$actress[$j]['name_kana']}}）</span> </a></li>
                    @endfor
                </ul>
            </div>
            <p id="full-actcheck"><a href="/tv/ac_list/search_aclist?key=new">»すべての女優を見る</a></p>
        </div>
    </div>
    <div id="c3-container" class="clearfix">
        <!-- ↓メイン部分 -->
        <div id="content">
        <!--
            <div class="toys-cmp" style="padding:0 0 20px">
                <a href="/tv/セール/lk/1">
                <img src="{{cdn_image_path()}}hangaku/summer-600-200.jpg" alt="サマーセール開催中！対象商品30%OFF！"
                     style="border:solid 1px #cccccc" width="100%"></a>
                <p>サマーセール開催中！対象商品30%OFF！</p>
            </div>
            -->
      @if(count($newProduct) > 0)
          <div class="pickup_wrap">
              <h1><img src="/images/tv/new.gif">最新作！{{date('n月j日', strtotime($newest))}}アップの先行配信！</h1>
              <div class="pickup_box">
                  <ul id="toplist-content">
                      @foreach($newProduct as $products)
                          @php
                              $avt = cdn_image_path() . 'mihoudai/' . $products->product_code . '-m-thumb.jpg';
                              $sample = cdn_image_path() . 'thumbs/' . $products->product_code . '/4x2.jpg';
                          @endphp
                          @if($products->hasHD($products))
                              <li class="title-section clearfix hd-list">
                                  <div class="hoveract">
                                      <p class="ts-title"><a
                                              href="/tv/{{ clean($products->title) }}/de/{{ $products->product_code }}/">{{$products->title}}</a>
                                      </p>
                                      <div class="jacket"><a
                                              href="/tv/{{ clean($products->title) }}/de/{{ $products->product_code }}/">
                                              <img src="{{$avt}}" width="280" height="400"
                                                   alt="{{$products->title}}"> @foreach($products->genres as $genre)
                                                  @if($genre->name == PRODUCT_DEBUT) <img
                                                      src="/images/mihoudai/newcommer1.gif" class="new-actress-icon"
                                                      width="72" height="32" alt="デビュー・初撮り作品"> @endif
                                              @endforeach </a></div>
                                  </div>
                                  <div class="section-right">
                                      <div
                                          class="pickup-image clearfix">
                                          @if(date('Y-m-d',strtotime($products->download_started_at)) > date('Y-m-d') and date('Y-m-d',strtotime($products->download_reservation_started_at)) <= date('Y-m-d'))
                                              <div class="senko-image"><a href="/tv/future/1/"><img
                                                          src="/images/senbile/senkou.png" alt="先行販売" width="130"
                                                          height="30"></a></div>
                                          @endif
                                          <div class="senko-date"><a
                                                  href="/tv/{{date('Ymd',strtotime($products->sale_started_at))}}/lr/1/">{{date('Y年m月d日',strtotime($products->sale_started_at))}}</a>発売
                                          </div>
                                      </div>
                                      <dl class="title-data clearfix">
                                          <dt class="actress">女優</dt>
                                          <dd class="actress"> @foreach($products->actress as $actress)
                                                  @if($actress->name) <a href="/tv/{{$actress->name}}/la/1"
                                                                         title="「{{$actress->name}}」が出演している動画を検索する">{{$actress->name}}</a>
                                                  &nbsp;
                                                  @endif
                                              @endforeach </dd>
                                          <dt class="partnumber">品番</dt>
                                          <dd class="partnumber">{{ $products->product_code }}</dd>
                                          <dt class="rectime">収録時間</dt>
                                          <dd class="rectime"><span>{{$products->movie_length}}分</span></dd>
                                          <dt class="label">レーベル</dt>
                                          <dd class="label"> @if($products->label) <a
                                                  href="/tv/{{ $products->label->name }}/ll/1/">{{ $products->label->name }}</a> @endif
                                          </dd>
                                          <dt class="price">価格</dt>
                                          <dd class="price"><span>{{ $products->price }}円</span>(税抜)</dd>
                                          <dt class="hd-price clearfix"><img src="/images/senbile/icon-hd_mini.png"
                                                                             width="24" height="16" alt="HD">価格
                                          </dt>
                                          <dd class="hd-price"><span>{{ $products->price_hd }}円</span>(税抜)</dd>
                                      </dl>
                                      <div class="sample-image"><a
                                              href="/tv/{{ clean($products->title) }}/de/{{ $products->product_code }}/"
                                              title="{{ $products->title }}"><img src="{{$sample}}" alt="の無料画像"
                                                                                  width="388"></a></div>
                                  </div>
                              </li>
                          @else
                              <li class="title-section clearfix">
                                  <div class="hoveract">
                                      <p class="ts-title"><a
                                              href="/tv/{{ clean($products->title) }}/de/{{ $products->product_code }}/">{{$products->title}}</a>
                                      </p>
                                      <div class="jacket"><a
                                              href="/tv/{{ clean($products->title) }}/de/{{ $products->product_code }}/">
                                              <img src="{{$avt}}" width="280" height="400"
                                                   alt="{{$products->title}}"> @foreach($products->genres as $genre)
                                                  @if($genre->name == PRODUCT_DEBUT) <img
                                                      src="/images/mihoudai/newcommer1.gif" class="new-actress-icon"
                                                      width="72" height="32" alt="デビュー・初撮り作品"> @endif
                                              @endforeach </a></div>
                                  </div>
                                  <div class="section-right">
                                      <div
                                          class="pickup-image clearfix"> @if(date('Y-m-d',strtotime($products->sale_started_at)) > date('Y-m-d') and date('Y-m-d',strtotime($products->sale_reservation_started_at)) <= date('Y-m-d'))
                                              <div class="senko-image"><a href="/tv/future/1/"><img
                                                          src="/images/senbile/senkou.png" alt="先行販売" width="130"
                                                          height="30"></a></div>
                                          @endif
                                          <div class="senko-date"><a
                                                  href="/tv/{{date('Ymd',strtotime($products->sale_started_at))}}/lr/1/">{{date('Y年m月d日',strtotime($products->sale_started_at))}}</a>発売
                                          </div>
                                      </div>
                                      <dl class="title-data clearfix">
                                          <dt class="actress">女優</dt>
                                          <dd class="actress"> @foreach($products->actress as $actress)
                                                  @if($products->actress) <a href="/tv/{{$actress->name}}/la/1"
                                                                             title="「{{$actress->name}}」が出演している動画を検索する">{{$actress->name}}</a>
                                                  &nbsp;
                                                  @endif
                                              @endforeach </dd>
                                          <dt class="partnumber">品番</dt>
                                          <dd class="partnumber">{{ $products->product_code }}</dd>
                                          <dt class="rectime">収録時間</dt>
                                          <dd class="rectime"><span>{{$products->movie_length}}分</span></dd>
                                          <dt class="label">レーベル</dt>
                                          <dd class="label"> @if($products->label) <a
                                                  href="/tv/{{ $products->label->name }}/ll/1/">{{ $products->label->name }}</a> @endif
                                          </dd>
                                          <dt class="price">価格</dt>
                                          <dd class="price"><span>{{ $products->price }}円</span>(税抜)</dd>
                                      </dl>
                                      <div class="sample-image"><a
                                              href="/tv/{{ clean($products->title) }}/de/{{ $products->product_code }}/"
                                              title="{{ $products->title }}"><img src="{{$sample}}" alt="の無料画像"
                                                                                  width="388"></a></div>
                                  </div>
                              </li>
                          @endif
                      @endforeach
                  </ul>
              </div>


                </div>
            @endif

            @if ($newMihouDaiProduct)
            <div class="pickup_wrap">
                <h1><img src="/images/tv/new.gif">2980円見放題プランの最新作！{{ Carbon\Carbon::create($newMihouDaiProduct->subscribe_started_at)->format('n月j日') }}配信！</h1>
                <div class="pickup_box">
                    <ul id="toplist-content">
                        @php
                            $avt = cdn_image_path() . 'mihoudai/' . $newMihouDaiProduct->product_code . '-m-thumb.jpg';
                            $sample = cdn_image_path() . 'thumbs/' . $newMihouDaiProduct->product_code . '/4x2.jpg';
                        @endphp

                        <li class="title-section clearfix">
                            <div class="hoveract">
                                <p class="ts-title"><a
                                        href="/mihoudai/{{ clean($newMihouDaiProduct->title) }}/de/{{ $newMihouDaiProduct->product_code }}/">{{$newMihouDaiProduct->title}}</a>
                                </p>
                                <div class="jacket"><a
                                        href="/mihoudai/{{ clean($newMihouDaiProduct->title) }}/de/{{ $newMihouDaiProduct->product_code }}/">
                                        <img src="{{$avt}}" width="280" height="400"
                                            alt="{{$newMihouDaiProduct->title}}"> @foreach($newMihouDaiProduct->genres as $genre)
                                            @if($genre->name == PRODUCT_DEBUT) <img
                                                src="/images/mihoudai/newcommer1.gif" class="new-actress-icon"
                                                width="72" height="32" alt="デビュー・初撮り作品"> @endif
                                        @endforeach </a></div>
                            </div>
                            <div class="section-right">
                                <div class="pickup-image clearfix">
                                    @if(date('Y-m-d',strtotime($newMihouDaiProduct->download_started_at)) > date('Y-m-d') and date('Y-m-d',strtotime($newMihouDaiProduct->download_reservation_started_at)) <= date('Y-m-d'))
                                        <div class="senko-image"><a href="/tv/future/1/"><img
                                                    src="/images/senbile/senkou.png" alt="先行販売" width="130"
                                                    height="30"></a></div>
                                    @endif
                                    <div class="senko-date">{{date('Y年m月d日',strtotime($newMihouDaiProduct->sale_started_at))}}発売
                                    </div>
                                </div>
                                <dl class="title-data clearfix">
                                    <dt class="actress">女優</dt>
                                    <dd class="actress"> @foreach($newMihouDaiProduct->actress as $actress)
                                            @if($actress->name) <a href="/mihoudai/{{$actress->name}}/la/1"
                                                                    title="「{{$actress->name}}」が出演している動画を検索する">{{$actress->name}}</a>
                                            &nbsp;
                                            @endif
                                        @endforeach </dd>
                                    <dt class="partnumber">品番</dt>
                                    <dd class="partnumber">{{ $newMihouDaiProduct->product_code }}</dd>
                                    <dt class="rectime">収録時間</dt>
                                    <dd class="rectime"><span>{{$newMihouDaiProduct->movie_length}}分</span></dd>
                                    <dt class="label">レーベル</dt>
                                    <dd class="label"> @if($newMihouDaiProduct->label) <a
                                            href="/mihoudai/{{ $newMihouDaiProduct->label->name }}/ll/1/">{{ $newMihouDaiProduct->label->name }}</a> @endif
                                    </dd>
                                </dl>
                                <div class="sample-image"><a
                                        href="/mihoudai/{{ clean($newMihouDaiProduct->title) }}/de/{{ $newMihouDaiProduct->product_code }}/"
                                        title="{{ $newMihouDaiProduct->title }}"><img src="{{$sample}}" alt="の無料画像"
                                                                            width="388"></a></div>
                            </div>
                        </li>

                    </ul>
                </div>
            </div>
            @endif

<!--
            <div class="pickup_wrap">
              <h1>
                <img src="/images/tv/new.gif">豪華福袋セット！人気作品15作品が1480円</h1>
              <div class="pickup_box">
                <ul id="toplist-content">
                  <li class="title-section clearfix">
                    <div class="hoveract">
                      <p class="ts-title">
                        <a href="/product-set/CVDA-042">大人になったらセンタービレッジ。福袋 巨乳爆乳中出しFUCK!!!怒涛の26時間30分 15作品1590分</a>
                      </p>
                      <div class="jacket">
                        <a href="/product-set/CVDA-042">
                          <img src="https://cdn.centervillage.co.jp/images/mihoudai/CVDA-042-m-thumb.jpg" width="280" height="400" alt="奇跡の六十路 内原美智子 Complete Best 36シーン22中出し28発射 11作品8時間2枚組">
                        </a>
                      </div>
                    </div>
                    <div class="section-right">
                      <div class="pickup-image clearfix">
                        <div class="senko-date">
                          2024年10月02日発売</div>
                      </div>
                      <dl class="title-data clearfix">
                        <dt class="partnumber">品番</dt>
                        <dd class="partnumber">CVDA-042</dd>
                        <dt class="rectime">収録時間</dt>
                        <dd class="rectime">
                          <span>1592分</span>
                        </dd>
                        <dt class="label">レーベル</dt>
                        <dd class="label">
                          大人になったらセンタービレッジ。
                        </dd>
                        <dt class="price">価格</dt>
                        <dd class="price">
                          <span>1480円</span>(税込)</dd>
                        <dt class="hd-price clearfix">
                      </dl>
                      <div class="sample-image">
                        <a href="/product-set/CVDA-042" title="巨乳！爆乳！やっぱりデカいおっぱいは正義！！そんな巨乳好きにお送りする福袋は、デビュー作もドラマもてんこ盛り！人気作品15タイトルをまるごとそのまま収録！センタービレッジならではのとろとろでふんわりした15人の熟れ乳たちと中出しSEXする怒涛の26時間！！1590分たっぷりお楽しみください！">
                          <img src="https://cdn.centervillage.co.jp/images/thumbs/CVDA-042/4x2.jpg" alt="の無料画像" width="388">
                        </a>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
-->
            <div class="toys-cmp" style="padding:0 0 20px">
                <a href="/tv/point.html">
                <img src="{{cdn_image_path()}}hangaku/point_pc.jpg" alt="センビレポイントキャンペーン"
                     style="border:solid 1px #cccccc" width="100%"></a>
                <p>銀行振込対応！ポイント購入でもっとお得にお買い物！</p>
            </div>



          @if(!$hasSubscription || $checkType == 3)
            <div class="toys-cmp" style="padding:0 0 20px">
                <a href="/register?banner=2980&qt=1">
                <img src="{{cdn_image_path()}}mihoudai/mihoudai01s.jpg" alt="新見放題サービス"
                     style="border:solid 1px #cccccc" width="100%"></a>
                <p>ラインナップが豊富！約1100作品が見放題！！</p>
            </div>
          @endif
          @if(!$hasSubscription || $checkType != 3)
            <div class="toys-cmp" style="padding:0 0 20px">
                <a href="/register?banner=1480&qt=2">
                <img src="{{cdn_image_path()}}mihoudai/mihoudai02s.jpg" alt="1480見放題サービス"
                     style="border:solid 1px #cccccc" width="100%"></a>
                <p>とにかく安く見たい！1480円見放題サービス開始！</p>
            </div>
        @endif

            @guest
            <!--
            <div class="toys-cmp" style="padding:0 0 20px">
                <a href="/registeruser">
                <img src="{{cdn_image_path()}}hangaku/point_740_245.jpg" alt="今なら新規会員登録で500ポイントGET！"
                     style="border:solid 1px #cccccc" width="100%"></a>
                <p>今なら新規会員登録で500ポイントGET！</p>
            </div>
            -->
            @endguest

            <!-- ▼センビレ見放題宣伝 -->
            <div class="pickup_wrap1" style="padding:0 0 20px">
                <h1><img src="/images/tv/new.gif"> 今週のおすすめ配信動画！！</h1>
                <div class="pickup_box1">
                    <ul class="pickup_new slider">
                        @foreach($recommend as $list)
                            @if($list->product)
                                <li><a href="/tv/-/de/{{$list->product->product_code}}"><img style="min-height: 249.5px;" src="{{cdn_image_path()}}mihoudai/{{$list->product->product_code}}-m-thumb.jpg"></a>
                                    <h3 style="height: 20px;overflow: hidden;">{{$list->product->title,60}}</h3>
                                    <p style="margin-left: 23px">価格<span class="price_pickup">{{number_format($list->product->price)}}円</span>（税込 {{ number_format($list->product->tax_price) == 0 ? number_format(floor($list->product->price * 1.1)) :  number_format($list->product->tax_price) }}円）</p>
                                    <p><img src="/images/senbile/btn-hd.png">価格<span class="price_pickup">{{number_format($list->product->price_hd)}}円</span>（税込 {{ number_format($list->product->tax_price_hd) == 0 ? number_format(floor($list->product->price_hd * 1.1)) :  number_format($list->product->tax_price_hd) }}円）</p>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="toys-cmp" style="padding:0 0 20px">
                <a href="/tv/discount/1">
                <img src="{{cdn_image_path()}}hangaku/one_dl.jpg" alt="1週間だけ半額キャンペーン"
                     style="border:solid 1px #cccccc" width="530" height="106"></a>
                <p>【毎週更新】1週間だけ半額キャンペーン！</p>
            </div>

            <div class="toys-cmp" style="padding:0 0 20px">
                <img src="{{cdn_image_path()}}hangaku/pc_ch.jpg" alt="旧作値下げ"
                     style="border:solid 1px #cccccc" width="530" height="106">
                <p>旧作の価格変更をしました！600円～</p>
            </div>


            <div id="newshead">
                <h1>熟女・人妻専門　センビレTV 最新情報</h1>
            </div>
            <div id="news">
                <!--第2木曜日に前月日付の行を消す-->
                <div id="news-list">
            <dl class="index-news clearfix">
                <dt>2024-2-21</dt>
                <dd>
                    <font color="#ff0000">【クレジットカード決済に関して】<br></font>
    現在、ご使用頂けるクレジットカードの種類は下記となります。<br>
    通信販売/動画見放題/ポイント購入：JCB<br>
    ダウンロード販売：Mastercard・JCB
                </dd>
            </dl>
                    <p style="margin-bottom:1em;font-size:14px;">■<a style="font-weight:bold;font-size:14px;" href="/tv/hd-list">センビレTV プレミアム画質HD動画配信!!</a></p>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-9-11">2026.9.11</time>
                        </dt><a href="/tv/-/de/JURA-214">初撮り人妻ドキュメント第二章 鷹宮麗華</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-9-10">2026.9.10</time>
                        </dt><a href="/tv/-/de/JRZE-322">初撮り五十路妻ドキュメント 青葉よしか</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-9-10">2026.9.10</time>
                        </dt>
                        <dd><a href="/tv/all/1/">»全作品一覧</a></dd>
                        <dd><a href="/tv/20260917/lr/1">»
                                <time datetime="2026-9-17">2026/9/17</time>
                                発売一覧</a></dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-9-9">2026.9.9</time>
                        </dt><a href="/tv/-/de/MEKO-413">試しにセンズリサポートしてよ！？ヌードモデルになって！？それとも…レンタル人妻さんへのエッチで過激な代行サービス依頼～追加で中出しSEXスペシャルセットもお願いしますッ！！～24人8時間2枚組</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-9-8">2026.9.8</time>
                        </dt><a href="/tv/-/de/MEKO-412">マッチングアプリナンパ中出し不倫SEX023</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-9-7">2026.9.7</time>
                        </dt><a href="/tv/-/de/TENH-25">突然ですが乳首弄ってもいいですか？〜巨乳コリコリ！貧乳びんびん！ちくびを摘んでコネて…ついでに素人熟女をチクイキ開発！！〜28人4時間</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-9-6">2026.9.6</time>
                        </dt><a href="/tv/-/de/CHERD-108">「初めてがおばさんと生じゃいやかしら？」童貞くんが人妻熟女と最高の筆下ろし性交 君佳詩織</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-9-5">2026.9.5</time>
                        </dt><a href="/tv/-/de/HONE-299">大好きな祖母にまさか童貞を捧げることになろうとは 春原早妃</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-9-4">2026.9.4</time>
                        </dt><a href="/tv/-/de/JRZE-321">初撮り六十路妻ドキュメント 小沢ゆき子</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-9-3">2026.9.3</time>
                        </dt><a href="/tv/-/de/JRZE-320">初撮り五十路妻ドキュメント 江南美沙子</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-9-3">2026.9.3</time>
                        </dt>
                        <dd><a href="/tv/all/1/">»全作品一覧</a></dd>
                        <dd><a href="/tv/20260917/lr/1">»
                                <time datetime="2026-9-17">2026/9/17</time>
                                発売一覧</a></dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-9-2">2026.9.2</time>
                        </dt><a href="/tv/-/de/IQQQX-05">声が出せない絶頂授業で10倍濡れる人妻教師DX5 10人4時間</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-8-31">2026.8.31</time>
                        </dt><a href="/tv/-/de/IRO-64">おっぱいが凄すぎて痴●がホイホイ寄ってくる爆乳人妻中出し電車 城ヶ崎百瀬</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-8-30">2026.8.30</time>
                        </dt><a href="/tv/-/de/EUUD-92">光原ほのかが浮世を忘れ本性むき出しでハメ倒す種付け混浴温泉旅行</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-8-29">2026.8.29</time>
                        </dt><a href="/tv/-/de/JURA-213">初撮り人妻ドキュメント第二章 森山あかり</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-8-28">2026.8.28</time>
                        </dt><a href="/tv/-/de/JRZE-319">初撮り人妻ドキュメント 波坂しのぶ</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-8-28">2026-8-28</time>
                        </dt>
                        <dd><a href="/tv/all/1/">»全作品一覧</a></dd>
                        <dd><a href="/tv/20260903/lr/1">»
                                <time datetime="2026-9-3">2026/9/3</time>
                                発売一覧</a></dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-8-26">2026.8.26</time>
                        </dt><a href="/tv/-/de/MEKO-411">「おばさんレンタル」サービスリターンズ123　お願いすればこっそり中出しセックスまでさせてくれるエロくて優しいおばさんともっとすげーセックスがしたくなったのでおかわりしてみた</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-8-26">2026.8.26</time>
                        </dt><a href="/tv/-/de/MEKO-410">「おばさんレンタル」サービスリターンズ122　お願いすればこっそり中出しセックスまでさせてくれるエロくて優しいおばさんともっとすげーセックスがしたくなったのでおかわりしてみた</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-8-25">2026.8.25</time>
                        </dt><a href="/tv/-/de/JRZDX-46">初撮り年鑑Vol.39～人となり・性事情まで奥さまのベールを脱がせるインタビュー映像を含む中出し60発射～30人8時間2枚組</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-8-24">2026.8.24</time>
                        </dt><a href="/tv/-/de/XMOM-122">デカチンに狂った欲求不満妻4時間</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-8-23">2026.8.23</time>
                        </dt><a href="/tv/-/de/XMOM-121">マッスル奥様はチ〇ポがお好き！ 山本かをり</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-8-23">2026.8.23</time>
                        </dt><a href="/tv/-/de/YOCH-035">年の離れたイケメンとはじめてのサシ飲み理性の限界を迎えた閉経おま◯こ妻がゴムなし生ハメで何度も激しく求め合いお好きな騎乗位でナマ精子を味わい尽くす御子柴美花52歳</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-8-22">2026.8.22</time>
                        </dt><a href="/tv/-/de/MESU-146">仕事は無能、夜は抜かずの超絶倫。女上司が堕ちた密着体位 三枝木玲実</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-8-22">2026.8.22</time>
                        </dt><a href="/tv/-/de/KAAD-87">我が家の美しい姑 和泉絹江</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-8-21">2026.8.21</time>
                        </dt><a href="/tv/-/de/JURA-212">初撮り人妻ドキュメント第二章 寺崎恭代</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-8-20">2026.8.20</time>
                        </dt><a href="/tv/-/de/JRZE-318">初撮り五十路妻ドキュメント 新堂かなえ</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-8-19">2026.8.19</time>
                        </dt>
                        <dd><a href="/tv/all/1/">»全作品一覧</a></dd>
                        <dd><a href="/tv/20260903/lr/1">»
                                <time datetime="2026-9-3">2026/9/3</time>
                                発売一覧</a></dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-8-19">2026.8.19</time>
                        </dt><a href="/tv/-/de/MESU-145">不倫発覚SNS拡散炎上！！美人女校長玩具化計画 八尋智香</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-8-15">2026.8.15</time>
                        </dt><a href="/tv/-/de/JURA-211">初撮り人妻ドキュメント第二章　雪平さやか</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-8-14">2026.8.14</time>
                        </dt><a href="/tv/-/de/JURA-210">初撮り人妻ドキュメント第二章　鳴宮史織</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-8-13">2026.8.13</time>
                        </dt><a href="/tv/-/de/JRZE-317">初撮り人妻ドキュメント　鷹宮麗華</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-8-13">2026.8.13</time>
                        </dt>
                        <dd><a href="/tv/all/1/">»全作品一覧</a></dd>
                        <dd><a href="/tv/20260820/lr/1">»
                                <time datetime="2026-8-20">2026/8/20</time>
                                発売一覧</a></dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-8-12">2026.8.12</time>
                        </dt><a href="/tv/-/de/MEKO-409">「おばさんレンタル」サービスリターンズDX5 20人8時間2枚組</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-8-11">2026.8.11</time>
                        </dt><a href="/tv/-/de/MEKO-408">マッチングアプリナンパ中出し不倫SEX022</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-8-9">2026.8.9</time>
                        </dt><a href="/tv/-/de/KEED-91">「こするだけなら浮気じゃないでしょ？」愛する彼女の家に一泊二日、妖艶すぎる母親の誘惑に抗えなかったボクは自責の念に駆られながらしこたま射精しまくった 君佳詩織</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-8-8">2026.8.8</time>
                        </dt><a href="/tv/-/de/KAAD-86">我が家の美しい姑 朝倉蓮</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-8-7">2026.8.7</time>
                        </dt><a href="/tv/-/de/JURA-209">初撮り五十路妻ドキュメント第二章 春原早妃</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-8-6">2026.8.6</time>
                        </dt><a href="/tv/-/de/JRZE-316">初撮り五十路妻ドキュメント 高丘千尋</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-8-20">2026.8.20</time>
                        </dt>
                        <dd><a href="/tv/all/1/">»全作品一覧</a></dd>
                        <dd><a href="/tv/20260820/lr/1">»
                                <time datetime="2026-8-20">2026/8/20</time>
                                発売一覧</a></dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-8-5">2026.8.5</time>
                        </dt><a href="/tv/-/de/HTHDX-32">友達の母親～最終章～DX Vol.25 10作品20SEX8時間2枚組</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-8-2">2026.8.2</time>
                        </dt><a href="/tv/-/de/FERA-213">母親がド派手ランジェリーを着けるのは息子を中出しに誘う欲求不満のサインです 城ヶ崎百瀬</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-8-1">2026.8.1</time>
                        </dt><a href="/tv/-/de/CHERD-107">「初めてがおばさんと生じゃいやかしら？」童貞くんが人妻熟女と最高の筆下ろし性交 光原ほのか</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-7-31">2026.7.31</time>
                        </dt><a href="/tv/-/de/EUUD-91">完熟・中出し浪漫秘湯 ドキッ！おばさんだらけの灼熱温泉大乱交SPECIAL</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-7-30">2026.7.30</time>
                        </dt><a href="/tv/-/de/JRZE-315">初撮り人妻ドキュメント 森山あかり</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-7-30">2026-7-30</time>
                        </dt>
                        <dd><a href="/tv/all/1/">»全作品一覧</a></dd>
                        <dd><a href="/tv/20260730/lr/1">»
                                <time datetime="2026-8-6">2026/8/6</time>
                                発売一覧</a></dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-7-26">2026.7.26</time>
                        </dt><a href="/tv/-/de/MEKO-407">「おばさんレンタル」サービスリターンズ121　お願いすればこっそり中出しセックスまでさせてくれるエロくて優しいおばさんともっとすげーセックスがしたくなったのでおかわりしてみた</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-7-25">2026.7.25</time>
                        </dt><a href="/tv/-/de/MEKO-406">「おばさんレンタル」サービスリターンズ120　お願いすればこっそり中出しセックスまでさせてくれるエロくて優しいおばさんともっとすげーセックスがしたくなったのでおかわりしてみた</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-7-24">2026.7.24</time>
                        </dt><a href="/tv/-/de/CVDX-651">羞恥の逸品！！熟女のアナル大鑑賞〜五十路六十路含む〜100人4時間</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-7-24">2026-7-24</time>
                        </dt>
                        <dd><a href="/tv/all/1/">»全作品一覧</a></dd>
                        <dd><a href="/tv/20260730/lr/1">»
                                <time datetime="2026-7-30">2026/7/30</time>
                                発売一覧</a></dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-7-23">2026.7.23</time>
                        </dt><a href="/tv/-/de/XMOM-120">淫らなドマゾ女たち　息子の嫁 隣人の奥さん 女●師 義理の母</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-7-22">2026.7.22</time>
                        </dt><a href="/tv/-/de/XMOM-119">美人淫乱妻の激イキファック4時間</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-7-20">2026.7.20</time>
                        </dt><a href="/tv/-/de/YOCH-034">熟女ま◯こ雑魚マン化若男たらしリモバイ漬け齢50歳。子◯ほど年の離れた若い男に街中で痴態を晒してトロマン化しちゃいました…美原すみれ</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-7-19">2026.7.19</time>
                        </dt><a href="/tv/-/de/HTHD-242">真・友達の母親　長澤史華</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-7-18">2026.7.18</time>
                        </dt><a href="/tv/-/de/HONE-298">大好きな祖母にまさか童貞を捧げることになろうとは　和泉絹江</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-7-17">2026.7.17</time>
                        </dt><a href="/tv/-/de/JURA-208">初撮り人妻ドキュメント第二章　樋口沙良</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-7-16">2026.7.16</time>
                        </dt><a href="/tv/-/de/JRZE-314">初撮り人妻ドキュメント　寺崎恭代</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-7-16">2026-7-16</time>
                        </dt>
                        <dd><a href="/tv/all/1/">»全作品一覧</a></dd>
                        <dd><a href="/tv/20260723/lr/1">»
                                <time datetime="2026-7-23">2026/7/23</time>
                                発売一覧</a></dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-7-5">2026.7.5</time>
                        </dt><a href="/tv/-/de/EUUD-90">極上熟女が卑猥な淫語とシチュエーションで貴方をダメにする最高の完全主観シコシコオナニーサポート　三枝木玲実</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-7-4">2026.7.4</time>
                        </dt><a href="/tv/-/de/JURA-207">初撮り五十路妻ドキュメント第三章　八尋智香</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-7-3">2026.7.3</time>
                        </dt><a href="/tv/-/de/JRZE-313">初撮り人妻ドキュメント　雪平さやか</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-7-2">2026.7.2</time>
                        </dt><a href="/tv/-/de/JRZE-312">初撮り人妻ドキュメント　鳴宮史織</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-7-2">2026-7-2</time>
                        </dt>
                        <dd><a href="/tv/all/1/">»全作品一覧</a></dd>
                        <dd><a href="/tv/20260716/lr/1">»
                                <time datetime="2026-7-16">2026/7/16</time>
                                発売一覧</a></dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-7-1">2026.7.1</time>
                        </dt><a href="/tv/-/de/MEKO-405">家事代行レンタルサービスを頼んだら…セクハラにも圧倒的エロポテンシャルで臨機応変に対応しちゃうボ〜ボ〜処理甘、剛毛、ぼさぼさマン毛家政婦おばさんに当たったラッキー中出しスペシャル　26人8時間2枚組</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-6-30">2026.6.30</time>
                        </dt><a href="/tv/-/de/MEKO-404">マッチングアプリナンパ中出し不倫SEX021</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-6-29">2026.6.29</time>
                        </dt><a href="/tv/-/de/TENH-24">ナンパにホイホイついてきちゃった素人熟女のセンズリ鑑賞！！お小遣い稼ぎにちょこっとモニタリングアルバイト。第3弾　50人4時間</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-6-28">2026.6.28</time>
                        </dt><a href="/tv/-/de/GOMU-37">大人になったらセンタービレッジ。2026年上半期BEST8時間2枚組　98タイトル全発射シーン完全収録！！</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-6-27">2026.6.27</time>
                        </dt><a href="/tv/-/de/CHERD-106">「初めてがおばさんと生じゃいやかしら？」童貞くんが人妻熟女と最高の筆下ろし性交　古村えりか</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-6-27">2026.6.27</time>
                        </dt><a href="/tv/-/de/JURA-206">初撮り五十路妻ドキュメント第三章　光原ほのか</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-6-26">2026.6.26</time>
                        </dt><a href="/tv/-/de/JURA-205">初撮り五十路妻ドキュメント第二章　朝倉蓮</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-6-25">2026.6.25</time>
                        </dt><a href="/tv/-/de/JRZE-311">初撮り五十路妻ドキュメント　春原早妃</a>
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt>■
                            <time datetime="2026-6-25">2026-6-25</time>
                        </dt>
                        <dd><a href="/tv/all/1/">»全作品一覧</a></dd>
                        <dd><a href="/tv/20260709/lr/1">»
                                <time datetime="2026-7-9">2026/7/9</time>
                                発売一覧</a></dd>
                    </dl>
                    </div>
                </div>
            <div id="pickup-bnr">
                <ul>
                    <!-- 2020618 毎週更新（先々週金曜日からの初撮り等を列挙・新規追加が上） -->
                    <li><a href="/tv/-/de/JRZE-320"><img
                                src="{{cdn_image_path()}}top/home/img_pub/JRZE-320_tv.jpg"
                                alt="江南美沙子"></a></li>
                    <li><a href="/tv/-/de/JRZE-318"><img
                                src="{{cdn_image_path()}}top/home/img_pub/JRZE-318_tv.jpg"
                                alt="新堂かなえ"></a></li>
                    <li><a href="/tv/-/de/JRZE-317"><img
                                src="{{cdn_image_path()}}top/home/img_pub/JRZE-317_tv.jpg"
                                alt="鷹宮麗華"></a></li>
                    <li><a href="/tv/-/de/JRZE-316"><img
                                src="{{cdn_image_path()}}top/home/img_pub/JRZE-316_tv.jpg"
                                alt="高丘千尋"></a></li>
                    <li><a href="/tv/-/de/JRZE-315"><img
                                src="{{cdn_image_path()}}top/home/img_pub/JRZE-315_tv.jpg"
                                alt="森山あかり"></a></li>
                    <li><a href="/tv/-/de/JRZE-314"><img
                                src="{{cdn_image_path()}}top/home/img_pub/JRZE-314_tv.jpg"
                                alt="寺崎恭代"></a></li>
                </ul>
            </div>
            <section>
                <h2 id="newtitle-head">センタービレッジ 最近公開した作品</h2>
                <ul id="toplist-content">
                    @foreach($product as $key => $products)
                    @if($products->product_code!=="CULL-15")
                        @if($key < 10)
                            @php
                                $avt = cdn_image_path() . 'mihoudai/' . $products->product_code . '-m-sogo.jpg';
                                $sample = cdn_image_path() . 'thumbs/' . $products->product_code . '/4x2.jpg';
                            @endphp
                            @if($products->hasHD($products))
                                <li class="title-section clearfix hd-list">
                                    <div class="hoveract">
                                        <p class="ts-title"><a
                                                href="/tv/{{ clean($products->title) }}/de/{{ $products->product_code }}/">{{$products->title}}</a>
                                        </p>
                                        <div class="jacket"><a
                                                href="/tv/{{ clean($products->title) }}/de/{{ $products->product_code }}/">
                                                <img src="{{$avt}}" width="280" height="400"
                                                     alt="{{$products->title}}"> @foreach($products->genres as $genre)
                                                    @if($genre->name == PRODUCT_DEBUT) <img
                                                        src="/images/mihoudai/newcommer1.gif" class="new-actress-icon"
                                                        width="72" height="32" alt="デビュー・初撮り作品"> @endif
                                                @endforeach </a></div>
                                    </div>
                                    <div class="section-right">
                                        <div
                                            class="pickup-image clearfix"> @if(date('Y-m-d',strtotime($products->download_started_at)) > date('Y-m-d') and date('Y-m-d',strtotime($products->download_reservation_started_at)) <= date('Y-m-d'))
                                                <div class="senko-image"><a href="/tv/future/1/"><img
                                                            src="/images/senbile/senkou.png" alt="先行販売" width="130"
                                                            height="30"></a></div>
                                            @endif
                                            <div class="senko-date"><a
                                                    href="/tv/{{date('Ymd',strtotime($products->sale_started_at))}}/lr/1/">{{date('Y年m月d日',strtotime($products->sale_started_at))}}</a>発売
                                            </div>
                                        </div>
                                        <dl class="title-data clearfix">
                                            <dt class="actress">女優</dt>
                                            <dd class="actress"> @foreach($products->actress as $actress)
                                                    @if($actress->name) <a href="/tv/{{$actress->name}}/la/1"
                                                                           title="「{{$actress->name}}」が出演している動画を検索する">{{$actress->name}}</a>
                                                    &nbsp;
                                                    @endif
                                                @endforeach </dd>
                                            <dt class="partnumber">品番</dt>
                                            <dd class="partnumber">{{ $products->product_code }}</dd>
                                            <dt class="rectime">収録時間</dt>
                                            <dd class="rectime"><span>{{$products->movie_length}}分</span></dd>
                                            <dt class="label">レーベル</dt>
                                            <dd class="label"> @if($products->label) <a
                                                    href="/tv/{{ $products->label->name }}/ll/1/">{{ $products->label->name }}</a> @endif
                                            </dd>
                                            <dt class="price">価格</dt>
                                            <dd class="price"><span>{{ $products->price }}円</span>(税抜)</dd>
                                            <dt class="hd-price clearfix"><img src="/images/senbile/icon-hd_mini.png"
                                                                               width="24" height="16" alt="HD">価格
                                            </dt>
                                            <dd class="hd-price"><span>{{ $products->price_hd }}円</span>(税抜)</dd>
                                        </dl>
                                        <div class="sample-image"><a
                                                href="/tv/{{ clean($products->title) }}/de/{{ $products->product_code }}/"
                                                title="{{ $products->title }}"><img src="{{$sample}}" alt="の無料画像"
                                                                                    width="388"></a></div>
                                    </div>
                                </li>
                            @else
                                <li class="title-section clearfix">
                                    <div class="hoveract">
                                        <p class="ts-title"><a
                                                href="/tv/{{ clean($products->title) }}/de/{{ $products->product_code }}/">{{$products->title}}</a>
                                        </p>
                                        <div class="jacket"><a
                                                href="/tv/{{ clean($products->title) }}/de/{{ $products->product_code }}/">
                                                <img src="{{$avt}}" width="280" height="400"
                                                     alt="{{$products->title}}"> @foreach($products->genres as $genre)
                                                    @if($genre->name == PRODUCT_DEBUT) <img
                                                        src="/images/mihoudai/newcommer1.gif" class="new-actress-icon"
                                                        width="72" height="32" alt="デビュー・初撮り作品"> @endif
                                                @endforeach </a></div>
                                    </div>
                                    <div class="section-right">
                                        <div
                                            class="pickup-image clearfix"> @if(date('Y-m-d',strtotime($products->download_started_at)) > date('Y-m-d') and date('Y-m-d',strtotime($products->download_reservation_started_at)) <= date('Y-m-d'))
                                                <div class="senko-image"><a href="/tv/future/1/"><img
                                                            src="/images/senbile/senkou.png" alt="先行販売" width="130"
                                                            height="30"></a></div>
                                            @endif
                                            <div class="senko-date"><a
                                                    href="/tv/{{date('Ymd',strtotime($products->sale_started_at))}}/lr/1/">{{date('Y年m月d日',strtotime($products->sale_started_at))}}</a>発売
                                            </div>
                                        </div>
                                        <dl class="title-data clearfix">
                                            <dt class="actress">女優</dt>
                                            <dd class="actress"> @foreach($products->actress as $actress)
                                                    @if($products->actress) <a href="/tv/{{$actress->name}}/la/1"
                                                                               title="「{{$actress->name}}」が出演している動画を検索する">{{$actress->name}}</a>
                                                    &nbsp;
                                                    @endif
                                                @endforeach </dd>
                                            <dt class="partnumber">品番</dt>
                                            <dd class="partnumber">{{ $products->product_code }}</dd>
                                            <dt class="rectime">収録時間</dt>
                                            <dd class="rectime"><span>{{$products->movie_length}}分</span></dd>
                                            <dt class="label">レーベル</dt>
                                            <dd class="label"> @if($products->label) <a
                                                    href="/tv/{{ $products->label->name }}/ll/1/">{{ $products->label->name }}</a> @endif
                                            </dd>
                                            <dt class="price">価格</dt>
                                            <dd class="price"><span>{{ $products->price }}円</span>(税抜)</dd>
                                        </dl>
                                        <div class="sample-image"><a
                                                href="/tv/{{ clean($products->title) }}/de/{{ $products->product_code }}/"
                                                title="{{ $products->title }}"><img src="{{$sample}}" alt="の無料画像"
                                                                                    width="388"></a></div>
                                    </div>
                                </li>
                            @endif
                        @endif
                        @endif
                    @endforeach
                </ul>
                @if(count($product) == 11)
                    <div id="tl-bottom-link"><a href="/tv/all/2/">»すべての作品を見る</a></div>
                @else
                    <div id="tl-bottom-link"><a href="/tv/all/1/">»すべての作品を見る</a></div>
                @endif </section>
        </div>
    </div>
<!--
    @include('inc.popup-mihoudai')
-->
@endsection
