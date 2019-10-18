<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $__env->yieldContent('title'); ?></title>
    <link rel="stylesheet" href="/libs/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/libs/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="\libs\jquery.growl.css">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/mediaQuery.css">

</head>
<body>
<div class="container">
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            ​​
            <!-- Brand and toggle get grouped for better mobile display小屏显示  商标图和按钮 -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo e(url('home')); ?>">
                    <img src="/imgs/logo.png" id="logo" class="img-responsive img-thumbnail"/>

                </a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling 大屏展开显示 -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <span id="logo-right-text">SYB 创业培训   在线考试系统</span>
                <ul class="nav navbar-nav navbar-right" id="header-nav">
                    <li class="text-center"> <a href="<?php echo e(url('home')); ?>"><i class="fa fa-home"></i> 首页 </a></li>
                    <li class="text-center"> <a href="<?php echo e(url('news')); ?>"><i class="fa fa-newspaper-o"></i> 考试新闻 </a></li>
                    <li class="dropdown text-center" >
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user"></i> 用户中心 <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <?php if(session('user')): ?>
                                <li class="text-center" id="user-name"> <?php echo e(session('user')); ?> </li>
                                <li class="text-center"><a href="<?php echo e(url('history_msg')); ?>"> <i class="fa fa-commenting fa-fw"></i> 历史消息</a></li>
                                
                                
                                <li class="text-center"><a href="<?php echo e(url('my_info')); ?>"> <i class="fa fa-info-circle fa-fw"></i> 我的信息</a></li>
                                <li role="separator" class="divider"></li>
                                <li class="text-center"><a href="<?php echo e(url('logout')); ?>" style="color: red;"> <i class="fa fa-sign-out"></i> 退出</a></li>
                            <?php else: ?>
                                <li class="text-center"><a href="<?php echo e(url('/')); ?>" > <i class="fa fa-sign-in fa-fw"></i> 登陆</a></li>
                                <li class="text-center"><a href="<?php echo e(url('register')); ?>"> <i class="fa fa-user-plus fa-fw"></i> 注册</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
    <?php if(session('top_msg')): ?>
        <div class="alert alert-warning" role="alert">
            <?php echo e(session('top_msg')); ?>

            <button type="button" class="close" aria-label="Close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php echo $__env->yieldContent('content'); ?>

    <footer style="margin-top: 50px;">
        <p class="text-center text-success">
            CopyRight © 2019. 西南交大峨眉 计算机系15级  毕业设计
        </p>
    </footer>
</div>
<script src="/js/jquery-3.3.1.min.js"></script>
<script src="/libs/bootstrap/js/bootstrap.min.js"></script>
<script src="/libs\jquery.growl.js"></script>
</body>
</html>