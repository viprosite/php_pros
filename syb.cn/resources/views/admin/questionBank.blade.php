@extends('layouts.adminLayout')

@section('content')
    <section class="frame-header ">
        <p>
            <i class="fa fa-home"></i> <a href="{{url('admin/home')}}" target="_parent">管理首页</a> »
            <mark> <i class="fa fa-book"></i> 题库管理 </mark>
        </p>
    </section>
    @if(session('top_msg'))
        <div class="alert alert-danger top-msg text-center" role="alert">
            {{session('top_msg')}}
        </div>
    @endif
    <div class="container changepasswordtop">
        <div class="panel panel-info">
            <div class="panel-heading">
                <span> <i class="fa fa-info-circle"></i> 组卷记录：</span><br>
                @if(count($res) != 0)
                    <ul class="list-group">
                        @for($i=0;$i<count($res);$i++)
                            <a href="{{url('admin/view_paper/'.$res[$i]->id)}}" >
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
                        暂无组卷记录。
                    </p>
                @endif
            </div>
            <br />
            <hr>
            <span> <i class="fa fa-info-circle"></i> 题库详情：</span><br>
            <div class="panel panel-body">
                @if(isset($question_bank) && count($question_bank)>0)
                    <table class="table table-striped table-bordered table-hover table-condensed">
                        <thead>
                        <tr></tr>
                        <tr>
                            <th>序号</th>
                            <th>题型</th>
                            <th>考点</th>
                            <th>题目</th>
                            <th>答案</th>
                            <th>正确答案</th>
                            <th>添加时间</th>
                            {{--<th>操作</th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        @for($i=0;$i<count($question_bank);$i++)
                            <tr>
                                <td>{{$i + 1}}</td>
                                <td>
                                    @if($question_bank[$i]->question_type == 2)
                                        判断题
                                    @elseif($question_bank[$i]->question_type == 4)
                                        单选题
                                    @else
                                        多选题
                                    @endif
                                </td>
                                <td>
                                    {{$question_bank[$i]->test_point}}
                                </td>
                                <td>{{$question_bank[$i]->title}}</td>
                                <td>
                                    @if($question_bank[$i]->answer_a != '')
                                        A.{{$question_bank[$i]->answer_a}}<br>
                                    @else
                                        {{$question_bank[$i]->answer_a}}<br>
                                    @endif
                                    @if($question_bank[$i]->answer_b != '')
                                        B.{{$question_bank[$i]->answer_b}}<br>
                                    @else
                                        {{$question_bank[$i]->answer_b}}<br>
                                    @endif
                                    @if($question_bank[$i]->answer_c != '')
                                        C.{{$question_bank[$i]->answer_c}}<br>
                                    @else
                                        {{$question_bank[$i]->answer_c}}<br>
                                    @endif
                                    @if($question_bank[$i]->answer_d != '')
                                        D.{{$question_bank[$i]->answer_d}}<br>
                                    @else
                                        {{$question_bank[$i]->answer_d}}<br>
                                    @endif
                                </td>
                                <td>
                                    @if($question_bank[$i]->answer_ok == 'n')
                                        错误
                                    @elseif($question_bank[$i]->answer_ok == 'y')
                                        正确
                                    @else
                                        {{strtoupper($question_bank[$i]->answer_ok)}}
                                    @endif
                                </td>
                                <td>{{$question_bank[$i]->add_time}}</td>
                                {{--<td>--}}
                                    {{--<button class="btn btn-danger">删除</button>--}}
                                {{--</td>--}}
                            </tr>
                        @endfor
                        </tbody>
                    </table>
                @else
                    <b class="text-warning col-md-offset-2 col-sm-offset-2"> <i class="fa fa-info-circle"></i> 暂无题库，您可点击下方的 <mark>[ 导入题库 ] </mark>导入本地已有题库或点击 <mark>[ 出题系统 ]</mark> 进行在线出题操作。</b>
                    <br><br>
                @endif
            </div>
            <div class="panel-footer">
                <a href="{{url('admin/pullin')}}" target="main">
                    <button class="btn btn-primary col-md-offset-4 col-sm-offset-3 col-xs-offset-3"> <i class="fa fa-tasks"></i> 导入题库 </button>
                </a>
                <a href="{{url('admin/addquestions')}}" target="main">
                    <button class="btn btn-primary col-md-offset-1 col-sm-offset-3 col-xs-offset-3"> <i class="fa fa-mortar-board"></i> 出题系统 </button>
                </a>
            </div>
        </div>
    </div>



@endsection    