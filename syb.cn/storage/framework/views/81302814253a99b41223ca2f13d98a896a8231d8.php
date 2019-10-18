<?php $__env->startSection('content'); ?>
    <section class="frame-header ">
        <p>
            <i class="fa fa-home"></i> <a href="<?php echo e(url('admin/home')); ?>" target="_parent">管理首页</a> »
            <mark> <i class="fa fa-mortar-board"></i> 出题系统 </mark>
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
                <h5> <i class="fa fa-mortar-board"></i> 出题系统</h5>
            </div>
            <div class="panel-body">
               <?php if(isset($title) && $title != '' && isset($add_time) && $add_time != ''): ?>
                    <b class=" text-info">
                       <?php echo e($add_time); ?> 出题： <?php echo e($title); ?>

                    </b>
                   <?php else: ?>
                    <b class="col-md-offset-4 text-danger">
                        <i class="fa fa-info-circle"> 暂无题目，点击下方的按钮进行添加！</i>
                    </b>
               <?php endif; ?>
                   <br>
                <button class="btn btn-success col-md-8 col-md-offset-2 show-question-box">
                    <i class="fa fa-plus-square"></i>
                    添加题目
                </button>
            </div>
           <div class="well-sm question-box">
               <hr>
               <form action="" class="form-inline">
                   <div class="form-group" >
                       <label>题号： <mark class="title_number">1</mark></label>
                   </div>
                   <select class="form-control col-md-offset-1 questiontype" name="questiontype" style="width: 110px;">
                       <option>题型选择</option>
                       <option value="2">判断题</option>
                       <option value="4">单选题</option>
                       <option value="44">多选题</option>
                   </select>
                   <div class="form-group" >
                       <input type="text" name="title" class="form-control col-md-offset-1" style="width:400%;" placeholder="在此输入题目" required>
                   </div>
                   <br>
                   <br>
                   <div class="type-box">
                       

                       

                       

                   </div>
               </form>
           </div>

        </div>
    </div>

    <script src="/js/jquery-3.3.1.min.js"></script>
    <script src="/js/index.js"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>