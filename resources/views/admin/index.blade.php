<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta id="token" name="csrf-token" content="{{ csrf_token() }}">
    <!-- meta name="csrf-token" content="{{-- csrf_token() --}}" -->

    <title>DMClinicYU</title>

    <!-- Fonts -->
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="/lte/js/bootstrap-3.3.5/css/bootstrap.min.css">
    <!-- Bootstrap 3.3.6 -->
    <!-- Bootstrap 3.3.7 -->
    <!-- link rel="stylesheet" href="/lte/css/bootstrap.min.css" -->
    <!-- link rel="stylesheet" href="/lte/js/bootstrap-3.3.7/css/bootstrap.min.css" -->
    <!-- link rel="stylesheet" href="/lte/css/bootstrap.css" -->
    <!-- link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/css/bootstrap.min.css" integrity="sha384-AysaV+vQoT3kOAXZkl02PThvDr8HYKPZhNT5h/CXfBThSRXQ6jW5DO2ekP5ViFdi" crossorigin="anonymous" -->
    <!-- Latest compiled and minified CSS -->
    <!-- link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous" -->
    <!-- Optional theme -->
    <!-- link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous" -->
    <!-- link rel="stylesheet" href="/lte/css/bootstrap-theme.css" -->
    <!-- link rel="stylesheet" href="/lte/css/bootstrap-theme.min.css" -->
    <link rel="stylesheet" href="/lte/js/bootstrap-3.3.5/css/bootstrap-theme.min.css">
    <!-- link rel="stylesheet" href="/lte/js/bootstrap-3.3.7/css/bootstrap-theme.min.css" -->

    <!-- Pnotify -->
    <!-- link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pnotify/3.0.0/pnotify.css" -->
    <!-- link rel="stylesheet" href="/css/pnotify.buttons.css" -->
    <link rel="stylesheet" href="/lte/css/pnotify.css">
    <link rel="stylesheet" href="/lte/css/pnotify.buttons.css">
    <!-- Font Awesome -->
    <!-- link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" -->
    <link rel="stylesheet" href="/lte/css/font-awesome.min.css">
    <!-- Sweetalert -->
    <!-- link rel="stylesheet" href="https://cdn.jsdelivr.net/sweetalert2/4.0.5/sweetalert2.css" -->
    <link rel="stylesheet" href="/lte/css/sweetalert2.min.css">
    <!-- Theme style -->
    <!-- link rel="stylesheet" href="/lte/css/AdminLTE.min.css" -->
    <link rel="stylesheet" href="/lte/js/AdminLTE-2.4.0/dist/css/AdminLTE.min.css">
    <!-- link rel="stylesheet" href="/lte/css/AdminLTE.css" -->
    <link rel="stylesheet" href="/lte/css/skin-blue.min.css">
    <!-- Datetime Picker -->
    <!-- link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.css" -->
</head>
<body class="skin-blue sidebar-mini">

<script>
    {{-- TODO: Remove else when check is done with middleware --}}
    @if(Auth::user())
        const User = {!! Fractal::includes(['role'])->item(Auth::user(), new \App\Transformers\UserTransformer)->getJson() !!};
        User.isAdmin = false;
        // User.isAdmin = {{-- Auth::user()->isAdmin() --}};
        // User.isAdmin = {{-- Auth::user()->role_level == 9 --}};
    @else
        const User = { isAdmin: false };
    @endif
</script>

<script type="text/javascript" src="/lte/js/html2canvas.js"></script>
{{-- jQuery --}}
<!-- script src="/lte/js/jQuery-2.2.0.min.js"></script -->
<!-- script src="/lte/js/jquery-3.1.0.min.js"></script -->
<script src="/lte/js/jquery-3.2.1.min.js"></script>
{{-- pnotify --}}
<!-- script src="https://cdnjs.cloudflare.com/ajax/libs/pnotify/3.0.0/pnotify.js"></script -->
<script type="text/javascript" src="/lte/js/pnotify.js"></script>
<script type="text/javascript" src="/lte/js/pnotify.buttons.js"></script>
{{-- Sweetalert --}}
<!-- script src="https://cdn.jsdelivr.net/sweetalert2/4.0.5/sweetalert2.min.js"></script -->
<script type="text/javascript" src="/lte/js/sweetalert2.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="/lte/js/bootstrap-3.3.5/js/bootstrap.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<!-- script src="/lte/js/bootstrap.js"></script -->
<!-- script src="/lte/js/bootstrap-3.3.7/js/bootstrap.min.js"></script -->
<!-- script src="/lte/js/bootstrap.min.js"></script -->
<!-- Bootstrap v4.0.0-alpha.5 -->
<!-- script src="/lte/js/tether.min.js"></script -->
<!-- script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js" integrity="sha384-BLiI7JTZm+JWlgKa0M0kGRpJbF2J8q+qreVrKBC47e3K6BW78kGLrCkeRX6I9RoK" crossorigin="anonymous"></script -->
<!-- Latest compiled and minified JavaScript -->
<!-- script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script -->
<!-- Moment -->
<!-- script src="https://cdn.bootcss.com/moment.js/2.10.6/moment-with-locales.min.js"></script -->
<!-- script src="https://cdn.bootcss.com/moment-timezone/0.4.0/moment-timezone-with-data.min.js"></script -->
<script src="/lte/js/moment-with-locales.min.js"></script>
<script src="/lte/js/moment-timezone-with-data.min.js"></script>
<!-- ChartJS 1.0.1 -->
<!-- script src="/lte/js/AdminLTE-2.3.7/plugins/chartjs/Chart.min.js"></script -->
<!-- script src="/lte/js/AdminLTE-2.4.0/plugins/chartjs/Chart.min.js"></script -->
<script src="/lte/js/AdminLTE-2.4.0/plugins/chartjs/Chart.min.js"></script>
<!-- FastClick -->
<!-- script src="/lte/js/AdminLTE-2.3.7/plugins/fastclick/fastclick.js"></script -->
<script src="/lte/js/AdminLTE-2.4.0/plugins/fastclick/fastclick.min.js"></script>
{{-- AdminLTE js --}}
<script src="/lte/js/app.min.js"></script>
<!-- script src="/lte/js/app.min.js"></script -->
<!-- Datetime Picker -->
<!-- script src="https://cdn.bootcss.com/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script -->
<!-- JavaScripts -->
<script type="text/javascript" src="{{ asset('/js/main.js') }}" charset="utf-8"></script>

<!-- Live Reload -->
@if ( Config::get('app.debug') )
    <script type="text/javascript">
    //    document.write('<script src="//192.168.0.8:35729/livereload.js?snipver=1" type="text/javascript"><\/script>')
    </script>
@endif
</body>
</html>
