<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

require 'libs/code/Code.class.php';
class IndexController extends Controller
{
//    管理员登陆
    public function login()
    {
        if ($input = Input::all()) {
//            取到生成的验证码
            $code = new \Code;
            $_code = $code->get();
            if (strtoupper($input['admin_code']) != $_code) {
                return back()->with('msg', '验证码错误！');
            }
            $admin_name = $input['admin_name'];
            $admin_password = $input['admin_password'];
            $user = DB::table('admin')->get();
//           var_dump($user[0]->student_id);
            if ($admin_name != $user[0]->admin_name || $admin_password != $user[0]->admin_password) {
                return back()->with('msg', '管理员用户名或密码错误！');
            }
            session(['admin' => $user[0]->admin_name]);
            session(['last'=>$user[0]->last_login_time]);
            DB::table('admin')->where('admin_name',$input['admin_name'])->update(['last_login_time'=>now()]);
//           echo session('admin_name');
//            dd($_SERVER);
            return redirect('admin/home');
        }
        return view('admin.login');
    }

    public function code()
    {
        $code = new \Code;
        $code->make();
    }
//父框架
    public function home()
    {
        return view('admin.home');
    }
//框架首页
    public function welcome()
    {
        return view('admin.welcome');
    }
//    更改密码
    public function changePassword()
    {
        if ($input = Input::all()) {
//            return back()->with('top_msg', '密码修改成功！');
            $password_o = $input['password_o'];
            $password = $input['password'];
            $password_c = $input['password_confirmation'];
            $rules = [
                'password' => 'required | between:6,12 | confirmed',               //校验字段对象=> 规则
            ];
            $message = [
                'password.required' => '新密码不能为空！',     //校验字段对象.规则=>错误提示信息
                'password.between' => '新密码必须在6到12位之间！',
                'password.confirmed' => '新密码和确认密码必须一致！',
            ];
            $validator = Validator::make($input, $rules, $message);        //make(验证的数据，验证规则，错误提示信息),返回值包含(->passes()【验证通过与否】、->errors()->all()【所有错误信息】、
            if ($validator->passes()) {            //如果所有规则通过
                $user = DB::table('admin')->get();
                if ($user[0]->admin_password != $password_o) {
                    //                    echo Crypt::decrypt($user[0]->user_psw);
                    return back()->with('top_msg', '原密码错误！');
                } else {
                    $num = DB::table('admin')->
                    where('admin_name', session('admin'))->
                    update(['admin_password' => $password_c]);
                    if ($num == 1) {
                        return back()->with('top_msg', '密码修改成功！');
                    }
                }
            } else {
//                               dd( $validator->errors()->all() );          //所有错误信息,类型为   array
                return back()->withErrors($validator);
            }
        } else {
            return view('admin.changePassword');
        }
    }
//    发布公告
    public function addnews() {
        if($input = Input::all()){
            if($input['title'] != '' && $input['content'] != ''){
                $title = $input['title'];
                $content = $input['content'];
                $news = DB::table('news')->
                    insert(['title'=>$title, 'content'=>$content, 'operator'=>session('admin'), 'add_at'=>now()]);
                if($news){
                    return redirect('admin/addnews') -> with('top_msg', '发布公告成功！');
                }
            }
        }else{
            return view('admin.addnews');
        }
    }
//查看公告
    public function seenews() {
        $news = DB::table('news')->
            select('id','title','operator','add_at')->
            get();
//        var_dump($news);                    // 数据库查询返回值类型是对象
        return view('admin.seenews',['news'=>$news]);
    }
//    某条公告的详细内容
    public function newdetail($id){
        $seenums = DB::table('news')->where('id',$id)->value('seenums');
        DB::table('news')->where('id',$id)->update(['seenums'=>$seenums + 1]);
        $new = DB::table('news')->where('id',$id)->get();
        return view('admin.newdetail',['new'=>$new]);
    }
//考生管理
    public function studentM() {
        $students = DB::table('user')->where('state','<>',0)->get();
        if(count($students) == 0){
            return view('admin.studentM',['info'=>'暂无学生注册本系统！']);
        }else{
            return view('admin.studentM',['students'=>$students]);
        }
    }
    public function searchStudent($key){
            $item = DB::table('user')->whereRaw('student_name = ?  or student_id = ?',[$key,$key])->where('state','=',1)->get();
            return $item;
    }
    public function delstudent($id){
        $bool = DB::table('user')->where('student_id',$id)->update(['state'=>0]);
        if($bool == 1){
            return 'y';
        }else{
            return 'n';
        }
    }
    public function sendmsg($id){
        $input = Input::all();
        $bool = DB::table('msg')->insert(['sender'=>session('admin'),'receiver'=>$id,'title'=>$input['title'],'content'=>$input['content'],'send_time'=>now()]);
        if($bool){
            return 'y';
        }else{
            return 'n';
        }
    }


//    题库管理
    public function questionBank(){
        $res = DB::table('exam_paper')->orderBy('add_time','desc')->get();
//        dd($res);
        for($i=0;$i<count($res);$i++){
            $res[$i]->question_2_id = json_decode($res[$i]->question_2_id);
            $res[$i]->question_4_id = json_decode($res[$i]->question_4_id);
        }
        $question_bank = DB::table('question_bank')->get();
        return view('admin.questionBank',['question_bank'=>$question_bank,'res'=>$res]);
    }
//    导入题库
    public function pullin(){
        if($input = Input::all()){
//            return $input['question_bank'];
//            dd($input);
            $question_bank = file_get_contents($input["question_bank"]) ;
//            dd($question_bank);
            $question_bank = preg_replace("'([\r\n\t])[\s]+'", '', $question_bank);  //去除回车换行符
//            dd($question_bank);
            $question_bank = preg_replace("/\'/", '"', $question_bank);
            $question_bank = preg_replace("/\"\"/", '"', $question_bank);
            $question_bank = preg_replace("/title/", '"title"', $question_bank);
            $question_bank = preg_replace("/question_type/", '"question_type"', $question_bank);
            $question_bank = preg_replace("/test_point/", '"test_point"', $question_bank);
            $question_bank = preg_replace("/answer_a/", '"answer_a"', $question_bank);
            $question_bank = preg_replace("/answer_b/", '"answer_b"', $question_bank);
            $question_bank = preg_replace("/answer_c/", '"answer_c"', $question_bank);
            $question_bank = preg_replace("/answer_d/", '"answer_d"', $question_bank);
            $question_bank = preg_replace("/answer_ok/", '"answer_ok"', $question_bank);
            $question_bank = preg_replace("/\"\"/", '"', $question_bank);
            $question_bank = json_decode($question_bank);
//            dd($question_bank);
            $question_bank = $question_bank->question_bank;
//            dd($question_bank);
            for($i=0;$i<count($question_bank);$i++){
               if($question_bank[$i]->answer_ok == 'y' || $question_bank[$i]->answer_ok == 'n'){
                   $bool1 = DB::table('question_bank')->insert(['question_type'=>2, 'title'=>$question_bank[$i]->title, 'test_point'=>$question_bank[$i]->test_point,'answer_ok'=>$question_bank[$i]->answer_ok, 'add_time'=>now()]);
               }else{
                   $bool2 = DB::table('question_bank')->insert(['question_type'=>4, 'title'=>$question_bank[$i]->title, 'test_point'=>$question_bank[$i]->test_point, 'answer_a'=>$question_bank[$i]->answer_a,'answer_b'=>$question_bank[$i]->answer_b,'answer_c'=>$question_bank[$i]->answer_c,'answer_d'=>$question_bank[$i]->answer_d,'answer_ok'=>$question_bank[$i]->answer_ok, 'add_time'=>now()]);
               }
            }
             if($bool1 && $bool2){
                return back()->with('top_msg','导入本地题库成功！');
             }
        }else{
            return view('admin.pullin');
        }
    }
//    出题系统
    public function addquestions() {
        if($input = Input::all()){
//            dd($input);
            $question_type = $input['questiontype'];
            $title = $input['title'];
           if ($question_type == 44 || $question_type == 4){
               $answer_a = $input['answer_a'];
               $answer_b = $input['answer_b'];
               $answer_c = $input['answer_c'];
               $answer_d = $input['answer_d'];
           }
            if ($question_type == 44){
                $answer_ok = implode($input['answer_ok']);
            }else{
                $answer_ok = $input['answer_ok'];
            }
            $test_point = $input['test_point'];
            if (isset($question_type) && isset($title) && isset($answer_ok) && isset($test_point)){
                if ($question_type == 44 || $question_type == 4){
                    $bool = DB::table('question_bank')->
                    insert(
                        [
                            'question_type'=>$question_type,
                            'title'=>$title,
                            'answer_a'=>$answer_a,
                            'answer_b'=>$answer_b,
                            'answer_c'=>$answer_c,
                            'answer_d'=>$answer_d,
                            'answer_ok'=>$answer_ok,
                            'test_point'=>$test_point,
                            'add_time'=>now()
                        ]);
                }else{
                    $bool = DB::table('question_bank')->
                    insert(
                        [
                            'question_type'=>$question_type,
                            'title'=>$title,
                            'answer_ok'=>$answer_ok,
                            'test_point'=>$test_point,
                            'add_time'=>now()
                        ]);
                }
                if($bool){
                    return back()->with('top_msg','添加题目成功！');
                }else{
                    return back()->with('top_msg','未知错误...');
                }
            }else{
                return back()->with('top_msg','请确认相关信息已填写完整！');
            }
        }else{
            $title = DB::table('question_bank')->orderBy('add_time','desc')->value('title');
            $add_time = DB::table('question_bank')->orderBy('add_time','desc')->value('add_time');
//            dd($title);
            return view('admin.addquestions',['title'=>$title, 'add_time'=>$add_time]);
        }
    }
//    组卷系统
    public function addexam(){
        if($input = Input::all()){
            $num_2 = DB::table('question_bank')->where('question_type','=','2')->count(); #题库中已有判断题个数
            $num_4 = DB::table('question_bank')->where('question_type','=','4')->count(); #题库中已有单选题个数
            $num_type_2 = $input['num_2']; #需要的判断题个数
            $num_type_4 = $input['num_4'];#需要的单选题个数
            $score_2 = $input['score_2'];#判断题分值
            $score_4 = $input['score_4'];#单选题分值
            $finish_time = $input['finish_time']; #完成时间
            $exam_num = $input['exam_num'];  #考试场次数
            $stu_num = $input['stu_num'];#  每场容纳学生数
            $firstId = DB::table('question_bank')->first();       //题库中起始id
//            dd($firstId);
            $arr2 = range($firstId->id,$firstId->id + $num_2 -1);
            shuffle($arr2);
//            dd($arr2);
            $type_2_question_id = array_slice($arr2,0,$num_type_2); #在 0-已有题目数 之间生成需要的判断题目随机数，保存题目id为一个数组
            $arr4 = range($firstId->id+$num_2,$firstId->id+$num_2+$num_4 -1);      //以判断题个数为起点，范围最大值为总题数
            shuffle($arr4);
            $type_4_question_id = array_slice($arr4,0,$num_type_4); #生成单选题id组成的数组
//            dd($type_4_question_id);
            $bool = DB::table('exam_paper')->insert(['finish_time'=>$finish_time,'exam_num'=>$exam_num,
                'score_2'=>$score_2,'stu_num'=>$stu_num, 'score_4'=>$score_4,'question_2_id'=>json_encode($type_2_question_id),
                'question_4_id'=>json_encode($type_4_question_id), 'add_time'=>now(),'operator'=>session('admin')]);
            if($bool){
                return back()->with('top_msg','组卷成功！');
            }else{
                return back()->with('top_msg','未知异常，请重试！');
            }
        }else{
            $num_2 = DB::table('question_bank')->where('question_type','=','2')->count();
            $num_4 = DB::table('question_bank')->where('question_type','=','4')->count();
            $paper_id = DB::table('exam_paper')->orderBy('add_time','desc')->first();
            $res = DB::table('exam_paper')->orderBy('add_time','desc')->first();  #最近一次组卷记录
            return view('admin.addexam',['num_2'=>$num_2, 'num_4'=>$num_4,'res'=>$res,'paper_id'=>$paper_id]);
        }
    }
//    浏览试题
    public function view_paper($paper_id){
        $question_2_id = DB::select('select question_2_id from syb_exam_paper where id=?',[$paper_id]);
        $question_2_id = json_decode($question_2_id[0]->question_2_id);
        $question_4_id = DB::select('select question_4_id from syb_exam_paper where id=?',[$paper_id]);
        $question_4_id = json_decode($question_4_id[0]->question_4_id);
//        dd($question_4_id);
        for ($i=0; $i<count($question_2_id);$i++){
            $res_2[$i] = DB::table('question_bank')->where('id','=',$question_2_id[$i])->get();
        }
//        dd($res_2[0][0]->id);
        for ($i=0; $i<count($question_4_id);$i++){
            $res_4[$i] = DB::table('question_bank')->where('id','=',$question_4_id[$i])->get();
        }
        for ($i=0;$i<count($res_2);$i++){
            $res_2[$i] = $res_2[$i][0];
        }
        for ($i=0;$i<count($res_4);$i++){
            $res_4[$i] = $res_4[$i][0];
        }
        $info = DB::table('exam_paper')->orderBy('add_time','desc')->first();

//        dd($info);
//        dd($res);
//        dd(count($res));
        return view('admin.view_paper',['res_2'=>$res_2, 'res_4'=>$res_4, 'info'=>$info]);
    }
    //考点管理-
    public function testPoint() {
        $nums = [];
        $test_point = DB::table('question_bank')->distinct()->pluck('test_point');
        for ($i=0;$i<count($test_point);$i++){
           $nums[$i] = DB::table('question_bank')->where('test_point','=',$test_point[$i])->count();
        }
        return view('admin.testPoint',['test_point'=>$test_point, 'nums'=>$nums]);
    }
    //成绩统计
    public function gradeM (){
        $paper_id = DB::table('exam_paper')->orderBy('add_time','desc')->value('id');
        $grades = DB::table('formal_exam')->where('paper_id','=',$paper_id)->get();
        if(count($grades) == 0){
            return view('admin.gradeM',['info'=>'暂无学生作答，没有成绩可供查看！']);
        }else{
            return view('admin.gradeM',['grades'=>$grades]);
        }
    }


//    退出登陆
    public function logout() {
        session(['admin'=>null]);
        return redirect('admin');
    }

//    public function test(){
//        return view('test');
//    }
}
