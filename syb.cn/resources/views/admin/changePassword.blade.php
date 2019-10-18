@extends('layouts.adminLayout')

@section('title')
    管理员 - 修改密码
@endsection


@section('content')
    <section class="frame-header">
        <p> <i class="fa fa-home"></i> <a href="{{url('admin/home')}}" target="_parent">管理首页</a> » <mark> 修改密码 </mark> </p>
    </section>
    @if( $errors)
        @foreach($errors->all() as $error)
            <div class="mark top_msg2 center-block">
                <p>{{$error}}</p>
            </div>
        @endforeach
    @endif
    @if(session('top_msg'))
    <div class="alert alert-danger top-msg text-center" role="alert">
    {{session('top_msg')}}
    </div>
    @endif
    <div class="well change-well changepasswordtop">
        <p class="lead"> <i class="fa fa-chain-broken"></i> 修改密码</p>
        <form action="" method="post" class="form-horizontal">
            {{csrf_field()}}
            <div class="form-group">
                <label class="col-md-3 control-label">原密码：</label>
                <div class="col-md-7">
                    <input type="password" class="form-control" name="password_o" placeholder="请输入原密码" autofocus required/> <br>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label">新密码：</label>
                <div class="col-md-7">
                    <input type="password" class="form-control" name="password" required placeholder="新密码，6到12位 字母数字下划线组合"/> <br>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label">确认密码：</label>
                <div class="col-md-7">
                    <input type="password" class="form-control" name="password_confirmation" required placeholder="确认新密码"/> <br>
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-info col-md-3 col-md-offset-5"> <i class="fa fa-check-square-o"></i> 确认修改 </button>
            </div>
        </form>
    </div>
@endsection