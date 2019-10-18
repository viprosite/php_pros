<?php $__env->startSection('title'); ?>
    SYB - 考试新闻
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container newsbox center-block" style="width: 85%;">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h5> <i class="fa fa-chain-broken"></i> 查看所有公告项</h5>
            </div>
            <?php if(count($news) != 0): ?>
                <ul class="list-group">
                    <?php for($i=0;$i<count($news);$i++): ?>
                        <li class="list-group-item">
                            <a href="<?php echo e(url('newdetail/'.$news[$i]->id)); ?>" target="">
                                <i class="fa fa-dot-circle-o"></i>
                                <mark><?php echo e($news[$i]->operator); ?></mark>
                                <span>发布于：<?php echo e($news[$i]->add_at); ?></span> &nbsp;&nbsp;
                                <span class="text-right">查看次数：<?php echo e($news[$i]->seenums); ?></span> &nbsp;&nbsp;
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>