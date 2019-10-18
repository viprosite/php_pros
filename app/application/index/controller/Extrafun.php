<?php


namespace app\index\controller;


use think\Controller;
use think\Cookie;

class ExtraFun extends Controller
{
    public function _empty(){
        return json([
            'status'=> 404,
            'msg'=> '指定的方法不存在!'
        ]);
    }

    ###生成首次握手token值
//    public function generateFirstToken(){
//        $ftoken = md5('tiaoci'.time());
//        if($ftoken){
//            Cookie::set('ftoken_' . $ftoken, $ftoken, time()+3600);
//            return json([
//                'status'=> 200,
//                'data'=> $ftoken
//            ]);
//        }
//    }

    ###扫码获得游戏机会
    public function scan(){
        $uid = input('uid');
        $number = input('number');
        $token = input('token');
        $device = input('device');
        if($device == 'app'){
            if($uid == '' || $number == '' || $token == ''){
                return json([
                    'status'=> 201,
                    'msg'=> 'uid|number|token参数为空'
                ]);
            }
            $code = db('qrcode')->where(['number'=>$number, 'token'=>$token])->select();
            if($code){
                $code = $code[0];
                $status = $code['status'];
                if($status == 1){
                    if($code['userid'] == $uid){
                        $advhis = db('adventure')->where(['toid'=> $uid, 'qrcodeid'=> $code['id']])->select();
                        $cardhid = db('carduser')->where(['qrcodeid'=> $code['id'], 'ownerid'=> $uid])->select();
                        if($advhis){
                            $data = $advhis[0];   //一个二维码只会有一种记录
                        }else{
                            $data = $cardhid[0];
                        }
                        return json([
                            'status'=> 301,
                            'msg'=> '您之前已扫描',
                            'data'=> $data // ----------------返回扫描后选择的游戏记录
                        ]);
                    }
                    return json([
                        'status'=> 202,
                        'msg'=> '已被扫描'
                    ]);
                }else{
                    return json([
                        'status'=> 200,
                        'msg'=> '扫码成功',
                        'data'=> $code
                    ]);
                }
            }else{
                return json([
                    'status'=> 404,
                    'msg'=> '不知名的二维码'
                ]);
            }
        }else{
            return redirect('http://tiaociapp.com');
        }

    }

    ###选择游戏后将扫描的二维码置为已扫描状态
    public function setAlreadyScan(){
        $codeid = input('id');   #二维码表记录id
        $cardid = input('cardid');   #卡片记录表id   仅用于晋级卡操作中
        $uid = input('uid');
        $choise = input('choise');
        if($codeid == '' || $uid==''){
            return json([
                'status'=> 201,
                'msg'=> '二维码编号为空或用户id为空'
            ]);
        }
        $code = db('qrcode')->where('id',$codeid)->select();
        if($code){
            if($choise == 'adventure'){   //选择大冒险
                $res = db('adventure')->insert([           //TODO ----------发送大冒险时更新
                    'toid'=> $uid,
                    'qrcodeid'=> $codeid,
                    'createtime'=> date('Y-m-d H:i:s')
                ]);
            }else{   //选择晋级卡
                $res = db('carduser')->insert([       //TODO ----------使用晋级卡时更新
                    'ownerid'=> $uid,
                    'qrcodeid'=> $codeid,
                    'cardid'=> $cardid,   #卡片表记录id
                    'createtime'=> date('Y-m-d H:i:s')
                ]);
            }
            $ret = db('qrcode')->where('id',$codeid)->update([
                'userid'=> $uid,
                'status'=> 1,
                'usetime'=> date('Y-m-d H:i:s')
            ]);
            if($ret && $res){
                return json([
                    'status'=> 200,
                    'msg'=> '更新状态成功'
                ]);
            }
        }else{
            return json([
                'status'=> 202,
                'msg'=> '不存在的二维码'
            ]);
        }
    }

    public function gcode(){
        echo md5('tiaociapp&youfankeji');
        return $this->fetch();
    }



    ###按照预设概率返回6张卡片
    public function getCards(){
        $arr = [];  //存储所有元素的数组
        $len = 101;  //总概率101%
        //
        for($i=0;$i<4;$i++){    // 2,3,4,5   70%    红桃各5% 其余色各4%
            array_push($arr,'黑桃2','方2','梅花2','黑桃3','方3','梅花3','黑桃4','方4','梅花4','黑桃5','方5','梅花5');
        }
        for($i=0;$i<5;$i++){
            array_push($arr,'红桃2','红桃3','红桃4','红桃5');
        }
        for($i=0;$i<2;$i++){
            array_push($arr,'红桃6','黑桃6','红桃7','黑桃7','方7','红桃8','黑桃8','方8','红桃9','红桃10');
        }
        array_push($arr,'方6','梅花6','梅花7','梅花8','黑桃9','方9','梅花9','黑桃10','方10','梅花10','红桃k','黑桃k','方k','梅花k');
        $ret = [];   //返回的6张卡片信息
        for($j=0;$j<6;$j++){
            $arrindex =  array_rand($arr,1);
            $index = $arr[$arrindex];
//        return $index;
            $res = db('promotioncard')->where('cardname',$index)->select();
            if($res){
                $res = $res[0];
                array_push($ret, $res);
            }
        }
        if($ret){
            return json([
                'status'=> 200,
                'data'=> $ret
            ]);
        }
    }

    ###返回游戏规则说明
    public function retGamerule(){
        $type = input('type');
        $res = db('gamerules')->where('type',$type)->value('content');
        if($res){
            return json([
                'status'=> 200,
                'data'=> $res
            ]);
        }else{
            return json([
                'status'=> 202,
                'data'=> '获取失败'
            ]);
        }
    }

    ###获得某张卡片信息
    public function getCardInfo(){
        $cid = input('cid');  #卡片表id值
        $card = db('promotioncard')->where('id',$cid)->select();
        if($card){
            $card = $card[0];
            return json([
                'status'=> 200,
                'data'=> [
                    'id'=> $card['id'],
                    'cardid'=> $card['cardid'],
                    'cardname'=> $card['cardname'],
                    'cardimgurl'=> $card['cardimgurl'],
                    'status'=> $card['status'],
                    'function'=> $card['function'],
                    'otherinfo'=> $card['otherinfo']
                ]
            ]);
        }else{
            return json([
                'status'=> 202,
                'msg'=> '卡片不存在'
            ]);
        }
    }
    ###获得用户的某张卡片使用情况
    public function getCardUse(){
        $cid = input('cid');
        $uid = input('uid');
        $res = db('carduser')->where('cardid',$cid)->where('ownerid',$uid)->select();
        if($res){
            $res = $res[0];
            return json([
                'status'=> 200,
                'data'=> $res
            ]);
        }else{
            return json([
                'status'=> 202,
                'data'=> '获取失败'
            ]);
        }
    }

    ###使用晋级卡增加公里数
    public function addDistance(){
        $cid = input('cid');
        $uid = input('uid');
        if($cid){
            $id = db('promotioncard')->where('id',$cid)->value('otherinfo');  //具体是卡片几
            $much = db('distancerules')->where('otherinfo',$id)->value('much');
            $distance = db('user')->where('uname',$uid)->value('distance');       ####-------------------------加距离操作
            $ret = db('user')->where('uname',$uid)->update(['distance'=>floatval($distance+$much)]);
            if($ret){
                $startuse = db('carduser')->where(['cardid'=>$cid, 'ownerid'=>$uid])->update([
                    'status'=> 2,
                    'startdate'=> date('Y-m-d H:i:s'),
                    'expirationdate'=> date('Y-m-d H:i:s',strtotime('+2 day'))
                ]);
                    if($startuse){
                        return json([
                            'status'=> 200,
                            'msg'=> true,
                            'inc'=> $distance+$much
                        ]);
                    }else{
                        return json([
                            'status'=> 202,
                            'msg'=> false
                        ]);
                    }
            }
        }
    }

    ###返回app其他信息
    public function retOtherinfo(){
        $res = db('otherinfo')->select();
        return json([
            'status'=> 200,
            'data'=> [
                'website'=> $res[0]['website'],
                'email'=> $res[0]['email'],
                'phone'=> $res[0]['phone']
            ]
        ]);
    }

}