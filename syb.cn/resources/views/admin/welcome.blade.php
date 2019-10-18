@extends('layouts.adminLayout')

@section('title')
    管理员 - 后台首页
@stop

@section('content')
    <section class="frame-header">
        <p> <i class="fa fa-home"></i> <a href="{{url('admin/home')}}" target="_parent">管理首页</a> » <mark> 使用帮助 </mark> </p>
    </section>
        <div class="panel panel-info well-box center-block" style="width: 60%;padding: 10px;">
            <p class="lead panel-heading">
               <i class="fa fa-chain-broken"></i> 使用帮助
            </p>
            <p>
                <i class="fa fa-circle-o-notch"></i> 点击 <mark>左侧链接</mark> 进入对应管理页面 <br>
                <i class="fa fa-circle-o-notch"></i> 点击页面右上角 <mark>[ 修改密码 ]</mark> 修改您的当前登陆密码 <br />
            </p>
            {{--<i class="fa fa-clock-o"></i>--}}
            {{--<time>--}}
                {{--现在时间：{{date('Y年m月d日 H时m分s秒')}}--}}
            {{--</time>--}}
        </div>
@stop