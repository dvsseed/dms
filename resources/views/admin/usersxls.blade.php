<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <table>
                <tr>
                    <th colspan="5">{{ $header }}</th>
                </tr>
                <tr>
                    <th>ID</th>
                    <th>使用者</th>
                    <th>醫事機構代碼</th>
                    <th>登入Email</th>
                    <th>職務</th>
                </tr>
                @include('quality.list0')
            </table>
        </div>
    </div>
</div>
</body>

</html>