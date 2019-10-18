<?php $__env->startSection('title'); ?>
    管理员 - 后台首页
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="frame-header">
        <p> <i class="fa fa-home"></i> <a href="<?php echo e(url('admin/home')); ?>" target="_parent">管理首页</a> » <mark> 使用帮助 </mark> </p>
    </section>
        <div class="panel panel-info well-box center-block" style="width: 60%;padding: 10px;">
            <p class="lead panel-heading">
               <i class="fa fa-chain-broken"></i> 使用帮助
            </p>
            <p>
                <i class="fa fa-circle-o-notch"></i> 点击 <mark>左侧链接</mark> 进入对应管理页面 <br>
                <i class="fa fa-circle-o-notch"></i> 点击页面右上角 <mark>[ 修改密码 ]</mark> 修改您的当前登陆密码 <br />
            </p>
            
            
                
            
        </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>