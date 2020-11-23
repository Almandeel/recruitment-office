<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name') }} | الدخول</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dashboard/css/adminlte.min.css ')}}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/parsleyjs/parsley.min.css') }}">
    @if(!isset($ltr))
    <!-- RTL -->
    <link rel="stylesheet" href="{{ asset('dashboard/css/bootstrap-rtl.css') }}">
    <style>
        table.table-bordered.dataTable th:first-child,
        table.table-bordered.dataTable td:first-child {
            border-left-width: 0px !important;
            border-right-width: 0px !important;
        }
    
        table.table-bordered.dataTable th:last-child,
        table.table-bordered.dataTable td:last-child {
            border-right-width: 1px !important;
            border-left-width: 0px !important;
        }
    </style>
    @else
    <style>
        table.table-bordered.dataTable th:last-child,
        table.table-bordered.dataTable td:last-child {
            border-left-width: 1px !important;
            border-right-width: 0px !important;
        }
    
        table.table-bordered.dataTable th:first-child,
        table.table-bordered.dataTable td:first-child {
            border-right-width: 0px !important;
            border-left-width: 0px !important;
        }
    </style>
    @endif
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="/">{{ config('app.name') }}</a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">لوحة تسجيل الدخول</p>
            @include('partials.messages')
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group has-feedback">
                    <label for="username"><span class="fa fa-user form-control-feedback"></span> اسم الدخول</label>
                    <input id="username" name="username" type="text" class="form-control" placeholder="اسم الدخول" required autofocus>
                </div>
                <div class="form-group has-feedback">
                    <label for="password"><span class="fa fa-lock form-control-feedback"></span> كلمة المرور</label>
                    <input name="password" type="password" class="form-control" placeholder="كلمة المرور" min="6" required>
                </div>
                <div class="row">
                    <div class="col-8">
                        {{--  <div class="checkbox icheck">
                            <label>
                                <input name="remember" value="on" type="checkbox"> Remember Me
                            </label>
                        </div>  --}}
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">
                            <i class="fa fa-sign-in"></i>
                            <span>دخول</span>
                        </button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="{{ asset('dashboard/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('dashboard/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- iCheck -->
{{--  <script src="{{ asset('dashboard/plugins/iCheck/icheck.min.js') }}"></script>  --}}
<script src="{{ asset('dashboard/plugins/parsleyjs/parsley.js')}}"></script>
<script src="{{ asset('dashboard/plugins/parsleyjs/i18n/ar.js')}}"></script>
<script>
    $(function () {
        $('form').parsley();
        
        window.Parsley.on('form:success', function(){
            $('button[type="submit"]').attr('disabled', true)
        })
    })
</script>
</body>
</html>
