<?php $__env->startSection('title'); ?>
    SYB - 考点练习
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container newsbox center-block" style="width: 85%;">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h5> <i class="fa fa-chain-broken"></i> 考点练习</h5>
            </div>
            <?php if(count($res) != 0): ?>
                <ul class="list-group">
                    <?php for($i=0;$i<count($res);$i++): ?>
                        <li class="list-group-item">
                            <a href="<?php echo e(url('test_point/'.$res[$i])); ?>" target="">
                                <i class="fa fa-dot-circle-o"></i>
                                有 <mark><?php echo e($num[$i]); ?></mark> 道 &nbsp;&nbsp;&nbsp;
                                <b><?php echo e($res[$i]); ?></b>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            <?php else: ?>
                <p>
                    &nbsp; <i class="fa fa-info-circle"></i>
                    暂未提供考题。
                </p>
            <?php endif; ?>
            <div class="panel-footer">
                <br>
                <p class=""> <i class="fa fa-history"></i> 考点作答历史记录：</p>
                <?php if(isset($history_exam) && count($history_exam) != 0): ?>
                    <ul class="list-group text-warning">
                        <?php for($i=0;$i<count($history_exam);$i++): ?>
                            <li class="list-group-item">
                                    <i class="fa fa-dot-circle-o"></i>
                                    <mark><?php echo e($history_exam[$i]->exam_time); ?></mark> &nbsp;&nbsp;&nbsp;&nbsp;
                                    <b><?php echo e($history_exam[$i]->test_point); ?></b>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <b>答对 <?php echo e($history_exam[$i]->grade); ?> 道</b>
                            </li>
                        <?php endfor; ?>
                    </ul>
                <?php else: ?>
                    <p>
                        <i class="fa fa-info-circle"></i>
                        暂无作答记录。
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>