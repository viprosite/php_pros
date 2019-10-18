@extends('layouts.layout')

@section('title')
    SYB - 考点练习
@stop

@section('content')
    <div class="container newsbox center-block" style="width: 85%;">
        <div class="panel panel-info">
            <div class="panel-heading" style="display: flex;">
                <span style="flex: 1;"> <i class="fa fa-chain-broken"></i> <mark><b>{{$res[0]->test_point}}</b></mark> 练习</span>
                {{--@if(session('answer'))--}}
                    {{--<b class="exam_time" style="flex: 1.5">测试用时：<mark id="ma">29</mark> 分 <mark id="sa">59</mark> 秒</b>--}}
                {{--@endif--}}
                {{--@if(isset($res) && $res != '[]' && session('answer') == '')--}}
                    {{--<b class="exam_time" style="flex: 1.5">考试时间剩余：<mark id="m">29</mark> 分 <mark id="s">59</mark> 秒</b>--}}
                {{--@endif--}}
            </div>
            <div class="well">
                @if(isset($res) && $res != '[]')
                    <form action="{{url('check_test_point')}}" class="form-horizontal" method="post">
                        {{csrf_field()}}
                        <input type="hidden" name="test_point" value="{{$res[0]->test_point}}">
                        <input type="hidden" name="type" value="{{$res[0]->question_type}}">
                            @for($i=0;$i<count($res);$i++)
                                <li class="list-group-item">
                                    {{$i+1}}. {{$res[$i]->title}} <br>
                                    @if($res[$i]->question_type == 44)
                                        <span class="row">
                                            <label class="col-md-6">
                                            <input  name="{{$res[$i]->title}}[]" value="a" type="checkbox">{{$res[$i]->answer_a}}
                                            </label>
                                            <label class="col-md-6" >
                                                <input name="{{$res[$i]->title}}[]" value="b" type="checkbox">{{$res[$i]->answer_b}}
                                            </label>
                                        </span>

                                        <span class="row">
                                            <label class="col-md-6">
                                                <input  name="{{$res[$i]->title}}[]" value="c" type="checkbox">{{$res[$i]->answer_c}}
                                            </label>
                                            <label class="col-md-6" >
                                                <input name="{{$res[$i]->title}}[]" value="d" type="checkbox">{{$res[$i]->answer_d}}
                                            </label>
                                        </span>
                                        @elseif($res[$i]->question_type == 4)
                                        <span class="row">
                                            <label class="col-md-6">
                                            <input  name="{{$res[$i]->title}}" value="a" type="radio">{{$res[$i]->answer_a}}
                                            </label>
                                            <label class="col-md-6" >
                                                <input name="{{$res[$i]->title}}" value="b" type="radio">{{$res[$i]->answer_b}}
                                            </label>
                                        </span>

                                        <span css="row"radio>
                                            <label class="col-md-6">
                                                <input  name="{{$res[$i]->title}}" value="c" type="radio">{{$res[$i]->answer_c}}
                                            </label>
                                            <label class="col-md-6" >
                                                <input name="{{$res[$i]->title}}" value="d" type="radio">{{$res[$i]->answer_d}}
                                            </label>
                                        </span>
                                        @else
                                        <span class="row">
                                            <label class="col-md-6">
                                            <input  name="{{$res[$i]->title}}" value="y" type="radio"> ✔
                                            </label>
                                            <label class="col-md-6" >
                                                <input name="{{$res[$i]->title}}" value="n" type="radio"> ✖
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
//        $(function () {
//            let m = 28;   //考试时间  分种
//            let s = 58;    //考试时间  秒
//            let mtimer = setInterval(
//                function () {
//                    $('#m').text(m)
//                    m--
//                    if(m == 5){
//                        $.growl.notice({
//                            title: "考试时间提示",
//                            message: "考试时间剩余5分钟!"
//                        });
//                    }
//                    if(m < -1){
//                        clearInterval(mtimer)
//                        clearInterval(stimer)
//                        $('#m').text(0)
//                        timeout()
//                    }
//                }, 60000
//            )
//            let stimer = setInterval(
//                function () {
//                    $('#s').text(s)
//                    s--
//                    if(s < 0){
//                        s=59
//                    }
//                }, 1000
//            )
//            $('#submit_answer').click(function () {
//                console.log(m,s)
//            })
//            function timeout(){
//                $('.modal-backdrop').removeClass('hidden')
//                $('#timeoutModal').css('display','block')
//            }
//            $('[class="close"]').click(function () {
//                $('.modal-backdrop').addClass('hidden')
//                $('#timeoutModal').css('display','none')
//            })
//
//
//
//
//
//        })
    </script>
@endsection