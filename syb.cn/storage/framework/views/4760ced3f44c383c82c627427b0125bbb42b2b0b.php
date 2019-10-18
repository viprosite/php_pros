<?php $__env->startSection('title'); ?>
    SYB - 我的历史消息
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container newsbox center-block" style="width: 85%;">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h5> <i class="fa fa-chain-broken"></i> 查看所有消息</h5>
            </div>
            <?php if(count($msgs) != 0): ?>
                <ul class="list-group">
                    <?php for($i=0;$i<count($msgs);$i++): ?>
                        <li class="list-group-item" id="<?php echo e($msgs[$i]->id); ?>">
                                <?php if($msgs[$i]->viewor == 1): ?>
                                <a href="<?php echo e(url('msgdetail/'.$msgs[$i]->id)); ?>" target="">
                                    <span class="text-success"><i class="fa fa-check-square-o"></i> 已读</span>
                                    <span> <mark><?php echo e($msgs[$i]->sender); ?></mark> 发送于：<?php echo e($msgs[$i]->send_time); ?></span> &nbsp;&nbsp;
                                    <b><?php echo e($msgs[$i]->title); ?></b>
                                </a> &nbsp;&nbsp;&nbsp;
                                <button class="btn btn-sm btn-danger delmsgbtn" data-toggle="modal" data-target="#delModal" data-id="<?php echo e($msgs[$i]->id); ?>">删除</button>
                                <?php else: ?>
                                <a href="<?php echo e(url('msgdetail/'.$msgs[$i]->id)); ?>" target="">
                                    <i class="fa fa-dot-circle-o"></i>
                                    <span> <mark><?php echo e($msgs[$i]->sender); ?></mark> 发送于：<?php echo e($msgs[$i]->send_time); ?></span> &nbsp;&nbsp;
                                    <b><?php echo e($msgs[$i]->title); ?></b>
                                </a>
                                 <?php endif; ?>
                        </li>
                    <?php endfor; ?>
                </ul>
            <?php else: ?>
                <p>
                    &nbsp;&nbsp;<i class="fa fa-info-circle"></i>
                    暂无消息。
                </p>
            <?php endif; ?>
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
                        您确认删除该消息？
                    </h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger del-ok" data-id="" >删除</button>
                    <button type="button" class="btn btn-default delStudent" data-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
    </div>
    <script src="<?php echo e(url('/js/jquery-3.3.1.min.js')); ?>"></script>
    <script>
        $(function () {
            $('.delmsgbtn').click(function () {
                let msg_id = $(this).attr('data-id')
                $('.del-ok').attr('data-id',msg_id)
            })
            $('.del-ok').click(function () {
                let msg_id = $(this).attr('data-id')
                $.post('delmsg/'+msg_id,{'_token':"<?php echo e(csrf_token()); ?>"},function (res) {
                    if(res == 'y'){
                        $('#delModal, .modal-backdrop').hide()
                        $.growl.notice({
                            title: "删除提示",
                            message: "删除成功!"
                        });
                        $('#'+msg_id).remove()
                    }
                })
            })
        })
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>