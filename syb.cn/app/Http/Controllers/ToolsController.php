<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ToolsController extends Controller
{
//    检查学号是否重复
    public function checkname($student_id) {
        $student = DB::table('user')->
            where('student_id',$student_id)->
            get();
        if (count($student) != 0){
            return 'n';
        }else{
            return 'y';
        }
    }
    public function exam_num(){       //获得最近一次组卷的场次数和容纳数
        $exam_num = DB::table('exam_paper')->orderBy('add_time','desc')->value('exam_num');
        $stu_num = DB::table('exam_paper')->orderBy('add_time','desc')->value('stu_num');
        for ($i=0;$i<$exam_num;$i++){
            $already_reg_num[] = DB::table('reg_exam')->where('exam_num','=',$i+1)->where('reg_exam_state','=',1)->count();  #数组元素顺序+1对应场次数
        }
        $res = ['exam_num'=>$exam_num, 'stu_num'=>$stu_num, 'already_reg_num'=>$already_reg_num];
        if (count($res) > 0){
            return $res;
        }
    }

//    public function check_reg_exam($student_id){
//        $bool = DB::table('reg_exam')->where('student_id','=',$student_id)->get();
////        dd($bool);
//        if (count($bool) == 1){
//            return 'y';
//        }else{
//            return 'n';
//        }
//    }
}
