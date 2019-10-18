<?php $__env->startSection('title'); ?>
    SYB - 考点练习
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container newsbox center-block" style="width: 85%;">
        <div class="panel panel-info">
            <div class="panel-heading" style="display: flex;">
                <span style="flex: 1;"> <i class="fa fa-chain-broken"></i> <mark><b><?php echo e($res[0]->test_point); ?></b></mark> 练习</span>
                
                    
                
                
                    
                
            </div>
            <div class="well">
                <?php if(isset($res) && $res != '[]'): ?>
                    <form action="<?php echo e(url('check_test_point')); ?>" class="form-horizontal" method="post">
                        <?php echo e(csrf_field()); ?>

                        <input type="hidden" name="test_point" value="<?php echo e($res[0]->test_point); ?>">
                        <input type="hidden" name="type" value="<?php echo e($res[0]->question_type); ?>">
                            <?php for($i=0;$i<count($res);$i++): ?>
                                <li class="list-group-item">
                                    <?php echo e($i+1); ?>. <?php echo e($res[$i]->title); ?> <br>
                                    <?php if($res[$i]->question_type == 44): ?>
                                        <span class="row">
                                            <label class="col-md-6">
                                            <input  name="<?php echo e($res[$i]->title); ?>[]" value="a" type="checkbox"><?php echo e($res[$i]->answer_a); ?>

                                            </label>
                                            <label class="col-md-6" >
                                                <input name="<?php echo e($res[$i]->title); ?>[]" value="b" type="checkbox"><?php echo e($res[$i]->answer_b); ?>

                                            </label>
                                        </span>

                                        <span class="row">
                                            <label class="col-md-6">
                                                <input  name="<?php echo e($res[$i]->title); ?>[]" value="c" type="checkbox"><?php echo e($res[$i]->answer_c); ?>

                                            </label>
                                            <label class="col-md-6" >
                                                <input name="<?php echo e($res[$i]->title); ?>[]" value="d" type="checkbox"><?php echo e($res[$i]->answer_d); ?>

                                            </label>
                                        </span>
                                        <?php elseif($res[$i]->question_type == 4): ?>
                                        <span class="row">
                                            <label class="col-md-6">
                                            <input  name="<?php echo e($res[$i]->title); ?>" value="a" type="radio"><?php echo e($res[$i]->answer_a); ?>

                                            </label>
                                            <label class="col-md-6" >
                                                <input name="<?php echo e($res[$i]->title); ?>" value="b" type="radio"><?php echo e($res[$i]->answer_b); ?>

                                            </label>
                                        </span>

                                        <span css="row"radio>
                                            <label class="col-md-6">
                                                <input  name="<?php echo e($res[$i]->title); ?>" value="c" type="radio"><?php echo e($res[$i]->answer_c); ?>

                                            </label>
                                            <label class="col-md-6" >
                                                <input name="<?php echo e($res[$i]->title); ?>" value="d" type="radio"><?php echo e($res[$i]->answer_d); ?>

                                            </label>
                                        </span>
                                        <?php else: ?>
                                        <span class="row">
                                            <label class="col-md-6">
                                            <input  name="<?php echo e($res[$i]->title); ?>" value="y" type="radio"> ✔
                                            </label>
                                            <label class="col-md-6" >
                                                <input name="<?php echo e($res[$i]->title); ?>" value="n" type="radio"> ✖
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
//        $(function () {
//            let m = 28;   //考试时间  分种
//            let s = 58;    //考试时间  秒
//            let mtimer = setInterval(
//                function () {
//                    $('#m').text(m)
//                    m--
//                    if(m == 5){
//                        $.growl.notice({
//                            title: "考试时间提示",
//                            message: "考试时间剩余5分钟!"
//                        });
//                    }
//                    if(m < -1){
//                        clearInterval(mtimer)
//                        clearInterval(stimer)
//                        $('#m').text(0)
//                        timeout()
//                    }
//                }, 60000
//            )
//            let stimer = setInterval(
//                function () {
//                    $('#s').text(s)
//                    s--
//                    if(s < 0){
//                        s=59
//                    }
//                }, 1000
//            )
//            $('#submit_answer').click(function () {
//                console.log(m,s)
//            })
//            function timeout(){
//                $('.modal-backdrop').removeClass('hidden')
//                $('#timeoutModal').css('display','block')
//            }
//            $('[class="close"]').click(function () {
//                $('.modal-backdrop').addClass('hidden')
//                $('#timeoutModal').css('display','none')
//            })
//
//
//
//
//
//        })
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>