@extends('layouts.adminLayout')

@section('title')
    管理员 - 查看已发公告
@endsection

@section('content')
    <section class="frame-header ">
        <p>
            <i class="fa fa-home"></i> <a href="{{url('admin/home')}}" target="_parent">管理首页</a> »
            <mark> <i class="fa fa-newspaper-o"></i> 查看已发公告 </mark>
        </p>
    </section>
    @if(session('top_msg'))
        <div class="alert alert-danger top-msg text-center" role="alert">
            {{session('top_msg')}}
        </div>
    @endif
    <div class="container changepasswordtop">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h5> <i class="fa fa-chain-broken"></i> 查看已发公告</h5>
            </div>
            <div class="panel-body">
                @if(count($news) != 0)
                    <ul class="list-group">
                        @for($i=0;$i<count($news);$i++)
                        <li class="list-group-item">
                            <a href="{{url('admin/newdetail/'.$news[$i]->id)}}" target="main">
                                <i class="fa fa-dot-circle-o"></i>
                                <span>发布时间：{{$news[$i]->add_at}}</span> &nbsp;&nbsp;
                                <b>{{$news[$i]->title}}</b>
                            </a>
                        </li>
                        @endfor
                    </ul>
                    @else
                    <p>
                        <i class="fa fa-info-circle"></i>
                        暂未发布任何公告，您可点击左侧 <mark>发布公告</mark> 菜单进行添加。
                    </p>
                @endif
            </div>

        </div>
    </div>


@endsection