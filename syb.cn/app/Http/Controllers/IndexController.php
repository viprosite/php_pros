<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
\View::addExtension('html','php');
require 'libs/code/Code.class.php';
class IndexController extends Controller
{
    public function register() {
        if( $input = Input::all() ){
//            取到生成的验证码
            $code = new \Code;
            $_code =  $code->get();
            if(strtoupper($input['code']) != $_code){
                return back()->with('msg','验证码错误');
            }
                $student = DB::table('user')->where('student_id',$input['student_id'])->get();
                if (count($student) != 0){
                    return back()->with('msg','学号重复，请确认！');
                }else{
                    $student_id = $input['student_id'];
                    $password = $input['password'];
                    $email = $input['email'];
                    $bool = DB::table('user')->
                    insert(['student_id'=>$student_id,'student_name'=>$input['student_name'],
                        'password'=>$password,'email'=>$email,'register_at'=>now()]);
                    if(!$bool){
                        return back()->with('msg','请重新填写注册信息！');
                    }
                    session(['user'=>$student_id]);
                    return redirect('home')->with('top_msg','注册成功,欢迎新用户：'.$input['student_name']);
                }
        }else{
            return view('register');        //views 文件夹下的admin文件夹中的register.blage.php文件
        }
    }

    //    此login方法对应get和post两种请求，若input有值即为post提交由if分支处理，若input没有值则else分支处理返回登录视图
    public function login() {
        if( $input = Input::all() ){
//            取到生成的验证码
            $code = new \Code;
            $_code =  $code->get();
            if(strtoupper($input['code']) != $_code){
                return back()->with('msg','验证码错误');
            }
            $student_id = $input['student_id'];
            $password = $input['password'];
            $user = DB::table('user')->where('student_id',$student_id)->get();
            if (count($user)!=1){
                return back()->with('msg','账号未注册或异常，请确认！');
            }
            if($student_id != $user[0]->student_id || $password !=$user[0]->password){
                return back()->with('msg','学号或密码错误');
            }
            session(['user'=>$user[0]->student_id]);
            $reg_exam_state = DB::table('reg_exam')->where('student_id','=',session('user'))->value('reg_exam_state');
            session(['reg_exam_state'=>$reg_exam_state]);
            return redirect('home');
        }else{
            return view('index');        //views 文件夹下的admin文件夹中的index.php文件
        }
    }

    public function code() {
        $code = new \Code;
        $code->make();
    }

    public function home() {
        $new = DB::table('news')->orderBy('add_at','desc')->first();
        $info = DB::table('msg')->where('receiver','=',session('user'))->orderBy('send_time','desc')->get();
        $reg_exam_state = DB::table('reg_exam')->where('student_id','=',session('user'))->value('reg_exam_state');
        $exam_num = DB::table('reg_exam')->where('student_id','=',session('user'))->value('exam_num');
        $paper_id = DB::table('exam_paper')->orderBy('add_time','desc')->value('id'); //最近一次组卷的试卷id
//        dd($paper_id);
        $examed = DB::table('formal_exam')->where('student_id','=',session('user'))->where('paper_id','=',$paper_id)->count();
//        dd($examed);
            return view('home',['new'=>$new,'info'=>$info,'reg_exam_state'=>$reg_exam_state,'exam_num'=>$exam_num,'examed'=>$examed]);
    }

    public function news() {
        $news = DB::table('news')->get();
        return view('news',['news'=>$news]);
    }
    public function newdetail($id){
        $seenums = DB::table('news')->where('id',$id)->value('seenums');
        DB::table('news')->where('id',$id)->update(['seenums'=>$seenums + 1]);
        $new = DB::table('news')->where('id',$id)->get();
        return view('newdetail',['new'=>$new]);
    }
//    历史消息
    public function history_msg(){
        $id = session('user');
        $msgs = DB::table('msg')->where('receiver','=',$id)->where('state','=','1')->orderBy('send_time','desc')->get();
        if($msgs != null){
            return view('historymsg',['msgs'=>$msgs]);
        }
    }
    public function msgdetail($id){
        DB::table('msg')->where('id',$id)->update(['viewor'=>1]);
        $msg = DB::table('msg')->where('id',$id)->get();
        return view('msgdetail',['msg'=>$msg]);
    }
    public function delmsg($id){
        $bool = DB::table('msg')->where('id',$id)->update(['state'=>0]);
        if($bool == 1){
            return 'y';
        }else{
            return 'n';
        }
    }
//    我的收藏------------------------------------------------暂时不写
//    public function my_collection(){
//        $user = session('user');
//        $res = DB::table('collection')->where('student_id','=',$user)->orderBy('collection_time','desc')->get();
////        dd($res);
//        if($res != '[]'){
//            return view('my_collection',['res',$res]);
//        }
//    }

//    我的信息

    public function my_info() {
       if($input = Input::all()){
           $o_password = $input['o_password'];
           $user = DB::table('user')->where('student_id','=',$input['id'])->value('password');
           if($o_password == $user) {
               $bool = DB::table('user')->where('student_id','=',$input['id'])->update(['password'=>$input['n_password'],'email'=>$input['email']]);
               if($bool){
                   \Session::flash('top_msg','个人信息更新成功！');
                   return 'y';
               }else{
                   return 'n';
               }
           }else{
               return 'err';
           }
       }else{
           $res = DB::table('user')->where('student_id','=',session('user'))->get();
           return view('my_info');
       }
    }

//考点练习
    public function test_point(){
        $num = [];
        $res = DB::table('question_bank')->distinct()->pluck('test_point');
        for($i=0;$i<count($res);$i++){
            $num[$i] = DB::table('question_bank')->where('test_point','=',$res[$i])->count();
        }
        $history_exam = DB::select('select * from syb_test_point_exam where student_id = ? order by exam_time desc limit 5',[session('user')]);
//        dd($history_exam);
        if(count($history_exam) > 0){
            return view('test_point',['res'=>$res,'num'=>$num,'history_exam'=>$history_exam]);
        }else{
            return view('test_point',['res'=>$res,'num'=>$num]);
        }
    }
    public function test_point_detail($point){
        $res = DB::table('question_bank')->where('test_point','=',$point)->get();
        return view('test_point_detail',['res'=>$res]);
    }
    public function check_test_point(){
            $input = Input::all();
            if ($input['type'] == 44){
                foreach ($input as $key=>$value){         //关联数组转为索引数组
                    $grade_detail[] = $value;
                }
                $grade_detail = array_slice($grade_detail,3);    //去除token和考点，题型三个字段
                foreach ($grade_detail as $key=>$value){
                    $grade_detail[] = implode($value);            //将答案为数组类型转为字符串类型
                }
                $grade_detail =  array_slice($grade_detail,2);         //提交的答案最终形式
                $answer_ok = DB::table('question_bank')->where('test_point','=',$input['test_point'])->pluck('answer_ok');      //该考点依次的正确答案
                $grade = 0;    //保存提交答案对应分数（正确答案的个数）
                $ok = [];      //保存正确答案与提交答案对应关系的数组
                for ($i=0;$i<count($answer_ok);$i++){
                    $ok[] = 0;             //初始化答案对应数组，默认值为0
                    if ($grade_detail[$i] == $answer_ok[$i]){
                        $ok[$i] = 1;        //若该题正确，则对应索引值置为1
                        $grade += 1;
                    }
                }
                $bool = DB::table('test_point_exam')->insert(['student_id'=>session('user'),'test_point'=>$input['test_point'],'exam_time'=>now(),'grade'=>$grade,'grade_detail'=>json_encode($grade_detail)]);
                $res = DB::table('question_bank')->where('test_point','=',$input['test_point'])->get();      //得到该考点的考题
                return view('test_point_detail_answer',['ok'=>$ok,'answers'=>$grade_detail,'answer_ok'=>$answer_ok,'res'=>$res,'grade'=>$grade]);
                //ok表明该题正确与否   answers对应作答答案   answer_ok对应正确答案，   res是考点的题目    grade是答对个数
            }elseif($input['type'] == 4){
                foreach ($input as $key=>$value){         //关联数组转为索引数组
                    $grade_detail[] = $value;
                }
                $grade_detail = array_slice($grade_detail,3);    //去除token和考点,题型三个字段
                $answer_ok = DB::table('question_bank')->where('test_point','=',$input['test_point'])->pluck('answer_ok');      //该考点依次的正确答案
                $grade = 0;    //保存提交答案对应分数（正确答案的个数）
                $ok = [];      //保存正确答案与提交答案对应关系的数组
                for ($i=0;$i<count($answer_ok);$i++){
                    $ok[] = 0;             //初始化答案对应数组，默认值为0
                    if ($grade_detail[$i] == $answer_ok[$i]){
                        $ok[$i] = 1;        //若该题正确，则对应索引值置为1
                        $grade += 1;
                    }
                }
                $bool = DB::table('test_point_exam')->insert(['student_id'=>session('user'),'test_point'=>$input['test_point'],'exam_time'=>now(),'grade'=>$grade,'grade_detail'=>json_encode($grade_detail)]);
                $res = DB::table('question_bank')->where('test_point','=',$input['test_point'])->get();      //得到该考点的考题
                return view('test_point_detail_answer',['ok'=>$ok,'answers'=>$grade_detail,'answer_ok'=>$answer_ok,'res'=>$res,'grade'=>$grade]);
                //ok表明该题正确与否   answers对应作答答案   answer_ok对应正确答案，   res是考点的题目    grade是答对个数
            }else{
//                判断题型
                foreach ($input as $key=>$value){         //关联数组转为索引数组
                    $grade_detail[] = $value;
                }
//                dd($grade_detail);
                $grade_detail = array_slice($grade_detail,3);    //去除token和考点,题型三个字段
                $answer_ok = DB::table('question_bank')->where('test_point','=',$input['test_point'])->pluck('answer_ok');      //该考点依次的正确答案
                $grade = 0;    //保存提交答案对应分数（正确答案的个数）
                $ok = [];      //保存正确答案与提交答案对应关系的数组
                for ($i=0;$i<count($answer_ok);$i++){
                    $ok[] = 0;             //初始化答案对应数组，默认值为0
                    if ($grade_detail[$i] == $answer_ok[$i]){
                        $ok[$i] = 1;        //若该题正确，则对应索引值置为1
                        $grade += 1;
                    }
                }
                $bool = DB::table('test_point_exam')->insert(['student_id'=>session('user'),'test_point'=>$input['test_point'],'exam_time'=>now(),'grade'=>$grade,'grade_detail'=>json_encode($grade_detail)]);
                $res = DB::table('question_bank')->where('test_point','=',$input['test_point'])->get();      //得到该考点的考题
                return view('test_point_detail_answer',['ok'=>$ok,'answers'=>$grade_detail,'answer_ok'=>$answer_ok,'res'=>$res,'grade'=>$grade]);
                //ok表明该题正确与否   answers对应作答答案   answer_ok对应正确答案，   res是考点的题目    grade是答对个数
            }
    }

//题型练习 (忽略所有注释--------------------------------
    public function question_type(){
        $res = DB::table('question_bank')->distinct()->pluck('question_type');      //所有题型
        $num_2 = DB::table('question_bank')->where('question_type','=',2)->count();  //num_2表示判断题型的数量
        $num_4 = DB::table('question_bank')->where('question_type','=',4)->count();  //num_4表示单选题型的数量
        $num_44 = DB::table('question_bank')->where('question_type','=',44)->count();  //num_44表示多选题型的数量
        $history_exam = DB::select('select * from syb_question_type_exam where student_id = ? order by exam_time desc limit 5',[session('user')]);
        $data = [
            'res'=>$res,
            'num_2'=>$num_2,
            'num_4'=>$num_4,
            'num_44'=>$num_44,
            'history_exam'=> $history_exam
        ];
        return view('question_type',$data);
    }
    public function question_type_detail($type){
        $res = DB::table('question_bank')->where('question_type','=',$type)->get();
        return view('question_type_detail',['res'=>$res]);
    }
    public function check_question_type(){
        $input = Input::all();
//        dd($input);
        if ($input['question_type'] == 44){
            foreach ($input as $key=>$value){         //关联数组转为索引数组
                $grade_detail[] = $value;
            }
            $grade_detail = array_slice($grade_detail,2);    //去除token和考点，题型三个字段
            foreach ($grade_detail as $key=>$value){
                $grade_detail[] = implode($value);            //将答案为数组类型转为字符串类型
            }
            $grade_detail =  array_slice($grade_detail,2);         //提交的答案最终形式
            $answer_ok = DB::table('question_bank')->where('question_type','=',$input['question_type'])->pluck('answer_ok');      //该考点依次的正确答案
            $grade = 0;    //保存提交答案对应分数（正确答案的个数）
            $ok = [];      //保存正确答案与提交答案对应关系的数组
            for ($i=0;$i<count($answer_ok);$i++){
                $ok[] = 0;             //初始化答案对应数组，默认值为0
                if ($grade_detail[$i] == $answer_ok[$i]){
                    $ok[$i] = 1;        //若该题正确，则对应索引值置为1
                    $grade += 1;
                }
            }
            $bool = DB::table('question_type_exam')->insert(['student_id'=>session('user'),'question_type'=>$input['question_type'],'exam_time'=>now(),'grade'=>$grade,'grade_detail'=>json_encode($grade_detail)]);
            $res = DB::table('question_bank')->where('question_type','=',$input['question_type'])->get();      //得到该考点的考题
            return view('question_type_detail_answer',['ok'=>$ok,'answers'=>$grade_detail,'answer_ok'=>$answer_ok,'res'=>$res,'grade'=>$grade]);
            //ok表明该题正确与否   answers对应作答答案   answer_ok对应正确答案，   res是考点的题目    grade是答对个数
        }elseif($input['question_type'] == 4){
            foreach ($input as $key=>$value){         //关联数组转为索引数组
                $grade_detail[] = $value;
            }
            $grade_detail = array_slice($grade_detail,2);    //去除token和考点,题型三个字段
            $answer_ok = DB::table('question_bank')->where('question_type','=',$input['question_type'])->pluck('answer_ok');      //该考点依次的正确答案
            $grade = 0;    //保存提交答案对应分数（正确答案的个数）
            $ok = [];      //保存正确答案与提交答案对应关系的数组
            for ($i=0;$i<count($answer_ok);$i++){
                $ok[] = 0;             //初始化答案对应数组，默认值为0
                if ($grade_detail[$i] == $answer_ok[$i]){
                    $ok[$i] = 1;        //若该题正确，则对应索引值置为1
                    $grade += 1;
                }
            }
            $bool = DB::table('question_type_exam')->insert(['student_id'=>session('user'),'question_type'=>$input['question_type'],'exam_time'=>now(),'grade'=>$grade,'grade_detail'=>json_encode($grade_detail)]);
            $res = DB::table('question_bank')->where('question_type','=',$input['question_type'])->get();      //得到该考点的考题
            return view('question_type_detail_answer',['ok'=>$ok,'answers'=>$grade_detail,'answer_ok'=>$answer_ok,'res'=>$res,'grade'=>$grade]);
            //ok表明该题正确与否   answers对应作答答案   answer_ok对应正确答案，   res是考点的题目    grade是答对个数
        }else{
//                判断题型
            foreach ($input as $key=>$value){         //关联数组转为索引数组
                $grade_detail[] = $value;
            }
            $grade_detail = array_slice($grade_detail,2);    //去除token和题型2个字段
//            dd($grade_detail);
            $answer_ok = DB::table('question_bank')->where('question_type','=',$input['question_type'])->pluck('answer_ok');      //该考点依次的正确答案
            $grade = 0;    //保存提交答案对应分数（正确答案的个数）
            $ok = [];      //保存正确答案与提交答案对应关系的数组
            for ($i=0;$i<count($answer_ok);$i++){
                $ok[] = 0;             //初始化答案对应数组，默认值为0
                if ($grade_detail[$i] == $answer_ok[$i]){
                    $ok[$i] = 1;        //若该题正确，则对应索引值置为1
                    $grade += 1;
                }
            }
            DB::table('question_type_exam')->insert(['student_id'=>session('user'),'question_type'=>$input['question_type'],'exam_time'=>now(),'grade'=>$grade,'grade_detail'=>json_encode($grade_detail)]);
            $res = DB::table('question_bank')->where('question_type','=',$input['question_type'])->get();      //得到该考点的考题
            return view('question_type_detail_answer',['ok'=>$ok,'answers'=>$grade_detail,'answer_ok'=>$answer_ok,'res'=>$res,'grade'=>$grade]);
            //ok表明该题正确与否   answers对应作答答案   answer_ok对应正确答案，   res是考点的题目    grade是答对个数
        }
    }
//模拟测试
    public function mock_test(){
        $res = DB::table('exam_paper')->orderBy('add_time','desc')->get();
//        dd($res);
        for($i=0;$i<count($res);$i++){
            $res[$i]->question_2_id = json_decode($res[$i]->question_2_id);
            $res[$i]->question_4_id = json_decode($res[$i]->question_4_id);
        }
//        dd($question_2_id, $question_4_id);
//        dd($res);
        $history_exam = DB::select('select * from syb_mock_test_exam where student_id = ? order by submit_time desc limit 5',[session('user')]);
        return view('mock_test',['res'=>$res,'history_exam'=>$history_exam]);
    }
    public function mock_test_detail($id){
        $question_2_id = DB::select('select question_2_id from syb_exam_paper where id=?',[$id]);
        $question_2_id = json_decode($question_2_id[0]->question_2_id);
        $question_4_id = DB::select('select question_4_id from syb_exam_paper where id=?',[$id]);
        $question_4_id = json_decode($question_4_id[0]->question_4_id);
//        dd($question_4_id);
        for ($i=0; $i<count($question_2_id);$i++){
            $res_2[$i] = DB::table('question_bank')->where('id','=',$question_2_id[$i])->get();
        }
//        dd($res);
        for ($i=0; $i<count($question_4_id);$i++){
            $res_4[$i] = DB::table('question_bank')->where('id','=',$question_4_id[$i])->get();
        }
        for ($i=0;$i<count($res_2);$i++){
            $res_2[$i] = $res_2[$i][0];
        }
		 for ($i=0;$i<count($res_4);$i++){
            $res_4[$i] = $res_4[$i][0];
        }
        $finish_time = DB::table('exam_paper')->where('id','=',$id)->value('finish_time');
        $score_2 = DB::table('exam_paper')->where('id','=',$id)->value('score_2');
        $score_4 = DB::table('exam_paper')->where('id','=',$id)->value('score_4');
        $info = ['finish_time'=>$finish_time, 'score_2'=>$score_2, 'score_4'=>$score_4];
//        dd($info);
//        dd($res_2);
//        dd(count($res));
        return view('mock_test_detail',['res_2'=>$res_2, 'res_4'=>$res_4, 'info'=>$info]);
//        $res = DB::table('exam_paper')->where('id','=',$id)->get();
//        return view('mock_test_detail',['res'=>$res]);
    }
    public function check_mock_test(){
        $input = Input::all();
//        dd($input);
        if(count($input)==0){
          return  redirect('mock_test')->with('top_msg','方才提交答案为空，请确认！！！');
        }
        $grade_2 = json_decode($input['score_2']);   //分别保存判断题和单选题每个题的分值
        $grade_4 = json_decode($input['score_4']);
//        dd($grade_2);
        $input = array_slice($input,3);
//            dd($input);
        foreach ($input as $key=>$value){         //得到提交的题目组成的数组
            $keys[] = $key;
            $answer[] = $value;            //answer保存提交的答案组成的数组
        }
//        dd($keys);
//        dd($answer);
        for ($i=0;$i<count($keys);$i++){       //获取对应的正确答案
            $answer_ok[] = DB::table('question_bank')->where('title','like',$keys[$i])->value('answer_ok');
        }
//        dd($answer_ok);
//        foreach ($answer_ok as $key=>$val){
//            $answer_ok[] = $val[0];
//        }
//        $answer_ok = array_slice($answer_ok,count($answer_ok)/2);
//        dd($answer_ok);   //y c   nyndda
        $num_2 = 0;
        $num_4 = 0;
        for ($i=0;$i<count($answer_ok);$i++){
            $ok_2[$i] = 0;
            $ok_4[$i] = 0;
            if ($answer[$i] == 'y' || $answer[$i] == 'n') {
                $type = '2';         //表明了这是判断题答案
            } else {
                $type = '4';
            }
            if(($answer_ok[$i] == $answer[$i]) && ($type == '2')){
                $ok_2[$i] = 1;
                $num_2 = $num_2 +1 ;   //判断题正确个数
            }
            if(($answer_ok[$i] == $answer[$i]) && ($type == '4')){
                $ok_4[$i] = 1;
                $num_4 = $num_4 +1 ;   //
            }
        }
        for($i=0;$i<count($answer_ok);$i++){
            $ok[$i] = $ok_2[$i] | $ok_4[$i];
        }
        $total_score = $num_2*$grade_2 + $num_4*$grade_4;       //总分
        $bool = DB::table('mock_test_exam')->insert(['student_id'=>session('user'), 'submit_time'=>now(), 'grades'=>$total_score,'detail'=>json_encode($answer),'answer_ok'=>json_encode($answer_ok)]);
        if($bool){
            for ($i=0;$i<count($keys);$i++){       //获取对应的正确答案
                $res[] = DB::table('question_bank')->where('title','like',$keys[$i])->get();
            }
//           dd($res, $ok, $answer_ok, $answer);
            return view('mock_test_detail_answer',['ok'=>$ok,'answers'=>$answer,'answer_ok'=>$answer_ok,'res'=>$res,'grade'=>$total_score]);
        }
    }
//正式考试
    public function formal_exam(){
        $exam_paper_num = DB::table('exam_paper')->count(); #组卷系统生成的总套数
        if($exam_paper_num >0 ){
            $question_id =  DB::table('exam_paper')->orderBy('add_time','desc')->value('id');
//            $start_id = DB::table('exam_paper')->value('id');#得到已有卷子的起始id方便下面生成随机数
////        dd($start_id);
//            $arr = range($start_id,$start_id + $exam_paper_num -1); #生成 start-end 之间的数组，包含起终点
////        dd($arr);
//            shuffle($arr);  //打乱元素原先的顺序
////        dd($arr);
//            $question_id = array_slice($arr,0,1)[0]; #考生得到的卷子id  随机的  每个考生都不一样
////        dd($question_id);
            $question_2_id = DB::select('select question_2_id from syb_exam_paper where id=?',[$question_id]);
            $question_2_id = json_decode($question_2_id[0]->question_2_id);
            $question_4_id = DB::select('select question_4_id from syb_exam_paper where id=?',[$question_id]);
            $question_4_id = json_decode($question_4_id[0]->question_4_id);
//        dd($question_4_id);
            $res_2=[];
            $res_4=[];
            for ($i=0; $i<count($question_2_id);$i++){
                $res_2[$i] = DB::table('question_bank')->where('id','=',$question_2_id[$i])->get();
            }
//        dd($res_2[0][0]);
            for ($i=0; $i<count($question_4_id);$i++){
                $res_4[$i] = DB::table('question_bank')->where('id','=',$question_4_id[$i])->get();
            }
//            dd($res_4);
            for ($i=0;$i<count($res_2);$i++){
                $res_2[$i] = $res_2[$i][0];
            }
//            dd( count($res_4));
            for ($i=0;$i<count($res_4);$i++){
//                dd($res_4[$i][0]);
                $res_4[$i] = $res_4[$i][0];
            }

            $finish_time = DB::table('exam_paper')->where('id','=',$question_id)->value('finish_time');
            $score_2 = DB::table('exam_paper')->where('id','=',$question_id)->value('score_2');
            $score_4 = DB::table('exam_paper')->where('id','=',$question_id)->value('score_4');
            $info = ['finish_time'=>$finish_time, 'score_2'=>$score_2, 'score_4'=>$score_4,'paper_id'=>$question_id];
            return view('formal_exam_detail',['res_2'=>$res_2, 'res_4'=>$res_4, 'info'=>$info]);
        }else{
            return redirect('home')->with('top_msg','暂未组卷！');
        }


    }
    public function check_formal_exam(){
        $input = Input::all();
//        $input = array_slice($input,4);
//        dd($input);
        $grade_2 = json_decode($input['score_2']);   //分别保存判断题和单选题每个题的分值
        $grade_4 = json_decode($input['score_4']);
        $paper_id = $input['paper_id'];  //卷子id
//        dd($grade_2);
        $input = array_slice($input,4);
        if(count($input)==0){
            return  redirect('home')->with('top_msg','方才提交答案为空，请确认！！！');
        }
//            dd($input);
        foreach ($input as $key=>$value){         //得到提交的题目组成的数组
            $keys[] = $key;
            $answer[] = $value;            //answer保存提交的答案组成的数组
        }
//        dd($keys);
//        dd($answer);
        for ($i=0;$i<count($keys);$i++){       //获取对应的正确答案
            $answer_ok[] = DB::table('question_bank')->where('title','like',$keys[$i])->value('answer_ok');
        }
//        dd($answer_ok);
//        foreach ($answer_ok as $key=>$val){
//            $answer_ok[] = $val[0];
//        }
//        $answer_ok = array_slice($answer_ok,count($answer_ok)/2);
//        dd($answer_ok);   //y c   nyndda
        $num_2 = 0;
        $num_4 = 0;
        for ($i=0;$i<count($answer_ok);$i++){
            $ok_2[$i] = 0;
            $ok_4[$i] = 0;
            if ($answer[$i] == 'y' || $answer[$i] == 'n') {
                $type = '2';         //表明了这是判断题答案
            } else {
                $type = '4';
            }
            if(($answer_ok[$i] == $answer[$i]) && ($type == '2')){
                $ok_2[$i] = 1;
                $num_2 = $num_2 +1 ;   //判断题正确个数
            }
            if(($answer_ok[$i] == $answer[$i]) && ($type == '4')){
                $ok_4[$i] = 1;
                $num_4 = $num_4 +1 ;   //
            }
        }
        for($i=0;$i<count($answer_ok);$i++){
            $ok[$i] = $ok_2[$i] | $ok_4[$i];
        }
        $total_score = $num_2*$grade_2 + $num_4*$grade_4;       //总分
//        dd($grades_2, $grades_4, $ok);
        $bool = DB::table('formal_exam')->insert(['student_id'=>session('user'), 'submit_time'=>now(), 'grades'=>$total_score,'detail'=>json_encode($answer),'answer_ok'=>json_encode($answer_ok),'paper_id'=>$paper_id,'student_name'=>session('user')]);
        if($bool){
            for ($i=0;$i<count($keys);$i++){       //获取对应的正确答案
                $res[] = DB::table('question_bank')->where('title','like',$keys[$i])->get();
            }
//           dd($res);
//            正式考试完成后把该场考试状态禁用
            DB::table('reg_exam')->where('student_id','=',session('user'))->update(['reg_exam_state'=>0]);
            return view('formal_exam_detail_answer',['ok'=>$ok,'answers'=>$answer,'answer_ok'=>$answer_ok,'res'=>$res,'grade'=>$total_score]);
        }
    }
//    考试报名
    public function reg_exam($exam_num){
        $num = DB::table('reg_exam')->where('student_id','=',session('user'))->count();
        if($num == 1){
            DB::table('reg_exam')->where('student_id','=',session('user'))->update(['reg_exam_state'=>1]);
        }else {
            $bool = DB::table('reg_exam')->insert(['student_id' => session('user'), 'reg_exam_state' => 1, 'reg_exam_time' => now(), 'exam_num' => $exam_num]);
            if ($bool) {
//           session(['reg_exam_state'=>1]);
                return redirect('home');
            }
        }
    }

    public function logout() {
        session(['user'=>null]);
        return redirect('/');
    }
}
