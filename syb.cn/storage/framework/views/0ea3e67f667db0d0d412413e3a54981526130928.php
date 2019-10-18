<?php $__env->startSection('content'); ?>
    <section class="frame-header ">
        <p>
            <i class="fa fa-home"></i> <a href="<?php echo e(url('admin/home')); ?>" target="_parent">管理首页</a> »
            <mark> <i class="fa fa-bar-chart"></i> 成绩统计 </mark>
        </p>
    </section>
    <?php if(session('grades')): ?>
        <div class="alert alert-danger top-msg text-center" role="alert">
            <?php echo e(session('grades')); ?>

        </div>
    <?php endif; ?>
    <div class="container changepasswordtop">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h5> <i class="fa fa-chain-broken"></i> 成绩统计</h5>
            </div>
            <div class="panel-body">
                <?php if(isset($info)): ?>
                    <span class="text-info"><?php echo e($info); ?></span>
                <?php endif; ?>
                <?php if(isset($grades)): ?>
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>序号</th>
                            <th>学号</th>
                            <th>姓名</th>
                            <th>题库号</th>
                            <th>成绩</th>
                            <th>作答时间</th>
                        </tr>
                        </thead>
                        <tbody>
                        
                        <?php for($i=0;$i<count($grades);$i++): ?>
                            <tr>
                                <td><?php echo e($i + 1); ?></td>
                                <td><?php echo e($grades[$i]->student_id); ?></td>
                                <td><?php echo e($grades[$i]->student_name); ?></td>
                                <td><?php echo e($grades[$i]->paper_id); ?></td>
                                <?php if($grades[$i]->grades < 60): ?>
                                    <td class="text-danger"><?php echo e($grades[$i]->grades); ?></td>
                                <?php else: ?>
                                    <td class="text-success"><?php echo e($grades[$i]->grades); ?></td>
                                <?php endif; ?>
                                <td><?php echo e($grades[$i]->submit_time); ?></td>
                            </tr>
                        <?php endfor; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
            <div class="panel-footer">
                <p class="text-danger text-center">您可快捷操作！</p>
            </div>
        </div>
    </div>



<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>