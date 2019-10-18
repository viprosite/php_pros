@extends('layouts.layout')

@section('title')
    SYB - 考点练习
@stop

@section('content')
    <div class="container newsbox center-block" style="width:85%">
        <div class="panel panel-info">
            <div class="panel-heading" style="display: flex;">
               @if($res[0]->question_type == 2)
                    <span style="flex: 1;"> <i class="fa fa-chain-broken"></i> <mark><b>判断题</b></mark> 练习</span>
                @elseif($res[0]->question_type == 4)
                    <span style="flex: 1;"> <i class="fa fa-chain-broken"></i> <mark><b>单选题</b></mark> 练习</span>
                @else
                    <span style="flex: 1;"> <i class="fa fa-chain-broken"></i> <mark><b>多选题</b></mark> 练习</span>
                @endif

            </div>
            <div class="well">
                @if(isset($res) && $res != '[]')
                    <form action="{{url('check_question_type')}}" class="form-horizontal" method="post">
                        {{csrf_field()}}
                        <input type="hidden" name="question_type" value="{{$res[0]->question_type}}">
                            @for($i=0;$i<count($res);$i++)
                                <li class="list-group-item">
                                    {{$i+1}}. {{$res[$i]->title}} <br>
                                    @if($res[$i]->question_type == 44)
                                        <span class="row">
                                            <label class="col-md-6">
                                            <input  name="{{$res[$i]->title}}[]" value="a" type="checkbox" required>{{$res[$i]->answer_a}}
                                            </label>
                                            <label class="col-md-6" >
                                                <input name="{{$res[$i]->title}}[]" value="b" type="checkbox" required>{{$res[$i]->answer_b}}
                                            </label>
                                        </span>

                                        <span class="row">
                                            <label class="col-md-6">
                                                <input  name="{{$res[$i]->title}}[]" value="c" type="checkbox" required>{{$res[$i]->answer_c}}
                                            </label>
                                            <label class="col-md-6" >
                                                <input name="{{$res[$i]->title}}[]" value="d" type="checkbox" required>{{$res[$i]->answer_d}}
                                            </label>
                                        </span><br><br>
                                        @elseif($res[$i]->question_type == 4)
                                        <span class="row">
                                            <label class="col-md-6">
                                            <input  name="{{$res[$i]->title}}" value="a" type="radio">{{$res[$i]->answer_a}}
                                            </label>
                                            <label class="col-md-6" >
                                                <input name="{{$res[$i]->title}}" value="b" type="radio" required>{{$res[$i]->answer_b}}
                                            </label>
                                        </span>

                                        <span css="row"radio>
                                            <label class="col-md-6">
                                                <input  name="{{$res[$i]->title}}" value="c" type="radio" required>{{$res[$i]->answer_c}}
                                            </label>
                                            <label class="col-md-6" >
                                                <input name="{{$res[$i]->title}}" value="d" type="radio" required>{{$res[$i]->answer_d}}
                                            </label>
                                        </span><br><br>
                                        @else
                                        <span class="row">
                                            <label class="col-md-6">
                                            <input  name="{{$res[$i]->title}}" value="y" type="radio" required> ✔
                                            </label>
                                            <label class="col-md-6" >
                                                <input name="{{$res[$i]->title}}" value="n" type="radio" required> ✖
                                            </label>
                                        </span>
                                    @endif
                                </li>
                            @endfor
                                <br>
                        <span class="row" style="display: flex;justify-content: center">
                            <button type="submit" class="btn btn-primary col-md-5" style="flex: 0.3;" id="submit_answer">提交答案</button>
                        </span>
                    </form>
                @else
                    <p class="text-center lead">没有该考点！</p>
                @endif
            </div>

        </div>
    </div>
    {{--超时模态框--}}
    {{--<div class="modal fade in" id="timeoutModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">--}}
        {{--<div class="modal-dialog modal-sm" role="document">--}}
            {{--<div class="modal-content">--}}
                {{--<div class="modal-header">--}}
                    {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                        {{--<span aria-hidden="true">&times;</span>--}}
                    {{--</button>--}}
                    {{--<h4 class="modal-title text-danger" id="myModalLabel">--}}
                        {{--<i class="fa fa-exclamation-circle"></i> 考试时间提示--}}
                    {{--</h4>--}}
                {{--</div>--}}
                {{--<div class="modal-body">--}}
                    {{--<h5 class="text-center">--}}
                        {{--考试超时，已自动交卷！--}}
                    {{--</h5>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
    <div class="modal-backdrop fade in hidden"></div>
    <script src="{{url('/js/jquery-3.3.1.min.js')}}"></script>
    <script>

    </script>
@endsection