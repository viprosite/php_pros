@extends('layouts.layout')

@section('title')
    SYB - 考点练习答案解析
@stop

@section('content')
    <div class="container newsbox center-block" style="width: 85%;">
        <div class="panel panel-info">
            <div class="panel-heading" style="display: flex;">
                <span style="flex: 1;"> <i class="fa fa-chain-broken"></i> <mark><b>{{$res[0]->test_point}}</b></mark> 答案 </span>
                    <span style="flex: 1;"> <i class="fa fa-list-alt"></i> <mark>答对<b> {{$grade}} </b>个</mark>  </span>
                    <span style="flex: 2;"> <i class="fa fa-info-circle"></i> 答对显示为 <b class="text-success">绿色</b>，答错显示为 <b class="text-danger">红色</b>  </span>
            </div>
            <div class="well">
                @if(isset($res) && $res != '[]')
                    <form class="form-horizontal" onsubmit="return false">
                            @for($i=0;$i<count($res);$i++)
                                    <li class="list-group-item">
                                        @if($ok[$i] == 1 )
                                            <i class="fa fa-smile-o"></i> <span style="background-color: green;color: white">  {{$i+1}}. {{$res[$i]->title}}</span> <span>
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
                                            <i class="fa fa-frown-o"></i> <span style="background-color: red;color: white">  {{$i+1}}. {{$res[$i]->title}}</span> <span>
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
                                    @if($res[$i]->question_type == 44)
                                        <span class="row">
                                            <label class="col-md-6">
                                             {{$res[$i]->answer_a}}
                                            </label>
                                            <label class="col-md-6" >
                                                 {{$res[$i]->answer_b}}
                                            </label>
                                        </span>

                                        <span class="row">
                                            <label class="col-md-6">
                                                 {{$res[$i]->answer_c}}
                                            </label>
                                            <label class="col-md-6" >
                                                 {{$res[$i]->answer_d}}
                                            </label>
                                        </span>
                                    @elseif($res[$i]->question_type == 4)
                                        <span class="row">
                                            <label class="col-md-6">
                                             {{$res[$i]->answer_a}}
                                            </label>
                                            <label class="col-md-6" >
                                                {{$res[$i]->answer_b}}
                                            </label>
                                        </span>

                                        <span class="row">
                                            <label class="col-md-6">
                                                {{$res[$i]->answer_c}}
                                            </label>
                                            <label class="col-md-6" >
                                                {{$res[$i]->answer_d}}
                                            </label>
                                        </span>
                                    @else

                                    @endif
                                </li>
                            @endfor
                                <br>
                    </form>
                @else
                    <p class="text-center lead">没有该考点！</p>
                @endif
            </div>

        </div>
    </div>
    {{--超时模态框--}}
    <div class="modal fade in" id="timeoutModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title text-danger" id="myModalLabel">
                        <i class="fa fa-exclamation-circle"></i> 考试时间提示
                    </h4>
                </div>
                <div class="modal-body">
                    <h5 class="text-center">
                        考试超时，已自动交卷！
                    </h5>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade in hidden"></div>
    <script src="{{url('/js/jquery-3.3.1.min.js')}}"></script>
    <script>

    </script>
@endsection