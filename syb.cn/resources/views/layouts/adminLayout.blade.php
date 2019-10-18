<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <link rel="stylesheet" href="/libs/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/libs/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/adminStyle.css">
    <link rel="stylesheet" href="\libs\jquery.growl.css">
</head>

@yield('content')


<script src="/js/jquery-3.3.1.min.js"></script>
<script src="/libs/bootstrap/js/bootstrap.min.js"></script>
<script src="/libs\jquery.growl.js"></script>
</body>
</html>
