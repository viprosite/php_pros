<?php $__env->startSection('content'); ?>
    <section class="frame-header ">
        <p>
            <i class="fa fa-home"></i> <a href="<?php echo e(url('admin/home')); ?>" target="_parent">管理首页</a> »
            <mark> <i class="fa fa-group"></i> 考生管理 </mark>
        </p>
    </section>
    <div class="container changepasswordtop">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h5> <i class="fa fa-chain-broken"></i> 考生管理</h5>
            </div>
            <div class="panel-body">
                <form class="form-inline" onsubmit="return false">
                    <?php echo e(csrf_field()); ?>

                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="输入学号或姓名搜索" name="key_word" required>
                    </div>
                    <button class="btn btn-info search">搜索</button>
                </form>
            </div>
                <?php if(isset($info)): ?>
                    <span class="text-info"><?php echo e($info); ?></span>
                <?php endif; ?>
                <?php if(isset($students)): ?>
                    <table class="table table-striped table-bordered table-hover table-condensed" id="main-table">
                        <thead>
                        <tr>
                            <th>序号</th>
                            <th>学号</th>
                            <th>姓名</th>
                            <th>邮箱</th>
                            <th>密码</th>
                            <th>注册时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        
                        <?php for($i=0;$i<count($students);$i++): ?>
                            <tr id="<?php echo e($students[$i]->student_id); ?>">
                                <td><?php echo e($i + 1); ?></td>
                                <td class="student_id"><?php echo e($students[$i]->student_id); ?></td>
                                <td><?php echo e($students[$i]->student_name); ?></td>
                                <td><?php echo e($students[$i]->email); ?></td>
                                <td><?php echo e($students[$i]->password); ?></td>
                                <td><?php echo e($students[$i]->register_at); ?></td>
                                <td class="action">
                                    <button class="btn btn-primary actionBtn"  data-toggle="modal" data-target="#sendModal" data-id="<?php echo e($students[$i]->student_id); ?>" data-name="<?php echo e($students[$i]->student_name); ?>">发送信息</button>
                                    

                                </td>
                            </tr>
                        <?php endfor; ?>
                        </tbody>
                    </table>
                
                <div class="modal fade" id="sendModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">发送消息给 <span class="student_name"></span> 学员</h4>
                            </div>
                            <div class="modal-body">
                                <form class="form-horizontal">
                                    <div class="form-group">
                                        <label class="control-label col-md-2">标题</label>
                                        <div class="col-md-8">
                                            <input type="text" name="title" class="form-control" placeholder="输入消息的概要提示" required>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <label class="control-label col-md-2">内容</label>
                                        <div class="col-md-8">
                                            <textarea name="msg"  rows="5" class="form-control" required placeholder="输入发送给学员的消息"></textarea>
                                        </div>
                                    </div>

                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary sendmsg" >发送</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal fade" id="delModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog modal-sm" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h4 class="modal-title text-danger" id="myModalLabel">
                                    <i class="fa fa-trash"></i> 删除提示
                                </h4>
                            </div>
                            <div class="modal-body">
                                <h5 class="text-center">
                                    您确认删除
                                    <mark><span class="student_name"></span></mark>
                                    学员？
                                </h5>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger del-ok" data-id="">删除</button>
                                <button type="button" class="btn btn-default delStudent" data-dismiss="modal">取消</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            <div class="panel-footer">
                <p class="text-danger text-center">您可快捷操作！</p>
            </div>
        </div>
    </div>

<script src="<?php echo e(url('/js/jquery-3.3.1.min.js')); ?>"></script>
<script>
    $(function () {
//        发送消息给学员
        $('[data-target="#sendModal"]').click(function () {
            let student_id = $(this).attr('data-id')
            let student_name = $(this).attr('data-name')
            $('.student_name').text(student_name)
            $('.sendmsg').attr('data-id',student_id);
        })
        $('.sendmsg').click(function () {
            let student_id = $(this).attr('data-id')
            let title = $("[name='title']").val()
            let content = $("[name='msg']").val()
            $.post('sendmsg/student_id/'+student_id,{'_token':"<?php echo e(csrf_token()); ?>",'title':title,'content':content},function (res) {
                if(res === 'y'){
                    $('#sendModal, .modal-backdrop').hide()
                    $.growl.notice({
                        title: "发送提示",
                        message: "发送消息成功!"
                    });
                }else{
                    $('#sendModal, .modal-backdrop').hide()
                    $.growl.notice({
                        title: "发送提示",
                        message: "发送消息失败，请重试!"
                    });
                }
            });
        })

//        学员搜索
        $('.search').click(function () {
            var search_key = $('[name="key_word"]').val()
            $.post('search_student/'+search_key,{'_token':"<?php echo e(csrf_token()); ?>"},function(data){
                console.log(data.length)
                if(data.length>0) {
                    let tr = ''
                    for (var i = 0; i < data.length; i++) {
                        tr += `
                                <tr id="${data[i].student_id}">
                                        <td class="student_id">${data[i].student_id}</td>
                                        <td>${data[i].student_name}</td>
                                        <td>${data[i].email}</td>
                                        <td>${data[i].password}</td>
                                        <td>${data[i].register_at}</td>
                                        <td class="action">
                                            <button class="btn btn-primary actionBtn"  data-toggle="modal" data-target="#sendModal">发送信息</button>
                                            <button id="del" class="btn btn-danger" data-toggle="modal" data-target="#delModal" data-id="${data[i].student_id}" data-name="${data[i].student_name}">删除</button>
                                        </td>
                                 </tr>`
                    }
                    let gen_table = `
                        <table class="table table-striped table-bordered table-hover table-condensed" id="main-table">
                            <thead>
                                <tr>
                                    <th>学号</th>
                                    <th>姓名</th>
                                    <th>邮箱</th>
                                    <th>密码</th>
                                    <th>注册时间</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>`
                        + tr +
                            `
                             </tbody>
                        </table>
                    `
                        $('#main-table').remove()
                        $('.panel-body').after(gen_table)
                }else{
                    $.growl.notice({
                        title: "搜索提示",
                        message: "没有符合条件的内容!"
                    });
                }

            })

        })

//        点击删除操作时弹出模态框，确认删除时调用AJAX函数
        $('[data-target="#delModal"]').click(function () {
            let student_id = $(this).attr('data-id')
            let student_name = $(this).attr('data-name')
            console.log(student_id,student_name)
            $('#student_name').text(student_name)
            $('.del-ok').attr('data-id',student_id);
        })
        $('.del-ok').click(function () {
            let student_id = $(this).attr('data-id')
            $.post('delstudent/student_id/'+student_id,{'_token':"<?php echo e(csrf_token()); ?>"},function (res) {
                if(res === 'y'){
                    $('#delModal, .modal-backdrop').hide()
                    $.growl.notice({
                        title: "删除提示",
                        message: "删除成功!"
                    });
                    $('#'+student_id).remove()
                }
            });
        })
    })
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>