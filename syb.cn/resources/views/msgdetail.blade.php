@extends('layouts.layout')
@section('title')
    前台 - 查看消息详细内容
@endsection

@section('content')

    @if(session('top_msg'))
        <div class="alert alert-danger top-msg text-center" role="alert">
            {{session('top_msg')}}
        </div>
    @endif
    <div class="container changepasswordtop">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h5> <i class="fa fa-chain-broken"></i> 查看消息详细内容</h5>
            </div>
            <div class="panel-body">
                <h4 class="text-center title">
                    {{$msg[0]->title}} <br />
                    <small>
                       <mark> {{$msg[0]->sender}} </mark>
                        发送于： {{$msg[0]->send_time}} &nbsp;&nbsp;
                    </small>
                </h4>
                <pre>{!! $msg[0]->content !!}</pre>
            </div>
        </div>
    </div>

@endsection