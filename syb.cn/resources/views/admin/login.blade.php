<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>管理员 登陆</title>
    <link rel="stylesheet" href="/libs/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/libs/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/adminStyle.css">
    <link rel="stylesheet" href="/css/mediaQuery.css">
</head>
<body style="background-color: #eee">
    <div class="container">
       <div class="logo-text center-block">
           <img class="img-responsive img-thumbnail" src="{{url('/imgs/logo-ilo.jpg')}}" alt="logo" id="admin-logo" /> <br />
           <span id="logo-right-text" class="text-center">SYB 创业培训   在线考试系统  <br /> 管理员登陆</span>

           <form action="" class="form-horizontal" method="post">
               {{csrf_field()}}
               @if(session('msg'))
                  <div class="center-block">
                      <span class="alert alert-danger text-center col-md-9 col-md-pull-2" role="alert">{{session('msg')}}</span> <br /><br />
                  </div>
               @endif
               <div class="form-group">
                   <div class="input-group col-md-4 ">
                       <span class="input-group-addon"> <i class="fa fa-user-circle-o fa-fw"></i> </span>
                       <input type="text" class="form-control"  name="admin_name" placeholder="管理员用户名" required>
                   </div>​​
               </div>
               <div class="form-group">
                   <div class="input-group col-md-4 ">
                       <span class="input-group-addon"> <i class="fa fa-lock fa-fw"></i> </span>
                       <input type="password" class="form-control" name="admin_password" placeholder="管理员密码" required>
                   </div>​​
               </div>
               <div class="form-group" id="code-box">
                   <div class="input-group col-md-2">
                       <span class="input-group-addon"> <i class="fa fa-check-square-o fa-fw"></i> </span>
                       <input type="text" class="form-control" name="admin_code" placeholder="验证码" required>
                   </div>​​
                   <img class="img-rounded" id="code" src="{{url('code')}}" alt="验证码" title="点击可更换" onclick="this.src='{{url('code')}}?'+Math.random()">​​​​
               </div>
                <div class="form-group">
                    <button class="btn btn-info col-md-4 col-xs-6 col-sm-5"> 立即登陆 </button>
                </div>
           </form>

       </div>

    </div>
</body>