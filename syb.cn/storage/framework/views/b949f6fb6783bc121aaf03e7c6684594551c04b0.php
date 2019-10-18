<?php $__env->startSection('title'); ?>
    管理员 - 查看已发公告
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="frame-header ">
        <p>
            <i class="fa fa-home"></i> <a href="<?php echo e(url('admin/home')); ?>" target="_parent">管理首页</a> »
            <mark> <i class="fa fa-newspaper-o"></i> 查看已发公告 </mark>
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
                <h5> <i class="fa fa-chain-broken"></i> 查看已发公告</h5>
            </div>
            <div class="panel-body">
                <?php if(count($news) != 0): ?>
                    <ul class="list-group">
                        <?php for($i=0;$i<count($news);$i++): ?>
                        <li class="list-group-item">
                            <a href="<?php echo e(url('admin/newdetail/'.$news[$i]->id)); ?>" target="main">
                                <i class="fa fa-dot-circle-o"></i>
                                <span>发布时间：<?php echo e($news[$i]->add_at); ?></span> &nbsp;&nbsp;
                                <b><?php echo e($news[$i]->title); ?></b>
                            </a>
                        </li>
                        <?php endfor; ?>
                    </ul>
                    <?php else: ?>
                    <p>
                        <i class="fa fa-info-circle"></i>
                        暂未发布任何公告，您可点击左侧 <mark>发布公告</mark> 菜单进行添加。
                    </p>
                <?php endif; ?>
            </div>

        </div>
    </div>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>