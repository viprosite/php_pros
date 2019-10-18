<?php
namespace app\Api\controller;

use think\Controller;
use think\Db;
use think\Request;

class Api extends Controller{

    /**
     *文本消息的数据持久化
     */
    public function save_message(){
            $message = input("post.");
            $datas['fromid']=$message['fromid'];
            // $datas['fromname']= $this->getName($datas['fromid']);
            $datas['toid']=$message['toid'];
            // $datas['toname']= $this->getName($datas['toid']);
            $datas['content']=$message['data'];
            // $datas['covercontent'] = isset($message['covercontent'])?$message['covercontent']:"";
            //$datas['time']=$message['time'];
            $datas['isread']=$message['isread'];
            //$datas['isread']=0;
            $datas['type'] = 1;
        $datas['createtime'] = date('Y-m-d H:i:s');
            $res = Db::name("communication")->insert($datas);
            return $res;
    }

    /**
     * 根据用户id返回用户姓名
     */
    public function getName($uid){
        $userinfo = Db::name("user")->where('uname',$uid)->field('nickname')->find();
        return $userinfo['nickname'];
    }


    public function getUserInfo($uid){
        $userinfo = Db::name("user")->where('uname',$uid)->find();
        $info = array(
            'uid' => $userinfo['uname'],
            'nickname'=>$userinfo['nickname'],
            'headimgurl'=>$userinfo['headimgurl'],
            'sex'=>$userinfo['sex']
            // 'relname'=>$userinfo['relname']
        );
        return json($info);
    }


    public function checkUserInfo(){
        $uid = input('uid');
        $pwd = input('password');

        $userinfo = Db::name("user")->where('(id=:uid and pwd=:pwd) || (nickname=:uid2 and pwd=:pwd2)',['uid'=>$uid,'pwd'=>$pwd,'uid2'=>$uid,'pwd2'=>$pwd])->find();
        if($userinfo){

            $info = array(
                'id' => $userinfo['id'],
                'nickname'=>$userinfo['nickname'],
                'headimgurl'=>$userinfo['headimgurl'],
                'sex'=>$userinfo['sex'],
                'relname'=>$userinfo['relname']
            );
            return json_encode($info);
        }else{
            return json_encode(array(
                'id' => 0
            ));
        }
    }



    /**
     * 根据用户id获取聊天双方的头像信息；
     */
    public function get_head(){
            $fromid = input('fromid');
            $toid = input('toid');
            $frominfo = Db::name('user')->where('id',$fromid)->field('headimgurl')->find();
            $toinfo = Db::name('user')->where('id',$toid)->field('headimgurl')->find();
            return [
                'from_head'=>$frominfo['headimgurl'],
                'to_head'=>$toinfo['headimgurl']
            ];
    }

    /**
     * 根据用户id返回用户姓名；
     */
    public function get_name($uid){
        if(Request::instance()->isAjax()){
            $uid = input('uid');
            $toinfo = Db::name('user')->where('uname',$uid)->field('nickname')->find();
            return ["toname"=>$toinfo['nickname']];
        }
    }

    public function get_namestring($uid){
        $uid = input('uid');
        $toinfo = Db::name('user')->where('uname',$uid)->field('nickname')->find();
        echo $toinfo['nickname'];
    }

    /**
     * 页面加载返回聊天记录
     */
    public function load(){
            $fromid = input('fromid');
            $toid = input('toid');
             $count =  Db::name('communication')->where('((fromid=:fromid and toid=:toid) || (fromid=:toid1 and toid=:fromid1)) && status = 1',['fromid'=>$fromid,'toid'=>$toid,'toid1'=>$toid,'fromid1'=>$fromid])->count('id');
            if($count>=10){
             $message = Db::name('communication')->where('((fromid=:fromid and toid=:toid) || (fromid=:toid1 and toid=:fromid1)) && status = 1',['fromid'=>$fromid,'toid'=>$toid,'toid1'=>$toid,'fromid1'=>$fromid])->limit($count-10,10)->order('id')->select();
            }else{
              $message = Db::name('communication')->where('((fromid=:fromid and toid=:toid) || (fromid=:toid1 and toid=:fromid1)) && status = 1',['fromid'=>$fromid,'toid'=>$toid,'toid1'=>$toid,'fromid1'=>$fromid])->order('id')->select();
            }
            return json_encode($message);
    }
    ###返回所有聊天记录
    public function loadAll(){
        $fromid = input('fromid');
        $toid = input('toid');
        $count =  Db::name('communication')->where('((fromid=:fromid and toid=:toid) || (fromid=:toid1 and toid=:fromid1)) && status = 1',['fromid'=>$fromid,'toid'=>$toid,'toid1'=>$toid,'fromid1'=>$fromid])->count('id');
        $message = Db::name('communication')->where('((fromid=:fromid and toid=:toid) || (fromid=:toid1 and toid=:fromid1)) && status = 1',['fromid'=>$fromid,'toid'=>$toid,'toid1'=>$toid,'fromid1'=>$fromid])->order('id')->select();
        return json_encode($message);
    }


    /**
     * 上传语音，返回图片地址
     */
    public function uploadVoice(){
        $file = $_FILES['file'];
        $fromid = input('fromid');
        $toid = input('toid');
        $online = input('online');
        $len = input('len');

        $filename =  uniqid("chat_voice_",false);
        $uploadpath = ROOT_PATH."public/uploads/";
        $file_up = $uploadpath.$filename.'.amr';
        $re = move_uploaded_file($file['tmp_name'],$file_up);
        if($re){
            $name = $filename.'.amr';
            $data['content'] = $name;
            $data['fromid'] = $fromid;
            $data['toid'] = $toid;
            $data['covercontent'] = $len;
            // $data['fromname'] = $this->getName($data['fromid']);
           // $data['toname'] = $this->getName($data['toid']);
           // $data['time'] = time();
            $data['isread'] = $online;
            $data['createtime'] = date('Y-m-d H:i:s');
//            $data['isread'] = 0;
            $data['type'] = 7;
            $message_id = Db::name('communication')->insertGetId($data);
            if($message_id){
                return json(['status'=>'ok','img_name'=>$name]);
            }else{
                return json(['status'=>'false']);
            }

        }
    }

    /**
     * 上传图片，返回图片地址
     */
    public function uploadimg(){
        $file = $_FILES['file'];
        $fromid = input('fromid');
        $toid = input('toid');
        $online = input('online');
        $suffix =  strtolower(strrchr($file['name'],'.'));
        $type = ['.jpg','.jpeg','.gif','.png'];
        if(!in_array($suffix,$type)){
            return json(['status'=>'img type error']);
        }

        if($file['size']/1024>5120){
            return json(['status'=>'img is too large']);
        }

        $filename =  uniqid("chat_img_",false);
        $uploadpath = ROOT_PATH."public/uploads/";
        $file_up = $uploadpath.$filename.$suffix;
        $re = move_uploaded_file($file['tmp_name'],$file_up);

        if($re){
            $name = $filename.$suffix;
            $data['content'] = $name;
            $data['fromid'] = $fromid;
            $data['toid'] = $toid;
            // $data['fromname'] = $this->getName($data['fromid']);
            // $data['toname'] = $this->getName($data['toid']);
            // $data['time'] = time();
            // $data['isread'] = $online;
            $data['isread'] = 0;
            $data['type'] = 2;
            $message_id = Db::name('communication')->insertGetId($data);
            if($message_id){
                return json(['status'=>'ok','img_name'=>$name]);
            }else{
                return json(['status'=>'false']);
            }

        }
    }

    public function setnotshow(){
        //软删除全部信息
        $toid = input('toid');
        $uid = input('uid');
        $res = Db::name('communication')->where('(toid=:fromid) && status=1 && fromid=:toid',['fromid'=>$uid,'toid'=>$toid])->update(['status'=>0]);
        //$res2 = Db::name('communication')->where('(toid=:fromid) && status=1 && fromid=:toid',['fromid'=>$uid,'toid'=>$toid])->update(['status'=>0]);
        if($res){
            echo json_encode(array(
                'status'=>'ok',
                'res' => $res
            ));
        }else{
            echo json_encode(array(
                'status'=>'false'
            ));
        }
    }







    public function uploadvideo(){

        $file = $_FILES['file'];
        $fromid = input('fromid');
        $toid = input('toid');
        $online = input('online');

        $suffix =  strtolower(strrchr($file['name'],'.'));
//        $type = ['.jpg','.jpeg','.gif','.png'];
//        if(!in_array($suffix,$type)){
//            return ['status'=>'img type error'];
//        }
//
//        if($file['size']/1024>5120){
//            return ['status'=>'img is too large'];
//        }

        $filename =  uniqid("chat_video_",false);
        $uploadpath = ROOT_PATH."public/uploads/";
        $file_up = $uploadpath.$filename.$suffix;
        $re = move_uploaded_file($file['tmp_name'],$file_up);

        if($re){
            $name = $filename.$suffix;
            $data['content'] = $name;
            $data['fromid'] = $fromid;
            $data['toid'] = $toid;
            //$data['fromname'] = $this->getName($data['fromid']);
            //$data['toname'] = $this->getName($data['toid']);

            // $data['time'] = time();
            // $data['isread'] = $online;
            $data['isread'] = 0;
            $data['type'] = 3;

            $input = $file_up;
            $output_temp = uniqid("chat_cover_",false).".jpg";

            $data['covercontent'] = $output_temp;

            $output =$uploadpath.$output_temp;

            //ffmpeg获取视频帧 -i 后面是输出 -y 是质量 -f 是输出格式  -t 时间  
            $command ="/monchickey/ffmpeg/bin/ffmpeg -i {$input} -y -f image2 -t 0.05 -s 800*400 {$output}";
            shell_exec($command);
            
            // return 1;
            $message_id = Db::name('communication')->insertGetId($data);

            if($message_id){
                return json_encode(['status'=>'ok','video_name'=>$name,'covercontent'=>$output]);
            }else{
                return json(['status'=>'false']);
            }
        }
    }



    public function sendposition(){
        $content = input('content');
        //$covercontent = input('covercontent');
        $fromid = input('fromid');
        $toid = input('toid');
        $fromname = $this->getName($fromid);
        $toname = $this->getName($toid);
        $isread = 0;
        $type = 5;
        $data = array(
            'content' => $content,
            // 'covercontent' => $covercontent,
            'fromid' =>$fromid,
            'toid' => $toid,
            // 'fromname' => $fromname,
            // 'toname' => $toname,
            'isread' => $isread,
            'type' => $type
        );
        $message_id = Db::name('communication')->insertGetId($data);
        if($message_id){
            return json(['status'=>'ok']);
        }else{
            return json(['status'=>'false']);
        }

    }


    public  function  uploadfile(){
        $file = $_FILES['file'];
        $fromid = input('fromid');
        $toid = input('toid');
        $online = input('online');

        $suffix =  strtolower(strrchr($file['name'],'.'));
//        $type = ['.jpg','.jpeg','.gif','.png'];
//        if(!in_array($suffix,$type)){
//            return ['status'=>'img type error'];
//        }
//
//        if($file['size']/1024>5120){
//            return ['status'=>'img is too large'];
//        }

        $filename =  uniqid("chat_file_",false);
        $uploadpath = ROOT_PATH."public/uploads/";
        $file_up = $uploadpath.$filename.$suffix;
        $re = move_uploaded_file($file['tmp_name'],$file_up);

        if($re){
            $name = $filename.$suffix;
            // $data['covercontent'] = $suffix;
            $data['content'] = $name;
            $data['fromid'] = $fromid;
            $data['toid'] = $toid;
            // $data['fromname'] = $this->getName($data['fromid']);
            // $data['toname'] = $this->getName($data['toid']);
            // $data['time'] = time();
            // $data['isread'] = $online;
            $data['isread'] = 0;
            $data['type'] = 4;
            $message_id = Db::name('communication')->insertGetId($data);
            if($message_id){
                return['status'=>'ok','file_name'=>$name,'covercontent'=>$suffix];
            }else{
                return ['status'=>'false'];
            }

        }
    }



    //返回全部用户列表
    public function  get_alluser(){
        $rows = $fromhead = Db::name('user')->select();
        $infos = [];
        foreach ($rows as $row){
            $info = array(
                'id' =>$row['id'],
                'nickname' => $row['nickname'],
                'firstchar' =>$this->getFirstCharter($row['nickname']),
                'headimgurl' => $row['headimgurl'],
                'sex' =>$row['sex'],
                'relname' =>$row['relname']
            );
            array_push($infos,$info);
        }

        $last_names = array_column($infos ,'firstchar');
        array_multisort($last_names,SORT_ASC,$infos);


        return json_encode($infos);
    }

    function getFirstCharter($str){
        if (empty($str)) {
            return '';
        }
        $fchar = ord($str{0});
        // return $fchar;
        if ($fchar >= 1 && $fchar <= 222) return strtoupper($str{0});
        $s1 = iconv('UTF-8', 'gb2312', $str);
        $s2 = iconv('gb2312', 'UTF-8', $s1);
        $s = $s2 == $str ? $s1 : $str;
        $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
        if ($asc >= -20319 && $asc <= -20284) return 'A';
        if ($asc >= -20283 && $asc <= -19776) return 'B';
        if ($asc >= -19775 && $asc <= -19219) return 'C';
        if ($asc >= -19218 && $asc <= -18711) return 'D';
        if ($asc >= -18710 && $asc <= -18527) return 'E';
        if ($asc >= -18526 && $asc <= -18240) return 'F';
        if ($asc >= -18239 && $asc <= -17923) return 'G';
        if ($asc >= -17922 && $asc <= -17418) return 'H';
        if ($asc >= -17417 && $asc <= -16475) return 'J';
        if ($asc >= -16474 && $asc <= -16213) return 'K';
        if ($asc >= -16212 && $asc <= -15641) return 'L';
        if ($asc >= -15640 && $asc <= -15166) return 'M';
        if ($asc >= -15165 && $asc <= -14923) return 'N';
        if ($asc >= -14922 && $asc <= -14915) return 'O';
        if ($asc >= -14914 && $asc <= -14631) return 'P';
        if ($asc >= -14630 && $asc <= -14150) return 'Q';
        if ($asc >= -14149 && $asc <= -14091) return 'R';
        if ($asc >= -14090 && $asc <= -13319) return 'S';
        if ($asc >= -13318 && $asc <= -12839) return 'T';
        if ($asc >= -12838 && $asc <= -12557) return 'W';
        if ($asc >= -12556 && $asc <= -11848) return 'X';
        if ($asc >= -11847 && $asc <= -11056) return 'Y';
        if ($asc >= -11055 && $asc <= -10247) return 'Z';
        return null;
    }


    /**
     * @param $uid
     * 根据uid来获取它的头像
     */
    public function get_head_one($uid){

        $fromhead = Db::name('user')->where('uname',$uid)->field('headimgurl')->find();

        return $fromhead['headimgurl'];
   }

    /**
     * @param $fromid
     * @param $toid
     * 根据fromid来获取fromid同toid发送的未读消息。
     */
    public function getCountNoread($fromid,$toid){

        return Db::name('communication')->where(['fromid'=>$fromid,'toid'=>$toid,'isread'=>0])->count('id');

    }

    /**
     * @param $fromid
     * @param $toid
     * 根据fromid和toid来获取他们聊天的最后一条数据
     */
    public function getLastMessage($fromid,$toid){

        $info = Db::name('communication')->where('((fromid=:fromid&&toid=:toid)||(fromid=:fromid2&&toid=:toid2)) && status = 1',['fromid'=>$fromid,'toid'=>$toid,'fromid2'=>$toid,'toid2'=>$fromid])->order('id DESC')->limit(1)->find();
        switch($info['type']){
            case 2:
                $info['content'] = "[图片]";
                break;
            case 3:
                $info['content'] = "[视频]";
                break;
            case 4:
                $info['content'] = "[文件]";
                break;
            case 5:
                $info['content'] = "".$info['content']."";
                break;
        }
        return $info;
    }



    /**
     * 根据fromid来获取当前用户聊天列表
     */
    public function get_list(){

            $fromid = input('id');
            $infos  = Db::name('communication')->field(['fromid','toid','fromname'])->where('(toid=:fromid) && status=1',['fromid'=>$fromid])->group('fromid')->select();
//            $rows = array_map(function($res){
//                return [
//                    'head_url'=>$this->get_head_one($res['fromid']),
//                    'username'=>$res['fromname'],
//                    'countNoread'=>$this->getCountNoread($res['fromid'],$res['toid']),
//                    'last_message'=>$this->getLastMessage($res['fromid'],$res['toid']),
//                    'chat_page'=>"http://chat.com/index.php/index/index/index?fromid={$res['toid']}&toid={$res['fromid']}"
//                ];
//
//            },$info);
//
//            return $rows;
        $rows = [];
        foreach ($infos as $info){
            $myinfo = array(
                'head_url'=>$this->get_head_one($info['fromid']),
                'username'=>$info['fromname'],
                'countNoread'=>$this->getCountNoread($info['fromid'],$info['toid']),
                'last_message'=>$this->getLastMessage($info['fromid'],$info['toid']),
                'toid' => $info['fromid'],
                'chat_page'=>"http://im.tiaociapp.com/index.php/index/index/index?fromid={$info['toid']}&toid={$info['fromid']}"
            );
            array_push($rows,$myinfo);
        }
        return json_encode($rows);



    }

    public function changeNoRead(){

            $fromid = input('toid');
            $toid = input('fromid');
            Db::name('communication')->where(['fromid'=>$fromid,"toid"=>$toid])->update(['isread'=>1]);
    }

	// public function getLastMsg(){   //获得所有未读消息
	// 	$toid = input('toid');
    //     $fromid = input('fromid');
	// 	$res = db('communication')->where(["toid"=>$toid,"isread"=>0])->order('createtime desc')->select();
    //     $data = [];
    //     if(count($res)>0){
    //         foreach($res as $item){
    //             $user = db('user')->where('id',$item['fromid'])->select()[0];
    //             array_push($data,[
    //                 'msg'=> $item,
    //                 'fromheadimg'=>$user['headimgurl'],
    //                 'fromnickname'=>$user['nickname']
    //             ]);
    //         }
    //         return json([
    //             'status'=> 200,
    //             'data'=> $data
    //         ]);
    //     }else{
    //         return json([
    //             'status'=> 201,
    //             'msg'=> '没有新消息！',
    //             'data'=> []
    //         ]);
    //     }
		
	// }

	//message页展示所有用户与自己的最后一条记录
//    public function getEnd($uid){
//        $uid = input('uid');  //当前登录的uid
//  	//    $toid = input('toid');
//     //    $fromid = input('fromid');
//        //所有人发给我的最新一条消息
//         $res = Db::query('SELECT * FROM (SELECT * FROM yf_communication where toid=? and status=? ORDER BY createtime desc) AS T GROUP BY T.fromid',[$uid,1]);
//     //    $res = db('communication')->where("toid",$toid)->order('createtime desc')->group('fromid')->select();
//         //我发给别人的最后一条消息
//         $ress = Db::query('SELECT * FROM (SELECT * FROM yf_communication where fromid=? and status=? ORDER BY createtime desc) AS T GROUP BY T.toid',[$uid,1]);
//     //    return json($res);
//         $data = [];
//         $tome = [];
//         $meto = [];
//        if(count($res)>0 || count($ress)>0){
//            foreach($res as $item){
//             //    return json($item);
//                $user = db('user')->where('id',$item['fromid'])->select();
//                if($user){
//                    $user = $user[0];
//                    array_push($tome,
//                    ['msg'=> $item,
//                    'fromheadimg'=>$user['headimgurl'],
//                    'fromnickname'=>$user['nickname']
//                ]);
//                }
//             //    return json($user);
               
//            }
//            foreach($ress as $item){
//             $user = db('user')->where('id',$item['toid'])->select();
//             if($user){
//                 $user = $user[0];
//                 array_push($meto,
//                 ['msg'=> $item,
//                 'fromheadimg'=>$user['headimgurl'],
//                 'fromnickname'=>$user['nickname']
//             ]);
//             }
            
//         }
//         array_push($data,['tome'=>$tome],['meto'=>$meto]);
//            return json([
//                'status'=> 200,
//                'data'=> $data
//            ]);
//        }else{
//            return json([
//                'status'=> 201,
//                'msg'=> '没有新消息！',
//                'data'=> []
//            ]);
//        }
//      }
     
      public function recallMsg(){
          $mid = input('mid');  //消息记录id
          if($mid == ''){
              return json([
                  'status'=> 201,
                  'msg'=> 'mid参数错误'
              ]);
          }
          $res = db('communication')->where('id',$mid)->update(['status'=>5]);
          if($res){
              return json([
                  'status'=> 200,
                  'msg'=> '撤回成功'
              ]);
          }else{
              return json([
                  'status'=> 202,
                  'msg'=> '再试试吧'
              ]);
          }

      }

     ###获得聊天记录最后一条消息  重合部分前端处理
    public function getLastMsg(){
        $uid = input('uid');
        $res = Db::query('SELECT * FROM (SELECT * FROM tc_communication where toid=? and status=? ORDER BY createtime desc) AS T GROUP BY T.fromid',[$uid,1]);
        $meto = Db::query('SELECT * FROM (SELECT * FROM tc_communication where fromid=? and status=? ORDER BY createtime desc) AS T GROUP BY T.toid',[$uid,1]);
        $signalmsg = [];
        if(count($res)>0){
            foreach ($res as $item){
                $msguser = db('user')->where('uname',$item['fromid'])->select();
                if($msguser){
                    $msguser = $msguser[0];
                    array_push($signalmsg,[
                        'headimgurl'=> $msguser['headimgurl'],
                        'nickname'=> $msguser['nickname'],
                        'msg'=> $item
                    ]);
                }
            }
        }
//        return json($signalmsg);
        $signalmsg1 = [];
        if(count($meto)>0){
            foreach ($meto as $item){
                $msguser1 = db('user')->where('uname',$item['toid'])->select();
                if($msguser1){
                    $msguser1 = $msguser1[0];
                    array_push($signalmsg1,[
                        'headimgurl'=> $msguser1['headimgurl'],
                        'nickname'=> $msguser1['nickname'],
                        'msg'=> $item
                    ]);
                }
            }
        }
//        return json($signalmsg1);
        $alldata = [];
        if( (count($signalmsg) + count($signalmsg1)) >0){
            array_push($alldata,['tome'=>$signalmsg], ['meto'=>$signalmsg1]);
            return json([
                'status'=> 200,
                'data'=> $alldata
            ]);
        }else{
            return json([
                'status'=> 200,
                'data'=> []
            ]);
        }
    }

}
