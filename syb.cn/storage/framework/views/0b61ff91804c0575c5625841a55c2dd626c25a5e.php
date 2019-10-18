<?php $__env->startSection('title'); ?>
    SYB - 考点练习答案解析
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container newsbox center-block" style="width: 85%;">
        <div class="panel panel-info">
            <div class="panel-heading" style="display: flex;">
                <span style="flex: 1;"> <i class="fa fa-chain-broken"></i> <mark><b><?php echo e($res[0]->test_point); ?></b></mark> 答案 </span>
                    <span style="flex: 1;"> <i class="fa fa-list-alt"></i> <mark>答对<b> <?php echo e($grade); ?> </b>个</mark>  </span>
                    <span style="flex: 2;"> <i class="fa fa-info-circle"></i> 答对显示为 <b class="text-success">绿色</b>，答错显示为 <b class="text-danger">红色</b>  </span>
            </div>
            <div class="well">
                <?php if(isset($res) && $res != '[]'): ?>
                    <form class="form-horizontal" onsubmit="return false">
                            <?php for($i=0;$i<count($res);$i++): ?>
                                    <li class="list-group-item">
                                        <?php if($ok[$i] == 1 ): ?>
                                            <i class="fa fa-smile-o"></i> <span style="background-color: green;color: white">  <?php echo e($i+1); ?>. <?php echo e($res[$i]->title); ?></span> <span>
                                                你的答案：
                                                <?php if($answers[$i] == 'y'): ?>
                                                    <mark> ✔ </mark>
                                                <?php elseif($answers[$i] == 'n'): ?>
                                                    <mark> ✖ </mark>
                                                    <?php else: ?>
                                                    <mark><?php echo e($answers[$i]); ?></mark>
                                                <?php endif; ?>
                                            </span><br>
                                        <?php else: ?>
                                            <i class="fa fa-frown-o"></i> <span style="background-color: red;color: white">  <?php echo e($i+1); ?>. <?php echo e($res[$i]->title); ?></span> <span>
                                                 你的答案：
                                                <?php if($answers[$i] == 'y'): ?>
                                                    <mark> ✔ </mark>
                                                <?php elseif($answers[$i] == 'n'): ?>
                                                    <mark> ✖ </mark>
                                                <?php else: ?>
                                                    <mark><?php echo e($answers[$i]); ?></mark>
                                                <?php endif; ?>
                                            </span> &nbsp;&nbsp;&nbsp;正确答案：<mark>
                                                <?php if($answer_ok[$i] == 'y'): ?>
                                                    <mark> ✔ </mark>
                                                <?php elseif($answer_ok[$i] == 'n'): ?>
                                                    <mark> ✖ </mark>
                                                <?php else: ?>
                                                    <mark><?php echo e($answer_ok[$i]); ?></mark>
                                                <?php endif; ?>
                                            </mark><br>
                                        <?php endif; ?>
                                    <?php if($res[$i]->question_type == 44): ?>
                                        <span class="row">
                                            <label class="col-md-6">
                                             <?php echo e($res[$i]->answer_a); ?>

                                            </label>
                                            <label class="col-md-6" >
                                                 <?php echo e($res[$i]->answer_b); ?>

                                            </label>
                                        </span>

                                        <span class="row">
                                            <label class="col-md-6">
                                                 <?php echo e($res[$i]->answer_c); ?>

                                            </label>
                                            <label class="col-md-6" >
                                                 <?php echo e($res[$i]->answer_d); ?>

                                            </label>
                                        </span>
                                    <?php elseif($res[$i]->question_type == 4): ?>
                                        <span class="row">
                                            <label class="col-md-6">
                                             <?php echo e($res[$i]->answer_a); ?>

                                            </label>
                                            <label class="col-md-6" >
                                                <?php echo e($res[$i]->answer_b); ?>

                                            </label>
                                        </span>

                                        <span class="row">
                                            <label class="col-md-6">
                                                <?php echo e($res[$i]->answer_c); ?>

                                            </label>
                                            <label class="col-md-6" >
                                                <?php echo e($res[$i]->answer_d); ?>

                                            </label>
                                        </span>
                                    <?php else: ?>

                                    <?php endif; ?>
                                </li>
                            <?php endfor; ?>
                                <br>
                    </form>
                <?php else: ?>
                    <p class="text-center lead">没有该考点！</p>
                <?php endif; ?>
            </div>

        </div>
    </div>
    
    <div class="modal fade in" id="timeoutModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title text-danger" id="myModalLabel">
                        <i class="fa fa-exclamation-circle"></i> 考试时间提示
                    </h4>
                </div>
                <div class="modal-body">
                    <h5 class="text-center">
                        考试超时，已自动交卷！
                    </h5>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade in hidden"></div>
    <script src="<?php echo e(url('/js/jquery-3.3.1.min.js')); ?>"></script>
    <script>

    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>