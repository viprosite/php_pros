@extends('layouts.layout')

@section('title')
    SYB - 考点练习
@stop

@section('content')
    <div class="container newsbox center-block" style="width: 85%;">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h5> <i class="fa fa-chain-broken"></i> 考点练习</h5>
            </div>
            @if(count($res) != 0)
                <ul class="list-group">
                    @for($i=0;$i<count($res);$i++)
                        <li class="list-group-item">
                            <a href="{{url('test_point/'.$res[$i])}}" target="">
                                <i class="fa fa-dot-circle-o"></i>
                                有 <mark>{{$num[$i]}}</mark> 道 &nbsp;&nbsp;&nbsp;
                                <b>{{$res[$i]}}</b>
                            </a>
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
                <p class=""> <i class="fa fa-history"></i> 考点作答历史记录：</p>
                @if(isset($history_exam) && count($history_exam) != 0)
                    <ul class="list-group text-warning">
                        @for($i=0;$i<count($history_exam);$i++)
                            <li class="list-group-item">
                                    <i class="fa fa-dot-circle-o"></i>
                                    <mark>{{$history_exam[$i]->exam_time}}</mark> &nbsp;&nbsp;&nbsp;&nbsp;
                                    <b>{{$history_exam[$i]->test_point}}</b>&nbsp;&nbsp;&nbsp;&nbsp;
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