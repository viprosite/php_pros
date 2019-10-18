@extends('layouts.layout')

@section('title')
    SYB - 模拟测试
@stop

@section('content')
    <div class="container newsbox center-block" style="width: 85%;">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h5> <i class="fa fa-chain-broken"></i> 模拟测试 <small>点击下方某场次进行模拟练习</small> </h5>
            </div>
            @if(count($res) != 0)
                <ul class="list-group">
                    @for($i=0;$i<count($res);$i++)
                        <a href="{{url('mock_test_detail/'.$res[$i]->id)}}" >
                            <li class="list-group-item">
                                <mark>{{$res[$i]->add_time}}</mark> 组卷，
                                共判断题{{count($res[$i]->question_2_id)}}道，每道{{$res[$i]->score_2}}分；
                                单选题{{count($res[$i]->question_4_id)}}道，每道{{$res[$i]->score_4}}分；
                                完成时间{{$res[$i]->finish_time}}分钟内。
                            </li>
                        </a>
                    @endfor
                </ul>
            @else
                <p>
                    <i class="fa fa-info-circle"></i>
                    暂未提供考题。
                </p>
            @endif
            <div class="panel-footer">
                <br>
                @if(isset($history_exam) && count($history_exam) != 0)
                <p class=""> <i class="fa fa-history"></i> 模拟测试历史记录：</p>
                    <ul class="list-group text-warning">
                        @for($i=0;$i<count($history_exam);$i++)
                                <li class="list-group-item">
                                    <i class="fa fa-dot-circle-o"></i>
                                    <mark>{{$history_exam[$i]->submit_time}}</mark> &nbsp;&nbsp;&nbsp;&nbsp;
                                    <b>得分 {{$history_exam[$i]->grades}} 分</b>
                                </li>
                        @endfor
                    </ul>
                @else
                    <p>
                        <i class="fa fa-info-circle"></i>
                        暂无作答记录。
                    </p>
                @endif
            </div>
        </div>
    </div>
@stop