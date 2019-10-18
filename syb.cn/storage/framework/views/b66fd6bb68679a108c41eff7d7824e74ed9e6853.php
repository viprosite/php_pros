<style>
    /*显示数字的文字*/
    .value {
        font-weight: bold;
        font-size: 15px;
    }
    /*滑动杆样式*/
    input[type="range"] {
        display: inline-block;
        margin-top: 10px;
        -webkit-appearance: none;
        background-color: #bdc3c7;
    /*//滑动杆宽度（无效？？？）*/
        width: 0px;
    /*//滑动杆高度*/
        height: 10px;
        border-radius: 5px;
        outline: 0;
    }
    /*滑动球样式*/
    input[type="range"]::-webkit-slider-thumb {
        -webkit-appearance: none;
        background-color: #e74c3c;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 2px solid white;
        cursor: pointer;
        transition: 0.3s ease-in-out;
    }
    input[type="range"]::-webkit-slider-thumb:hover {
        background-color: white;
        border: 2px solid #e74c3c;
    }
    input[type="range"]::-webkit-slider-thumb:active {
        transform: scale(1.3);
    }
    #range{
        width: 300px;
        position: relative;
    }
    .value2, .value4, .value44{
        position: absolute;
        right:-35px;
        top: -3px;
    }
    .score{
        position: absolute;
        top: -20px;
        right: -360px;
    }
    .score2, .score4, .score44{
        position: absolute;
        right: -320px;
        top: -5px;
    }
    .min{
        position: absolute;
        right:-20px;
        top: 5px;
    }

</style>
<?php $__env->startSection('content'); ?>
    <section class="frame-header ">
        <p>
            <i class="fa fa-home"></i> <a href="<?php echo e(url('admin/home')); ?>" target="_parent">管理首页</a> »
            <mark> <i class="fa fa-mortar-board"></i> 组卷系统 </mark>
        </p>
    </section>
    <?php if(session('top_msg')): ?>
        <div class="alert alert-info top-msg text-center" role="alert">
            <?php echo e(session('top_msg')); ?>

            <button type="button" class="close" aria-label="Close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>
    <div class="container changepasswordtop">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h5> <i class="fa fa-mortar-board"></i> 组卷系统</h5>
            </div>
            <div class="panel-body">
                <?php if(isset($res) && $res != '' && $res!=null): ?>
                    <b >
                       最近于 <mark> <?php echo e($res->add_time); ?> </mark> 组卷，<a href="<?php echo e(url('admin/view_paper/'.$paper_id->id)); ?>" target="main">点击此处</a> 查看组卷详情！
                    </b>
                <?php else: ?>
                    <b class="text-danger">
                        <i class="fa fa-info-circle"> 暂无组卷记录，填写下方相关内容进行组卷！</i>
                    </b>
                <?php endif; ?>

                <br>
            </div>
            <div class="well question-box">
                <form class="form-horizontal" method="post" action="" >
                    <?php echo e(csrf_field()); ?>

                    <div class="form-group ">
                        <label class="col-md-2 col-md-offset-3 control-label">题库中有单选题 <mark><?php echo e($num_4); ?></mark> 道</label>
                        <label class="col-md-2 control-label">判断题 <mark><?php echo e($num_2); ?></mark> 道</label>
                    </div>
                    <br>
                    <div class="form-group">
                        <label class="col-md-4 control-label">单选题个数：</label>
                        <div class="col-md-8">
                            <div id="range">
                                <input id="num_4"  name="num_4" type="range" min="0" max="30" step="1" value="0" class="form-control" required>
                                <span class="value4">0 道</span>
                                <span class="score col-md-5">
                                    <input type="number"  id="score_4" name="score_4" class="form-control col-md-1" placeholder="几分 / 题" required>
                                </span>
                                
                            </div>
                        </div>
                    </div>
                    
                        
                        
                            
                                
                                
                                
                                    
                                
                                
                            
                        
                    
                    <div class="form-group">
                        <label class="col-md-4 control-label">判断题个数：</label>
                        <div class="col-md-8">
                            <div id="range">
                                <input id="num_2" name="num_2" type="range" min="0" max="30" step="1" value="0" class="form-control" required>
                               <span class="value2">0 道</span>
                                <span class="score col-md-5">
                                    <input type="number"  id="score_2" name="score_2" class="form-control col-md-1" placeholder="几分 / 题" required>
                                </span>
                                
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-6 control-label">完成时间： <small>设置该套卷子多长时间内完成</small> </label>
                        
                        
                        
                        <div class="col-md-1" id="range">
                            <div class="col-md-6">
                                <input type="number" id="finish_time" name="finish_time" class="form-control col-md-1"  placeholder="分钟数" required>
                                <span class="min">分钟</span>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <label class="col-md-6 control-label">考试场次： <small>设置需要的考试场次数</small> </label>
                        
                        
                        
                        <div class="col-md-1" id="range">
                            <div class="col-md-6">
                                <input type="number" id="exam_num" name="exam_num" class="form-control col-md-1"  placeholder="场次数" required>
                                <span class="min">次</span>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <label class="col-md-6 control-label">每场容纳的学生数：</label>
                        
                        
                        
                        <div class="col-md-1" id="range">
                            <div class="col-md-6">
                                <input type="number" id="stu_num" name="stu_num" class="form-control col-md-1"  placeholder="学生数" required>
                                <span class="min">位</span>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                       <button class="btn btn-primary col-md-3 col-md-offset-4" id="addexam_btn">确认组卷</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

<?php $__env->stopSection(); ?>
<script src="/js/jquery-3.3.1.min.js"></script>
<script src="/libs/range/prefixfree.min.js"></script>
<script>
    window.onload = function () {
        var elem4 = document.querySelector('input[name="num_4"]');
        var rangeValue = function(){
            var newValue = elem4.value;
            var target = document.querySelector('.value4');
            target.innerHTML = newValue+ '道';
        }
        elem4.addEventListener("input", rangeValue);

//        var elem44 = document.querySelector('input[name="num_44"]');
//        var rangeValue = function(){
//            var newValue = elem44.value;
//            var target = document.querySelector('.value44');
//            target.innerHTML = newValue+ '道';
//        }
//        elem44.addEventListener("input", rangeValue);

        var elem2= document.querySelector('input[name="num_2"]');
        var rangeValue = function(){
            var newValue = elem2.value;
            var target = document.querySelector('.value2');
            target.innerHTML = newValue+ '道';
        }
        elem2.addEventListener("input", rangeValue);

//        document.querySelector('#addexam_btn').onclick = function () {
//            let num_2 = elem2.value        //判断题个数
//            let num_4 = elem4.value        //单选题个数
//            let score_2 = document.querySelector('#score_2').value      //判断题分值
//            let score_4 = document.querySelector('#score_4').value      //单选题分值
//            let finish_time = document.querySelector('#finish_time').value     //完成时间
//            let exam_num = document.querySelector('#exam_num').value     //场次数
//            console.log(num_2, num_4, score_2, score_4, finish_time, exam_num)
//
//        }

    }


</script>
<?php echo $__env->make('layouts.adminLayout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>