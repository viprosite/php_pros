<?php $__env->startSection('title'); ?>
    前台 - 查看公告详情
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php if(session('top_msg')): ?>
        <div class="alert alert-danger top-msg text-center" role="alert">
            <?php echo e(session('top_msg')); ?>

        </div>
    <?php endif; ?>
    <div class="container changepasswordtop">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h5> <i class="fa fa-chain-broken"></i> 查看公告详情</h5>
            </div>
            <div class="panel-body">
                <h4 class="text-center title">
                    <?php echo e($new[0]->title); ?> <br />
                    <small>
                       <mark> <?php echo e($new[0]->operator); ?> </mark>
                        发布于： <?php echo e($new[0]->add_at); ?> &nbsp;&nbsp;
                        查看次数：<?php echo e($new[0]->seenums); ?>

                    </small>
                </h4>
                <pre><?php echo $new[0]->content; ?></pre>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>