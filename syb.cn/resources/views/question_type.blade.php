@extends('layouts.layout')

@section('title')
    SYB - 题型练习
@stop

@section('content')
    <div class="container newsbox center-block" style="width: 85%;">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h5> <i class="fa fa-chain-broken"></i> 题型练习</h5>
            </div>
            @if(count($res) != 0)
                <ul class="list-group">
                    @for($i=0;$i<count($res);$i++)
                        <li class="list-group-item">
                                @if($res[$i] == '2')
                                <a href="{{url('question_type/2')}}" target="">
                                    <i class="fa fa-dot-circle-o"></i>
                                    有 <mark>{{$num_2}}</mark> 道 &nbsp;&nbsp;&nbsp;<b>判断题</b>
                                </a>
                                    @elseif($res[$i] == '4')
                                <a href="{{url('question_type/4')}}" target="">
                                    <i class="fa fa-dot-circle-o"></i>
                                    有 <mark>{{$num_4}}</mark> 道 &nbsp;&nbsp;&nbsp;<b>单选题</b>
                                </a>
                                    @else
                                <a href="{{url('question_type/44')}}" target="">
                                    <i class="fa fa-dot-circle-o"></i>
                                    有 <mark>{{$num_44}}</mark> 道 &nbsp;&nbsp;&nbsp;<b>多选题</b>
                                </a>
                                @endif
                        </li>
                    @endfor
                </ul>
            @else
                <p>
                    &nbsp; <i class="fa fa-info-circle"></i>
                    暂未提供考题。
                </p>
            @endif
            <div class="panel-footer">
                <br>
                @if(isset($history_exam) && count($history_exam) != 0)
                <p class=""> <i class="fa fa-history"></i> 题型作答历史记录：</p>
                    <ul class="list-group text-warning">
                        @for($i=0;$i<count($history_exam);$i++)
                            <li class="list-group-item">
                                    <i class="fa fa-dot-circle-o"></i>
                                    <mark>{{$history_exam[$i]->exam_time}}</mark> &nbsp;&nbsp;&nbsp;&nbsp;
                                    @if($history_exam[$i]->question_type == '44')
                                        <b>多选题</b>&nbsp;&nbsp;&nbsp;&nbsp;
                                    @elseif($history_exam[$i]->question_type  == '4')
                                        <b>单选题</b>&nbsp;&nbsp;&nbsp;&nbsp;
                                    @else
                                        <b>判断题</b>&nbsp;&nbsp;&nbsp;&nbsp;
                                    @endif
                                        <b>答对 {{$history_exam[$i]->grade}} 道</b>
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