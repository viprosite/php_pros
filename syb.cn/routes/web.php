<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//ajax操作
Route::get('checkname/{student_id}','ToolsController@checkname');
Route::get('exam_num','ToolsController@exam_num');  #获得考试场次数
Route::get('stu_num/{exam_num}','ToolsController@stu_num');  #选择考试场次数后获得该场的已报名数
Route::get('check_reg_exam/{student_id}','ToolController@check_reg_exam');      #正式考试前检测是否已报名


Route::match(['get','post'],'/','IndexController@login');         //1.默认首页
Route::match(['get','post'],'register','IndexController@register');         //注册
Route::get('code','IndexController@code');       //验证码
Route::get('news','IndexController@news');
Route::get('newdetail/{id}','IndexController@newdetail');

//用户登陆后的操作-》CheckUserLogin中间件
Route::group(['middleware'=>'user.login'],function() {
    Route::get('home','IndexController@home');
    Route::get('logout','IndexController@logout');
    Route::get('msgdetail/{id}','IndexController@msgdetail');
    Route::get('history_msg','IndexController@history_msg');
    Route::get('my_collection','IndexController@my_collection');
    Route::match(['get','post'],'my_info','IndexController@my_info');
    Route::post('delmsg/{id}','IndexController@delmsg');

    Route::get('test_point','IndexController@test_point');   //考点练习
    Route::get('test_point/{detail}','IndexController@test_point_detail');   //考点练习具体考点
    Route::post('check_test_point','IndexController@check_test_point');       //考点练习的答案检测
    Route::get('question_type','IndexController@question_type');   //题型练习
    Route::get('question_type/{type}','IndexController@question_type_detail');   //题型练习具体题型
    Route::post('check_question_type','IndexController@check_question_type');
    Route::get('mock_test','IndexController@mock_test');        //模拟测试
    Route::get('mock_test_detail/{id}','IndexController@mock_test_detail');   //模拟测试具体考题
    Route::post('check_mock_test','IndexController@check_mock_test');   //模拟测试答案
    Route::post('reg_exam/{exam_num}','IndexController@reg_exam');       //考试报名
    Route::get('formal_exam','IndexController@formal_exam');   //正式考试
    Route::post('check_formal_exam','IndexController@check_formal_exam');   //模拟测试答案


});


//后台操作
Route::group(['namespace'=>'Admin','prefix'=>'admin'],function() {
    Route::match(['get','post'],'/','IndexController@login');
});
//后台登陆后
Route::group(['prefix'=>'admin','middleware'=>'admin.login','namespace'=>'Admin'],function() {
    Route::get('home','IndexController@home');
    Route::match(['get','post'],'changePassword','IndexController@changePassword');
    Route::match(['get','post'],'seenews','IndexController@seenews');
    Route::match(['get','post'],'addnews','IndexController@addnews');
    Route::get('welcome','IndexController@welcome');
    Route::get('newdetail/{id}','IndexController@newdetail');
    Route::get('studentm','IndexController@studentM');          //考生管理
    Route::post('search_student/{search_key}','IndexController@searchStudent'); //考生搜索
    Route::post('sendmsg/student_id/{student_id}','IndexController@sendmsg');// 发送信息
    Route::post('delstudent/student_id/{student_id}','IndexController@delstudent');//删除
    Route::match(['get','post'],'pullin','IndexController@pullin');        //导入题库
    Route::get('questionbank','IndexController@questionBank');       //题库管理
    Route::match(['get','post'],'addquestions','IndexController@addquestions');       //出题系统
    Route::match(['get','post'],'addexam','IndexController@addexam');     //组卷系统
    Route::get('view_paper/{paper_id}','IndexController@view_paper');     //浏览组卷
    Route::get('testpoint','IndexController@testPoint');          //考点管理
    Route::get('gradem','IndexController@gradeM');          //成绩统计

    Route::get('logout','IndexController@logout');
//    Route::get('test','IndexController@test');
});

