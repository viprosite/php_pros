<?php $__env->startSection('content'); ?>
    <section class="frame-header ">
        <p>
            <i class="fa fa-home"></i> <a href="<?php echo e(url('admin/home')); ?>" target="_parent">管理首页</a> »
            <mark><i class="fa fa-mortar-board"></i> 查看最近一次组卷详情</mark>
        </p>
    </section>
    <div class="container newsbox center-block" style="width: 85%;">

        <div class="panel panel-info">
            <div class="panel-heading" style="display: flex;">
                <?php if(isset($info) && $info != '[]' && $info!= null): ?>
                    <mark><?php echo e($info->add_time); ?> 出卷， 共<?php echo e($info->exam_num); ?>场考试，每场<?php echo e($info->stu_num); ?>人。</mark>
                <?php endif; ?>
            </div>
            <div class="well">
                <?php if(isset($res_2) || isset($res_4) || $res_44 ): ?>
                <form class="form-horizontal" onsubmit="return false">
                    <?php if(isset($res_2) && $res_2 != '[]' && count($res_2)>0): ?>
                        <?php for($i=0;$i<count($res_2);$i++): ?>
                            <li class="list-group-item">
                                <?php echo e($i+1); ?>. <?php echo e($res_2[$i]->title); ?> <br>
                                <span class="row">
                                        <label class="col-md-6">
                                        <input name="<?php echo e($res_2[$i]->title); ?>" value="y" type="radio" required> ✔
                                        </label>
                                        <label class="col-md-6">
                                            <input name="<?php echo e($res_2[$i]->title); ?>" value="n" type="radio" required> ✖
                                        </label>
                                </span>
                            </li>
                        <?php endfor; ?>
                    <?php endif; ?>
                    <?php if(isset($res_4) && $res_4 != '[]' && count($res_4)>0): ?>
                        <?php for($i=0;$i<count($res_4);$i++): ?>
                            <li class="list-group-item">
                                <?php echo e($i+count($res_2)+1); ?>. <?php echo e($res_4[$i]->title); ?> <br>
                                <span class="row">
                                        <label class="col-md-6">
                                        <input name="<?php echo e($res_4[$i]->title); ?>" value="a" type="radio"><?php echo e($res_4[$i]->answer_a); ?>

                                        </label>
                                        <label class="col-md-6">
                                        <input name="<?php echo e($res_4[$i]->title); ?>" value="b" type="radio" required><?php echo e($res_4[$i]->answer_b); ?>

                                        </label>
                                </span>
                                <span css="row" radio>
                                    <label class="col-md-6">
                                    <input name="<?php echo e($res_4[$i]->title); ?>" value="c" type="radio" required><?php echo e($res_4[$i]->answer_c); ?>

                                    </label>
                                    <label class="col-md-6">
                                    <input name="<?php echo e($res_4[$i]->title); ?>" value="d" type="radio" required><?php echo e($res_4[$i]->answer_d); ?>

                                    </label>
                                </span><br><br>
                            </li>
                         <?php endfor; ?>
                        <?php endif; ?>
                    <?php if(isset($res_44) && $res_44 != '[]' && count($res_44)>0): ?>
                        <?php for($i=0;$i<count($res_44);$i++): ?>
                             <li class="list-group-item">
                             <?php echo e($i+count($res_2)+1); ?>. <?php echo e($res_44[$i]->title); ?> <br>
                                 <span class="row">
                                     <label class="col-md-6">
                                     <input  name="<?php echo e($res_44[$i]->title); ?>[]" value="a" type="checkbox" required><?php echo e($res_44[$i]->answer_a); ?>

                                     </label>
                                     <label class="col-md-6" >
                                     <input name="<?php echo e($res_44[$i]->title); ?>[]" value="b" type="checkbox" required><?php echo e($res_44[$i]->answer_b); ?>

                                     </label>
                                 </span>
                                 <span class="row">
                                     <label class="col-md-6">
                                     <input  name="<?php echo e($res_44[$i]->title); ?>[]" value="c" type="checkbox" required><?php echo e($res_44[$i]->answer_c); ?>

                                     </label>
                                     <label class="col-md-6" >
                                     <input name="<?php echo e($res_44[$i]->title); ?>[]" value="d" type="checkbox" required><?php echo e($res_44[$i]->answer_d); ?>

                                     </label>
                                 </span> <br><br>
                             </li>
                        <?php endfor; ?>
                     <?php endif; ?>
                </form>
                <?php else: ?>
                    <p class="text-center text-danger lead">没有组卷记录！</p>
                <?php endif; ?>
            </div>

        </div>
    </div>

    <div class="modal-backdrop fade in hidden"></div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>