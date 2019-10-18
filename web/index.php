<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>挑刺 - 首页</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/index.css">
</head>

<body>

  <nav class="nav">
    <div class="container">
      <div class="nav_wrap">
        <a class="nav_l" href="#"><img src="./img/Logo.png" alt=""></a>
        <div class="nav_r">
        <?php 
          $servername = "125.65.82.84";
          $username = "prick";
          $password = "prick666";
          $database = 'prick';
          // 创建连接
          $con = new mysqli($servername, $username, $password);
          // 检测连接
          if ($con->connect_error) {
              die("连接失败: " . $con->connect_error);
          }else{
            mysqli_select_db($con,$database);//选择数据库 
            $sql= 'SELECT * FROM tc_appinfo order by createtime desc limit 1';
            $result = mysqli_query( $con, $sql );
            while($row = mysqli_fetch_array($result))
            {
             $iosurl = $row['iosurl'];
              $androidurl = $row['androidurl'];
            }
          }
        ?>
        <a href="<?php echo('http://admin.tiaociapp.com/'.$androidurl);?>">Android下载</a>
        <?php if($iosurl){
          echo('<a href="<?php echo(\'http://admin.tiaociapp.com/\'.$iosurl); ?>">Ios下载</a></div>');
        }else{
          echo('<a href="#">Ios下载</a></div>');
        }
         ?>
        
      </div>
    </div>
  </nav>
  <section class="sec1">
    <div class="container">
      <div class="row">
        <div class="col-xs-12 col-md-6 pic">
          <div class="home_page"><img src="./img/iPhoneBlue.png" alt=""></div>
        </div>
        <div class="col-xs-12 col-md-6 txt">
          <div class="text">
            <h3>挑刺APP</h3>
            <h3>你是我在万人中选择的人</h3>
            <p>有趣的婚恋平台</p>
            <a class="down"></a>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="sec2">
    <div class="container">
      <div class="row">
        <div class="col-xs-12 col-sm-4 item chat">
          <div class="con_icon"><img src="img/chat.png"></div>
          <h5>聊天</h5>
          <p>随时随地，语音、图片0距离分享</p>
        </div>
        <div class="col-xs-12 col-sm-4 item prick">
          <div class="con_icon"><img src="img/prick.png"></div>
          <h5>挑刺</h5>
          <p>随时随地寻找收入距离匹配的伴侣</p>
        </div>
        <div class="col-xs-12 col-sm-4 item card">
          <div class="con_icon"><img src="img/scan.png"></div>
          <h5>大冒险、晋级卡</h5>
          <p>和TA的距离更进一步</p>
          <p>大冒险勇敢表白、晋级卡随机出牌</p>
        </div>
      </div>
    </div>
  </section>
  <section class="sec3">
    <div class="container">
      <div class="row">
        <div class="col-xs-12 col-sm-6 sec3_l">
          <div class="sec3_l_t">
            <div class="outside">
              <div class="mid">
                <div class="inside"></div>
              </div>
            </div>
            <div class="sec3_l_b">
              <h3>友爱</h3>
              <h3>聊天</h3>
            </div>
          </div>
        </div>
        <div class="col-xs-12 col-sm-6 sec3_r">
          <div class="avatar_l"></div>
          <div class="sec3_chat"></div>
          <div class="avatar_r"></div>
        </div>
      </div>
    </div>
  </section>
  <section class="sec3 prick_">
    <div class="container">
      <div class="row">
        <div class="col-xs-12 col-sm-6 sec3_l">
          <div class="sec3_l_t">
            <div class="outside">
              <div class="mid">
                <div class="inside prick"></div>
              </div>
            </div>
            <div class="sec3_l_b">
              <h3>挑刺</h3>
              <p>你是我在万人中选择的人</p>
            </div>
          </div>
        </div>
        <div class="col-xs-12 col-sm-6 sec3_r">
          <div class="sec3_chat prick"></div>
        </div>
      </div>
    </div>
  </section>
  <section class="sec3 card">
    <div class="container">
      <div class="row">
        <div class="col-xs-12 col-sm-6 sec3_l">
          <div class="sec3_l_t">
            <div class="outside">
              <div class="mid">
                <div class="inside prick"></div>
              </div>
            </div>
            <div class="sec3_l_b">
              <h3 class="top">扫一扫</h3>
              <h3>选择大冒险、晋级卡</h3>
              <p>扫码倾城酒瓶上的二维码进入游戏，选择晋级卡<br>或者大冒险，您和TA的距离将更近一步</p>
            </div>
          </div>
        </div>
        <div class="col-xs-12 col-sm-6 sec3_r">
          <div class="sec3_chat card"></div>
        </div>
      </div>
    </div>
  </section>
  <section class="show_con">
    <div class="container">
      <div class="row">
        <div class="col-xs-12 col-md-3 show"><img src="img/show_1.png" alt=""></div>
        <div class="col-xs-12 col-md-3 show"><img src="img/show_2.png" alt=""></div>
        <div class="col-xs-12 col-md-3 show"><img src="img/show_3.png" alt=""></div>
        <div class="col-xs-12 col-md-3 show"><img src="img/show_4.png" alt=""></div>
      </div>
    </div>
  </section>
  <footer><img src="img/ContactBg.png" alt=""></footer>
</body>


</html>