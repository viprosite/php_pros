@extends('layouts.adminLayout')
@section('title')
    管理员 - 查看公告详情
@endsection

@section('content')
    <section class="frame-header ">
        <p>
            <i class="fa fa-home"></i> <a href="{{url('admin/home')}}" target="_parent">管理首页</a> »
            <mark> <i class="fa fa-newspaper-o"></i> 查看公告详情 </mark>
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
                <h5> <i class="fa fa-chain-broken"></i> 查看公告详情</h5>
            </div>
            <div class="panel-body">
                <h4 class="text-center title">
                    {{$new[0]->title}} <br />
                    <small>
                       <mark> {{$new[0]->operator}} </mark>
                        发布于： {{$new[0]->add_at}} &nbsp;&nbsp;
                        查看次数：{{$new[0]->seenums}}
                    </small>
                </h4>
                <pre>{!! $new[0]->content !!}</pre>
            </div>
        </div>
    </div>

@endsection