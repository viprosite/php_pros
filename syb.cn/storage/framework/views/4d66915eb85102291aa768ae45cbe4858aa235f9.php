<?php $__env->startSection('content'); ?>
    <section class="frame-header ">
        <p>
            <i class="fa fa-home"></i> <a href="<?php echo e(url('admin/home')); ?>" target="_parent">管理首页</a> »
            <mark> <i class="fa fa-book"></i> 题库管理 </mark>
        </p>
    </section>
    <?php if(session('top_msg')): ?>
        <div class="alert alert-danger top-msg text-center" role="alert">
            <?php echo e(session('top_msg')); ?>

        </div>
    <?php endif; ?>
    <div class="container changepasswordtop">
        <div class="panel panel-info">
            <div class="panel-heading">
                <span> <i class="fa fa-info-circle"></i> 组卷记录：</span><br>
                <?php if(count($res) != 0): ?>
                    <ul class="list-group">
                        <?php for($i=0;$i<count($res);$i++): ?>
                            <a href="<?php echo e(url('admin/view_paper/'.$res[$i]->id)); ?>" >
                                <li class="list-group-item">
                                    <mark><?php echo e($res[$i]->add_time); ?></mark> 组卷，
                                    共判断题<?php echo e(count($res[$i]->question_2_id)); ?>道，每道<?php echo e($res[$i]->score_2); ?>分；
                                    单选题<?php echo e(count($res[$i]->question_4_id)); ?>道，每道<?php echo e($res[$i]->score_4); ?>分；
                                    完成时间<?php echo e($res[$i]->finish_time); ?>分钟内。
                                </li>
                            </a>
                        <?php endfor; ?>
                    </ul>
                <?php else: ?>
                    <p>
                        <i class="fa fa-info-circle"></i>
                        暂无组卷记录。
                    </p>
                <?php endif; ?>
            </div>
            <br />
            <hr>
            <span> <i class="fa fa-info-circle"></i> 题库详情：</span><br>
            <div class="panel panel-body">
                <?php if(isset($question_bank) && count($question_bank)>0): ?>
                    <table class="table table-striped table-bordered table-hover table-condensed">
                        <thead>
                        <tr></tr>
                        <tr>
                            <th>序号</th>
                            <th>题型</th>
                            <th>考点</th>
                            <th>题目</th>
                            <th>答案</th>
                            <th>正确答案</th>
                            <th>添加时间</th>
                            
                        </tr>
                        </thead>
                        <tbody>
                        <?php for($i=0;$i<count($question_bank);$i++): ?>
                            <tr>
                                <td><?php echo e($i + 1); ?></td>
                                <td>
                                    <?php if($question_bank[$i]->question_type == 2): ?>
                                        判断题
                                    <?php elseif($question_bank[$i]->question_type == 4): ?>
                                        单选题
                                    <?php else: ?>
                                        多选题
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php echo e($question_bank[$i]->test_point); ?>

                                </td>
                                <td><?php echo e($question_bank[$i]->title); ?></td>
                                <td>
                                    <?php if($question_bank[$i]->answer_a != ''): ?>
                                        A.<?php echo e($question_bank[$i]->answer_a); ?><br>
                                    <?php else: ?>
                                        <?php echo e($question_bank[$i]->answer_a); ?><br>
                                    <?php endif; ?>
                                    <?php if($question_bank[$i]->answer_b != ''): ?>
                                        B.<?php echo e($question_bank[$i]->answer_b); ?><br>
                                    <?php else: ?>
                                        <?php echo e($question_bank[$i]->answer_b); ?><br>
                                    <?php endif; ?>
                                    <?php if($question_bank[$i]->answer_c != ''): ?>
                                        C.<?php echo e($question_bank[$i]->answer_c); ?><br>
                                    <?php else: ?>
                                        <?php echo e($question_bank[$i]->answer_c); ?><br>
                                    <?php endif; ?>
                                    <?php if($question_bank[$i]->answer_d != ''): ?>
                                        D.<?php echo e($question_bank[$i]->answer_d); ?><br>
                                    <?php else: ?>
                                        <?php echo e($question_bank[$i]->answer_d); ?><br>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($question_bank[$i]->answer_ok == 'n'): ?>
                                        错误
                                    <?php elseif($question_bank[$i]->answer_ok == 'y'): ?>
                                        正确
                                    <?php else: ?>
                                        <?php echo e(strtoupper($question_bank[$i]->answer_ok)); ?>

                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($question_bank[$i]->add_time); ?></td>
                                
                                    
                                
                            </tr>
                        <?php endfor; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <b class="text-warning col-md-offset-2 col-sm-offset-2"> <i class="fa fa-info-circle"></i> 暂无题库，您可点击下方的 <mark>[ 导入题库 ] </mark>导入本地已有题库或点击 <mark>[ 出题系统 ]</mark> 进行在线出题操作。</b>
                    <br><br>
                <?php endif; ?>
            </div>
            <div class="panel-footer">
                <a href="<?php echo e(url('admin/pullin')); ?>" target="main">
                    <button class="btn btn-primary col-md-offset-4 col-sm-offset-3 col-xs-offset-3"> <i class="fa fa-tasks"></i> 导入题库 </button>
                </a>
                <a href="<?php echo e(url('admin/addquestions')); ?>" target="main">
                    <button class="btn btn-primary col-md-offset-1 col-sm-offset-3 col-xs-offset-3"> <i class="fa fa-mortar-board"></i> 出题系统 </button>
                </a>
            </div>
        </div>
    </div>



<?php $__env->stopSection(); ?>    
<?php echo $__env->make('layouts.adminLayout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>