<?php
namespace app\index\controller;

//use matotool\Tools;
use matotool\Tools;
use think\Controller;

class Index extends Controller
{
    public function _empty(){
        return json([
            'status'=> 404,
            'msg'=> '未找到对应方法！'
        ]);
    }

    public function index()
    {
        return json('2019-09-12' < '2018-02-19');
        return 'index';
    }

    ###对象数组据某个键排序
    function arr_sort($array,$key,$order="asc") {
        $arr_nums = $arr = array();
        foreach ($array as $k => $v) {
            $arr_nums[$k] = $v[$key]; //将排序的键值取出
        }
        if($order == "asc") {//对键值进行排序，并保留索引
            asort($arr_nums);
        } else {
            arsort($arr_nums);
        }
        foreach ($arr_nums as $k => $v) {
            $arr[] = $array[$k];//按照跑留的索引进行赋值
        }
        return $arr;
    }

    ###发现页数据
    public function getDiscoverInfo(){
        #更新卡片过期
        $cards = db('carduser')->where('status',2)->select();   //2为生效中，查询其对应失效时间
        if($cards){
            foreach ($cards as $card){
                $cdistance = db('user')->where('uname',$card['ownerid'])->value('distance');
                $now = date('Y-m-d H:i:s');
                $cardname = db('promotioncard')->where('id',$card['cardid'])->value('otherinfo');  //当前生效的是卡片几
                $much = db('distancerules')->where('otherinfo',$cardname)->value('much');  //卡片几对应的距离
                if ($card['expirationdate'] && $card['expirationdate'] <= $now){
                    $cdistance -= $much;
                    db('carduser')->where('id',$card['id'])->update(['status'=> 0]);
                    db('user')->where('uname',$card['ownerid'])->update(['distance'=> $cdistance]);
                }
            }
        }
        $uid = input('uid');
        $noreaduid = input('noreaduid');   //传递 动态记录中的uid
        if($noreaduid == null){
            $noreaduid = '';
        }
        if($uid == '' || $uid == null){   #未登录 默认distance=5.0km
            $users = db('user')->where('uname','not in', $noreaduid)->where('distance','between','0.0,10.0')->limit(8)->select();
//            return json($users);
            if(count($users)>0){
                $alldata = [];
                foreach ($users as  $item) {
                    $cuser = $item;
                    $content = db('content')->where('uid', $cuser['uname'])->where('status', 1)->where('publicor', 2)->order('createtime desc')->limit(1)->select();
                    $cards = db('carduser')->where('ownerid', $cuser['uname'])->where('status', 2)->select();
                    $signalcontent = [];
                    if ($content) {
                        $content = $content[0];
                        if (strlen($content['imagesid']) != 0) {
                            $coverimgid = explode(',', $content['imagesid']);
                            $coverimgurl = [];
                            for ($i = 0; $i < count($coverimgid); $i++) {
                                $coverimg = db('source')->where('id', $coverimgid[$i])->value('url');
                                $thumbnail = db('source')->where('id', $coverimgid[$i])->value('thumbnail');
                                if(strlen($thumbnail) != 0 && substr($thumbnail,-3) == 'mp4'){
                                    array_push($coverimgurl, ['cover'=>$coverimg, 'video'=> $thumbnail]);
                                }else{
                                    array_push($coverimgurl, ['cover'=>$thumbnail, 'video'=> '']);
                                }
                            }
                        } else {
                            $coverimgurl = [];
                        }
                        $supportnum = db('support')->where('contentid', $content['id'])->where('type', 2)->count();
                        $commentnum = db('comment')->where('contentid', $content['id'])->where('status',1)->count();
//                        $issupport = db('support')->where(['contentid' => $content['id'], 'uid' => $uid])->count();
//                        if ($issupport == 1) {
//                            $issupport = 1;
//                        } else {
//                            $issupport = 0;
//                        }
                        $rcards = [];
                        if (count($cards) > 0) {
                            foreach ($cards as $item) {
                                $card = db('promotioncard')->where('id', $item['cardid'])->select();
                                if ($card) {
                                    $card = $card[0];
                                    array_push($rcards, ['cardinfo' => [
                                        'cardid' => $card['cardid'],
                                        'cardname' => $card['cardname'],
                                        'cardimgurl' => $card['cardimgurl'],
                                        'toid' => $item['toid'],
                                        'content' => $item['content'],
                                        'status' => $item['status'],
                                        'startdate' => $item['startdate'],
                                        'expirationdate' => $item['expirationdate'],
                                        'createtime' => $item['createtime']
                                    ]]);
                                }
                            }
                        }
                        array_push($signalcontent, ['content' => [
                            'contentid' => $content['id'],
                            'uid' => $cuser['uname'],
                            'headimgurl' => $cuser['headimgurl'],
                            'nickname' => $cuser['nickname'],
                            'distance' => $cuser['distance'],
                            'sex' => $cuser['sex'],
                            'title' => $content['title'],
                            'position' => $content['position'],
                            'posttime'=> $content['createtime'],
                            'coverimgurls' => $coverimgurl,
                            'supportnum' => $supportnum,
                            'commentnum' => $commentnum,
//                            'issupport' => $issupport
                        ], 'cardinfo' => $rcards]);
                    array_push($alldata,$signalcontent[0]);
                    }
                }
                return json([
                    'status'=> 200,
                    'data'=> $alldata
                ]);
            }else{
                return json([
                    'status'=> 201,
                    'msg'=> '没有符合条件的用户，没有更多动态内容可推荐'
                ]);
            }
        }else{  #用户登陆据设置的条件
            $user = db('user')->where('uname',$uid)->select();
            if(count($user) == 1){
                $user = $user[0];
                $uname = $user['uname'];
                $blacklist = \db('blacklist')->where(['uid'=> $uname, 'type'=>1])->column('cid');
                $contentbalck = db('blacklist')->where('uid',$uname)->where('type',2)->column('cid');
                $location = $user['location'];
                $hometown = $user['hometown'];
//                $education = $user['education'];  //学历  2本科  3硕士
//                $marital = $user['marital'];   //婚否   0 离异  1未婚
                $conditions = db('recommendations')->where('uid',$uid)->select();
                if($conditions){
                    $conditions = $conditions[0];
                    $allusers = [];  //存储满足任一条件的用户
                    $cagelow = substr($conditions['age'],0,stripos($conditions['age'],'-'));
                    $cageup = substr($conditions['age'],stripos($conditions['age'],'-')+1);
                    $cheightlow = substr($conditions['height'],0,stripos($conditions['height'],'-'));
                    $cheightup = substr($conditions['height'],stripos($conditions['height'],'-')+1);
                    $clocation = $conditions['location'];  #  1同城优先  2只要同城
                    $chometown = $conditions['hometown'];   #1都可以   2只要同省
                    $ceducation = $conditions['education'];  #1都可以  2本科  3硕士  4博士  5其他
                    $cmarital = $conditions['marital'];  #  1未婚    2可以离异
                    $users = \db('user')->where('uname','<>',$uname)->where('uname','not in',$blacklist)->where('uname','not in', $noreaduid)->limit(5)->select();   //user表中所有数据
                    if(count($users)>0){
                        $users_age = [];  //存储年龄满足条件的用户信息
                        $users_height = [];
                        foreach ($users as $item){
                            $age = date("Y")-substr($item['birthday'],0,4);
                            if($age >= $cagelow && $age <= $cageup){
                                array_push($users_age,$item);
                            }
                            if($item['height'] >= $cheightlow && $item['height'] <= $cheightup){
                                array_push($users_height,$item);
                            }
                        }
                    }
                    if($clocation == 2){
                        $users_location = \db('user')->where('uname','not in', $noreaduid)->where('uname','<>',$uname)->where('uname','not in',$blacklist)->where('location',$location)->select();
                    }else{
                        $users_location = $users;
                    }
                    if($chometown == 2){
                        $users_hometown = \db('user')->where('uname','not in', $noreaduid)->where('uname','<>',$uname)->where('uname','not in',$blacklist)->where('hometown',$hometown)->select();
                    }else{
                        $users_hometown = $users;
                    }
                    if($ceducation == 2){
                        $users_education = \db('user')->where('uname','not in', $noreaduid)->where('uname','<>',$uname)->where('uname','not in',$blacklist)->where('education',2)->select();
                    }elseif ($ceducation == 3){
                        $users_education = \db('user')->where('uname','not in', $noreaduid)->where('uname','<>',$uname)->where('uname','not in',$blacklist)->where('education',3)->select();
                    }elseif($ceducation == 4){
                        $users_education = \db('user')->where('uname','not in', $noreaduid)->where('uname','<>',$uname)->where('uname','not in',$blacklist)->where('education',4)->select();
                    }elseif($ceducation == 5){
                        $users_education = \db('user')->where('uname','not in', $noreaduid)->where('uname','<>',$uname)->where('uname','not in',$blacklist)->where('education',5)->select();
                    }else{
                        $users_education = $users;
                    }
                    if($cmarital == 1){
                        $users_marital = \db('user')->where('uname','not in', $noreaduid)->where('uname','<>',$uname)->where('uname','not in',$blacklist)->where('marital',1)->select();
                    }else{
                        $users_marital = $users;
                    }
                    array_push($allusers,$users_age,$users_height,$users_location,$users_hometown,$users_education,$users_marital);
                    $alluser = [];   //存储所有条件中的用户uname 数组
                    $userlen = count($allusers);
                    for($i=0;$i<$userlen;$i++){
                        $item = $allusers[$i];   //满足任一条件的用户数组
                        $itemlen = count($item);
                        for($j=0;$j<$itemlen;$j++){
                            array_push($alluser,$item[$j]['uname']);
                        }
                    }
                    $score_users = array_count_values($alluser);   //数组， 满足任一条件的集合-> uname出现次数 -> 次数=分数 ["10002":6,"10003":5,"10004":6,"10005":6,"10060":5,"10036":4]
//                    return json($score_users);
                    arsort($score_users);  //据分数降序排序后的uname数组   满足条件的用户列表（已排除自身和黑名单）
                    $score_users = array_keys($score_users);
                    $score_userslen = count($score_users);
//                    return $score_userslen;
                    $alldata = [];
                    for($j=0;$j<$score_userslen;$j++){
                        $cuser = \db('user')->where('uname',$score_users[$j])->select();
                        if($cuser) {
                            $cuser = $cuser[0];
//                        return json($cuser);
                            $content = \db('content')->where('id','not in',$contentbalck)->where('uid', $cuser['uname'])->where('status', 1)->where('publicor', 2)->order('createtime desc')->limit(1)->select();
                            $cards = db('carduser')->where('ownerid',$cuser['uname'])->where('status',2)->select();
                            $signalcontent = [];
                            if ($content) {
                                $content = $content[0];
//                            return json($content);
//                                if(in_array($content['id'],$contentbalck)){
//                                    continue;
//                                }
                                if (strlen($content['imagesid']) != 0) {
                                    $coverimgid = explode(',', $content['imagesid']);
                                    $coverimgurl = [];
                                    for ($i = 0; $i < count($coverimgid); $i++) {
                                        $coverimg = db('source')->where('id', $coverimgid[$i])->value('url');
                                        $thumbnail = db('source')->where('id', $coverimgid[$i])->value('thumbnail');
                                        if(strlen($thumbnail) != 0 && substr($thumbnail,-3) == 'mp4'){
                                            array_push($coverimgurl, ['cover'=>$coverimg, 'video'=> $thumbnail]);
                                        }else{
                                            array_push($coverimgurl, ['cover'=>$thumbnail, 'video'=> '']);
                                        }
                                    }
                                } else {
                                    $coverimgurl = [];
                                }
                                $supportnum = db('support')->where('contentid', $content['id'])->where('type', 2)->count();
                                $commentnum = db('comment')->where('contentid', $content['id'])->where('status',1)->count();
                                $issupport = db('support')->where(['contentid' => $content['id'], 'uid' => $uid, 'type'=> 2])->count();
                                if ($issupport == 1) {
                                    $issupport = 1;
                                } else {
                                    $issupport = 0;
                                }
                                $rcards = [];
                                if(count($cards)>0) {
                                    foreach ($cards as $item) {
                                        $card = db('promotioncard')->where('id', $item['cardid'])->select();
//                return json($card);
                                        if ($card) {
                                            $card = $card[0];
                                            array_push($rcards,['cardinfo'=>[
                                                'cardid' => $card['cardid'],
                                                'cardname' => $card['cardname'],
                                                'cardimgurl' => $card['cardimgurl'],
                                                'toid' => $item['toid'],
                                                'content' => $item['content'],
                                                'status' => $item['status'],
                                                'startdate' => $item['startdate'],
                                                'expirationdate' => $item['expirationdate'],
                                                'createtime' => $item['createtime']
                                            ]]);
                                        }
                                    }
//                                    array_push($signalcontent,$rcards);
                                }
                                array_push($signalcontent,['content'=> [
                                    'contentid' => $content['id'],
                                    'uid' => $cuser['uname'],
                                    'headimgurl' => $cuser['headimgurl'],
                                    'nickname' => $cuser['nickname'],
                                    'distance' => $cuser['distance'],
                                    'sex' => $cuser['sex'],
                                    'title' => $content['title'],
                                    'position' => $content['position'],
                                    'posttime'=> $content['createtime'],
                                    'coverimgurls' => $coverimgurl,
                                    'supportnum' => $supportnum,
                                    'commentnum' => $commentnum,
                                    'issupport' => $issupport
                                ],'cardinfo'=> $rcards]);
                                array_push($alldata,$signalcontent[0]);
                            }
//                            return json($content);
                        }
                    }
                    return json([
                        'status'=> 200,
                        'data'=> $alldata,
                        'userdistance'=> $user['distance']
                    ]);
                }else{   //登陆了但是没设置推荐条件
                    $users = db('user')->where('uname','not in', $noreaduid)->where('uname','<>',$uid)->where('distance','between','0.0,20.0')->limit(5)->select();
                    if(count($users)>0){
                        $alldata = [];
                        foreach ($users as  $item) {
                            $cuser = $item;
//                        return json($cuser);
                            $content = \db('content')->where('id','not in',$contentbalck)->where('uid', $cuser['uname'])->where('status', 1)->where('publicor', 2)->order('createtime desc')->limit(1)->select();
                            $cards = db('carduser')->where('ownerid', $cuser['uname'])->where('status',  2)->select();
                            $signalcontent = [];
                            if ($content) {
                                $content = $content[0];
//                            return json($content);
                                if (strlen($content['coverimgid']) != 0) {
                                    $coverimgid = explode(',', $content['imagesid']);
                                    $coverimgurl = [];
                                    for ($i = 0; $i < count($coverimgid); $i++) {
                                        $coverimg = db('source')->where('id', $coverimgid[$i])->value('url');
                                        $thumbnail = db('source')->where('id', $coverimgid[$i])->value('thumbnail');
                                        if(strlen($thumbnail) != 0 && substr($thumbnail,-3) == 'mp4'){
                                            array_push($coverimgurl, ['cover'=>$coverimg, 'video'=> $thumbnail]);
                                        }else{
                                            array_push($coverimgurl, ['cover'=>$thumbnail, 'video'=> '']);
                                        }
                                    }
                                } else {
                                    $coverimgurl = [];
                                }
                                $supportnum = db('support')->where('contentid', $content['id'])->where('type', 2)->count();
                                $commentnum = db('comment')->where('contentid', $content['id'])->where('status',1)->count();
                                $issupport1 = db('support')->where(['contentid' => $content['id'], 'uid' => $uid, 'type'=> 2])->count();
                                if ($issupport1 == 1) {
                                    $issupport1 = 1;
                                } else {
                                    $issupport1 = 0;
                                }
                                $rcards = [];
                                if (count($cards) > 0) {
                                    foreach ($cards as $item) {
                                        $card = db('promotioncard')->where('id', $item['cardid'])->select();
//                return json($card);
                                        if ($card) {
                                            $card = $card[0];
                                            array_push($rcards, ['cardinfo' => [
                                                'cardid' => $card['cardid'],
                                                'cardname' => $card['cardname'],
                                                'cardimgurl' => $card['cardimgurl'],
                                                'toid' => $item['toid'],
                                                'content' => $item['content'],
                                                'status' => $item['status'],
                                                'startdate' => $item['startdate'],
                                                'expirationdate' => $item['expirationdate'],
                                                'createtime' => $item['createtime']
                                            ]]);
                                        }
                                    }
//                                    array_push($signalcontent,$rcards);
                                }
                                array_push($signalcontent, ['content' => [
                                    'contentid' => $content['id'],
                                    'uid' => $cuser['uname'],
                                    'headimgurl' => $cuser['headimgurl'],
                                    'nickname' => $cuser['nickname'],
                                    'distance' => $cuser['distance'],
                                    'sex' => $cuser['sex'],
                                    'title' => $content['title'],
                                    'position' => $content['position'],
                                    'posttime'=> $content['createtime'],
                                    'coverimgurls' => $coverimgurl,
                                    'supportnum' => $supportnum,
                                    'commentnum' => $commentnum,
                                    'issupport' => $issupport1
                                ], 'cardinfo' => $rcards]);
                                array_push($alldata,$signalcontent[0]);
                            }
                        }
                        return json([
                            'status'=> 200,
                            'data'=> $alldata,
                            'userdistance'=> $user['distance']
                        ]);
                }else{
                        return json([
                            'status'=> 201,
                            'msg'=> '没有符合条件的用户，没有更多动态内容可推荐'
                        ]);
                    }
                }
            }else{
                return json([
                    'status'=> 201,
                    'msg'=> '用户不存在！'
                ]);
            }
        }
    }

    ###附近页数据   封面仅一图
    public function getNearbyInfo(){
        #更新卡片过期
        $cards = db('carduser')->where('status',2)->select();   //2为生效中，查询其对应失效时间
        if($cards){
            foreach ($cards as $card){
                $cdistance = db('user')->where('uname',$card['ownerid'])->value('distance');
                $now = date('Y-m-d H:i:s');
                $cardname = db('promotioncard')->where('id',$card['cardid'])->value('otherinfo');  //当前生效的是卡片几
                $much = db('distancerules')->where('otherinfo',$cardname)->value('much');  //卡片几对应的距离
                if ($card['expirationdate'] && $card['expirationdate'] <= $now){
                    $cdistance -= $much;
                    db('carduser')->where('id',$card['id'])->update(['status'=> 0]);
                    db('user')->where('uname',$card['ownerid'])->update(['distance'=> $cdistance]);
                }
            }
        }
        $uid = input('uid');
        $noreaduid = input('noreaduid');
        if($noreaduid == null){
            $noreaduid = '';
        }
        if($uid){ //用户登陆情况下
            $blacklistuser = db('blacklist')->where(['uid'=>$uid, 'type'=>1])->column('cid'); #返回数组或空数组
            $blacklistcontent = db('blacklist')->where(['uid'=>$uid, 'type'=>2])->column('cid'); #返回数组或空数组
            $distance = db('user')->where('uname',$uid)->value('distance');
//            return $distance;
            $users = db('user')->where('uname','not in',$noreaduid)->where('uname','not in',$blacklistuser)->where('uname','<>',$uid)->where('distance','between',[($distance-5),($distance+5)])->limit(8)->select();
            if(count($users)>0){
                $contents = [];
                foreach ($users as $user){
                    $content = db('content')->where('id','not in',$blacklistcontent)->where('uid',$user['uname'])->where('status',1)->where('publicor', 2)->order('createtime desc')->limit(1)->select();
                    if($content){
                        $content = $content[0];
                        $contentuser = db('user')->where('uname',$content['uid'])->select()[0];
						$coverimgurl = db('source')->where('id',explode(',',$content['coverimgid'])[0])->value('url');
						$thumbnail = db('source')->where('id', explode(',',$content['coverimgid'])[0])->value('thumbnail');
						$coverimgurls = [];
						if(strlen($thumbnail) != 0 && substr($thumbnail,-3) == 'mp4'){
							array_push($coverimgurls, ['cover'=>$coverimgurl, 'video'=> $thumbnail]);
						}else{
							array_push($coverimgurls, ['cover'=>$thumbnail, 'video'=> '']);
						}
                        $supportnum = db('support')->where('contentid',$content['id'])->where('type',2)->count();
                        $commentnum = db('comment')->where('contentid',$content['id'])->count();
                        $issupport = db('support')->where(['contentid'=>$content['id'],'uid'=>$uid, 'type'=> 2])->count();
                        if($issupport == 1){
                            $issupport = 1;
                        }else{
                            $issupport = 0;
                        }
                        array_push($contents,[
                            'contentid'=> $content['id'],
                            'contentuid'=> $content['uid'],
                            'headimgurl'=> $contentuser['headimgurl'],
                            'nickname'=> $contentuser['nickname'],
                            'distance'=> $contentuser['distance'],
                            'sex'=> $contentuser['sex'],
                            'title'=> $content['title'],
                            'location'=> $content['location'],
                            'position'=> $content['position'],
                            'coverimgurl'=> $coverimgurls,
                            'supportnum'=> $supportnum,
                            'commentnum'=> $commentnum,
                            'issupport'=> $issupport
//                        'imagesurl'=> $imagesurl,
//                        'topic'=> $content['topic'],
//                        'label'=> $content['label']
                        ]);
                    }
                }
                return json([
                    'status'=> 200,
                    'msg'=> '获取数据成功',
                    'data'=> $contents
                ]);
            }else{
                return json([
                    'status'=> 201,
                    'msg'=> '没有符合条件的用户'
                ]);
            }
        }else{   //未登录情况
            $users = db('user')->where('uname','not in',$noreaduid)->where('distance','between','0.0,10.0')->limit(8)->select();
//            return json($users);
            if(count($users)>0){
                $contents = [];
                foreach ($users as $user){
                    $content = db('content')->where('uid',$user['uname'])->where('status',1)->where('publicor', 2)->order('createtime desc')->limit(1)->select();
                    if($content){
                        $content = $content[0];
                        $contentuser = db('user')->where('uname',$content['uid'])->select()[0];
                        $coverimgurl = db('source')->where('id',explode(',',$content['coverimgid'])[0])->value('url');
						$thumbnail = db('source')->where('id', explode(',',$content['coverimgid'])[0])->value('thumbnail');
						$coverimgurls = [];
						if(strlen($thumbnail) != 0 && substr($thumbnail,-3) == 'mp4'){
							array_push($coverimgurls, ['cover'=>$coverimgurl, 'video'=> $thumbnail]);
						}else{
							array_push($coverimgurls, ['cover'=>$thumbnail, 'video'=> '']);
						}
                        $supportnum = db('support')->where('contentid',$content['id'])->where('type',2)->count();
                        $commentnum = db('comment')->where('contentid',$content['id'])->count();
                        array_push($contents,[
                            'contentid'=> $content['id'],
                            'contentuid'=> $content['uid'],
                            'headimgurl'=> $contentuser['headimgurl'],
                            'nickname'=> $contentuser['nickname'],
                            'distance'=> $contentuser['distance'],
                            'sex'=> $contentuser['sex'],
                            'title'=> $content['title'],
                            'location'=> $content['location'],
                            'position'=> $content['position'],
                            'coverimgurl'=> $coverimgurls,
                            'supportnum'=> $supportnum,
                            'commentnum'=> $commentnum,
//                        'imagesurl'=> $imagesurl,
//                        'topic'=> $content['topic'],
//                        'label'=> $content['label']
                        ]);
                    }
                }
                return json([
                    'status'=> 200,
                    'msg'=> '获取数据成功',
                    'data'=> $contents
                ]);
            }else{
                return json([
                    'status'=> 201,
                    'msg'=> '没有符合条件的用户'
                ]);
            }
        }
    }

    ###动态详情页数据
    public function dynamicDetailInfo(){
        $cid = input('cid');
        $uid = input('uid');
        if($cid == ''){
            return json([
                'status'=> 201,
                'msg'=> 'cid参数错误'
            ]);
        }
        $content = db('content')->where('id',$cid)->select();
        if($content){
            $content = $content[0];
            $contentuser = db('user')->where('uname',$content['uid'])->select()[0];
            $imagesurl = [];
            if($content['imagesid']){
				$coverimgid = explode(',', $content['imagesid']);
				$coverimgurl = [];
				for ($i = 0; $i < count($coverimgid); $i++) {
					$coverimg = db('source')->where('id', $coverimgid[$i])->value('url');
					$thumbnail = db('source')->where('id', $coverimgid[$i])->value('thumbnail');
					if(strlen($thumbnail) != 0 && substr($thumbnail,-3) == 'mp4'){
						array_push($imagesurl, ['cover'=>$coverimg, 'video'=> $thumbnail]);
					}else{
						array_push($imagesurl, ['cover'=>$thumbnail, 'video'=> '']);
					}
				}
            }
            $supportnum = db('support')->where('contentid',$cid)->where('type',2)->count();
            $commentnum = db('comment')->where('contentid',$cid)->where('status',1)->count();
            if($uid == ''){
                $issupport = 0;
            }else{
                $issupport = db('support')->where(['contentid'=>$cid, 'uid'=>$uid,'type'=>2])->count();
                if($issupport == 1){
                    $issupport = 1;
                }else{
                    $issupport = 0;
                }
            }
            $nowy = date('Y');
            $data = [
                'contentuid'=> $contentuser['uname'],
                'title'=> $content['title'],
                'headimgurl'=> $contentuser['headimgurl'],
                'nickname'=> $contentuser['nickname'],
                'distance'=> $contentuser['distance'],
                'sex'=> $contentuser['sex'],
                'age'=> $nowy - substr($contentuser['birthday'], 0, 4),
                'imagesurl'=> $imagesurl,
                'supportnum'=> $supportnum,
                'commentnum'=> $commentnum,
                'issupport'=> $issupport,
            ];
            return json([
                'status'=> 200,
                'msg'=> '获取详情页数据成功',
                'data'=> $data
            ]);
        }

    }

    ##工具函数，获取文件大小格式化返回
    public function getsize($size, $format = 'kb') {
        $p = 0;
        if ($format == 'kb') {
            $p = 1;
        } elseif ($format == 'mb') {
            $p = 2;
        } elseif ($format == 'gb') {
            $p = 3;
        }
        $size /= pow(1024, $p);
        return number_format($size, 3);
    }
//    public function size(){
//        $size = filesize(ROOT_PATH.'public/uploads/2019-09-17/thumb_img_5d806a3681577.jpg');
//        $size = $this->getsize($size, 'kb'); //进行单位转换
//        print $size;
//    }
    #发布动态   #TODO   -------其余字段据页面实现--------------------
    public function releaseDynamic(){
        $uid = input('uid');
        $title = input('title');
//        $position = input('position');
        $location = input('location');  // xx-xx
        $imgnum = input('imgnum');
        $videonum = input('videonum');
        $publicor = input('publicor'); //  1私密  2公开
        #图片上传
        $url_data = [];
        $tool = new Tools();
        for($i =1 ;$i<=$imgnum;$i++){
            $myfile = $_FILES['img'.$i];
            $data = $tool->imageuploader($myfile);

            array_push($url_data,$data);
        }
//        return json($url_data);
        //写库
        $imageid = [];
        foreach ($url_data as $item){
            $status = $item['status'];
            if($status == 1){
                $size = filesize(ROOT_PATH.$item['fileurl']);
                $size = $this->getsize($size, 'kb'); //进行单位转换
                if($size > 250){
                    $source =  ROOT_PATH.$item['fileurl'];//原图片名称
                    $imgname = substr($source,strrpos($source,'/')+1);
                    $dst_img = ROOT_PATH."public/uploads/".date("Y-m-d").'/thumb_'.$imgname;//压缩后图片的名称
//                return $dst_img;
                    $percent = 0.5;  #原图压缩，不缩放，但体积大大降低
//                return ROOT_PATH.$source;
                    $image = (new \matotool\Imgcompress($source,$percent))->compressImg($dst_img);
                    $res = db('source')->insertGetId(
                        [
                            'url'=>$item['fileurl'],
                            'width'=>$item['width'],
                            'height'=>$item['height'],
                            'status'=>1,
                            'thumbnail'=> "public/uploads/".date("Y-m-d").'/thumb_'.$imgname
                        ]
                    );
                    array_push($imageid,$res);
                }else{
                    $res = db('source')->insertGetId(
                        [
                            'url'=>$item['fileurl'],
                            'width'=>$item['width'],
                            'height'=>$item['height'],
                            'status'=>1,
                            'thumbnail'=> $item['fileurl']
                        ]
                    );
                    array_push($imageid,$res);
                }
            }
        }
        #视频上传
        if($videonum == 1){
            $tool = new Tools();
            $video = $_FILES['video'];
            $video_cover = $_FILES['video_cover'];
            $data = $tool->videouploader($video);
            $cover = $tool->imageuploader($video_cover);
            if($data['status'] == 1){
                $imgurl = $data['fileurl'];
//                $coverimgname = $data['coverimgname'];
//                $coverimg = $data['coverimgurl'];
                $res = db('source')->insertGetId(
                    [
                        'url'=>$cover['fileurl'],
                        'thumbnail'=> $imgurl,
                        'status'=>1
                    ]
                );
                array_push($imageid,$res);
            }
        }
        $cid = db('content')->insertGetId([
            'uid'=> $uid,
            'title'=> $title,
//            'position'=> $position,
            'location'=> $location,
            'coverimgid'=> $imageid[0],
            'imagesid'=> implode(",", $imageid),
            'publicor'=> $publicor,
            'createtime'=> date('Y-m-d H:i:s')
        ]);
        if($cid){
            return json([
                'status'=> 200,
                'msg'=> '发布成功',
                'cid'=> $cid
            ]);
        }else{
            return json([
                'status'=> 201,
                'msg'=> '请重试'
            ]);
        }
    }

    ###内容拉黑
    public function addBlacklist(){
        $uid = input('uid');
        $contentid = input('contentid');
        $type = input('type');
        if($uid == '' || $contentid == '' || $type == ''){
            return json([
                'status'=> 201,
                'msg'=> 'uid|contentid|type参数为空'
            ]);
        }
        $ret = db('blacklist')->where(['uid'=> $uid, 'cid'=> $contentid, 'type'=> $type])->select();
        if($ret){
            return json([
                'status'=> '203',
                'msg'=> '早前已被拉黑'
            ]);
        }else{
            $res = db('blacklist')->insert([
                'uid'=> $uid,
                'cid'=> $contentid,
                'type'=> $type,
                'createtime'=> date('Y-m-d H:i:s')
            ]);
            if($res == 1){
                return json([
                    'status'=> 200,
                    'msg'=> '拉黑成功'
                ]);
            }else{
                return json([
                    'status'=> 202,
                    'msg'=> '拉黑失败，重新操作'
                ]);
            }
        }
    }

    ###内容举报
    public function feedback(){
        $uid = input('uid');
        $cid = input('cid');
        $cuid = input('cuid');
        $content = input('content');
        $type = input('type');
        $email = input('email');
        $concat = input('concat');
        if($uid == ''){
            return json([
                'status'=> 201,
                'msg'=> 'uid参数错误'
            ]);
        }
        if($cid == '' || $cid == null){
            $cid = '';
        }
        if($cuid == '' || $cuid == null){
            $cuid = '';
        }
        $res = db('feedback')->insert([
            'uid'=> $uid,
            'content'=> $content,
            'contact'=> $concat,
            'type'=> $type,
            'otherinfo'=> 'cid:'.$cid.',cuid:'.$cuid.',email:'.$email,
            'createtime'=> date('Y-m-d H:i:s')
        ]);
        if($res){
            return json([
                'status'=> 200,
            ]);
        }else{
            return json([
                'status'=> 201
            ]);
        }
    }

    ###获得评论列表
    public function getCommentList($num = 10){
        $cid = input('cid');
        $uid = input('uid');
        $noreadcid = input('noreadcid');
        if($noreadcid == null){
            $noreadcid = '';
        }
        $retcomments = [];
        $comments = db('comment')->where('id','not in',$noreadcid)->where('contentid',$cid)->where('status',1)->order('createtime desc')->limit($num)->select();
        if(count($comments)>0){
            foreach ($comments as $comment){
                $commentuser = db('user')->where('uname',$comment['uid'])->select()[0];  //有评论则用户一定存在无需判断存在性
                $supportnum = db('support')->where('contentid',$comment['id'])->where('type',3)->count();
                $replynum = db('comment')->where('parentid',$comment['id'])->count();
                if($uid){
                    $issupport = db('support')->where(['contentid'=>$comment['id'],'type'=>3,'uid'=>$uid])->count();
                    if($issupport == 1){
                        $issupport = 1;
                    }else{
                        $issupport = 0;
                    }
                }else{
                    $issupport = 0;
                }
                $data = [
                    'commentid'=> $comment['id'],
                    'commentuid'=> $comment['uid'],
                    'headimgurl'=> $commentuser['headimgurl'],
                    'nickname'=> $commentuser['nickname'],
                    'content'=> $comment['content'],
                    'createtime'=> $comment['createtime'], //发表时间
                    'supportnum'=> $supportnum,
                    'issupport'=> $issupport,
                    'replynum'=> $replynum   //回复数
                ];
                array_push($retcomments,$data);
            }
            return json([
                'status'=> 200,
                'msg'=> '获得评论列表成功',
                'data'=> $retcomments
            ]);
        }else{
            return json([
                'status'=> 201,
                'msg'=> '暂无更多评论'
            ]);
        }
    }

    ###评论赞与取消赞
    public function supportComment(){
        $uid = input('uid');
        $commentid = input('commentid');
        $commentuid = input('commentuid');
        if($uid == '' || $commentid == '' || $commentuid == ''){
            return json([
                'status'=> 201,
                'msg'=> 'uid|commentid|commentuid参数为空'
            ]);
        }
        $res = db('support')->where(['uid'=>$uid,'contentid'=>$commentid,'type'=>3])->select();
        if(count($res) == 1){
            $ret = db('support')->where([
                'uid'=> $uid,
                'contentid'=> $commentid,
                'contentuid'=> $commentuid,
                'type'=> 3
            ])->delete();
            if($ret){
                return json([
                    'status'=> 200,
                    'data'=> 0,
                    'msg'=> '取消赞成功'
                ]);
            }
        }else{
            $ret = db('support')->insert([
                'uid'=> $uid,
                'contentid'=> $commentid,
                'contentuid'=> $commentuid,
                'createtime'=> date('Y-m-d H:i:s'),
                'type'=> 3
            ]);
            if($ret){
                return json([
                    'status'=> 200,
                    'data'=> 1,
                    'msg'=> '赞成功'
                ]);
            }
        }
    }

    ###获得单条评论   #TODO    看页面具体需要------------
    public function getSignalComment(){
        $comment_id = input('comment_id');
        $comment = db('comment')->where('id',$comment_id)->select();
        if($comment){
            $comment = $comment[0];
            #TODO    看页面具体需要--------------------

        }
    }

    ###发表评论
    public function postComment(){
        $uid = input('uid');
        $cid = input('cid');
        $contentuid = input('contentuid');
        $comment = htmlspecialchars(input('comment'));
        $ret = db('comment')->insertGetId([
            'uid'=> $uid,
            'contentid'=> $cid,
            'contentuid'=> $contentuid,
            'content'=> $comment,
            'createtime'=> date('Y-m-d H:i:s')
        ]);
        if($ret){
            return json([
                'status'=> 200,
                'msg'=> '发表评论成功',
                'cid'=> $ret
            ]);
        }else{
            return json([
                'status'=> 202,
                'msg'=> '失败'
            ]);
        }
    }

    ###返回挑刺页开通城市
    public function retOpencity(){
        $res = db('otherinfo')->where('id',1)->value('opencity');
        if($res){
            return json([
                'status'=> 200,
                'data'=> $res
            ]);
        }else{
            return json([
                'status'=> 201
            ]);
        }
    }

   ###挑刺页搜索结果数据
    public function searchResult(){
        $maxdistanceuser = db('user')->order('distance desc')->limit(1)->select();  //user表中距离最大值用户
        if($maxdistanceuser){
            $maxdistanceuser = $maxdistanceuser[0];
            $maxdistanceuser = [
                'uname'=> $maxdistanceuser['uname'],
                'headimgurl'=> $maxdistanceuser['headimgurl'],
                'distance'=> $maxdistanceuser['distance'],
                'sex'=> $maxdistanceuser['sex']
            ];
        }else{
            $maxdistanceuser = [];
        }
        $uid = input('uid');
        if($uid == '' || $uid == null){   #未登录 默认distance=5.0km
            return json([
                'status'=> 201,
                'msg'=> 'uid参数为空'
            ]);
        }else{  #用户登陆
            $user = db('user')->where('uname',$uid)->select();
            if(count($user) == 1){
                $user = $user[0];
                $userdistance = $user['distance'];
                if($user['uname'] == $maxdistanceuser['uname']){
                    $users = db('user')->where('uname','<>',$maxdistanceuser['uname'])->select();
                }else{
                    $users = db('user')->where('uname','<>',$user['uname'])->where('uname','<>',$maxdistanceuser['uname'])->where('distance','>',$userdistance-5)->where('distance','<',$userdistance+5)->where('distance','<>',$userdistance)->select();
                }
                $alldata = [];
                $retusers = [];
                if(count($users)>0){
                    foreach ($users as $item){
                        array_push($retusers,[
                            'uname'=> $item['uname'],
                            'headimgurl'=> $item['headimgurl'],
                            'distance'=> $item['distance'],
                            'sex'=> $item['sex']
                        ]);
                    }
                }
                array_push($alldata,['cuser'=> ['uname'=>$user['uname'],'headimgurl'=>$user['headimgurl'],'distance'=>$user['distance'],'sex'=> $user['sex']]],['users'=>$retusers], ['maxdistance'=> $maxdistanceuser]);
                return json([
                    'status'=> 200,
                    'data'=> $alldata
                ]);
            }else{
                return json([
                    'status'=> 201,
                    'msg'=> '用户不存在！'
                ]);
            }
        }
    }

    ###删除某评论
    public function delComment(){
        $cid = input('cid');
        if(!$cid){
            return json([
                'status'=> 202,
                'msg'=> '评论id为空'
            ]);
        }
        $res = db('comment')->where('id',$cid)->update(['status'=> 0]);
        if($res){
            return json([
                'status'=> 200,
                'msg'=> '删除成功'
            ]);
        }else{
            return json([
                'status'=> 201,
                'msg'=> '删除失败'
            ]);
        }
    }





}
