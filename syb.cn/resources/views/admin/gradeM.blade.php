@extends('layouts.adminLayout')

@section('content')
    <section class="frame-header ">
        <p>
            <i class="fa fa-home"></i> <a href="{{url('admin/home')}}" target="_parent">管理首页</a> »
            <mark> <i class="fa fa-bar-chart"></i> 成绩统计 </mark>
        </p>
    </section>
    @if(session('grades'))
        <div class="alert alert-danger top-msg text-center" role="alert">
            {{session('grades')}}
        </div>
    @endif
    <div class="container changepasswordtop">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h5> <i class="fa fa-chain-broken"></i> 成绩统计</h5>
            </div>
            <div class="panel-body">
                @if(isset($info))
                    <span class="text-info">{{$info}}</span>
                @endif
                @if(isset($grades))
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>序号</th>
                            <th>学号</th>
                            <th>姓名</th>
                            <th>题库号</th>
                            <th>成绩</th>
                            <th>作答时间</th>
                        </tr>
                        </thead>
                        <tbody>
                        {{--{{dd($grades[0]->id)}}--}}
                        @for($i=0;$i<count($grades);$i++)
                            <tr>
                                <td>{{$i + 1}}</td>
                                <td>{{$grades[$i]->student_id}}</td>
                                <td>{{$grades[$i]->student_name}}</td>
                                <td>{{$grades[$i]->paper_id}}</td>
                                @if($grades[$i]->grades < 60)
                                    <td class="text-danger">{{$grades[$i]->grades}}</td>
                                @else
                                    <td class="text-success">{{$grades[$i]->grades}}</td>
                                @endif
                                <td>{{$grades[$i]->submit_time}}</td>
                            </tr>
                        @endfor
                        </tbody>
                    </table>
                @endif
            </div>
            <div class="panel-footer">
                <p class="text-danger text-center">您可快捷操作！</p>
            </div>
        </div>
    </div>



@endsection