@extends('layouts.layout')

@section('title')
    SYB - 模拟测试
@stop

@section('content')
    <div class="container newsbox center-block" style="width:85%">
        <div class="help-block" style="padding: 5px;background-color: lightgray;border: 1px solid #aaa;width: 8%;
        position: fixed;left: 7%;top: 20%;">
            <mark>温馨提示：</mark>
            <br>
            <p class="text-danger" style="font-weight: bold;">
                <i class="fa fa-info-circle"> </i> 作答期间请不要刷新本页面！<br>
                <i class="fa fa-info-circle"> </i>  剩余5分钟时去除提交限制，即倒数5分钟内您可自由提交！
            </p>
        </div>
        <div class="panel panel-info">
            <div class="panel-heading" style="display: flex;">
                @if(session('answer'))
                <b class="exam_time" style="flex: 1.5">测试用时：<mark id="ma">29</mark> 分 <mark id="sa">59</mark> 秒</b>
                @endif
                @if(session('answer') == '')
                <b class="exam_time text-center" style="flex: 1.5">考试时间剩余：<mark id="m">{{$info['finish_time'] - 1}}</mark> 分 <mark id="s">59</mark> 秒</b>
                @endif
            </div>
            <div class="well">
                @if(isset($res_2) || isset($res_4) || $res_44 )
                    <form method="post" class="form-horizontal" action="{{url('check_mock_test')}}" >
                        {{csrf_field()}}
                        <input type="hidden" name="score_2" value="{{$info['score_2']}}">
                        <input type="hidden" name="score_4" value="{{$info['score_4']}}">
                    @if(isset($res_2) && $res_2 != '[]' && count($res_2)>0)
                            @for($i=0;$i<count($res_2);$i++)
                                <li class="list-group-item">
                                    {{$i+1}}. {{$res_2[$i]->title}}
                                    <br>
                                    <span class="row">
                                        <label class="col-md-6">
                                        <input name="{{$res_2[$i]->title}}" value="y" type="radio" required="required"> ✔
                                        </label>
                                        <label class="col-md-6">
                                            <input name="{{$res_2[$i]->title}}" value="n" type="radio" required="required"> ✖
                                        </label>
                                </span>
                                </li>
                            @endfor
                        @endif
                        @if(isset($res_4) && $res_4 != '[]' && count($res_4)>0)
                            @for($i=0;$i<count($res_4);$i++)
                                <li class="list-group-item">
                                    {{$i+count($res_2)+1}}. {{$res_4[$i]->title}} <br>
                                    <span class="row">
                                        <label class="col-md-6">
                                        <input name="{{$res_4[$i]->title}}" value="a" type="radio">{{$res_4[$i]->answer_a}}
                                        </label>
                                        <label class="col-md-6">
                                        <input name="{{$res_4[$i]->title}}" value="b" type="radio" required="required">{{$res_4[$i]->answer_b}}
                                        </label>
                                </span>
                                    <span css="row" radio>
                                    <label class="col-md-6">
                                    <input name="{{$res_4[$i]->title}}" value="c" type="radio" required="required">{{$res_4[$i]->answer_c}}
                                    </label>
                                    <label class="col-md-6">
                                    <input name="{{$res_4[$i]->title}}" value="d" type="radio" required="required">{{$res_4[$i]->answer_d}}
                                    </label>
                                </span> <br><br>
                                </li>
                            @endfor
                        @endif
                        @if(isset($res_44) && $res_44 != '[]' && count($res_44)>0)
                            @for($i=0;$i<count($res_44);$i++)
                                <li class="list-group-item">
                                    {{$i+count($res_4)+1}}. {{$res_44[$i]->title}} <br>
                                    <span class="row">
                                     <label class="col-md-6">
                                     <input  name="{{$res_44[$i]->title}}[]" value="a" type="checkbox" required="required">{{$res_44[$i]->answer_a}}
                                     </label>
                                     <label class="col-md-6" >
                                     <input name="{{$res_44[$i]->title}}[]" value="b" type="checkbox" required="required">{{$res_44[$i]->answer_b}}
                                     </label>
                                 </span>
                                    <span class="row">
                                     <label class="col-md-6">
                                     <input  name="{{$res_44[$i]->title}}[]" value="c" type="checkbox" required="required">{{$res_44[$i]->answer_c}}
                                     </label>
                                     <label class="col-md-6" >
                                     <input name="{{$res_44[$i]->title}}[]" value="d" type="checkbox" required="required">{{$res_44[$i]->answer_d}}
                                     </label>
                                 </span> <br><br>
                                </li>
                            @endfor
                        @endif
                            <br>
                            <button class="btn btn-success col-md-4 col-md-offset-4" id="submit_exam"> 提交答案 </button>
                    </form>
                @else
                    <p class="text-center text-danger lead">没有组卷记录！</p>
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
        $(function () {
            let m = $('#m').text();   //考试时间  分种
//            let m = 1;   //考试时间  分种
            let s = 58;    //考试时间  秒
//            let s = 5;    //考试时间  秒
            let mtimer = setInterval(
                function () {
                    m--
                    $('#m').text(m)
                    if(m == 4){
                        $('input').attr('required',null)
                        $.growl.notice({
                            title: "考试时间提示",
                            message: "考试时间剩余5分钟!"
                        });
                    }
                    if(m <= -1){
                        clearInterval(mtimer)
                        clearInterval(stimer)
                        $('#m').text(0)
                        timeout()
                        $('#submit_exam').click()
                    }
                }, 60000
            )
            let stimer = setInterval(
                function () {
                    $('#s').text(s)
                    s--
                    if(s < 0){
                        s=59
                    }
                }, 1000
            )
            $('#submit_answer').click(function () {
                console.log(m,s)
            })
            function timeout(){
                $('.modal-backdrop').removeClass('hidden')
                $('#timeoutModal').css('display','block')
            }
            $('[class="close"]').click(function () {
                $('.modal-backdrop').addClass('hidden')
                $('#timeoutModal').css('display','none')
            })
        })
    </script>
@endsection