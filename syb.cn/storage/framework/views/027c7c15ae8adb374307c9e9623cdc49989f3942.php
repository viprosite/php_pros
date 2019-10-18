<?php $__env->startSection('content'); ?>
    <section class="frame-header ">
        <p>
            <i class="fa fa-home"></i> <a href="<?php echo e(url('admin/home')); ?>" target="_parent">管理首页</a> »
            <mark> <i class="fa fa-tasks"></i> 导入外部题库 </mark>
        </p>
    </section>
    <?php if(session('top_msg')): ?>
        <div class="alert alert-info top-msg text-center" role="alert">
            <?php echo e(session('top_msg')); ?>

            <button type="button" class="close" aria-label="Close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>
    <div class="container changepasswordtop">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h5> <i class="fa fa-chain-broken"></i> 导入题库 </h5>
            </div>
            <div class="panel-body">
                <span> <i class="fa fa-info-circle"></i> 您可点击 <mark>[下载题目模板] </mark>后按照模板说明进行出题，之后点击<mark> [导入外部已有题库] </mark>按钮进行导入！</span>
            </div>
            <div class="well well-sm center-block" style="width: 80%;">
                <div class="example row">

                </div>
                <br>
               <div class="row">
                   <a href="<?php echo e(url('/js/question_bank_model.txt')); ?>" download class="btn btn-success col-md-offset-3" role="button">下载题目模板</a>
                   <button id="pull_btn" class="btn btn-primary col-md-offset-1">导入外部已有题库</button>
                   <br>
                   <br>
                   <form action="" method="post" enctype="multipart/form-data">
                       <?php echo e(csrf_field()); ?>

                       <input type="file" name="question_bank" class="hidden">
                       <button type="submit" class="btn btn-primary col-md-3 col-md-offset-4 hidden"> 确认导入 </button>
                   </form>
               </div>
            </div>

            
                
                <div class="modal fade" id="pullModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">发送消息给 <span class="student_name"></span> 学员</h4>
                            </div>
                            <div class="modal-body">

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary sendmsg" >发送</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
<div id="text"></div>
<script src="<?php echo e(url('/js/jquery-3.3.1.min.js')); ?>"></script>
<script>
    $(function () {
        $('#pull_btn').click(function(){
           $('input[type="file"]').click()

            $('input[type="file"]').change(function (e) {
                $('button[type="submit"]').removeClass('hidden')
                var fr = new FileReader();
                fr.readAsText(this.files[0]);   //异步接口，返回值undefined
                fr.onload = function () {
                    $('#text').text( fr.result);
                }
            })
        })
        $('button[type="submit"]').click(function(){
            let question_bank_path =  $('input[name="question_bank"]').val()
//            console.log(question_bank_path)
            $.post('pullin',{'_token':"<?php echo e(csrf_token()); ?>",'question_bank':question_bank_path},function (res) {
                console.log(res)
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