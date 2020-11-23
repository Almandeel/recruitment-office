<!DOCTYPE html>
<html lang="ar">

    <head>
        <meta charset="utf-8">
        <title>{{ env('APP_NAME') }} {{ isset($title) ? ' - ' . $title : '' }}</title>
        <link rel="stylesheet" href="{{ asset('dashboard/css/print.css') }}" media="all" />
        <link rel="stylesheet" href="{{ asset('dashboard/css/awesome.min.css') }}" media="all" />
        <style>
            .foot .separator{
                font-weight: bold;
                font-size: 1rem;
            }
        </style>
    </head>

    <body>
        <div class="tools-bar">
            <button onclick="window.print()" class="print_btn">
                <i class="fa fa-print"></i>
                طباعة</button>

            <button onclick="location.reload()" class="print_btn">
                <i class="fa fa-refresh"></i>
                تحديث</button>


            <a href="{{ isset($prev_url) ? $prev_url : (request()->has('prev_url') ? request('prev_url') : url()->previous()) }}" class="print_btn">
                <i class="fa fa-reply"></i>
                عودة</a>
            <a href="{{ route('home') }}" class="print_btn">
                <i class="fa fa-home"></i>
                الرئيسية</a>
        </div>
        <div class="buttons-group">
            <button type="button" class="button-group" onClick="window.print()" title="طباعة">
                <span>طباعة</span>
                <i class="fa fa-print fa-lg"></i>
            </button>
            <button onclick="location.reload()" class="button-group">
                <span>تحديث</span>
                <i class="fa fa-refresh"></i>
            </button>

            <button onclick="window.location.href = '{{ isset($prev_url) ? $prev_url : (request()->has('prev_url') ? request('prev_url') : url()->previous()) }}'" class="button-group">
                <span>عودة</span>
                <i class="fa fa-reply"></i>
            </button>

            <button onclick="window.location.href = '{{ route('home') }}'" class="button-group">
                <span>الرئيسية</span>
                <i class="fa fa-home"></i>
            </button>
        </div>
        <div class="header">
            <div id="logo">
                <img src="{{ asset('dashboard/img/logo.png') }}">
            </div>
            @isset($heading)
                <div class="heading">{!! $heading !!}</div>
            @endisset
            <div id="company" class="p-15">
                <h2 class="name">{{ config('reports.company_name') }}</h2>
                <div>{!! str_replace(',', '<br/>', config('reports.company_address')) !!}</div>
                <div class="ltr">{{ config('reports.company_phone') }}</div>
                <div class="ltr"><a href="mailto:{{ config('reports.company_email') }}">{{ config('reports.company_email') }}</a></div>
            </div>
        </div>
        <div class="footer">
            <div class="footer-extra">
                @stack('footer-extra')
            </div>
            <div class="foot">
                {{--  <p>{{ env('APP_NAME') }} - {{ date('Y/m/d') }}</p>  --}}
                <p>
                    <span>{{ config('reports.company_name') }}</span>
                    <span class="separator">|</span>
                    <span>{{ config('reports.company_address') }}</span>
                    <span class="separator">|</span>
                    <span>{{ config('reports.company_phone') }}</span>
                    <span class="separator">|</span>
                    <span>{{ config('reports.company_email') }}</span>
                    {{--  <span class="separator">|</span>
                    <span>{{ date('Y/m/d') }}</span>  --}}
                </p>
            </div>
        </div>

        <table class="layout">
            <thead>
                <tr>
                    <td>
                        <div class="t-head">&nbsp;</div>
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="content">
                            @stack('content')
                            {{--  <div class="page">PAGE 1 <small>(empty)</small></div>
                            <div class="page">PAGE 2 <small>(empty)</small></div>
                            <div class="page"></div>  --}}
                    </td>
                </tr>
            </tbody>
            </div>
            <tfoot>
                <tr>
                    <td>
                        <div class="t-foot">&nbsp;</div>
                    </td>
                </tr>
            </tfoot>
        </table>
        @php
            /*
        <header class="clearfix">
            <div id="logo">
                <img src="{{ asset('img/ldashboard/ogo.jpg') }}">
            </div>
            <div id="company">
                <h2 class="name">{{ config('reports.company_NAME') }}</h2>
                <div>{{ config('reports.company_ADDRESS') }}</div>
                <div>{{ config('reports.company_PHONE') }}</div>
                <div><a href="mailto:{{ config('reports.company_EMAIL') }}">{{ config('reports.company_EMAIL') }}</a></div>
            </div>
            </div>
        </header>
        <main>
            @stack('content')
            {{--
            <table border="0" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th class="no">#</th>
                        <th class="desc">DESCRIPTION</th>
                        <th class="unit">UNIT PRICE</th>
                        <th class="qty">QUANTITY</th>
                        <th class="total">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="no">01</td>
                        <td class="desc">
                            <h3>Website Design</h3>Creating a recognizable design solution based on the company's
                            existing visual identity
                        </td>
                        <td class="unit">$40.00</td>
                        <td class="qty">30</td>
                        <td class="total">$1,200.00</td>
                    </tr>
                    <tr>
                        <td class="no">02</td>
                        <td class="desc">
                            <h3>Website Development</h3>Developing a Content Management System-based Website
                        </td>
                        <td class="unit">$40.00</td>
                        <td class="qty">80</td>
                        <td class="total">$3,200.00</td>
                    </tr>
                    <tr>
                        <td class="no">03</td>
                        <td class="desc">
                            <h3>Search Engines Optimization</h3>Optimize the site for search engines (SEO)
                        </td>
                        <td class="unit">$40.00</td>
                        <td class="qty">20</td>
                        <td class="total">$800.00</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2"></td>
                        <td colspan="2">SUBTOTAL</td>
                        <td>$5,200.00</td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <td colspan="2">TAX 25%</td>
                        <td>$1,300.00</td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <td colspan="2">GRAND TOTAL</td>
                        <td>$6,500.00</td>
                    </tr>
                </tfoot>
            </table>
            <div id="thanks">Thank you!</div>
            <div id="notices">
                <div>NOTICE:</div>
                <div class="notice">A finance charge of 1.5% will be made on unpaid balances after 30 days.</div>
            </div>
            --}}
        </main>
        <footer>
            اي تعديل اوكشط يلغي هذا التقرير
        </footer>
            */
        @endphp
        <script src="{{ asset('dashboard/plugins/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('dashboard/js/snippts.js') }}"></script>
        @isset($models_js)
            @foreach ($models_js as $model)
                <script src="{{ asset('dashboard/js/models/' . $model .'.js') }}"></script>
            @endforeach
            @php
                $accounts = isset($accounts) ? $accounts : Modules\Accounting\Models\Account::allToJson();
                $year = isset($year) ? $year : year();
                $year = is_null($year) ? ['id' => null] : $year;
            @endphp
            <script>
                const accounts = new Accounts({!! $accounts !!}, {{ $year['id'] }});
            </script>
        @endisset
        @stack('scripts')
        <script>
            @php
                $auto_print = isset($auto_print) ? $auto_print : true;
            @endphp
            @if ($auto_print)
                window.print();
            @endif
        </script>
    </body>
</html>
