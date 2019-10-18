<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    public function getInfo($id){
       $user = DB::table('user')->where('student_id','=',$id)->get();
       if($user != '[]'){
           return $user;
       }
    }
}
