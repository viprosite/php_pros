@extends('layouts.layout')

@section('title')
    SYB - 题型练习答案解析
@stop

@section('content')
    <div class="container newsbox center-block" style="width: 85%;">
        <div class="panel panel-info">
            <div class="panel-heading" style="display: flex;">
                    <span style="flex: 1;"> <i class="fa fa-chain-broken"></i>
                        <mark>模拟测试</mark> 答案
                    </span>
                    <span style="flex: 1;"> <i class="fa fa-list-alt"></i> <mark>得分<b> {{$grade}} </b>分</mark>  </span>
                    <span style="flex: 2;"> <i class="fa fa-info-circle"></i> 答对显示为 <b class="text-success">绿色</b>，答错显示为 <b class="text-danger">红色</b>  </span>
            </div>
            <div class="well">
                @if(isset($res) && $res != '[]')
                    <form class="form-horizontal" onsubmit="return false">
                            @for($i=0;$i<count($res);$i++)
                                    <li class="list-group-item">
                                        @if($ok[$i] == 1 )
                                            <i class="fa fa-smile-o"></i> <span style="background-color: green;color: white">  {{$i+1}}. {{$res[$i][0]->title}}</span> <span>
                                                你的答案：
                                                @if($answers[$i] == 'y')
                                                    <mark> ✔ </mark>
                                                @elseif($answers[$i] == 'n')
                                                    <mark> ✖ </mark>
                                                    @else
                                                    <mark>{{$answers[$i]}}</mark>
                                                @endif
                                            </span><br>
                                        @else
                                            <i class="fa fa-frown-o"></i> <span style="background-color: red;color: white">  {{$i+1}}. {{$res[$i][0]->title}}</span> <span>
                                                 你的答案：
                                                @if($answers[$i] == 'y')
                                                    <mark> ✔ </mark>
                                                @elseif($answers[$i] == 'n')
                                                    <mark> ✖ </mark>
                                                @else
                                                    <mark>{{$answers[$i]}}</mark>
                                                @endif
                                            </span> &nbsp;&nbsp;&nbsp;正确答案：<mark>
                                                @if($answer_ok[$i] == 'y')
                                                    <mark> ✔ </mark>
                                                @elseif($answer_ok[$i] == 'n')
                                                    <mark> ✖ </mark>
                                                @else
                                                    <mark>{{$answer_ok[$i]}}</mark>
                                                @endif
                                            </mark><br>
                                        @endif
                                    @if($res[$i][0]->question_type == 44)
                                        <span class="row">
                                            <label class="col-md-6">
                                             {{$res[$i][0]->answer_a}}
                                            </label>
                                            <label class="col-md-6" >
                                                 {{$res[$i][0]->answer_b}}
                                            </label>
                                        </span>

                                        <span class="row">
                                            <label class="col-md-6">
                                                 {{$res[$i][0]->answer_c}}
                                            </label>
                                            <label class="col-md-6" >
                                                 {{$res[$i][0]->answer_d}}
                                            </label>
                                        </span>
                                    @elseif($res[$i][0]->question_type == 4)
                                        <span class="row">
                                            <label class="col-md-6">
                                             {{$res[$i][0]->answer_a}}
                                            </label>
                                            <label class="col-md-6" >
                                                {{$res[$i][0]->answer_b}}
                                            </label>
                                        </span>

                                        <span class="row">
                                            <label class="col-md-6">
                                                {{$res[$i][0]->answer_c}}
                                            </label>
                                            <label class="col-md-6" >
                                                {{$res[$i][0]->answer_d}}
                                            </label>
                                        </span>
                                    @else

                                    @endif
                                </li>
                            @endfor
                                <br>
                        <a class="btn btn-primary col-md-3 col-md-offset-4" role="button" href="{{url('home')}}"> 返回首页 </a>
                    </form>
                @else
                    <p class="text-center lead">暂无成绩可供查看！</p>
                @endif
            </div>

        </div>
    </div>
    <script src="{{url('/js/jquery-3.3.1.min.js')}}"></script>
@endsection