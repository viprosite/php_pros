<?php $__env->startSection('title'); ?>
    SYB创业培训在线考试系统 - 用户注册
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="well well-lg" id="login-box">
        
            
        
        <?php if(session('msg')): ?>
            <div class="alert alert-warning" role="alert"><?php echo e(session('msg')); ?></div>
        <?php endif; ?>
        <div>
            <span class="h5 login-title center-block col-md-offset-2"> <i class="fa fa-user-plus" aria-hidden="true"></i> 用户注册 </span>
            <hr />
            <form class="form-horizontal" action="" method="post">
                <?php echo e(csrf_field()); ?>

                
                    
                    
                        
                        
                    
                
                <div class="form-group" style="position: relative;">
                    <label class="col-md-3 control-label">学号:</label>
                    <div class="col-md-8 input-group">
                        <span class="input-group-addon"> <i class="fa fa-fw fa-sort-numeric-asc"></i></span>
                        <input type="number" id="student_id" name="student_id" class="form-control" placeholder="学号" required>
                    </div>
                    <span class="checkname">  </span>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">姓名:</label>
                    <div class="col-md-8 input-group">
                        <span class="input-group-addon"> <i class="fa fa-fw fa-id-card"></i></span>
                        <input type="text" name="student_name" class="form-control" placeholder="姓名" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">密码:</label>
                    <div class="col-md-8 input-group">
                        <span class="input-group-addon"> <i class="fa fa-fw fa-key"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="密码" required>
                    </div>
                    <span class="help-block col-md-offset-4 pass-help">6-12位 字母[a-z A-Z] 数字[0-9] 下划线_ 的组合</span>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">邮箱:</label>
                    <div class="col-md-8 input-group">
                        <span class="input-group-addon"> <i class="fa fa-fw fa-envelope"></i></span>
                        <input type="email" name="email" class="form-control" placeholder="邮箱地址" required>
                    </div>
                </div>
                <div class="form-group" id="code-box">
                    <label class="col-md-3 control-label">验证码:</label>
                    <div class="col-md-5 col-xs-6 col-sm-8 input-group">
                        <span class="input-group-addon"> <i class="fa fa-fw fa-info-circle"></i></span>
                        <input type="text" name="code" class="form-control" placeholder="验证码" required>
                    </div>
                    <img class="code img-rounded" src="<?php echo e(url('code')); ?>" alt="验证码" title="点击可更换" onclick="this.src='<?php echo e(url('code')); ?>?'+Math.random()">​​​​
                </div>
                    
                <div class="form-group">
                    <div class="col-md-10 col-md-offset-4 col-sm-10 col-sm-offset-3 col-xs-10 col-xs-offset-2">
                        <button type="submit" class="btn btn-success"> <i class="fa fa-user-plus"></i> 注册 </button>
                        <span class="col-xs-offset-1 col-sm-offset-2 col-md-offset-1"></span>
                        <button type="reset" class="btn btn-warning col-md-offset-2"> <i class="fa fa-mail-reply"></i> 重置 </button>
                    </div>
                </div>

               <div class="form-group">
                   <div class="col-md-10 col-md-offset-5">
                       <a href="<?php echo e(url('/')); ?>" class="regToLogin"> <i class="fa fa-lg fa-hand-o-right" style="color: #666;"></i> 已经注册？点我登陆</a>
                   </div>
               </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<script src="/js/jquery-3.3.1.min.js"></script>
<script src="/js/index.js"></script>
<?php echo $__env->make('layouts.layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>