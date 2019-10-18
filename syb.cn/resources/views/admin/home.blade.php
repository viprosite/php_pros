@extends('layouts.adminLayout')

@section('title')
    管理员 - 后台首页
@stop

@section('content')
<div>
    <header>
        <nav id="nav">
            <h4 class="header-text pull-left">SYB 后台管理</h4>
            <ul class="header-nav pull-left" >
                <li><a href="{{url('admin/home')}}"  class="active">首页</a></li>
                <li><a href="{{url('admin/seenews')}}" target="main">查看已发公告</a></li>
                {{--<li><a href="#">管理页</a></li>--}}
            </ul>
            <ul class="header-nav pull-right">
                <li><span style="color: #aaa;border-bottom: 1px solid #cccccc;"><i class="fa fa-history"></i> 上次登陆时间：{{session('last')}}</span> </li> &nbsp;&nbsp;
                <li id="admin-name">管理员：{{session('admin')}}</li>
                <li><a href="{{url('admin/changePassword')}}" target="main">修改密码</a></li>
                <li><a href="{{url('admin/logout')}}">退出</a></li>
            </ul>
        </nav>
    </header>

    <main >
        <div id="left-nav" class="">
            <ul class="nav nav-stacked">
                <li class="text-center"><a href="{{url('admin/addnews')}}" target="main"> <i class="fa fa-fw fa-newspaper-o"></i> 发布公告 </a></li>
                <li class="text-center"><a href="{{url('admin/studentm')}}" target="main"> <i class="fa fa-fw fa-group"></i> 考生管理 </a></li>
                <li class="text-center"><a href="{{url('admin/pullin')}}" target="main"> <i class="fa fa-fw fa-tasks"></i> 导入题库</a></li>
                <li class="text-center"><a href="{{url('admin/questionbank')}}" target="main"> <i class="fa fa-fw fa-book"></i> 题库管理</a></li>
                <li class="text-center"><a href="{{url('admin/testpoint')}}" target="main"> <i class="fa fa-fw fa-check-square"></i> 考点管理</a></li>
                <li  class="text-center"><a href="{{url('admin/addexam')}}" target="main"> <i class="fa fa-cogs fa-fw"></i> 组卷系统</a></li>
                <li  class="text-center"><a href="{{url('admin/addquestions')}}" target="main"> <i class="fa fa-cog fa-fw"></i> 出题系统</a></li>
                <li class="text-center"><a href="{{url('admin/gradem')}}" target="main"> <i class="fa fa-fw fa-bar-chart"></i> 成绩统计</a></li>
                {{--<li class="text-center"><a href="#"> <i class="fa fa-cog fa-fw fa-spin"></i> 系统设置</a></li>--}}
            </ul>
        </div>




        <div id="iframe-box">
            <iframe src="{{url('admin/welcome')}}" frameborder="0" width="100%" height="100%" name="main"></iframe>
        </div>
    </main>

    <footer id="footer">
        <p>
            CopyRight © 2019. 西南交大 计算机系15级  毕业设计
        </p>
    </footer>

</div>
@stop
