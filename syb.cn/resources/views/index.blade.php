@extends('layouts.layout')

@section('title')
    SYB创业培训在线考试系统首页
@stop

@section('content')
<div class="container newsbox">
    <div class="well well-lg" id="login-box">
        {{--<div>--}}
            {{--<img src="{{url('/imgs/logo-ilo.jpg')}}" id="login-img" class="img-responsive img-thumbnail center-block"/>--}}
        {{--</div>--}}
        @if(session('msg'))
            <div class="alert alert-danger" role="alert">{{session('msg')}}</div>
        @endif
        <div>
            <span class="h5 login-title center-block col-md-offset-2"> <i class="fa fa-sign-in" aria-hidden="true"></i> 用户登陆</span>
            <hr />
            <form class="form-horizontal" action="" method="post">
                {{csrf_field()}}
                {{--<div class="form-group">--}}
                    {{--<label class="col-md-3 control-label">姓名:</label>--}}
                    {{--<div class="col-md-8 input-group">--}}
                        {{--<span class="input-group-addon"> <i class="fa fa-user-circle-o"></i></span>--}}
                        {{--<input type="text" class="form-control" placeholder="姓名" required>--}}
                    {{--</div>--}}
                {{--</div>--}}
                <div class="form-group">
                    <label class="col-md-3 control-label">学号:</label>
                    <div class="col-md-8 input-group">
                        <span class="input-group-addon"> <i class="fa fa-fw fa-sort-numeric-asc"></i></span>
                        <input type="number" name="student_id" class="form-control" placeholder="学号" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">密码:</label>
                    <div class="col-md-8 input-group">
                        <span class="input-group-addon"> <i class="fa fa-fw fa-key"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="密码" required>
                    </div>
                    <span class="help-block col-md-offset-4 pass-help">6-12位 字母[a-z A-Z] 数字[0-9] 下划线_ 的组合</span>
                </div>
                <div class="form-group" id="code-box">
                    <label class="col-md-3 control-label">验证码:</label>
                    <div class="col-md-5 col-xs-6 col-sm-8 input-group">
                        <span class="input-group-addon"> <i class="fa fa-fw fa-info-circle"></i></span>
                        <input type="text" name="code" class="form-control" placeholder="验证码" >
                    </div>
                    <img class="code img-rounded" src="{{url('code')}}" alt="验证码" title="点击可更换" onclick="this.src='{{url('code')}}?'+Math.random()">​​​​
                </div>
                <div class="form-group">
                    <div class="col-md-10 col-md-offset-4 col-sm-10 col-sm-offset-3 col-xs-10 col-xs-offset-2">
                        <button type="submit" class="btn btn-success"> <i class="fa fa-sign-in"></i> 登陆 </button>
                        <span class="col-xs-offset-1 col-sm-offset-2 col-md-offset-1"></span>
                        <button type="reset" class="btn btn-warning col-md-offset-2"> <i class="fa fa-mail-reply"></i> 重置 </button>
                    </div>
                </div>

               <div class="form-group">
                   <div class="col-md-10 col-md-offset-5">
                       <a href="{{url('register')}}" class="regToLogin"> <i class="fa fa-lg fa-hand-o-right" style="color: #666;"></i> 还没注册？点击我</a>
                       <br><br>
                           <mark><b><a href="{{url('admin')}}" class=""> <i class="fa fa-lg fa-desktop" style="color: #666;padding-left: 5%"></i> 管理员登陆</a></b></mark>
                   </div>
               </div>
            </form>
        </div>
    </div>
</div>
@stop