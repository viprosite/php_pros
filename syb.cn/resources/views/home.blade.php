@extends('layouts.layout')

@section('title')
    SYB创业培训在线考试系统 - 首页
@stop

@section('content')
<div class="">
    <section>
        <div class="">
                    <div class="well well-sm" style="color: darkgray;">
                        @if(isset($new) && $new!= '' )
                            <mark> <i class="fa fa-bell fa-fw"></i> 最新公告&nbsp;&nbsp;&nbsp;</mark>
                                <a href="{{url('newdetail/'.$new->id)}}" target="">
                                    <i class="fa fa-dot-circle-o"></i>
                                    <mark>{{$new->operator}}</mark>
                                    <span>发布于：{{$new->add_at}}</span> &nbsp;&nbsp;
                                    <b>{{$new->title}}</b>
                                </a> <br>
                        @else
                            <p>
                                <i class="fa fa-info-circle"></i>
                                暂无公告。
                            </p>
                        @endif
                            <br>
                            @if(isset($info) && $info!= '[]')
                                @if($info[0]->viewor == 1)
                                    <i class="fa fa-envelope-open fa-fw"></i> 已读消息&nbsp;&nbsp;&nbsp;
                                    <i class="fa fa-dot-circle-o"></i> <mark>{{$info[0]->sender}}</mark>
                                    <span>发送于：{{$info[0]->send_time}}</span> &nbsp;&nbsp;
                                    <b>{{$info[0]->title}}</b>
                                @else
                                <mark> <i class="fa fa-envelope fa-fw"></i> 最新消息&nbsp;&nbsp;&nbsp;</mark>
                                    <a href="{{url('msgdetail/'.$info[0]->id)}}" target="">
                                        <i class="fa fa-dot-circle-o"></i>
                                            <mark>{{$info[0]->sender}}</mark>
                                            <span>发送于：{{$info[0]->send_time}}</span> &nbsp;&nbsp;
                                            <b>{{$info[0]->title}}</b>
                                        @endif
                                    </a>
                            @else
                                <p>
                                    <i class="fa fa-info-circle"></i>
                                    暂无消息。
                                </p>
                            @endif
                    </div>
        </div>
    </section>
    <section>
        <div class="panel panel-info">
            <div class="panel-heading">
                <span class="panel-title">
                    <i class="fa fa-cogs"></i> 快捷操作
                </span>
            </div>
            ​<div class="row">
                <div class="col-sm-6 col-md-2 col-md-offset-1">
                    <div class="thumbnail">
                        <i class="fa fa-dot-circle-o"></i>
                        <h5 class="text-center"></h5>
                        <div class="caption">
                            <i class="fa fa-location-arrow"></i>
                            <a href="{{url('test_point')}}" class="btn btn-info center-block icon-click" role="button">
                               <span class="text-left">
                                   <i class="fa fa-arrow-circle-o-right"></i> 考点练习
                               </span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-2">
                    <div class="thumbnail">
                        <i class="fa fa-dot-circle-o"></i>
                        <h5 class="text-center"></h5>
                        <div class="caption">
                           <i class="fa fa-book"></i>
                            <a href="{{url('question_type')}}" class="btn btn-info center-block icon-click" role="button">
                               <span class="text-left">
                                   <i class="fa fa-arrow-circle-o-right"></i> 题型练习
                               </span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-2">
                    <div class="thumbnail">
                        <i class="fa fa-dot-circle-o"></i>
                        <h5 class="text-center"></h5>
                        <div class="caption">
                          <i class="fa fa-pencil"></i>
                            <a href="{{url('mock_test')}}" class="btn btn-info center-block icon-click" role="button">
                               <span class="text-left">
                                   <i class="fa fa-arrow-circle-o-right"></i> 模拟测试
                               </span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-2">
                    <div class="thumbnail">
                        <i class="fa fa-dot-circle-o"></i>
                        <h5 class="text-center"></h5>
                        <div class="caption">
                            <i class="fa fa-id-card"></i>
                            @if($reg_exam_state != 1)
                                <a class="btn btn-info center-block icon-click reg_exam" id="reg_exam" role="button" data-toggle="modal" data-target="#regExamModal">
                                    <span class="text-left">
                                    <i class="fa fa-arrow-circle-o-right"></i>
                                    <span>考试报名</span>
                                  </span>
                                </a>
                            @else
                                <a class="btn btn-info center-block icon-click disabled" role="button">
                                    <span class="text-left">
                                    <i class="fa fa-check-square-o"></i>
                                    <span>第 {{$exam_num}} 场</span>
                                  </span>
                                </a>
                            @endif
                        </div>
                    </div>
                    {{--报名模态框--}}
                    <div class="modal fade" id="regExamModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog modal-sm" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 class="modal-title text-primary" id="myModalLabel">
                                        <i class="fa fa-id-card"></i> 考试报名提示
                                    </h4>
                                </div>
                                <div class="modal-body">
                                    <h5 class="text-center">
                                        <select name="exam_num" id="exam_num" class="form-control col-md-5">
                                            <option>选择考试场次</option>
                                            {{--动态插入场次数--}}
                                        </select>
                                    </h5>
                                </div>
                                <br><br>
                                <div class="modal-footer">
                                    <a  class="btn btn-info reg-ok" data-id="">报名</a>
                                    <button type="button" class="btn btn-default delStudent" data-dismiss="modal">取消</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-2">
                    <div class="thumbnail">
                        <i class="fa fa-dot-circle-o"></i>
                        <h5 class="text-center"></h5>
                        <div class="caption">
                            <i class="fa fa-graduation-cap"></i>
                            @if($reg_exam_state != 1)
                                <a class="btn btn-info center-block icon-click disabled" id="formal_exam" role="button">
                                    <span class="text-left">
                                    <i class="fa fa-arrow-circle-o-right"></i>
                                    <span>正式考试</span>
                                  </span>
                                </a>
                            @else
                                <a class="btn btn-info center-block icon-click " role="button" data-toggle="modal" data-target="#foraml_exam">
                                    <span class="text-left">
                                    <i class="fa fa-check-square-o"></i>
                                    <span>正式考试</span>
                                  </span>
                                </a>
                            @endif
                        </div>
                    </div>
                    {{--正式考试前提示模态框--}}
                    <div class="modal  fade" id="foraml_exam" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog modal-sm" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 class="modal-title text-primary" id="myModalLabel">
                                        <i class="fa fa-graduation-cap"></i> 正式考试前提示
                                    </h4>
                                </div>
                                <div class="modal-body">
                                    <h5 class="text-center">
                                        <i class="fa fa-info-circle fa-fw"></i> 只有您成功进行了 <mark>[考试报名]</mark> 操作才可开始正式考试！
                                        <br><br>
                                        <i class="fa fa-info-circle fa-fw"></i> 系统将从已组好的试题中随机抽取一套供您作答！
                                    </h5>
                                </div>
                                <br><br>
                                <div class="modal-footer">
                                    <a href="{{url('formal_exam')}}" class="btn btn-info" >进入考试</a>
                                    <button type="button" class="btn btn-default delStudent" data-dismiss="modal">取消</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">

            </div>
        </div>
    </section>

    <script src="{{url('/js/jquery-3.3.1.min.js')}}"></script>
    <script>
        $(function () {
            $('#reg_exam').click(function () {     //考试报名
                $.get('exam_num', function (data) {          //获得场次数和每场容纳学生数
//                    console.log(data)
                    var options = ''
                    for(let i=0;i<data.exam_num;i++){
                        if(data.already_reg_num[i] ==data.stu_num ){
                            options += `
                        <option disabled="disabled" value="${i+1}"> 第${i+1}场， <span class="reg_ok_num">${data.already_reg_num[i]}</span> / <span class="stu_num">${data.stu_num}</span> </option>
                    <br>`;
                        }else{
                            options += `
                        <option value="${i+1}"> 第${i+1}场， <span class="reg_ok_num">${data.already_reg_num[i]}</span> / <span class="stu_num">${data.stu_num}</span> </option>
                    <br>`;
                        }
                    }
                    $('#exam_num').append(options)
                })
            })

            $('#exam_num').change(function () {
                var exam_num = this.value           //选择的场次数
                $('.reg-ok').click(function () {
                    $.post('reg_exam/'+exam_num,{'_token':"{{csrf_token()}}"},function (res) {
                        $('#regExamModal, .modal-backdrop').hide()
                        $('.reg_exam>span span').text('第'+exam_num+'场')
                        $('.reg_exam').addClass('disabled')
                        $.growl.notice({
                            title: "报名提示",
                            message: "考试报名成功!"
                        });
                        setTimeout(()=>{
                            location.href = location.href
                        },1000)
                    })
                })
            })
        })
    </script>
@stop