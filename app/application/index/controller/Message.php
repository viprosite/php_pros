<?php


namespace app\index\controller;


use think\Controller;

class Message extends Controller
{
    public function _empty(){
        return json([
            'status'=> 404,
            'msg'=> '方法不存在！'
        ]);
    }

    ###获得uid的黑名单（屏蔽 + 拉黑）
    public function getBlacklist(){
        $uid = input('uid');
        $list = db('blacklist')->where(['uid'=> $uid, 'type'=> 1])->column('cid');
        return json([
            'status'=> 200,
            'data'=> $list
        ]);
    }

    #comment & support num 获得赞数评论数
    public function totalNum(){
        $uid = input('uid');
        if($uid == ''){
            return json([
                'status'=> 201,
                'msg'=> 'uid参数为空'
            ]);
        }
        $supportnum = db('support')->where('contentuid',$uid)->where('isread',0)->count(); //内容作者id是当前登陆者id
        $commentnum = db('comment')->where('contentuid',$uid)->where('isread',0)->count();
//        $totalnum = $supportnum + $commentnum;
        return json([
            'status'=> 200,
            'data'=> [
                'supportnum'=> $supportnum,
                'commentnum'=> $commentnum
            ]
        ]);
    }

    ###获得评论列表
    public function getCommentList(){
        $noreadcid = input('noreadcid');
        if($noreadcid == null){
            $noreadcid = '';
        }
        $uid = input('uid');
        if($uid == ''){
            return json([
                'status'=> 201,
                'msg'=> 'uid参数为空'
            ]);
        }
        $res = db('comment')->where('id','not in', $noreadcid)->where('contentuid',$uid)->order('createtime desc')->limit(8)->select();
        if($res){
            $alldata = [];
            foreach ($res as $item){
                $user = db('user')->where('uname',$item['uid'])->select();
                if($user){
                    $user = $user[0];
                    $coverimgid = db('content')->where('id',$item['contentid'])->value('coverimgid');
                    $coverimgid = explode(',',$coverimgid);
                    $coverimg = db('source')->where('id',$coverimgid[0])->value('url');
                    array_push($alldata,[
                        'id'=> $item['id'],
                        'uid'=> $item['uid'],
                        'nickname'=> $user['nickname'],
                        'headimgurl'=> $user['headimgurl'],
                        'content'=> $item['content'],
                        'contentid'=> $item['contentid'],
                        'createtime'=> $item['createtime'],
                        'coverimg'=> $coverimg
                    ]);
                }
            }
            return json([
                'status'=> 200,
                'data'=> $alldata
            ]);
        }else{
            return json([
                'status'=> 202,
                'data'=> []
            ]);
        }
    }
    ###获得赞列表
    public function getSupportList(){
        $uid = input('uid');
        $noreadsid = input('noreadsid');
        if($noreadsid == null){
            $noreadsid = '';
        }
        if($uid == ''){
            return json([
                'status'=> 201,
                'msg'=> 'uid参数为空'
            ]);
        }
        $res = db('support')->where('id', 'not in', $noreadsid)->where(['contentuid'=>$uid])->order('createtime desc')->limit(8)->select();
        if($res){
            $alldata = [];
            foreach ($res as $item){
                $user = db('user')->where('uname',$item['uid'])->select();
                $contentid = $item['contentid'];
                if($item['type'] == 3){
                    $contentid = db('comment')->where('id',$contentid)->value('contentid');  //被评论内容id
                }
                $coverimgid = db('content')->where('id',$contentid)->value('coverimgid');
                $coverimgid = explode(',',$coverimgid);
                $coverimg = db('source')->where('id',$coverimgid[0])->value('url');
                if($user){
                    $user = $user[0];
                    array_push($alldata,[
                        'id'=> $item['id'],
                        'uid'=> $item['uid'],
                        'nickname'=> $user['nickname'],
                        'headimgurl'=> $user['headimgurl'],
                        'contentid'=> $contentid,
                        'createtime'=> $item['createtime'],
                        'coverimg'=> $coverimg,
                        'type'=> $item['type']  //1用户 2动态 3评论
                    ]);
                }
            }
            return json([
                'status'=> 200,
                'data'=> $alldata
            ]);
        }else{
            return json([
                'status'=> 202,
                'data'=> []
            ]);
        }
    }

    ###将未读评论赞状态置为已读
    public function updateCS(){
        $uid = input('uid');
        $retc = db('comment')->where('contentuid',$uid)->update([
            'isread'=> 1
        ]);
        $rets = db('support')->where('contentuid',$uid)->update([
            'isread'=> 1
        ]);
        if($retc || $rets){
            return json([
                'status'=> 200
            ]);
        }else{
            return json([
                'status'=> 202
            ]);
        }
    }

    ###保存大冒险问题
    public function saveAdventureQues(){
        $uid = input('uid');
        $q = input('question');
        $count = db('adventureq')->where('postuid',$uid)->count();
        if($count == 10){
            return json([
                'status'=> 202,
                'msg'=> '当前用户生成问题过多'
            ]);
        }else{
            $ret = db('adventureq')->insertGetId([
                'postuid'=> $uid,
                'content'=> $q,
                'createtime'=> date('Y-m-d H:i:s')
            ]);
            if($ret){
                return json([
                    'status'=> 200,
                    'data'=> $ret
                ]);
            }else{
                return json([
                    'status'=> 201,
                    'msg'=> '保存失败'
                ]);
            }
        }
    }
    ###返回当前用户保存的大冒险问题列表
    public function retAdventureList(){
        $uid = input('uid');
        $res = db('adventureq')->where('postuid',$uid)->order('createtime desc')->select();
        if($res){
            return json([
                'status'=> 200,
                'data'=> $res
            ]);
        }else{
            return json([
                'status'=> 202,
                'msg'=> '用户暂未保存大冒险问题'
            ]);
        }
    }
    ###删除某条问题
    public function delq(){
        $qid = input('qid');
        $ret = db('adventureq')->where('id',$qid)->delete();
        if($ret){
            return json([
                'status'=> 200,
                'msg'=> '删除成功'
            ]);
        }else{
            return json([
                'status'=> 202,
                'msg'=> '删除失败'
            ]);
        }
    }

    ###大冒险页面待选用户
    public function retAdventureUser()
    {
        $uid = input('uid');
        $noreaduid = input('noreaduid');
        if ($uid == '') {
            return json([
                'status' => 201,
                'msg' => 'uid参数为空'
            ]);
        }
        if ($noreaduid == null) {
            $noreaduid = '';
        }
        $sex = db('user')->where('uname', $uid)->value('sex');
        $distance = db('user')->where('uname', $uid)->value('distance');
        if ($sex == 1) {
            $sex = 2;
        } else {
            $sex = 1;
        }
        $questionuser = db('adventureq')->where('postuid','<>',$uid)->group('postuid')->select();
        if ($questionuser) {
            $retusers = [];
            foreach ($questionuser as $item) {
                $user = db('user')->where('uname','not in', $noreaduid)->where('uname', $item['postuid'])->select();
                if ($user) {
                    $user = $user[0];
                    if ($user['sex'] == $sex && $user['distance'] - $distance >= 5) {
                        $questions = db('adventureq')->where('postuid', $user['uname'])->column('content');
                        if ($questions) {
                            $retq = $questions[array_rand($questions, 1)];
                            array_push($retusers, [
                                'qid'=> $item['id'],
                                'uid' => $user['uname'],
                                'headimgurl' => $user['headimgurl'],
                                'nickname' => $user['nickname'],
                                'distance' => $user['distance'],
                                'question' => $retq
                            ]);
                        }
                    }
                }
            }
            if($retusers){
                return json([
                    'status' => 200,
                    'data' => $retusers
                ]);
            }else{
                return json([
                    'status' => 202,
                    'msg'=> '暂无更多符合条件用户'
                ]);
            }
        }
        return json($questionuser);
    }



    ###发送大冒险
    public function sendAdv(){
        $toid = input('fromid');
        $fromid = input('toid');  //自己的id
        $q = input('q');
        $qid = input('qid');
        $qrcodeid = input('qrcodeid');  //二维码表记录id
        if($fromid == '' || $toid == '' || $q == ''){
            return json([
                'status'=> 201,
                'msg'=> 'fromid|toid|q参数为空'
            ]);
        }
        $res = db('communication')->insert([
            'fromid'=> $toid,
            'toid'=> $fromid,
            'content'=> $q,
            'type'=> 6,
            'otherinfo'=> $qid,
            'createtime'=> date('Y-m-d H:i:s')
        ]);
        $insert = db('adventure')->where(['toid'=>$fromid,'qrcodeid'=> $qrcodeid])->update([
            'userid'=> $toid,
            'adventureqid'=> $qid,
            'createtime'=> date('Y-m-d H:i:s')
        ]);
        if($res && $insert){
            return json([
                'status'=> 200,
                'msg'=> '发送成功'
            ]);
        }else{
            return json([
                'status'=> 202,
                'msg'=> '发送失败'
            ]);
        }
    }

    ###返回坦白卡待选用户
    public function retFrankUser(){
        $uid = input('uid');
        $noreaduid = input('noreaduid');
        $blacklist = db('blacklist')->where('uid',$uid)->where('type',1)->column('cid');
        $cuser = db('user')->where('uname',$uid)->value('distance');
        $res = db('user')->where('uname','not in',$blacklist)->where('uname','not in',$noreaduid)
            ->where('uname','<>',$uid)->where('distance','>',$cuser)->limit(6)->select();
        if($res){
            $alluser = [];
            foreach ($res as $item){
                array_push($alluser,[
                    'uid'=> $item['uname'],
                    'headimgurl'=> $item['headimgurl'],
                    'nickname'=> $item['nickname'],
                    'distance'=>$item['distance'],
                    'sex'=> $item['sex']
                ]);
            }
            return json([
                'status'=> 200,
                'data'=>$alluser
            ]);
        }else{
            return json([
                'status'=> 202,
                'data'=> []
            ]);
        }
    }

    ###发送坦白
    public function sendFrank(){
        $fromid = input('fromid');
        $toid = input('toid');
        $q = input('q');
        $cid = input('cardid');
        if($fromid == '' || $toid == '' || $q == ''){
            return json([
                'status'=> 201,
                'msg'=> 'fromid|toid|q参数为空'
            ]);
        }
        $res = db('communication')->insert([
            'fromid'=> $fromid,
            'toid'=> $toid,
            'content'=> $q,
            'type'=> 8,
            'createtime'=> date('Y-m-d H:i:s')
        ]);
        $insert = db('carduser')->where(['ownerid'=>$fromid,'cardid'=> $cid])->update([
            'toid'=> $toid,
            'content'=> $q,
            'status'=> 0,   //发送问题后即失效
            'startdate'=> date('Y-m-d H:i:s')
        ]);
        if($res && $insert){
            return json([
                'status'=> 200,
                'msg'=> '发送成功'
            ]);
        }else{
            return json([
                'status'=> 202,
                'msg'=> '发送失败'
            ]);
        }
    }

    ###获取大冒险记录页面数据
    public function getAdventureList(){
        $uid = input('uid');
        $noreadaid = input('noreadaid');

        $res = db('adventure')->where('id','not in', $noreadaid)->where('userid',$uid)->where('toid','<>','null')->order('createtime desc')->limit(5)->select();
        if($res){
            $alla = [];
            foreach ($res as $item){
                $adventureq = db('adventureq')->where('id',$item['adventureqid'])->value('content');
                $nickname = db('user')->where('uname',$item['toid'])->value('nickname');
                $isread = db('communication')->where(['fromid'=> $uid, 'toid'=> $item['toid'],'type'=> 6, 'otherinfo'=>$item['adventureqid']])->value('isread');
                array_push($alla,[
                    'qrcodeid'=> $item['qrcodeid'],
                    'toid'=> $item['toid'],
                    'nickname'=> $nickname,
                    'adventureq'=> $adventureq,
                    'isread'=> $isread,
                    'answer'=> $item['answer'],
                    'createtime'=> $item['createtime']
                ]);
            }
            return json([
                'status'=> 200,
                'data'=> $alla
            ]);
        }else{
            return json([
                'status'=> 202,
                'msg'=> '暂无大冒险记录'
            ]);
        }
    }

    ###查询当前用户时由有未回答的坦白卡问题
    public function checkFrank(){
        $uid = input('uid');
        if(!$uid){
            return json([
                'status'=> 201,
                'msg'=> 'uid参数错误'
            ]);
        }
        $frank_msg = db('communication')->where(['toid'=> $uid,'type'=> 8])->order('createtime desc')->limit(1)->select();
        if($frank_msg){  //有坦白卡问题
            $frank_msg = $frank_msg[0];
            $current_id = $frank_msg['id'];
            $fromid = $frank_msg['fromid'];
            $answer_msg = db('communication')->where('id','>',$current_id)->where(['fromid'=>$uid,'toid'=> $fromid])->select();
            if($answer_msg){ #回答了坦白卡问题
                return json([
                    'status'=> 200,
                    'msg'=> '已回答'
                ]);
            }else{  #未回答
                $from_user = db('user')->where('uname',$fromid)->value('nickname');
                return json([
                    'status'=> 202,
                    'msg'=> '还有未回答坦白卡问题',
                    'fromid'=> $fromid,
                    'from_nickname'=> $from_user
                ]);
            }
        }else{
            return json([
                'status'=> 203,
                'msg'=> '暂无坦白卡问题'
            ]);
        }
    }

}