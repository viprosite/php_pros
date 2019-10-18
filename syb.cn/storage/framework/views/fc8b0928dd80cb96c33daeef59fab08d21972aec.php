<?php $__env->startSection('title'); ?>
    SYB - 考点练习
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container newsbox center-block" style="width:85%">
        <div class="panel panel-info">
            <div class="panel-heading" style="display: flex;">
               <?php if($res[0]->question_type == 2): ?>
                    <span style="flex: 1;"> <i class="fa fa-chain-broken"></i> <mark><b>判断题</b></mark> 练习</span>
                <?php elseif($res[0]->question_type == 4): ?>
                    <span style="flex: 1;"> <i class="fa fa-chain-broken"></i> <mark><b>单选题</b></mark> 练习</span>
                <?php else: ?>
                    <span style="flex: 1;"> <i class="fa fa-chain-broken"></i> <mark><b>多选题</b></mark> 练习</span>
                <?php endif; ?>

            </div>
            <div class="well">
                <?php if(isset($res) && $res != '[]'): ?>
                    <form action="<?php echo e(url('check_question_type')); ?>" class="form-horizontal" method="post">
                        <?php echo e(csrf_field()); ?>

                        <input type="hidden" name="question_type" value="<?php echo e($res[0]->question_type); ?>">
                            <?php for($i=0;$i<count($res);$i++): ?>
                                <li class="list-group-item">
                                    <?php echo e($i+1); ?>. <?php echo e($res[$i]->title); ?> <br>
                                    <?php if($res[$i]->question_type == 44): ?>
                                        <span class="row">
                                            <label class="col-md-6">
                                            <input  name="<?php echo e($res[$i]->title); ?>[]" value="a" type="checkbox" required><?php echo e($res[$i]->answer_a); ?>

                                            </label>
                                            <label class="col-md-6" >
                                                <input name="<?php echo e($res[$i]->title); ?>[]" value="b" type="checkbox" required><?php echo e($res[$i]->answer_b); ?>

                                            </label>
                                        </span>

                                        <span class="row">
                                            <label class="col-md-6">
                                                <input  name="<?php echo e($res[$i]->title); ?>[]" value="c" type="checkbox" required><?php echo e($res[$i]->answer_c); ?>

                                            </label>
                                            <label class="col-md-6" >
                                                <input name="<?php echo e($res[$i]->title); ?>[]" value="d" type="checkbox" required><?php echo e($res[$i]->answer_d); ?>

                                            </label>
                                        </span><br><br>
                                        <?php elseif($res[$i]->question_type == 4): ?>
                                        <span class="row">
                                            <label class="col-md-6">
                                            <input  name="<?php echo e($res[$i]->title); ?>" value="a" type="radio"><?php echo e($res[$i]->answer_a); ?>

                                            </label>
                                            <label class="col-md-6" >
                                                <input name="<?php echo e($res[$i]->title); ?>" value="b" type="radio" required><?php echo e($res[$i]->answer_b); ?>

                                            </label>
                                        </span>

                                        <span css="row"radio>
                                            <label class="col-md-6">
                                                <input  name="<?php echo e($res[$i]->title); ?>" value="c" type="radio" required><?php echo e($res[$i]->answer_c); ?>

                                            </label>
                                            <label class="col-md-6" >
                                                <input name="<?php echo e($res[$i]->title); ?>" value="d" type="radio" required><?php echo e($res[$i]->answer_d); ?>

                                            </label>
                                        </span><br><br>
                                        <?php else: ?>
                                        <span class="row">
                                            <label class="col-md-6">
                                            <input  name="<?php echo e($res[$i]->title); ?>" value="y" type="radio" required> ✔
                                            </label>
                                            <label class="col-md-6" >
                                                <input name="<?php echo e($res[$i]->title); ?>" value="n" type="radio" required> ✖
                                            </label>
                                        </span>
                                    <?php endif; ?>
                                </li>
                            <?php endfor; ?>
                                <br>
                        <span class="row" style="display: flex;justify-content: center">
                            <button type="submit" class="btn btn-primary col-md-5" style="flex: 0.3;" id="submit_answer">提交答案</button>
                        </span>
                    </form>
                <?php else: ?>
                    <p class="text-center lead">没有该考点！</p>
                <?php endif; ?>
            </div>

        </div>
    </div>
    
    
        
            
                
                    
                        
                    
                    
                        
                    
                
                
                    
                        
                    
                
            
        
    
    <div class="modal-backdrop fade in hidden"></div>
    <script src="<?php echo e(url('/js/jquery-3.3.1.min.js')); ?>"></script>
    <script>

    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>