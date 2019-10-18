@extends('layouts.layout')

@section('title')
    SYB - 考试新闻
@stop

@section('content')
    <div class="container newsbox center-block" style="width: 85%;">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h5> <i class="fa fa-chain-broken"></i> 查看所有公告项</h5>
            </div>
            @if(count($news) != 0)
                <ul class="list-group">
                    @for($i=0;$i<count($news);$i++)
                        <li class="list-group-item">
                            <a href="{{url('newdetail/'.$news[$i]->id)}}" target="">
                                <i class="fa fa-dot-circle-o"></i>
                                <mark>{{$news[$i]->operator}}</mark>
                                <span>发布于：{{$news[$i]->add_at}}</span> &nbsp;&nbsp;
                                <span class="text-right">查看次数：{{$news[$i]->seenums}}</span> &nbsp;&nbsp;
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
@stop