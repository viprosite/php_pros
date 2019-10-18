<?php $__env->startSection('content'); ?>
    <section class="frame-header ">
        <p>
            <i class="fa fa-home"></i> <a href="<?php echo e(url('admin/home')); ?>" target="_parent">管理首页</a> »
            <mark> <i class="fa fa-check-square-o"></i> 考点管理 </mark>
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
                <h5> <i class="fa fa-check-square-o"></i> 考点一览 </h5>
            </div>
            <br />
            <?php if(isset($test_point) && count($test_point)>0): ?>
                
                    
                        
                            
                            
                            
                            
                        
                        
                            
                            
                            
                            
                        
                        
                            
                            
                        
                    
                
                
                <table class="table table-striped table-bordered table-hover table-condensed">
                    <thead>
                    <tr></tr>
                    <tr>
                        <th>序号</th>
                        <th>考点</th>
                        <th>对应题目数</th>
                        
                    </tr>
                    </thead>
                    <tbody>
                    <?php for($i=0;$i<count($test_point);$i++): ?>
                        <tr>
                            <td class="index"><?php echo e($i + 1); ?></td>
                            <td class="">
                                <?php echo e($test_point[$i]); ?>

                            </td>
                            <td class="question_num">
                                <?php echo e($nums[$i]); ?>

                            </td>
                            <td>
                                
                            </td>
                            
                                
                            
                        </tr>
                    <?php endfor; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <b class="text-warning col-md-offset-3 col-sm-offset-2"> <i class="fa fa-info-circle"></i> 暂无考点，您可点击左侧的 <mark>[ 题库管理 ]</mark> 菜单进行在线出题操作。</b>
            <?php endif; ?>
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