@extends('layouts.adminLayout')

@section('title')
    管理员 - 添加考试公告
@endsection

@section('content')
    <section class="frame-header ">
        <p>
            <i class="fa fa-home"></i> <a href="{{url('admin/home')}}" target="_parent">管理首页</a> »
            <mark> <i class="fa fa-newspaper-o"></i> 发布公告 </mark>
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
                <h3> <i class="fa fa-chain-broken"></i> 发布公告</h3>
            </div>
            <div class="panel-body">
                <form action="" method="post" class="form-horizontal">
                    {{csrf_field()}}
                    <div class="form-group">
                        <label class="control-label col-md-2">标题</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" placeholder="公告标题" name="title" required> <br />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">内容</label>
                        <div class="col-md-8">
                           <textarea class="form-control" rows="10" name="content" placeholder="公告内容" required></textarea> <br />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-8">
                            <button class="btn btn-info col-md-8 col-md-offset-5">
                                <i class="fa fa-check-square-o"></i> 确认发布
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="panel-footer">
                <p class="text-danger text-center">公告一经发表既不可修改亦不可删除，请谨慎操作！</p>
            </div>
        </div>
    </div>

@endsection