<?php
namespace app\index\controller;
use matotool\Tools;
use matotool\Ucpaas;
use think\Controller;
use think\Cookie;
use think\Image;
use think\Request;


class User extends Controller
{
    public function _empty($name)
    {
        return json([
            "status"=> '404'
        ]);
    }
    #----------工具类函数----------------
//发送验证短信   type:1注册 2验证码登录 3重置密码 4 认证 5.其他
    public function sendMsg($phone, $type='')
    {
        $phone = input('phone');
        if($type == 4){
            $phoneflag = db('identity')->where('bindphone', $phone)->count();
            if ($phoneflag > 0) {
                return json([
                    'status' => 'false',
                    'msg' => "该手机已认证"
                ]);
            }
        }
        if($type==1 || $type==2){
            $phoneflag = db('user')->where('phone', $phone)->count();
            if ($phoneflag > 0) {
                return json([
                    'status' => 'false',
                    'msg' => "手机号已被注册了，直接登录试试吧。"
                ]);
            }
        }
        if ($type == 3){
            $phoneflag = db('user')->where('phone', $phone)->count();
            if ($phoneflag == 0) {
                return json([
                    'status' => 'false',
                    'msg' => "该手机号未注册，直接注册使用吧~"
                ]);
            }
        }
        //初始化必填
        //填写在开发者控制台首页上的Account Sid
        $options['accountsid'] = '220c478c5d21e7ebca85c7b4b2366d88';
        //填写在开发者控制台首页上的Auth Token
        $options['token'] = '312a8bc65fe9dc4ef76d10c0655bdafc';
        $ucpass = new Ucpaas($options);
        $appid = "8d0b2b4db2f5418287a3304a7606c4fd";    //应用的ID，可在开发者控制台内的短信产品下查看
        $templateid = "490434";    //可在后台短信产品→选择接入的应用→短信模板-模板ID，查看该模板ID
        $captcha = strval(rand(1000, 9999));
        Cookie::set('captcha_' . $phone, $captcha, 360);
        $param = $captcha; //多个参数使用英文逗号隔开（如：param=“a,b,c”），如为参数则留空
        $mobile = $phone;
        $uid = "";
        return json([
            'status' => 200,
            'msg' => $ucpass->SendSms($appid, $templateid, $param, $mobile, $uid)
        ]);

    }
    //核验验证短信
    public function checkCaptcha($phone = "", $code = "", $token = "")
    {
        $errnum = 0;
        if (Cookie::has('captcha_' . $phone)) {
            $cap = Cookie::get('captcha_' . $phone);
            if ($cap == $code) {
                $res = true;
            } else {
                $res = false;
                if (Cookie::has('captcha_' . $errnum)) {
                    $errnum = Cookie::get('captcha_' . $errnum);
                    $errnum++;
                }
                Cookie::set('captcha_' . $errnum, $errnum, 180);
                if ($errnum > 10) {
                    return "错误次数过多，请3分钟后重试。";
                }
            }
        } else {
            $res = false;
        }
        if (request()->isAjax() || request()->isGet() || request()->isPost()) {
            //echo "p".$phone." c".$code."  rc:".$cap;
            return $res == false ? 0 : 1;  //正确返回1 ，0错误
        } else {
            return $res;
        }
    }
    //检测用户名存在
    public function isExistUname($uname)
    {
        $res = db('user')->where('uname', $uname)->count();
        if ($res > 0) {
            return true;
        } else {
            return false;
        }
    }
    //生成用户名
    public function generateuname($mode = 1)
    {
        $basenum_o = db('user')->field('id,uname')->order('id desc')->find();
        $basenum = $basenum_o['id'];
        $basenum_str = null;
        $factor = 3;//扩散因子
        if (is_numeric($basenum)) {
            $basenum_str = $basenum * $factor + 10000 + rand(1, 99) + $factor * $mode;
        } else {
            //用户ID出现异常情况的随机处理
            $basenum_str = rand(10000, 99999) + $factor * $mode;
        }
        //验证用户名是否存在
        $isExist = $this->isExistUname($basenum_str);
        if ($isExist) {
            //用户名存在则在增强模式中重新创建。
            return $this->generateuname(++$mode);
        } else {
            return $basenum_str;
        }
    }
    #-----------工具类函数---end----------

    ###微信授权绑定
    public function wxBindAccount(){
        $openid = input('openid');
        $nickname = input('nickname');
        $sex = input('sex');
        $headimgurl = $_FILES['head'];
        if($openid==''){
//            || $nickname=='' || $sex=='' || $headimgurl==''
            return json([
                'status'=> 201,
                'msg'=> 'openid参数为空'
            ]);
        }
        $user = db('user')->where('wxopenid',$openid)->select();
        if($user) {
            $user = $user[0];
            $nowy = date('Y');
            db('user')->where('wxopenid',$openid)->update([
                'lasttime'=> date('Y-m-d H:i:s'),
                'lastip'=> Request::instance()->ip()
            ]);
            return json([
                'status' => 200,
                'msg' => '绑定处理成功',
                'data' => [
                    'wxopenid' => $openid,
                    'uid' => $user['uname'],
                    'phone'=> $user['phone'],
                    'nickname' => $nickname,
                    'sex' => $sex,
                    'headimgurl' => $user['headimgurl'],
                    'birthday'=> $user['birthday'],
                    'age' => $nowy - substr($user['birthday'], 0, 4),
                    'location'=> $user['location'],
                    'distance'=> $user['distance'],
                    'university'=> $user['university'],
                    'job'=> $user['job'],
                    'company'=> $user['company'],
                    'income'=> $user['income'],
//                            'coverimgs'=> $coverimgs,
                    'education'=> $user['education'],
                    'hometown'=> $user['hometown'],
                    'marital'=> $user['marital'],
                    'height'=> $user['height'],
                    'weight'=> $user['weight'],
                    'certified'=> $user['certified'],
                ]
            ]);
        }else{
            $tool = new Tools();
            $data = $tool->imageuploader($headimgurl,ROOT_PATH."public/uploads/wxheadimg/".$openid."/");  // ['status'=>'1','fileurl' => $baseurl,'width'=>$width,'height'=>$height];
            if($data['status'] == 1){
                $headimgurl = $data['fileurl'];
            }else{
                $headimgurl = 'public/uploads/avatar/logo.png';
            }
            $res = db('user')->insertGetId([
                'wxopenid'=> $openid,
                'uname'=> $this->generateuname(),
                'nickname'=> $nickname,
                'sex'=> $sex,
                'headimgurl'=> $headimgurl,
                'createtime'=> date('Y-m-d H:i:s')
            ]);
            if($res){
                $user = db('user')->where('id',$res)->select();
                if($user){
                    $user = $user[0];
                    return json([
                        'status'=> 200,
                        'msg'=> '绑定处理成功',
                        'data'=>[
                            'wxopenid'=> $openid,
                            'uid'=> $user['uname'],
                            'nickname'=> $nickname,
                            'sex'=> $sex,
                            'headimgurl'=> $user['headimgurl']
                        ]
                    ]);
                }else{
                    return json([
                        'status'=> 202,
                        'msg'=> '请重新授权登陆'
                    ]);
                }
            }
        }
    }

    ###微信登陆绑定手机号
    public function bindPhone(){
        $uid = input('uid');
        $phone = input('phone');
        $vertify = input('vertify');
        if ($phone == "" || $vertify == '') {
            return json([
                'status' => '201',
                'msg' => "参数有误。"
            ]);
        }
        $code_ret = $this->checkCaptcha($phone, $vertify);
        if ($code_ret) {
            $ret = db('user')->where('uname',$uid)->update(['phone'=> $phone]);
            if($ret){
                $user = db('user')->where('uname',$uid)->select();
                if($user){
                    $res = $user[0];
                }
                return json([
                    'status'=> 200,
                    'msg'=> '绑定手机号成功',
                    'data'=> [
                        'uid' => $res['uname'],
                        'phone' => $res['phone'],
                        'nickname'=> $res['nickname'],
                        'sex'=> $res['sex'],
                        'birthday'=> $res['birthday'],
                        'wxopenid'=> $res['wxopenid'],
                        'location'=> $res['location'],
                        'distance'=> $res['distance'],
                        'university'=> $res['university'],
                        'job'=> $res['job'],
                        'company'=> $res['company'],
                        'income'=> $res['income'],
                        'headimgurl'=> $res['headimgurl'],
//                            'coverimgs'=> $coverimgs,
                        'education'=> $res['education'],
                        'hometown'=> $res['hometown'],
                        'marital'=> $res['marital'],
                        'height'=> $res['height'],
                        'weight'=> $res['weight'],
                        'certified'=> $res['certified'],
                    ]
                ]);
            }else{
                return json([
                    'status'=> 202,
                    'msg'=> '绑定失败'
                ]);
            }
        }else{
            return json([
                'status'=> 203,
                'msg'=> '验证码不正确'
            ]);
        }

    }

    #手机号密码验证码注册
    public function register()
    {
        $phone = input('phone');
        $pwd = input('pwd');
        $vertify = input('vertify');
//        $nickname = input('nickname');
        if ($phone == "" || $pwd == "" || $vertify == '') {
            return json([
                'status' => '201',
                'msg' => "参数有误。"
            ]);
        }
        $code_ret = $this->checkCaptcha($phone, $vertify);
        if ($code_ret) {
            $uname = $this->generateuname();
            //手机号用户查重
            $nickname = '用户'.substr($phone,-4);
            $res_uid = db('user')->insertGetId([
                'uname' => $uname,
                'phone' => $phone,
                'nickname' => $nickname,
                'pwd' => $pwd,
                'sex' => 1,
                'status' => 1,
                'headimgurl'=> 'public/uploads/avatar/avatar.png',
                'createip' => Request::Instance()->ip(),
                'createtime' => date('Y-m-d H:i:m'),
                'lasttime' => date('Y-m-d H:i:m'),
                'lastip' => Request::Instance()->ip(),
            ]);
            if ($res_uid) {
                if (Cookie::has('captcha_' . $phone))
                    Cookie::set('captcha_' . $phone, null);
                //注册成功　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　
                $res = db('user')->where('phone', $phone)->select()[0];
                //            return json($res);
                return json(
                    [
                        'status' => '200',
                        'msg' => "注册成功!",
                        'data' => [
                            'uid' => $res['uname'],
                            'phone' => $res['phone'],
                            'nickname'=> $res['nickname'],
                            'sex'=> $res['sex'],
                            'birthday'=> $res['birthday'],
                            'wxopenid'=> $res['wxopenid'],
                            'location'=> $res['location'],
                            'distance'=> $res['distance'],
                            'university'=> $res['university'],
                            'job'=> $res['job'],
                            'company'=> $res['company'],
                            'income'=> $res['income'],
                            'headimgurl'=> $res['headimgurl'],
//                            'coverimgs'=> $coverimgs,
                            'education'=> $res['education'],
                            'hometown'=> $res['hometown'],
                            'marital'=> $res['marital'],
                            'height'=> $res['height'],
                            'weight'=> $res['weight'],
                            'certified'=> $res['certified'],
                            'token' => md5($res['uname'] . $res['pwd'])     // 用户名连接密码md5加密作为token
                        ]
                    ]
                );
            } else {
                //注册失败
                return json([
                    'status' => 'false',
                    'msg' => "注册失败，请您尝试下重新注册。"
                ]);
            }
        } else {
            return json([
                'status' => 201,
                'msg' => '验证码不正确'
            ]);
        }
    }

    //手机号密码登录
    public function login(){
        $phone = input('phone');
        $pwd = input('pwd');
        $user_res = db('user')->where(['phone|uname'=>$phone,'pwd'=>$pwd])->select();
        if(count($user_res) == 1){
            $res = $user_res[0];
            #用户主页头像轮播，已去除
//            $headcoverimgsid = explode(',',$res['headcoverimgids']);
//            $headcoverimgs = [];
//            for($i=0;$i<count($headcoverimgsid);$i++){
//                $headcoverimg = db('source')->where('id',$headcoverimgsid[$i])->value('url');
//                array_push($headcoverimgs,$headcoverimg);
//            }
            #查询用户使用卡片情况，更新其距离值
            $cards = db('carduser')->where('ownerid',$res['uname'])->where('status',2)->select();   //2为生效中，查询其对应失效时间
            $cdistance = $res['distance'];  //当前登录用户的距离值
            if($cards){
                foreach ($cards as $card){
                    $now = date('Y-m-d H:i:s');
                    $cardname = db('promotioncard')->where('id',$card['cardid'])->value('otherinfo');  //当前生效的是卡片几
                    $much = db('distancerules')->where('otherinfo',$cardname)->value('much');  //卡片几对应的距离
//                    if ($card['expirationdate'] <= $now){  //已过期
                        if ($card['expirationdate'] && $card['expirationdate'] <= $now){
                        $cdistance -= $much;
                        db('carduser')->where('id',$card['id'])->update(['status'=> 0]);
                    }
                }
                db('user')->where('uname',$res['uname'])->update(['distance'=> $cdistance]);
            }
            return json([
                'status'=> 200,
                'msg'=> '登录成功！',
                'data'=> [
                    'uid' => $res['uname'],
                    'phone' => $res['phone'],
                    'nickname'=> $res['nickname'],
                    'sex'=> $res['sex'],
                    'birthday'=> $res['birthday'],
                    'wxopenid'=> $res['wxopenid'],
                    'location'=> $res['location'],
                    'distance'=> $res['distance'],
                    'university'=> $res['university'],
                    'job'=> $res['job'],
                    'company'=> $res['company'],
                    'income'=> $res['income'],
                    'headimgurl'=> $res['headimgurl'],
//                    'headcoverimgs'=> $headcoverimgs,
                    'education'=> $res['education'],
                    'hometown'=> $res['hometown'],
                    'marital'=> $res['marital'],
                    'height'=> $res['height'],
                    'weight'=> $res['weight'],
                    'certified'=> $res['certified'],
                    'token' => md5($res['uname'] . $res['pwd'])     // 用户名连接密码md5加密作为token
                ]
            ]);
        }else{
            return json([
                'status'=> 201,
                'msg'=> '用户不存在,请检查用户名及密码！'
            ]);
        }

    }

    #手机号密码验证码 重置密码
    public function resetPwd($phone='', $vertify='', $newpwd='')
    {
        $phone = input('phone');
        $vertify = input('vertify');
        $newpwd = input('newpwd');
        if ($phone == ""  || $newpwd == "") {
            return json([
                'status' => 201,
                'msg' => "参数有误。"
            ]);
        }
        $code_ret = $this->checkCaptcha($phone, $vertify);
        if ($code_ret) {
            $res = db('user')->where('phone', '=', $phone)->find();
            if ($res) {
                $resetp = db('user')->where('phone', $phone)->update(['pwd' => $newpwd]);
                if ($resetp == 1) {
                    $data = [];
                    array_push($data,[
                        'uid' => $res['uname'],
                        'phone' => $res['phone'],
                        'nickname'=> $res['nickname'],
                        'sex'=> $res['sex'],
                        'birthday'=> $res['birthday'],
                        'wxopenid'=> $res['wxopenid'],
                        'location'=> $res['location'],
                        'distance'=> $res['distance'],
                        'university'=> $res['university'],
                        'job'=> $res['job'],
                        'company'=> $res['company'],
                        'income'=> $res['income'],
                        'headimgurl'=> $res['headimgurl'],
//                        'headcoverimgs'=> $headcoverimgs,
                        'education'=> $res['education'],
                        'hometown'=> $res['hometown'],
                        'marital'=> $res['marital'],
                        'height'=> $res['height'],
                        'weight'=> $res['weight'],
                        'certified'=> $res['certified'],
                        'token' => md5($res['uname'] . $newpwd)     // 用户名连接新密码md5加密作为token
                    ]);

                    return json([
                        'status' => 200,
                        'msg' => '重置密码成功',
                        'data'=> $data
                    ]);
                } else {
                    return json([
                        'status' => 201,
                        'msg' => '重置密码失败，请重试',
                    ]);
                }
            } else {
                return json([
                    'status' => 202,
                    'msg' => '手机号不存在'
                ]);
            }
        } else {
            return json([
                'status' => 203,
                'msg' => '验证码不正确'
            ]);
        }
    }

    ###注册成功初始化基本资料
    public function initialInfo(){
        $uid = input('uid');
        if($uid == ''){
            return json([
                'status'=> 201,
                'msg'=> 'uid参数为空'
            ]);
        }
        $nickname = input('nickname');
        $sex = input('sex');
        $birthday = input('birthday');
        if($nickname == '' || $sex == '' || $birthday == ''){
            return json([
                'status'=> 201,
                'msg'=> '参数为空'
            ]);;
        }
        $res = db('user')->where('uname',$uid)->update([
            'nickname'=> $nickname,
            'sex'=> $sex,
            'birthday'=> $birthday
        ]);
        if($res == 1){
            return json([
                'status'=> 200,
                'msg'=> '初始化资料成功'
            ]);
        }else{
            return json([
                'status'=> 202,
                'msg'=> '初始化资料失败'
            ]);
        }
    }

    ###批量更新资料（填写资料页）
    public function updateInfos(){
        $uid = input('uid');
        if($uid == ''){
            return json([
                'status'=> 201,
                'msg'=> 'uid参数为空'
            ]);
        }
        $nickname = input('nickname');
        $sex = input('sex');
        $job = input('job');
        $income = input('income');
        $marital = input('marital');
        $worth = input('worth');
        $height = input('height');
        $education = input('education');
        $birthday = input('birthday');
        switch ($education){
            case '其它':
                $education = 1;break;
            case '本科':
                $education = 2;break;
            case '硕士':
                $education = 3;break;
            case '博士':
                $education = 4;break;
        }
        $university = input('university');
        $location = input('location');
        $hometown = input('hometown');
//        $worthret = db('identity')->where('uid',$uid)->update(['worth'=> $worth]);
        $res = db('user')->where('uname',$uid)->update([
            'nickname'=> $nickname,
            'sex'=> $sex,
            'income'=> $income,
            'job'=> $job,
            'birthday'=> $birthday,
            'marital'=> $marital,
            'height'=> $height,
            'education'=> $education,
            'university'=> $university,
            'location'=> $location,
            'hometown'=> $hometown
        ]);
        if($res){
            return json([
                'status'=> 200,
                'msg'=> '更新成功'
            ]);
        }else{
            return json([
                'status'=> 202,
                'msg'=> '更新失败'
            ]);
        }
    }

    #通过uid获得个人基本信息
    public function getUserInfoByUid(){
        $uid = input('uid');
        if($uid == ''){
            return json([
                'status'=> 201,
                'msg'=> 'uid参数错误'
            ]);
        }
        $user = db('user')->where('uname',$uid)->select();
        if($user){
            $user = $user[0];
            $cards = db('carduser')->where('ownerid', $user['uname'])->where('status', 'in', [1, 2])->select();
            $alldata = [];
            $rcards = [];
            if (count($cards) > 0) {
                foreach ($cards as $item) {
                    $card = db('promotioncard')->where('id', $item['cardid'])->select();
//                return json($card);
                    if ($card) {
                        $card = $card[0];
                        array_push($rcards,  [
                            'cardid' => $card['cardid'],
                            'cardname' => $card['cardname'],
                            'cardimgurl' => $card['cardimgurl'],
                            'toid' => $item['toid'],
                            'content' => $item['content'],
                            'status' => $item['status'],
                            'startdate' => $item['startdate'],
                            'expirationdate' => $item['expirationdate'],
                            'createtime' => $item['createtime']
                        ]);
                    }
                }
                array_push($alldata,['cards'=>$rcards]);
            }else{
                array_push($alldata,['cards'=>[]]);
            }
            $headcoverimgsid = explode(',',$user['headcoverimgids']);
            $headcoverimgsurl = [];
            for($i=0;$i<count($headcoverimgsid);$i++){
                $coverimg = db('source')->where('id',$headcoverimgsid[$i])->value('url');
                array_push($headcoverimgsurl,$coverimg);
            }
            $nowy = date('Y');
            $data = [
                'uid'=> $user['uname'],
                'phone'=> $user['phone'],
                'wxopenid'=> $user['wxopenid'],
                'nickname'=> $user['nickname'],
                'sex'=> $user['sex'],
                'birthday'=> $user['birthday'],
                'age' => $nowy - substr($user['birthday'], 0, 4),
                'location'=> $user['location'],
                'distance'=> $user['distance'],
                'university'=> $user['university'],
                'job'=> $user['job'],
                'company'=> $user['company'],
                'income'=> $user['income'],
                'headimgurl'=> $user['headimgurl'],
                'headcoverimgsurl'=> $headcoverimgsurl,
                'education'=> $user['education'],
                'hometown'=> $user['hometown'],
                'marital'=> $user['marital'],
                'height'=> $user['height'],
                'weight'=> $user['weight'],
                'certified'=> $user['certified']
            ];
            array_push($alldata,['userinfo'=> $data]);
            return json([
                'status'=> 200,
                'msg'=> '获取用户信息成功',
                'data'=> $alldata
            ]);
        }else{
            return json([
                'status'=> 201,
                'msg'=> '用户不存在'
            ]);
        }
    }

    ###通过uid获得编辑资料页数据
    public function editData(){
        $uid = input('uid');
        $user = db('user')->where('uname',$uid)->select();
        if($user){
            $user = $user[0];
            $worth = db('identity')->where('uid',$user['uname'])->value('worth');
            if(!$worth){
                $worth = '';
            }
            return json([
                'status'=> 200,
                'data'=> [
                    'uid'=> $uid,
                    'headimgurl'=> $user['headimgurl'],
                    'nickname'=> $user['nickname'],
                    'sex'=> $user['sex'],
                    'birthday'=> $user['birthday'],
                    'job'=> $user['job'],
                    'income'=> $user['income'],
                    'marital'=> $user['marital'],
                    'worth'=> $worth,
                    'height'=> $user['height'],
                    'education'=> $user['education'],
                    'university'=> $user['university'],
                    'location'=> $user['location'],
                    'hometown'=> $user['hometown']
                ]
            ]);
        }else{
            return json([
                'status'=> 202,
                'msg'=> '用户不存在'
            ]);
        }
    }

    ###通过uid获得个人的额外资料
    public function getExtraInfo(){
        $uid = input('uid');
        if($uid == ''){
            return json([
                'status'=> 201,
                'msg'=> 'uid参数错误'
            ]);
        }
        $res = db('extrainfo')->where('uid',$uid)->select();
        if($res){
            $res = $res[0];
            return json([
               'status'=> 200,
               'data'=> [
                   'uid'=> $res['uid'],
                   'labels'=> $res['labels'],
                   'description'=> $res['description'],
                   'desimg'=> $res['descriptionimgurl'],
                   'family'=> $res['family'],
                   'familyimg'=> $res['familyimgurl'],
                   'hobbies'=> $res['hobbies'],
                   'hobbyimg'=> $res['hobbyimgurl'],
                   'lovepoint'=> $res['lovepoint'],
                   'lovepointimg'=> $res['lovepointimgurl'],
                   'partner'=> $res['partner'],
                   'partnerimg'=> $res['partnerimgurl'],
                   'whysignal'=> $res['whysignal'],
                   'whysignalimg'=> $res['whysignalimgurl'],
                   'expectinglife'=> $res['expectinglife'],
                   'expectimg'=> $res['expectinglifeimgurl'],
                   'createtime'=> $res['createtime']
               ]
            ]);
        }else{
            return json([
                'status'=> 202,
                'msg'=> '获取个人额外资料失败'
            ]);
        }
    }

    ###单字段更新个人额外资料
    public function updateInfo(){
        $uid = input('uid');
        $field = input('field');
        $newval = input('newval');
        if($uid == ''){
            return json([
                'status'=> 201,
                'msg'=> 'uid参数错误'
            ]);
        }
        $imgnum = input('imgnum');

        if($imgnum && $imgnum>0){
            $tool = new Tools();
            $myfile = $_FILES['file1'];
            $data = $tool->imageuploader($myfile);
            if($data['status'] == 1){
                $imgurl = $data['fileurl'];
            }else{
                $imgurl = null;
            }
        }else{
            $imgurl = null;
        }
        switch ($field){
            case 'description':
                $imgf = 'descriptionimgurl';
                break;
            case 'family':
                $imgf = 'familyimgurl';
                break;
            case 'hobbies':
                $imgf = 'hobbyimgurl';
                break;
            case 'lovepoint':
                $imgf = 'lovepointimgurl';
                break;
            case 'partner':
                $imgf = 'partnerimgurl';
                break;
            case 'whysignal':
                $imgf = 'whysignalimgurl';
                break;
            case 'expectinglife':
                $imgf = 'expectinglifeimgurl';
                break;
        }
        $user = db('extrainfo')->where('uid',$uid)->select();
        if(count($user) == 1){  //执行更新操作
            $user = $user[0];
            if($user[$field] == '' || $user[$field] == null){
                if($user[$imgf] != null && $imgurl == null){
                    $ret = db('extrainfo')->where('uid',$uid)->update([
                        $field=> $newval,
                        'createtime'=> date('Y-m-d H:i:s')
                    ]);
                }else{
                    $ret = db('extrainfo')->where('uid',$uid)->update([
                        $field=> $newval,
                        $imgf => $imgurl,
                        'createtime'=> date('Y-m-d H:i:s')
                    ]);
                }
                if($ret){
                    $distance = db('user')->where('uname',$uid)->value('distance');   ####-------------------------加距离操作
                    $much = db('distancerules')->where('when',2)->value('much');
                    db('user')->where('uname',$uid)->update(['distance'=>floatval($distance+$much)]);
                    return json([
                        'status'=> 200,
                        'msg'=> '更新成功'
                    ]);
                }else{
                    return json([
                        'status'=> 204,
                        'msg'=> '更新失败'
                    ]);
                }
            }else{
                if($user[$imgf] != null && $imgurl == null){
                    $ret = db('extrainfo')->where('uid',$uid)->update([
                        $field=> $newval,
                        'createtime'=> date('Y-m-d H:i:s')
                    ]);
                }else{
                    $ret = db('extrainfo')->where('uid',$uid)->update([
                        $field=> $newval,
                        $imgf => $imgurl,
                        'createtime'=> date('Y-m-d H:i:s')
                    ]);
                }
                if($ret){
                    return json([
                        'status'=> 200,
                        'msg'=> '更新成功'
                    ]);
                }else{
                    return json([
                        'status'=> 204,
                        'msg'=> '更新失败'
                    ]);
                }
            }
//            }
        }else{ //执行插入操作
            $ret = db('extrainfo')->insert(['uid'=> $uid, $field=>$newval, $imgf=> $imgurl,'createtime'=> date('Y-m-d H:i:s')]);
            if($ret){
                $distance = db('user')->where('uname',$uid)->value('distance');       ####-------------------------加距离操作
                $much = db('distancerules')->where('when',2)->value('much');
                db('user')->where('uname',$uid)->update(['distance'=>floatval($distance+$much)]);
                return json([
                    'status'=> 200,
                    'msg'=> '更新成功'
                ]);
            }else{
                return json([
                    'status'=> 204,
                    'msg'=> '更新失败'
                ]);
            }
        }
    }
    ###更新标签
    public function updateLabels(){
        $uid = input('uid');
        $labels = input('labels');
        $res = db('extrainfo')->where('uid',$uid)->select();
        if($res){
            $res = $res[0];
            if($res['labels'] == '' || $res['labels'] == null){
                $ret = db('extrainfo')->where('uid',$uid)->update(['labels'=>$labels]);

                $distance = db('user')->where('uname',$uid)->value('distance');          ####-------------------------加距离操作
                $much = db('distancerules')->where('when',1)->value('much');
                db('user')->where('uname',$uid)->update(['distance'=>floatval($distance+$much)]);
            }else{
                $ret = db('extrainfo')->where('uid',$uid)->update(['labels'=>$labels]);
            }
        }else{
            $ret = db('extrainfo')->insert(['uid'=>$uid, 'labels'=>$labels]);

            $distance = db('user')->where('uname',$uid)->value('distance');             ####-------------------------加距离操作
            $much = db('distancerules')->where('when',1)->value('much');
            db('user')->where('uname',$uid)->update(['distance'=>floatval($distance+$much)]);
        }
        if($ret){
            return json([
                'status'=> 200,
                'msg'=> '更新成功'
            ]);
        }else{
            return json([
                'status'=> 201,
                'msg'=> '更新失败'
            ]);
        }
    }

    ###更新头像
    public function updateAvatar(){
        $uid = input('uid');
        if($uid == ''){
            return json([
                'status'=> 201,
                'msg'=> 'uid参数错误'
            ]);
        }

        $tool = new Tools();
        $myfile = $_FILES['img'];
        $data = $tool->imageuploader($myfile);
        $status = $data['status'];
        if($status == 1){
            $res = db('user')->where('uname',$uid)->update(['headimgurl'=>$data['fileurl']]);
            if($res == 1){
                return json([
                    'status'=> 200,
                    'msg'=> '更新头像成功'
                ]);
            }else{
                return json([
                    'status'=> 203,
                    'msg'=> '更新头像失败'
                ]);
            }
        }else{
            return json([
                'status'=> 201,
                'msg'=>$data['msg']
            ]);
        }
    }

    ###返回两个用户之间的关注关系 ------------------------------------
    public function attentInfo(){
        $uid = input('uid');
        $ouid = input('ouid');
        $uidattents = db('fan')->where('uid',$uid)->column('fanuid');
        $ouidattents = db('fan')->where('uid',$ouid)->column('fanuid');
        if(in_array($uid,$ouidattents) && in_array($ouid,$uidattents)){
            return json([
                'type'=> 1,
                'msg'=> '互关'
            ]);
        }elseif (in_array($uid,$ouidattents) && !in_array($ouid,$uidattents)){
            return json([
                'type'=> 2,
                'msg'=> 'uid被关注'
            ]);
        }elseif (!in_array($uid,$ouidattents) && in_array($ouid,$uidattents)){
            return json([
                'type'=> 3,
                'msg'=> 'uid关注了ouid'
            ]);
        }else{
            return json([
                'type'=> 4,
                'msg'=> '互不相关'
            ]);
        }
    }
    ###返回两个用户之间的距离及关注关系
    public function distanceInfo(){
        $uid = input('uid');
        $ouid = input('ouid');
        if(!$uid || !$ouid){
            return json([
                'status'=> 201,
                'msg'=>'参数错误'
            ]);
        }
        $uiddis = db('user')->where('uname',$uid)->value('distance');
        $ouiddis = db('user')->where('uname',$ouid)->value('distance');
        $uidattents = db('fan')->where('uid',$uid)->column('fanuid');
        $ouidattents = db('fan')->where('uid',$ouid)->column('fanuid');
        if(in_array($uid,$ouidattents) && in_array($ouid,$uidattents)){
                $typea = 1;
                $msga = '互关';
        }elseif (in_array($uid,$ouidattents) && !in_array($ouid,$uidattents)){
                $typea = 2;
                $msga = 'uid被关注';
        }elseif (!in_array($uid,$ouidattents) && in_array($ouid,$uidattents)){
                $typea = 3;
                $msga = 'uid关注了ouid';
        }else{
                $typea = 4;
                $msga = '互不相关';
        }
        if(abs($uiddis - $ouiddis) > 5){
            return json([
                'type'=> 1,
                'msg'=> '超出范围',
                'atype'=> $typea,
                'amsg'=> $msga
            ]);
        }else{
            return json([
                'type'=> 2,
                'msg'=> '范围内',
                'atype'=> $typea,
                'amsg'=> $msga
            ]);
        }
    }

    ###我的关注页
    public function myAttention(){
        $uid = input('uid');
        if($uid == ''){
            return json([
                'status'=> 201,
                'msg'=> 'uid参数错误'
            ]);
        }
        $attentions = db('fan')->where('uid',$uid)->column('fanuid');
        if(count($attentions)>0){
            $users = [];
            for($i=0; $i<count($attentions);$i++){
                $user = db('user')->where('uname',$attentions[$i])->select();
                if($user){
                    $user = $user[0];
                }
                $userfan = db('fan')->where('uid',$attentions[$i])->column('fanuid');
                if(in_array($uid,$userfan)){
                    $attenttype = 1;  //互相关注
                }else{
                    $attenttype = 2;  //uid单方面关注了用户
                }
                $createtime = db('fan')->where(['uid'=> $uid, 'fanuid'=> $attentions[$i]])->value('createtime');
                array_push($users,[
                    'uid'=> $uid,
                    'attentuid'=> $user['uname'],
                    'headimgurl'=> $user['headimgurl'],
                    'nickname'=> $user['nickname'],
                    'distance'=> $user['distance'],
                    'createtime'=>$createtime,
                    'attenttype'=> $attenttype

                ]);
            }
            return json([
                'status'=> 200,
                'msg'=> '获取关注列表成功',
                'data'=> $users
            ]);
        }else{
            return json([
                'status'=> 202,
                'msg'=> '暂未关注任何小伙伴'
            ]);
        }
    }

    ###我的粉丝页
    public function myFans(){
        $uid = input('uid');
        if($uid == ''){
            return json([
                'status'=> 201,
                'msg'=> 'uid参数错误'
            ]);
        }
        $fans = db('fan')->where('fanuid',$uid)->column('uid');  //我的粉丝uid列表
        if(count($fans)>0){
            $users = [];
            for($i=0; $i<count($fans);$i++){
                $user = db('user')->where('uname',$fans[$i])->select()[0];
                $attentuser = db('fan')->where('uid',$uid)->column('fanuid'); //我关注的uid列表
                if(in_array($fans[$i],$attentuser)){
                    $attenttype = 1;  //互相关注
                }else{
                    $attenttype = 2;  //粉丝单方面关注我
                }
                $createtime = db('fan')->where(['uid'=> $fans[$i], 'fanuid'=> $uid])->value('createtime');
                array_push($users,[
                    'uid'=> $uid,
                    'fanuid'=> $user['uname'],
                    'headimgurl'=> $user['headimgurl'],
                    'nickname'=> $user['nickname'],
                    'distance'=> $user['distance'],
                    'createtime'=>$createtime,
                    'attenttype'=> $attenttype
                ]);
            }
            return json([
                'status'=> 200,
                'msg'=> '获取粉丝列表成功',
                'data'=> $users
            ]);
        }else{
            return json([
                'status'=> 202,
                'msg'=> '暂无小伙伴关注我'
            ]);
        }
    }

    ###执行关注与取关动作
    public function attentUser(){
        $uid = input('uid');
        $otheruid = input('ouid');
        if ($uid == '' || $otheruid == ''){
            return json([
                'status'=> 201,
                'msg'=> 'uid或ouid参数错误'
            ]);
        }
        $fans = db('fan')->where('uid',$uid)->column('fanuid');
        $otherfans = db('fan')->where('uid',$otheruid)->column('fanuid');
        if(in_array($uid,$otherfans) && in_array($otheruid,$fans)){  //互相关注  ->执行取关,操作使得otheruid单方面关注我
            $ret = db('fan')->where(['uid'=> $uid, 'fanuid'=> $otheruid])->delete();
            if($ret == 1){
                return json([
                    'status'=> 200,
                    'msg'=> '取关成功',
                    'data'=> 0
                ]);
            }
        }elseif (in_array($uid,$otherfans) && !in_array($otheruid,$fans)){  //otheruid 单方面关注我，uid被关注->执行关注，操作使得互相关注
            $ret = db('fan')->insert(['uid'=>$uid, 'fanuid'=>$otheruid,'createtime'=> date('Y-m-d H:i:s')]);
            if($ret == 1){
                return json([
                    'status'=> 200,
                    'msg'=> '关注成功',
                    'data'=> 1
                ]);
            }
        }elseif (!in_array($uid,$otherfans) && in_array($otheruid,$fans)){  //uid单方面关注otheruid ->执行取关，操作使得互不相关
            $ret = db('fan')->where(['uid'=> $uid, 'fanuid'=> $otheruid])->delete();
            if($ret == 1){
                return json([
                    'status'=> 200,
                    'msg'=> '取关成功',
                    'data'=> 0
                ]);
            }
        }else{  //互不相关 ->执行关注，操作使得uid单方面关注otheruid
            $ret = db('fan')->insert(['uid'=> $uid, 'fanuid'=>$otheruid,'createtime'=> date('Y-m-d H:i:s')]);
            if($ret == 1){
                return json([
                    'status'=> 200,
                    'msg'=> '关注成功',
                    'data'=> 1
                ]);
            }
        }
    }

    ###执行喜欢与取消喜欢
    public function doLike(){
        $uid = input('uid');
        $cid = input('cid');  // user.id   content.id
        $type = input('type');
        if($uid == '' || $cid == '' || $type == ''){
            return json([
                'status'=> 201,
                'msg'=> 'uid或cid或type参数错误'
            ]);
        }
        $res = db('like')->where(['uid'=>$uid, 'contentid'=> $cid, 'type'=> $type])->count();
        if($res == 1){  //已经喜欢，执行取消喜欢动作
            $ret = db('like')->where(['uid'=>$uid, 'contentid'=> $cid, 'type'=> $type])->delete();
            if($ret == 1){
                return json([
                    'status'=> 200,
                    'msg'=> '取消喜欢成功'
                ]);
            }else{
                return json([
                    'status'=> 203,
                    'msg'=> '操作失败'
                ]);
            }
        }else{  //喜欢
            $ret = db('like')->insert(['uid'=>$uid, 'contentid'=> $cid, 'type'=> $type]);
            if($ret == 1){
                return json([
                    'status'=> 200,
                    'msg'=> '喜欢成功'
                ]);
            }
        }
    }

    ###动态/用户的拉黑操作   type：1.用户 2.动态
    public function addBlacklist(){
        $uid = input('uid');
        $type = input('type');
        $cid = input('cid');
        if($uid == '' || $type == '' || $cid == ''){
            return json([
                'msg'=> 'uid或type或cid参数不正确'
            ]);
        }
        $res = db('blacklist')->where('uid',$uid)->where('cid',$cid)->where('type',$type)->select();
        $data = '';
        $msg = '';
        if(count($res) == 0){
            $data = 1;  #之前未拉黑，执行拉黑成功
            $add_blacklist_ret = db('blacklist')->insert(['uid'=>$uid, 'type'=>$type, 'cid'=>$cid, 'createtime'=>date('Y-m-d H:i:s')]);
            $add_blacklist_ret == 1 ? ($msg = '拉黑成功') : ($msg = '请重新操作');
        }else{
            $data = 2;  #之前拉黑，取消拉黑成功
            $add_blacklist_ret = db('blacklist')->where(['uid'=>$uid,'cid'=>$cid,'type'=>$type])->delete();
            $add_blacklist_ret == 1 ? ($msg = '取消拉黑成功') : ($msg = '请重新操作');
        }
        $alldata = [
            'status'=> 200,
            'msg'=> $msg,
            'data'=> $data
        ];
        return json($alldata);
    }

    ###动态点赞与取消点赞
    public function supportDynamic(){
        $uid = input('uid');
        $cid = input('cid');
        $contentuid = input('contentuid');
        if($uid == '' || $cid == '' || $contentuid == ''){
            return json([
                'status'=> 201,
                'msg'=> 'uid或cid或contentuid参数错误'
            ]);
        }
        $issupport = db('support')->where(['contentid'=>$cid, 'uid'=>$uid,'type'=>2])->count();
        if($issupport == 1){  //已经赞，执行取消赞操作
            $res = db('support')->where(['contentid'=>$cid, 'uid'=>$uid,'type'=>2])->delete();
            if($res == 1){
                return json([
                    'status'=> 200,
                    'data'=> 0,
                    'msg'=> '取消赞成功'
                ]);
            }else{
                return json([
                    'status'=> 201,
                    'msg'=> '操作失败'
                ]);
            }
        }else{
            $res = db('support')->insert(['uid'=>$uid,'contentid'=>$cid,'contentuid'=>$contentuid,'type'=>2,'createtime'=>date('Y-m-d H:i:s')]);
            if($res == 1){
                return json([
                    'status'=> 200,
                    'data'=> 1,
                    'msg'=> '赞成功'
                ]);
            }else{
                return json([
                    'status'=> 201,
                    'msg'=> '操作失败'
                ]);
            }
        }
    }

    ###删除自己的某条动态
    public function delDynamic(){
        $uid = input('uid');
        $cid = input('cid');
        if($uid == '' || $cid == ''){
            return json([
                'status'=> 201,
                'msg'=> 'uid|cid参数为空'
            ]);
        }
        $res = db('content')->where('uid',$uid)->where('id',$cid)->update(['status'=>0]);
        if($res){
            return json([
                'status'=> 200,
                'data'=> 1
            ]);
        }else{
            return json([
                'status'=> 200,
                'data'=> 0
            ]);
        }
    }

    ###用户的动态列表页数据
    public function dynamicList($num = 8){
        $uid = input('uid');
        $noreadcid = input('noreadcid');
        if($uid == ''){
            return json([
                'status'=> 201,
                'msg'=> 'uid参数错误'
            ]);
        }
        if($noreadcid == null){
            $noreadcid = '';
        }
        $contents = db('content')->where('id','not in',$noreadcid)->where('uid',$uid)->where('status',1)->order('createtime desc')->limit(8)->select();
        if(count($contents)>0){
            $retcontents = [];
            //动态作者信息
            $contentuser = db('user')->where('uname',$contents[0]['uid'])->select()[0];

            $headcoverimgsid = explode(',',$contentuser['headcoverimgids']);
            $headcoverimgsurl = [];
            for($i=0;$i<count($headcoverimgsid);$i++){
                $coverimg = db('source')->where('id',$headcoverimgsid[$i])->value('url');
                array_push($headcoverimgsurl,$coverimg);
            }

            $contentuserinfo = ['phone'=> $contentuser['phone'],
                'wxopenid'=> $contentuser['wxopenid'],
                'nickname'=> $contentuser['nickname'],
                'sex'=> $contentuser['sex'],
                'birthday'=> $contentuser['birthday'],
                'location'=> $contentuser['location'],
                'distance'=> $contentuser['distance'],
                'university'=> $contentuser['university'],
                'job'=> $contentuser['job'],
                'company'=> $contentuser['company'],
                'income'=> $contentuser['income'],
                'headimgurl'=> $contentuser['headimgurl'],
                'headcoverimgsurl'=> $headcoverimgsurl,
                'education'=> $contentuser['education'],
                'hometown'=> $contentuser['hometown'],
                'marital'=> $contentuser['marital'],
                'height'=> $contentuser['height'],
                'weight'=> $contentuser['weight'],
                'certified'=> $contentuser['certified']];
            foreach ($contents as $content){
                if (strlen($content['coverimgid']) != 0) {
                    $coverimgid = explode(',', $content['imagesid']);
                    $coverimgurl = [];
                    for ($i = 0; $i < count($coverimgid); $i++) {
                        $coverimg = db('source')->where('id', $coverimgid[$i])->value('url');
                        $thumbnail = db('source')->where('id', $coverimgid[$i])->value('thumbnail');
                        if(strlen($thumbnail) != 0 && substr($thumbnail,-3) == 'mp4'){
                            array_push($coverimgurl, ['cover'=>$coverimg, 'video'=> $thumbnail]);
                        }else{
                            array_push($coverimgurl, ['cover'=>$coverimg, 'video'=> '']);
                        }
                    }
                } else {
                    $coverimgurl = [];
                }

                $supportnum = db('support')->where('contentid',$content['id'])->where('type',2)->count();
                $commentnum = db('comment')->where('contentid',$content['id'])->count();
                array_push($retcontents,[
                    'contentid'=> $content['id'],
                    'uid'=> $content['uid'],
                    'title'=> $content['title'],
//                    'coverimgurl'=> $coverimgurl,
                    'imagesurl'=> $coverimgurl,
                    'supportnum'=> $supportnum,
                    'commentnum'=> $commentnum
                ]);
            }
            return json([
                'status'=> 200,
                'msg'=> '获取动态列表成功',
                'data'=> ['contents'=>$retcontents, 'contentuser'=> $contentuserinfo]
            ]);
        }else{
            return json([
                'status'=> 201,
                'msg'=> '用户没有发布任何动态'
            ]);
        }
    }

    ###获得单条动态详情
    public function signalDynamic(){
        $cid = input('cid');
        if($cid == ''){
            return json([
                'status'=> 201,
                'msg'=> 'cid参数错误'
            ]);
        }
        $content = db('content')->where('id',$cid)->select();
        if(count($content) == 1){
            $content = $content[0];
            $contentuser = db('user')->where('uname',$content['uid'])->select()[0];
//            $coverimgurl = db('source')->where('id',$content['coverimgid'])->value('url');
            $imagesid = explode(',',$content['imagesid']);
            $imagesurl = [];
            for($i=0;$i<count($imagesid);$i++){
                $img = db('source')->where('id',$imagesid[$i])->value('url');
                array_push($imagesurl,$img);
            }
            $supportnum = db('support')->where('contentid',$cid)->where('type',2)->count();
            $commentnum = db('comment')->where('contentid',$cid)->count();
            $headcoverimgsid = explode(',',$contentuser['headcoverimgids']);
            $headcoverimgsurl = [];
            for($i=0;$i<count($headcoverimgsid);$i++){
                $coverimg = db('source')->where('id',$headcoverimgsid[$i])->value('url');
                array_push($headcoverimgsurl,$coverimg);
            }
            $data = [
                'contentid'=> $content['id'],
                'uid'=> $content['uid'],
                'title'=> $content['title'],
                'imagesurl'=> $imagesurl,
                'supportnum'=> $supportnum,
                'commentnum'=> $commentnum,
                'phone'=> $contentuser['phone'],
                'wxopenid'=> $contentuser['wxopenid'],
                'nickname'=> $contentuser['nickname'],
                'sex'=> $contentuser['sex'],
                'birthday'=> $contentuser['birthday'],
                'location'=> $contentuser['location'],
                'distance'=> $contentuser['distance'],
                'university'=> $contentuser['university'],
                'job'=> $contentuser['job'],
                'company'=> $contentuser['company'],
                'income'=> $contentuser['income'],
                'headimgurl'=> $contentuser['headimgurl'],
                'headcoverimgsurl'=> $headcoverimgsurl,
                'education'=> $contentuser['education'],
                'hometown'=> $contentuser['hometown'],
                'marital'=> $contentuser['marital'],
                'height'=> $contentuser['height'],
                'weight'=> $contentuser['weight'],
                'certified'=> $contentuser['certified']

            ];
            return json([
                'status'=> 200,
                'msg'=> '获取成功',
                'data'=> $data
            ]);
        }else{
            return json([
                'status'=> 201,
                'msg'=> '未找到指定内容'
            ]);
        }
    }

    ###获得用户卡片使用情况
    public function cardUser(){
        $uid = input('uid');
        if($uid == ''){
            return json([
                'status'=> 201,
                'msg'=> 'uid参数错误'
            ]);
        }
        $cards = db('carduser')->where('ownerid',$uid)->select();
        if(count($cards)>0){
            $data = [];
            foreach ($cards as $item){
                $now = date('Y-m-d H:i:s');
                if ($item['expirationdate'] && $item['expirationdate'] <= $now){  //已过期
                    db('carduser')->where('id',$item['id'])->update(['status'=> 0]);
                }
                $card = db('promotioncard')->where('id',$item['cardid'])->select();
//                return json($card);
                if($card){
                    $card = $card[0];
                    array_push($data,[
                        'cardid'=> $card['cardid'],
                        'cardname'=> $card['cardname'],
                        'cardimgurl'=> $card['cardimgurl'],
                        'toid'=> $item['toid'],
                        'content'=> $item['content'],
                        'status'=> $item['status'],
                        'startdate'=> $item['startdate'],
                        'expirationdate'=> $item['expirationdate'],
                        'createtime'=> $item['createtime']
                    ]);
                }
            }
            return json([
                'status'=> 200,
                'data'=> $data
            ]);
        }else{
            return json([
                'status'=> 201,
                'msg'=> '暂无特权卡'
            ]);
        }
    }

    ###我的页去除已使用的卡片
    public function cardMine(){
        $uid = input('uid');
        if($uid == ''){
            return json([
                'status'=> 201,
                'msg'=> 'uid参数错误'
            ]);
        }
        $cards = db('carduser')->where('ownerid',$uid)->where('status',2)->select();
        if(count($cards)>0){
            $data = [];
            foreach ($cards as $item){
                $now = date('Y-m-d H:i:s');
                if ($item['expirationdate'] && $item['expirationdate'] <= $now){  //已过期
                    db('carduser')->where('id',$item['id'])->update(['status'=> 0]);
                }
                $card = db('promotioncard')->where('id',$item['cardid'])->select();
//                return json($card);
                if($card){
                    $card = $card[0];
                    array_push($data,[
                        'cardid'=> $card['cardid'],
                        'cardname'=> $card['cardname'],
                        'cardimgurl'=> $card['cardimgurl'],
                        'toid'=> $item['toid'],
                        'content'=> $item['content'],
                        'status'=> $item['status'],
                        'startdate'=> $item['startdate'],
                        'expirationdate'=> $item['expirationdate'],
                        'createtime'=> $item['createtime']
                    ]);
                }
            }
            return json([
                'status'=> 200,
                'data'=> $data
            ]);
        }else{
            return json([
                'status'=> 201,
                'msg'=> '暂无特权卡'
            ]);
        }
    }

    ###个人认证情况
    public function verifyInfo(){
        $uid = input('uid');
        if($uid == ''){
            return json([
                'status'=> 201,
                'msg'=> 'uid参数为空'
            ]);
        }
        $useridentity = db('identity')->where('uid',$uid)->select();
        if($useridentity) {
            $useridentity = $useridentity[0];
            $realname = $useridentity['realname'];
            $idcard = $useridentity['idcard'];
            $idcardfront = $useridentity['idcardfront'];
            $idcardback = $useridentity['idcardback'];
            $education = $useridentity['education'];
            $university = $useridentity['university'];
            $educationimg = $useridentity['educationimg'];
            $industry = $useridentity['industry'];
            $companyname = $useridentity['companyname'];
            $duty = $useridentity['duty'];
            $incomeinfo = $useridentity['incomeinfo'];
            $jobimg = $useridentity['jobimg'];
            $worth = $useridentity['worth'];
            $worthimg = $useridentity['worthimg'];
            $realnamestatus = $useridentity['realnamestatus'];
            $edustatus = $useridentity['edustatus'];
            $jobstatus = $useridentity['jobstatus'];
            $worthstatus = $useridentity['worthstatus'];
            if ($realname != '' && $idcard != '' && $idcardfront != '' && $idcardback != '') {
                $realnamemsg = '已实名认证';
                $realnamestate = 1;
            }else{
                $realnamemsg = '未实名认证';
                $realnamestate = 0;
            }
            if($education!= '' && $university != '' && $educationimg != ''){
                $edumsg = '已学历认证';
                $edustate = 1;
            }else{
                $edumsg = '未学历认证';
                $edustate = 0;
            }
            if($industry!= '' && $companyname!='' && $duty != '' && $incomeinfo != '' && $jobimg != ''){
                $jobmsg = '已工作认证';
                $jobstate = 1;
            }else{
                $jobmsg = '未工作认证';
                $jobstate = 0;
            }
            if($worth != '' && $worthimg != ''){
                $worthmsg = '已资产认证';
                $worthstate = 1;
            }else{
                $worthmsg = '未资产认证';
                $worthstate = 0;
            }
            return json([
                'status'=> 200,
                'useridentity'=> $useridentity,
                'realnamemsg'=> $realnamemsg,
                'realnamestate'=> $realnamestate,
                'edumsg'=> $edumsg,
                'edustate'=> $edustate,
                'jobmsg'=> $jobmsg,
                'jobstate'=> $jobstate,
                'worthmsg'=> $worthmsg,
                'worthstate'=> $worthstate
            ]);
        }else{
            return json([
                'status'=> 202,
                'msg'=> '当前用户未进行任何认证'
            ]);
        }
    }

    ###用户认证  type决定是哪种认证  仅认证，不更新   若更新联系客服   认真审核成功才增加公里数，在管理后台控制器
    public function doVerify(){
        $type = input('type');
        $uid = input('uid');
        if($type != '' && $uid != ''){
//            //先返回各种认证情况
            $useridentity = db('identity')->where('uid',$uid)->select();
            if($useridentity){
                $useridentity = $useridentity[0];
                $realname = $useridentity['realname'];
                $idcard = $useridentity['idcard'];
                $idcardfront = $useridentity['idcardfront'];
                $idcardback = $useridentity['idcardback'];
                $education = $useridentity['education'];
                $university = $useridentity['university'];
                $educationimg = $useridentity['educationimg'];
                $industry = $useridentity['industry'];
                $companyname = $useridentity['companyname'];
                $duty = $useridentity['duty'];
                $incomeinfo = $useridentity['incomeinfo'];
                $jobimg = $useridentity['jobimg'];
                $worth = $useridentity['worth'];
                $worthimg = $useridentity['worthimg'];
                if($realname!='' && $idcard!='' && $idcardfront!= '' && $idcardback!= ''){
                    $realnamemsg = '已实名认证';
                    $realnamestate = 1;
                }else{
                    $realnamemsg = '未实名认证';
                    $realnamestate = 0;
                }
                if($education!= '' && $university != '' && $educationimg != ''){
                    $edumsg = '已学历认证';
                    $edustate = 1;
                }else{
                    $edumsg = '未学历认证';
                    $edustate = 0;
                }
                if($industry!= '' && $companyname!='' && $duty != '' && $incomeinfo != '' && $jobimg != ''){
                    $jobmsg = '已工作认证';
                    $jobstate = 1;
                }else{
                    $jobmsg = '未工作认证';
                    $jobstate = 0;
                }
                if($worth != '' && $worthimg != ''){
                    $worthmsg = '已资产认证';
                    $worthstate = 1;
                }else{
                    $worthmsg = '未资产认证';
                    $worthstate = 0;
                }
            }else{
                $realnamestate = 0;
                $edustate = 0;
                $jobstate = 0;
                $worthstate = 0;
            }
            switch ($type){
                case 'realname':
                        if ($realnamestate == 1) {
                            return json([
                                'status' => 205,
                                'msg' => '已实名认证',
                                'data' => [
                                    'uid' => $uid,
                                    'realname'=> $realname,
                                    'idcard' => $idcard,
                                    'idcardfront' => $idcardfront,
                                    'idcardback' => $idcardback
                                ]
                            ]);
                        } else {
                            $realname = input('realname');
                            $idcard = input('idcard');
                            $idcardfront = input('idcardfront');
                            $idcardback = input('idcardback');
                            if($realname == '' || $idcard == ''){
                                return json([
                                    'status'=> 201,
                                    'msg'=> 'realname|idcard参数错误'
                                ]);
                            }else{
//                                $tool = new Tools();
//                                $idcardfront = $_FILES['idcardfront'];
//                                $data = $tool->imageuploader($idcardfront);  // ['status'=>'1','fileurl' => $baseurl,'width'=>$width,'height'=>$height];
//                                $idcardback = $_FILES['idcardback'];
//                                $data1 = $tool->imageuploader($idcardback);  // ['status'=>'1','fileurl' => $baseurl,'width'=>$width,'height'=>$height];
//                                if($data['status'] == 1 && $data1['status'] == 1){
                                  if($idcardfront && $idcardback){
                                    if($useridentity){  //已认证，仅更新
                                        $res = db('identity')->where('uid', $uid)->update([
                                            'realname'=> $realname,
                                            'idcard'=> $idcard,
//                                            'idcardfront'=> $data['fileurl'],
//                                            'idcardback'=> $data1['fileurl']
                                            'idcardfront'=> $idcardfront,
                                            'idcardback'=> $idcardback,
                                            'updatetime'=> date('Y-m-d H:i:s'),
                                            'realnamestatus'=> 1
                                        ]);
                                    }else{
                                        $res = db('identity')->insert([
                                            'uid'=> $uid,
                                            'realname'=> $realname,
                                            'idcard'=> $idcard,
//                                            'idcardfront'=> $data['fileurl'],
//                                            'idcardback'=> $data1['fileurl']
                                            'idcardfront'=> $idcardfront,
                                            'idcardback'=> $idcardback,
                                            'createtime'=> date('Y-m-d H:i:s'),
                                            'realnamestatus'=> 1
                                        ]);
                                    }
                                    if ($res == 1) {  //添加记录数+更新数
                                        return json([
                                            'status' => 200,
                                            'msg' => '实名认证成功'
                                        ]);
                                    }
                                }else{
                                    return json([
                                        'status'=> 203,
//                                        'msg'=> $data['msg']
                                        'msg'=> '身份证照片为空'
                                    ]);
                                }
                             }
                        }
                    break;
                case 'education':
                    if ($edustate == 1) {
                        return json([
                            'status' => 205,
                            'msg' => '已学历认证',
                            'data' => [
                                'uid' => $uid,
                                'education'=> $education,
                                'university'=> $university,
                                'educationimg'=> $educationimg
                            ]
                        ]);
                    } else {
                        $education = input('education');
                        $university = input('university');
                        $educationimg = input('educationimg');
                        if($education == '' || $university == ''){
                            return json([
                                'status'=> 201,
                                'msg'=> 'education|university参数错误'
                            ]);
                        }else{
//                                $tool = new Tools();
//                                $educationimg = $_FILES['educationimg'];
//                                $data = $tool->imageuploader($educationimg);  // ['status'=>'1','fileurl' => $baseurl,'width'=>$width,'height'=>$height];
//                                if($data['status'] == 1){
                                if($educationimg){
                                    if($useridentity) {
                                        $res = db('identity')->where('uid',$uid)->update([
                                            'education'=> $education,
                                            'university'=> $university,
//                                            'educationimg'=> $data['fileurl']
                                            'educationimg'=> $educationimg,
                                            'updatetime'=> date('Y-m-d H:i:s'),
                                            'edustatus'=> 1
                                        ]);
                                    }else {
                                        $res = db('identity')->insert([
                                            'uid' => $uid,
                                            'education'=> $education,
                                            'university'=> $university,
//                                            'educationimg'=> $data['fileurl']
                                            'educationimg'=> $educationimg,
                                            'createtime'=> date('Y-m-d H:i:s'),
                                            'edustatus'=> 1

                                        ]);
                                    }
                                    if ($res == 1) {  //添加记录数+更新数
                                        return json([
                                            'status' => 200,
                                            'msg' => '学历认证成功'
                                        ]);
                                    }
                                }else{
                                    return json([
                                        'status'=> 203,
//                                        'msg'=> $data['msg']
                                        'msg'=> '学历证明照片为空'
                                    ]);
                                 }
                        }
                    }
                    break;
                case 'job':
                    if ($jobstate == 1) {
                        return json([
                            'status' => 205,
                            'msg' => '已工作认证',
                            'data' => [
                                'uid' => $uid,
                                'industry'=> $industry,
                                'companyname'=> $companyname,
                                'duty'=> $duty,
                                'incomeinfo'=> $incomeinfo,
                                'jobimg'=> $jobimg
                            ]
                        ]);
                    } else {
                        $industry = input('industry');
                        $companyname = input('companyname');
                        $duty = input('duty');
                        $incomeinfo = input('incomeinfo');
                        $jobimg = input('jobimg');
                        if($industry == '' || $companyname == '' || $duty == '' || $incomeinfo == ''){
                            return json([
                                'status'=> 201,
                                'msg'=> 'industry|companyname|duty|incomeinfo参数错误'
                            ]);
                        }else{
//                            $tool = new Tools();
//                            $jobimg = $_FILES['jobimg'];
//                            $data = $tool->imageuploader($jobimg);  // ['status'=>'1','fileurl' => $baseurl,'width'=>$width,'height'=>$height];
//                            if($data['status'] == 1){
                            if($jobimg){
                                if($useridentity){
                                    $res = db('identity')->where('uid',$uid)->update([
                                        'uid'=> $uid,
                                        'industry'=> $industry,
                                        'companyname'=> $companyname,
                                        'duty'=> $duty,
                                        'incomeinfo'=> $incomeinfo,
//                                        'jobimg'=> $data['fileurl']
                                        'jobimg'=> $jobimg,
                                        'updatetime'=> date('Y-m-d H:i:s'),
                                        'jobstatus'=> 1
                                    ]);
                                }else{
                                    $res = db('identity')->insert([
                                        'uid'=> $uid,
                                        'industry'=> $industry,
                                        'companyname'=> $companyname,
                                        'duty'=> $duty,
                                        'incomeinfo'=> $incomeinfo,
//                                        'jobimg'=> $data['fileurl']
                                        'jobimg'=> $jobimg,
                                        'createtime'=> date('Y-m-d H:i:s'),
                                        'jobstatus'=> 1
                                    ]);
                                }
                                if ($res == 1) {  //添加记录数+更新数
                                    return json([
                                        'status' => 200,
                                        'msg' => '工作认证成功'
                                    ]);
                                }
                            }else{
                                return json([
                                    'status'=> 203,
//                                    'msg'=> $data['msg']
                                    'msg'=> '收入证明照片为空'
                                ]);
                            }
                        }
                    }
                    break;
                case 'worth':
                    if ($worthstate == 1) {
                        return json([
                            'status' => 205,
                            'msg' => '已资产认证',
                            'data' => [
                                'uid' => $uid,
                                'worth'=> $worth,
                                'worthimg'=> $worthimg
                            ]
                        ]);
                    } else {
                        $worth = input('worth');
                        $worthimg = input('worthimg');
                        if($worth == ''){
                            return json([
                                'status'=> 201,
                                'msg'=> 'worth参数错误'
                            ]);
                        }else{
                            $tool = new Tools();
//                            $worthimg = $_FILES['worthimg'];
//                            $data = $tool->imageuploader($worthimg);  // ['status'=>'1','fileurl' => $baseurl,'width'=>$width,'height'=>$height];
//                            if($data['status'] == 1){
                            if($worthimg){
                                if($useridentity){
                                    $res = db('identity')->where('uid',$uid)->update([
                                        'worth'=> $worth,
//                                        'worthimg'=> $data['fileurl']
                                        'worthimg'=> $worthimg,
                                        'updatetime'=> date('Y-m-d H:i:s'),
                                        'worthstatus'=> 1
                                    ]);
                                }else{
                                    $res = db('identity')->insert([
                                        'uid'=> $uid,
                                        'worth'=> $worth,
//                                        'worthimg'=> $data['fileurl']
                                        'worthimg'=> $worthimg,
                                        'createtime'=> date('Y-m-d H:i:s'),
                                        'worthstatus'=> 1
                                    ]);
                                }
                                if ($res == 1) {  //添加记录数+更新数
                                    return json([
                                        'status' => 200,
                                        'msg' => '资产认证成功'
                                    ]);
                                }
                            }else{
                                return json([
                                    'status'=> 203,
//                                    'msg'=> $data['msg']
                                    'msg'=> '资产证明照片为空'
                                ]);
                            }
                        }
                    }
                    break;
            }
        }else{
            return json([
                'status'=> 201,
                'msg'=> 'type|uid参数错误'
            ]);
        }
    }

    ###照片加水印
    public function addWatermark(){
        $uid = input('uid');
        $back = input('back');
        $type = input('type');
        $tool = new Tools();
        $img = $_FILES['img'];
        if($type == 'realname'){
            $data = $tool->imageuploader($img,ROOT_PATH."public/uploads/idcard/");  // ['status'=>'1','fileurl' => $baseurl,'width'=>$width,'height'=>$height];
            if($data['status'] == 1) {
                $bigImgPath = 'http://app.tiaociapp.com/' . $data['fileurl'];
//            return $bigImgPath;
                $img = imagecreatefromstring(file_get_contents($bigImgPath));

                $font = __DIR__ . '/jd.ttf';//字体,字体文件需保存到相应文件夹下
                $black = imagecolorallocate($img, 180, 180, 180);//字体颜色 RGB
                $w = imagesx($img);
                $h = imagesy($img);
                $fontSize = 30;   //字体大小
                $circleSize = 0; //旋转角度
                $left = 40;      //左边距
                $top = $h / 2.3;       //顶边距
                imagefttext($img, $fontSize, $circleSize, $left, $top, $black, $font, '仅用于认证');
                list($bgWidth, $bgHight, $bgType) = getimagesize($bigImgPath);
                switch ($bgType) {
                    case 1: //gif
                        header('Content-Type:image/gif');
                        if ($back) {
                            imagepng($img, __DIR__ . '/../../../public/uploads/idcard/' . $uid . 'b.gif');
                            return json([
                                'data' => "http://app.tiaociapp.com/public/uploads/idcard/" . $uid . "b.gif"
                            ]);
                        } else {
                            imagepng($img, __DIR__ . '/../../../public/uploads/idcard/' . $uid . '.gif');
                            return json([
                                'data' => "http://app.tiaociapp.com/public/uploads/idcard/" . $uid . ".gif"
                            ]);
                        }
                        break;
                    case 2: //jpg
                        header('Content-Type:image/jpg');
                        if ($back) {
                            imagepng($img, __DIR__ . '/../../../public/uploads/idcard/' . $uid . 'b.jpg');
                            return json([
                                'data' => "http://app.tiaociapp.com/public/uploads/idcard/" . $uid . "b.jpg"
                            ]);
                        } else {
                            imagepng($img, __DIR__ . '/../../../public/uploads/idcard/' . $uid . '.jpg');
                            return json([
                                'data' => "http://app.tiaociapp.com/public/uploads/idcard/" . $uid . ".jpg"
                            ]);
                        }
                        break;
                    case 3: //png
                        header('Content-Type:image/png');
                        if ($back) {
                            imagepng($img, __DIR__ . '/../../../public/uploads/idcard/' . $uid . 'b.png');  //在 images 目录下就会生成一个 circle.png
                            return json([
                                'data' => "http://app.tiaociapp.com/public/uploads/idcard/" . $uid . "b.png"
                            ]);
                        } else {
                            imagepng($img, __DIR__ . '/../../../public/uploads/idcard/' . $uid . '.png');  //在 images 目录下就会生成一个 circle.png
                            return json([
                                'data' => "http://app.tiaociapp.com/public/uploads/idcard/" . $uid . ".png"
                            ]);
                        }
//                     文件,上面也可设置相应的保存目录及文件名。
                        break;
                    default:
                        break;
                }
            }
        }elseif ($type == 'edu') {
            $data = $tool->imageuploader($img, ROOT_PATH . "public/uploads/edu/");  // ['status'=>'1','fileurl' => $baseurl,'width'=>$width,'height'=>$height];
            if ($data['status'] == 1) {
                $bigImgPath = 'http://app.tiaociapp.com/' . $data['fileurl'];
//            return $bigImgPath;
                $img = imagecreatefromstring(file_get_contents($bigImgPath));

                $font = __DIR__ . '/jd.ttf';//字体,字体文件需保存到相应文件夹下
                $black = imagecolorallocate($img, 180, 180, 180);//字体颜色 RGB
                $w = imagesx($img);
                $h = imagesy($img);
                $fontSize = 30;   //字体大小
                $circleSize = 0; //旋转角度
                $left = 40;      //左边距
                $top = $h / 2.3;       //顶边距
                imagefttext($img, $fontSize, $circleSize, $left, $top, $black, $font, '仅用于认证');
                list($bgWidth, $bgHight, $bgType) = getimagesize($bigImgPath);
                switch ($bgType) {
                    case 1: //gif
                        header('Content-Type:image/gif');
                        imagepng($img, __DIR__ . '/../../../public/uploads/edu/' . $uid . '.gif');
                        return json([
                            'data' => "http://app.tiaociapp.com/public/uploads/edu/" . $uid . ".gif"
                        ]);

                        break;
                    case 2: //jpg
                        header('Content-Type:image/jpg');

                        imagepng($img, __DIR__ . '/../../../public/uploads/edu/' . $uid . '.jpg');
                        return json([
                            'data' => "http://app.tiaociapp.com/public/uploads/edu/" . $uid . ".jpg"
                        ]);

                        break;
                    case 3: //png
                        header('Content-Type:image/png');


                        imagepng($img, __DIR__ . '/../../../public/uploads/edu/' . $uid . '.png');  //在 images 目录下就会生成一个 circle.png
                        return json([
                            'data' => "http://app.tiaociapp.com/public/uploads/edu/" . $uid . ".png"
                        ]);

//                     文件,上面也可设置相应的保存目录及文件名。
                        break;
                    default:
                        break;
                }
            }
        }elseif ($type == 'job'){
                $data = $tool->imageuploader($img,ROOT_PATH."public/uploads/job/");  // ['status'=>'1','fileurl' => $baseurl,'width'=>$width,'height'=>$height];
                if($data['status'] == 1) {
                    $bigImgPath = 'http://app.tiaociapp.com/'.$data['fileurl'];
//            return $bigImgPath;
                    $img = imagecreatefromstring(file_get_contents($bigImgPath));

                    $font = __DIR__.'/jd.ttf';//字体,字体文件需保存到相应文件夹下
                    $black = imagecolorallocate($img, 180, 180, 180);//字体颜色 RGB
                    $w = imagesx($img);
                    $h = imagesy($img);
                    $fontSize = 30;   //字体大小
                    $circleSize = 0; //旋转角度
                    $left = 40;      //左边距
                    $top = $h / 2.3;       //顶边距
                    imagefttext($img, $fontSize, $circleSize, $left, $top, $black, $font, '仅用于认证');
                    list($bgWidth, $bgHight, $bgType) = getimagesize($bigImgPath);
                    switch ($bgType) {
                        case 1: //gif
                            header('Content-Type:image/gif');

                                imagepng($img, __DIR__.'/../../../public/uploads/job/'.$uid.'.gif');
                                return json([
                                    'data'=> "http://app.tiaociapp.com/public/uploads/job/".$uid.".gif"
                                ]);

                            break;
                        case 2: //jpg
                            header('Content-Type:image/jpg');

                                imagepng($img, __DIR__.'/../../../public/uploads/job/'.$uid.'.jpg');
                                return json([
                                    'data'=> "http://app.tiaociapp.com/public/uploads/job/".$uid.".jpg"
                                ]);

                            break;
                        case 3: //png
                            header('Content-Type:image/png');

                                imagepng($img, __DIR__.'/../../../public/uploads/job/'.$uid.'.png');  //在 images 目录下就会生成一个 circle.png
                                return json([
                                    'data'=> "http://app.tiaociapp.com/public/uploads/job/".$uid.".png"
                                ]);

//                     文件,上面也可设置相应的保存目录及文件名。
                            break;
                        default:
                            break;
                    }
                }
            }else{
                $data = $tool->imageuploader($img,ROOT_PATH."public/uploads/worth/");  // ['status'=>'1','fileurl' => $baseurl,'width'=>$width,'height'=>$height];
                if($data['status'] == 1) {
                    $bigImgPath = 'http://app.tiaociapp.com/'.$data['fileurl'];
//            return $bigImgPath;
                    $img = imagecreatefromstring(file_get_contents($bigImgPath));

                    $font = __DIR__.'/jd.ttf';//字体,字体文件需保存到相应文件夹下
                    $black = imagecolorallocate($img, 180, 180, 180);//字体颜色 RGB
                    $w = imagesx($img);
                    $h = imagesy($img);
                    $fontSize = 30;   //字体大小
                    $circleSize = 0; //旋转角度
                    $left = 40;      //左边距
                    $top = $h / 2.3;       //顶边距
                    imagefttext($img, $fontSize, $circleSize, $left, $top, $black, $font, '仅用于认证');
                    list($bgWidth, $bgHight, $bgType) = getimagesize($bigImgPath);
                    switch ($bgType) {
                        case 1: //gif
                            header('Content-Type:image/gif');

                                imagepng($img, __DIR__.'/../../../public/uploads/worth/'.$uid.'.gif');
                                return json([
                                    'data'=> "http://app.tiaociapp.com/public/uploads/worth/".$uid.".gif"
                                ]);

                            break;
                        case 2: //jpg
                            header('Content-Type:image/jpg');

                                imagepng($img, __DIR__.'/../../../public/uploads/worth/'.$uid.'.jpg');
                                return json([
                                    'data'=> "http://app.tiaociapp.com/public/uploads/worth/".$uid.".jpg"
                                ]);

                            break;
                        case 3: //png
                            header('Content-Type:image/png');

                                imagepng($img, __DIR__.'/../../../public/uploads/worth/'.$uid.'.png');  //在 images 目录下就会生成一个 circle.png
                                return json([
                                    'data'=> "http://app.tiaociapp.com/public/uploads/worth/".$uid.".png"
                                ]);

//                     文件,上面也可设置相应的保存目录及文件名。
                            break;
                        default:
                            break;
                    }
                }
            }
        }


    ###搜索
    public function doSearch(){
        $uid = input('uid');
        $word = input('word');
        $noreaduid = input('noreaduid');
        if(!$noreaduid){
            $noreaduid = '';
        }
        if(is_numeric($word)){
            if(strlen(strval($word)) == 2){  //年龄
                $nowy = date('Y');
                $res = db('user')->where('uname','not in',$noreaduid)->where('birthday', 'like', ($nowy - $word) . '%')->limit(10)->select();
                if($res){
                    $user = $res[0];
                    $nowy = date('Y');
                    return json([
                        'status'=> 200,
                        'data'=>[[
                            'uid' => $user['uname'],
                            'headimgurl' => $user['headimgurl'],
                            'nickname' => $user['nickname'],
                            'sex' => $user['sex'],
                            'height' => $user['height'],
                            'location' => $user['location'],
                            'hometown' => $user['hometown'],
                            'age' => $nowy - substr($user['birthday'], 0, 4),
                            'university' => $user['university'],
                            'job' => $user['job'],
                            'signature' => $user['signature']
                        ]]
                    ]);
                }else{
                    return json([
                        'status'=> 202,
                        'data'=> []
                    ]);
                }
            }elseif(strlen(strval($word)) == 5){  //id
                $res = db('user')->where('uname','not in',$noreaduid)->where('uname',  $word)->limit(10)->select();
                if($res){
                    $user = $res[0];
                    $nowy = date('Y');
                    return json([
                        'status'=> 200,
                        'data'=>[[
                            'uid' => $user['uname'],
                            'headimgurl' => $user['headimgurl'],
                            'nickname' => $user['nickname'],
                            'sex' => $user['sex'],
                            'height' => $user['height'],
                            'location' => $user['location'],
                            'hometown' => $user['hometown'],
                            'age' => $nowy - substr($user['birthday'], 0, 4),
                            'university' => $user['university'],
                            'job' => $user['job'],
                            'signature' => $user['signature']
                        ]]
                    ]);
                }else{
                    return json([
                        'status'=> 202,
                        'data'=> []
                    ]);
                }
            }else{
                $res = db('user')->where('uname','not in',$noreaduid)->where('nickname','like','%'.$word.'%')->limit(10)->select();
                if($res){
                    $user = $res[0];
                    $nowy = date('Y');
                    return json([
                        'status'=> 200,
                        'data'=>[[
                            'uid' => $user['uname'],
                            'headimgurl' => $user['headimgurl'],
                            'nickname' => $user['nickname'],
                            'sex' => $user['sex'],
                            'height' => $user['height'],
                            'location' => $user['location'],
                            'hometown' => $user['hometown'],
                            'age' => $nowy - substr($user['birthday'], 0, 4),
                            'university' => $user['university'],
                            'job' => $user['job'],
                            'signature' => $user['signature']
                        ]]
                    ]);
                }else{
                    return json([
                        'status'=> 202,
                        'data'=> []
                    ]);
                }
            }
        }else {
//        if($uid == '' || $word == ''){
//            return json([
//                'status'=> 201,
//                'msg'=> 'uid|搜索词为空'
//            ]);
//        }
            $wordarr = [];
//            for ($i = 0; $i < strlen($word); $i++) {
//                array_push($wordarr, '%' . mb_substr($word, $i, 1));  //分割字符串为单个字
//                array_push($wordarr, mb_substr($word, $i, 1) . '%');  //分割字符串为单个字
//            }
            for ($i = 0; $i < strlen($word); $i++) {
                array_push($wordarr, '%' . mb_substr($word, $i, 2));  //分割字符串为单个字
                array_push($wordarr, mb_substr($word, $i, 2) . '%');  //分割字符串为单个字
            }
            for ($i = 0; $i < strlen($word); $i++) {
                array_push($wordarr, '%' . mb_substr($word, $i, 3));  //分割字符串为单个字
                array_push($wordarr, mb_substr($word, $i, 3) . '%');  //分割字符串为单个字
            }
            array_push($wordarr, $word);
            for ($x = 0; $x < count($wordarr); $x++) {   //删除 % 元素
                $id = array_search('%', $wordarr);
                array_splice($wordarr, $id, 1);
            }
            for ($y = 0; $y < count($wordarr); $y++) {   //删除 % 元素
                for ($x = 0; $x < count($wordarr); $x++) {   //删除 % 元素
                    if ($wordarr[$x] == '%') {
                        array_splice($wordarr, $x, 1);
                    }
                }
            }
//            for($j=0;$j<count($wordarr);$j++){
//                if(strlen($wordarr[$j]) == 4){
//                    array_splice($wordarr, $j, 1);
//                }
//            }
//            for($j=0;$j<count($wordarr);$j++){
//                if(strlen($wordarr[$j]) == 4){
//                    array_splice($wordarr, $j, 1);
//                }
//            }
//            return json($index);
//            $wordarr = array_diff_key($wordarr,$index);

//        return json($wordarr);  //分词结果
            $alluid = [];
            for ($j = 0; $j < count($wordarr); $j++) {
                $res = db('user')->where('uname','not in',$noreaduid)->where('job|hometown|university|nickname', 'like', $wordarr[$j])->limit(10)->column('uname');  //行业  家乡  学校   昵称
                array_push($alluid, $res);
            }
            $res1 = db('extrainfo')->where('hobbies', 'like', '%' . $word . '%')->column('uid');  //爱好
//        return json($res1);
            $nowy = date('Y');
            $res2 = db('user')->where('uname','not in',$noreaduid)->where('uname', '<>', '10000')->where('birthday', 'like', '%' . ($nowy - $word) . '%')->limit(10)->column('uname');
//        return json($res2);
            array_push($alluid, $res1, $res2);
//        return json($alluid);
            $result = array_reduce($alluid, function ($result, $value) {  //合并多个数组
                return array_merge($result, array_values($value));
            }, array());
//        return json($result);
            $result = array_unique($result);  //元素去重  获得搜索结果用户uid
            $resu = [];
            foreach($result as $key=>$v){
                array_push($resu, strval($v));
            }
//            return json($resu);
            $result = $resu;
//            return json($result);
            if($result){
                $users = [];
                for ($ii = 0; $ii < count($result); $ii++) {
                    $user = db('user')->where('uname','not in',$noreaduid)->where('uname', $result[$ii])->limit(10)->select();
                    if ($user) {
                        $user = $user[0];
                        array_push($users, [
                            'uid' => $user['uname'],
                            'headimgurl' => $user['headimgurl'],
                            'nickname' => $user['nickname'],
                            'sex' => $user['sex'],
                            'height' => $user['height'],
                            'location' => $user['location'],
                            'hometown' => $user['hometown'],
                            'age' => $nowy - substr($user['birthday'], 0, 4),
                            'university' => $user['university'],
                            'job' => $user['job'],
                            'signature' => $user['signature']
                        ]);
                    }
                }
                return json([
                    'status' => 200,
                    'data' => $users
                ]);
            }else{
                return json([
                    'status'=> 202,
                    'data'=> []
                ]);
            }
        }
    }
    ###搜索后筛选
    public function filter(){
        $noreaduid = input('noreaduid');
        if(!$noreaduid){
            $noreaduid = '';
        }
        $sex = input('sex');
        $hometown = input('hometown');
        $agel = input('agel');
        $ageu = input('ageu');
        $heightl = input('heightl');
        $heightu = input('heightu');
        $school = input('school');
        $profession = input('profession');
        $nowy = date('Y');
        $nowl = intval($nowy-$ageu)."-00-00";
        $nowu = intval($nowy-$agel)."-12-31";
        if($hometown == '不限' && $school == '' && $profession == '不限'){
            $users = db('user')->where('uname','not in',$noreaduid)->where('sex',$sex)->whereTime('birthday','>=',$nowl)
                ->whereTime('birthday','<=',$nowu)->where('height','>=',$heightl)->where('height','<=',$heightu)->limit(10)->select();
//            return json($users);
        }elseif($school == '' && $profession == '不限'){
            $users = db('user')->where('uname','not in',$noreaduid)->where('sex',$sex)->where('hometown',$hometown)->whereTime('birthday','>=',$nowl)
                ->whereTime('birthday','<=',$nowu)->where('height','>=',$heightl)->where('height','<=',$heightu)->limit(10)->select();
        }elseif($school == '' && $hometown == '不限'){
            $users = db('user')->where('uname','not in',$noreaduid)->where('sex',$sex)->whereTime('birthday','>=',$nowl)
                ->whereTime('birthday','<=',$nowu)->where('height','>=',$heightl)->where('height','<=',$heightu)->where('job',$profession)->limit(10)->select();
        }elseif($profession == '不限' && $hometown == '不限') {
            $users = db('user')->where('uname','not in',$noreaduid)->where('sex',$sex)->whereTime('birthday','>=',$nowl)
                ->whereTime('birthday','<=',$nowu)->where('height','>',$heightl)->where('height','<',$heightu)
                ->where('university',$school)->limit(10)->select();
        }elseif($profession == '不限'){
            $users = db('user')->where('uname','not in',$noreaduid)->where('sex',$sex)->where('hometown',$hometown)->whereTime('birthday','>=',$nowl)
                ->whereTime('birthday','<=',$nowu)->where('height','>=',$heightl)->where('height','<=',$heightu)
                ->where('university',$school)->limit(10)->select();
        }elseif ($school == ''){
            $users = db('user')->where('uname','not in',$noreaduid)->where('sex',$sex)->where('hometown',$hometown)->whereTime('birthday','>=',$nowl)
                ->whereTime('birthday','<=',$nowu)->where('height','>=',$heightl)->where('height','<=',$heightu)
                ->where('job',$profession)->limit(10)->select();
        }else{   //hometown不限
            $users = db('user')->where('uname','not in',$noreaduid)->where('sex',$sex)->whereTime('birthday','>=',$nowl)
                ->whereTime('birthday','<=',$nowu)->where('height','>=',$heightl)->where('height','<=',$heightu)
                ->where('university',$school)->where('job',$profession)->limit(10)->select();
        }
        if($users){
            $all = [];
            foreach ($users as $user){
                $nowy = date('Y');
                    array_push($all,[
                        'uid'=> $user['uname'],
                        'headimgurl'=> $user['headimgurl'],
                        'nickname'=> $user['nickname'],
                        'sex'=> $user['sex'],
                        'height'=> $user['height'],
                        'location'=> $user['location'],
                        'hometown'=> $user['hometown'],
                        'age'=> $nowy - substr($user['birthday'],0,4),
                        'university'=> $user['university'],
                        'job'=> $user['job'],
                        'signature'=> $user['signature']
                    ]);
            }
            return json(['status'=> 200, 'data'=> $all]);
        }else{
            return json(['status'=> 202, 'data'=> []]);
        }
    }

    ###用户主页
    public function userIndex(){
        $uid = input('uid');
        $meid  = input('meid');
        $noreadcid = input('noreadcid');
        if(!$noreadcid){
            $noreadcid = '';
        }
        if($uid){
            $user = db('user')->where('uname',$uid)->select();
            if($user){
                $allinfo = [];
                $user = $user[0];
                $uidattents = db('fan')->where('uid',$meid)->column('fanuid');
                $ouidattents = db('fan')->where('uid',$uid)->column('fanuid');
                if(in_array($meid,$ouidattents) && in_array($uid,$uidattents)){
                        $type= 1;
                        $msg= '互关';
                }elseif (in_array($meid,$ouidattents) && !in_array($uid,$uidattents)){
                        $type= 2;
                        $msg= 'meid被关注';
                }elseif (!in_array($meid,$ouidattents) && in_array($uid,$uidattents)){
                        $type= 3;
                        $msg= 'meid关注了uid';
                }else{
                        $type= 4;
                        $msg= '互不相关';
                }
                $user['type'] = $type;
                $user['msg'] = $msg;
                array_push($allinfo,['user'=>$user]);
                $extrainfo = db('extrainfo')->where('uid',$user['uname'])->select();
                if($extrainfo){
                    $extrainfo  =$extrainfo[0];
                    array_push($allinfo,['extrainfo'=>$extrainfo]);
                }else{
                    array_push($allinfo,['extrainfo'=>'']);
                }
                $contents = db('content')->where('id','not in', $noreadcid)->where('uid',$uid)->where('status',1)->order('createtime desc')->limit(5)->select();
                if($contents){
                    $retcontents = [];
                    foreach ($contents as $content) {
                        $imagesurl = [];
                        if ($content['imagesid']) {
                            $coverimgid = explode(',', $content['imagesid']);
                            for ($i = 0; $i < count($coverimgid); $i++) {
                                $coverimg = db('source')->where('id', $coverimgid[$i])->value('url');
                                $thumbnail = db('source')->where('id', $coverimgid[$i])->value('thumbnail');
                                if (strlen($thumbnail) != 0 && substr($thumbnail, -3) == 'mp4') {
                                    array_push($imagesurl, ['cover' => $coverimg, 'video' => $thumbnail]);
                                } else {
                                    array_push($imagesurl, ['cover' => $thumbnail, 'video' => '']);
                                }
                            }
                        }
                        $commentnum = db('comment')->where('contentid', $content['id'])->count();
                        $supportnum = db('support')->where('contentid', $content['id'])->where('type', 2)->count();
                        if ($uid == '') {
                            $issupport = 0;
                        } else {
                            $supportor = db('support')->where(['contentid' => $content['id'], 'uid' => $meid, 'type' => 2])->count();
                            if ($supportor == 1) {
                                $issupport = 1;
                            } else {
                                $issupport = 0;
                            }
                        }
                        array_push($retcontents, [
                            'contentid' => $content['id'],
                            'contentuid' => $content['uid'],
                            'title' => $content['title'],
                            'coverimgurl' => $imagesurl,
                            'commentnum' => $commentnum,
                            'supportnum' => $supportnum,
                            'issupport' => $issupport,
                            'publicornot' => $content['publicor']  // 1 私密  2 公开
                        ]);
                    }
                    array_push($allinfo, ['content'=> $retcontents]);
                }else{
                    array_push($allinfo, ['content'=> '']);
                }
                return json([
                    'status'=> 200,
                    'data'=> $allinfo
                ]);
            }
        }else{
            return json([
                'status'=> 201,
                'msg'=> 'uid参数为空'
            ]);
        }
    }

    ###设置推荐条件
    public function setRecommend(){
        $uid = input('uid');
        $age = input('age');
        $height = input('height');
        $location = input('location');
        $hometown = input('hometown');
        $edu = input('edu');
        $marry = input('marry');
        if($location == '只要同城'){
            $location = 2;
        }else{
            $location = 1;
        }
        $hometown == '同省'? $hometown = 2:$hometown = 1;
        if($edu == '学历不限'){
            $edu = 1;
        }elseif ($edu == '本科'){
            $edu = 2;
        }elseif($edu == '硕士'){
            $edu = 3;
        }elseif($edu == '博士'){
            $edu = 4;
        }else{
            $edu = 5;
        }
        $marry == '未婚'? $marry = 1:$marry=2;
        if($uid == ''){
            return json([
                'status'=> 201,
                'msg'=> 'uid参数为空'
            ]);
        }
        $res = db('recommendations')->where('uid',$uid)->select();
        if($res){
            $ret = db('recommendations')->where('uid',$uid)->update([
                'age'=> $age,
                'height'=> $height,
                'location'=> $location,
                'hometown'=> $hometown,
                'education'=> $edu,
                'marital'=> $marry,
                'createtime'=> date('Y-m-d H:i:s')
            ]);
        }else{
            $ret = db('recommendations')->insert([
                'uid'=> $uid,
                'age'=> $age,
                'height'=> $height,
                'location'=> $location,
                'hometown'=> $hometown,
                'education'=> $edu,
                'marital'=> $marry,
                'createtime'=> date('Y-m-d H:i:s')
            ]);
        }
        if($ret){
            return json([
                'status'=> 200
            ]);
        }else{
            return json([
                'status'=> 202
            ]);
        }

    }

    ###返回推荐条件
    public function retRecommend(){
        $uid = input('uid');
        $res = db('recommendations')->where('uid',$uid)->select();
        if($res){
            return json([
                'status'=> 200,
                'data'=> $res[0]
            ]);
        }else{
            return json([
                'status'=> 202
            ]);
        }
    }

    //隐私政策
    public function Privacy(){
        $id = input('id');
        $res = db('privacy')->where('id',$id)->find();
        if($res){
            return json([
                'status'=> 200,
                'data'=> $res
            ]);
        }else{
            return json([
                'status'=> 202
            ]);
        }
    }
    //黑名单列表
    public function blacklist(){
        $id = input('uid');
        $res=db('blacklist')
            ->alias('b')
            ->join('user u','u.uname=b.cid')
            ->where('b.uid',$id)
            ->where('b.type=1')
            ->field(['b.uid','u.nickname,uname,distance,sex,headimgurl'])
            ->select();
        if($res){
            return json([
                'status'=> 200,
                'data'=> $res
            ]);
        }else{
            return json([
                'status'=> 202
            ]);
        }
    }
    //解除黑名单
    public function Relieve(){
        $heuid = input('heuid');
        $status = input('status');
        $uid = input('uid');
        $data = ['uid'=>$uid,'cid'=>$heuid,'type'=>1];
        $find = db('blacklist')->where('uid',$uid)->where('cid',$heuid)->where('type=1')->find();
        if($status == 1){
            $operation = db('blacklist')->where('uid',$uid)->where('cid',$heuid)->where('type=1')->delete();
        }else{
            if($find){
            }else{
                $operation = db('blacklist')->where('type=1')->insert($data);
            }
        }
        if($operation){
            return json([
                'status'=> 200
            ]);
        }
    }

}