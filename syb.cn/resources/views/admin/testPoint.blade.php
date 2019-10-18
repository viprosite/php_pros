@extends('layouts.adminLayout')

@section('content')
    <section class="frame-header ">
        <p>
            <i class="fa fa-home"></i> <a href="{{url('admin/home')}}" target="_parent">管理首页</a> »
            <mark> <i class="fa fa-check-square-o"></i> 考点管理 </mark>
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
                <h5> <i class="fa fa-check-square-o"></i> 考点一览 </h5>
            </div>
            <br />
            @if(isset($test_point) && count($test_point)>0)
                {{--<div>--}}
                    {{--<form method="post" class="form-inline">--}}
                        {{--<select class="form-control col-md-2" name="questiontype">--}}
                            {{--<option>题型选择</option>--}}
                            {{--<option value="2">判断题</option>--}}
                            {{--<option value="4">单选题</option>--}}
                            {{--<option value="44">多选题</option>--}}
                        {{--</select>--}}
                        {{--<select name="testpoint" class="form-control ">--}}
                            {{--<option>考点选择</option>--}}
                            {{--<option value="1">考点1</option>--}}
                            {{--<option value="2">考点1</option>--}}
                            {{--<option value="3">考点1</option>--}}
                        {{--</select>--}}
                        {{--<div class="form-group">--}}
                            {{--<input type="text" class="form-control col-md-offset-0" placeholder="输入关键字进行模糊筛选" required style="margin-left: 20px">--}}
                            {{--<button class="btn btn-info"> <i class="fa fa-search-plus"></i> 筛选 </button>--}}
                        {{--</div>--}}
                    {{--</form>--}}
                {{--</div>--}}
                {{--<br />--}}
                <table class="table table-striped table-bordered table-hover table-condensed">
                    <thead>
                    <tr></tr>
                    <tr>
                        <th>序号</th>
                        <th>考点</th>
                        <th>对应题目数</th>
                        {{--<th>操作</th>--}}
                    </tr>
                    </thead>
                    <tbody>
                    @for($i=0;$i<count($test_point);$i++)
                        <tr>
                            <td class="index">{{$i + 1}}</td>
                            <td class="">
                                {{$test_point[$i]}}
                            </td>
                            <td class="question_num">
                                {{$nums[$i]}}
                            </td>
                            <td>
                                {{--{{}}--}}
                            </td>
                            {{--<td>--}}
                                {{--<button class="btn btn-danger">删除</button>--}}
                            {{--</td>--}}
                        </tr>
                    @endfor
                    </tbody>
                </table>
                @else
                    <b class="text-warning col-md-offset-3 col-sm-offset-2"> <i class="fa fa-info-circle"></i> 暂无考点，您可点击左侧的 <mark>[ 题库管理 ]</mark> 菜单进行在线出题操作。</b>
            @endif
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
