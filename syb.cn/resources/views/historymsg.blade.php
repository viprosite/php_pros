@extends('layouts.layout')

@section('title')
    SYB - 我的历史消息
@stop

@section('content')
    <div class="container newsbox center-block" style="width: 85%;">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h5> <i class="fa fa-chain-broken"></i> 查看所有消息</h5>
            </div>
            @if(count($msgs) != 0)
                <ul class="list-group">
                    @for($i=0;$i<count($msgs);$i++)
                        <li class="list-group-item" id="{{$msgs[$i]->id}}">
                                @if($msgs[$i]->viewor == 1)
                                <a href="{{url('msgdetail/'.$msgs[$i]->id)}}" target="">
                                    <span class="text-success"><i class="fa fa-check-square-o"></i> 已读</span>
                                    <span> <mark>{{$msgs[$i]->sender}}</mark> 发送于：{{$msgs[$i]->send_time}}</span> &nbsp;&nbsp;
                                    <b>{{$msgs[$i]->title}}</b>
                                </a> &nbsp;&nbsp;&nbsp;
                                <button class="btn btn-sm btn-danger delmsgbtn" data-toggle="modal" data-target="#delModal" data-id="{{$msgs[$i]->id}}">删除</button>
                                @else
                                <a href="{{url('msgdetail/'.$msgs[$i]->id)}}" target="">
                                    <i class="fa fa-dot-circle-o"></i>
                                    <span> <mark>{{$msgs[$i]->sender}}</mark> 发送于：{{$msgs[$i]->send_time}}</span> &nbsp;&nbsp;
                                    <b>{{$msgs[$i]->title}}</b>
                                </a>
                                 @endif
                        </li>
                    @endfor
                </ul>
            @else
                <p>
                    &nbsp;&nbsp;<i class="fa fa-info-circle"></i>
                    暂无消息。
                </p>
            @endif
        </div>
    </div>
    {{--删除消息模态框--}}
    <div class="modal fade" id="delModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title text-danger" id="myModalLabel">
                        <i class="fa fa-trash"></i> 删除提示
                    </h4>
                </div>
                <div class="modal-body">
                    <h5 class="text-center">
                        您确认删除该消息？
                    </h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger del-ok" data-id="" >删除</button>
                    <button type="button" class="btn btn-default delStudent" data-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
    </div>
    <script src="{{url('/js/jquery-3.3.1.min.js')}}"></script>
    <script>
        $(function () {
            $('.delmsgbtn').click(function () {
                let msg_id = $(this).attr('data-id')
                $('.del-ok').attr('data-id',msg_id)
            })
            $('.del-ok').click(function () {
                let msg_id = $(this).attr('data-id')
                $.post('delmsg/'+msg_id,{'_token':"{{csrf_token()}}"},function (res) {
                    if(res == 'y'){
                        $('#delModal, .modal-backdrop').hide()
                        $.growl.notice({
                            title: "删除提示",
                            message: "删除成功!"
                        });
                        $('#'+msg_id).remove()
                    }
                })
            })
        })
    </script>
@stop