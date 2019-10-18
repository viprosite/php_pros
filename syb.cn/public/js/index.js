$(function () {
    // 学号重复性检测
    $('#student_id').change(function (e) {
        var student_id = e.currentTarget.value
        $.get('checkname/'+student_id, function (data) {
           if(data){
               if(data === 'n'){
                   $('.checkname').html('<span class="text-warning">' +
                       '<i class="fa fa-warning text-warning"></i> 重复，请确认！' +
                       '</span>')
               }
               if(data === 'y'){
                   $('.checkname').html('<span class="text-success">' +
                       '<i class="fa fa-info-circle text-success"></i> 欢迎新用户！' +
                       '</span>')
               }

           }
        })
    })
    //密码规则验证
    $('[name="password"]').blur(function(){
        var password = $(this).val()
        if(password.length <6){
            $(this).focus()
        }
    })

    $('.question-box').hide()
    $('.show-question-box').click(function () {
        $('.question-box').show()
    })
    $('.type4, .type44, .type2').remove()
    $('.questiontype').change(function (e) {
        var type = $(this).val()
        var type4 = `
       <ul class="list-group answers type4">
                           <li class="list-group-item">
                               选项A
                               <b> <input type="text" name="answer_a" class="form-control" style="width: 90%" placeholder="在此输入选项A内容"> </b>
                           </li>
                           <li class="list-group-item">
                               选项B
                               <b> <input type="text" name="answer_b" class="form-control" style="width: 90%" placeholder="在此输入选项B内容"> </b>
                           </li>
                           <br />
                           <li class="list-group-item">
                               选项C
                               <b> <input type="text" name="answer_c" class="form-control" style="width: 90%" placeholder="在此输入选项C内容"> </b>
                           </li>
                           <li class="list-group-item">
                               选项D
                               <b> <input type="text" name="answer_d" class="form-control" style="width: 90%" placeholder="在此输入选项D内容"> </b>
                           </li>
                           <li class="list-group-item text-success" style="width: 30%">
                               答案 &nbsp;
                               <b> <label><input type="radio" value="a" name="answer_ok"> A</label> </b> &nbsp;
                               <b> <label><input type="radio" value="b" name="answer_ok"> B</label> </b> &nbsp;
                               <b> <label><input type="radio" value="c" name="answer_ok"> C</label> </b> &nbsp;
                               <b> <label><input type="radio" value="d" name="answer_ok"> D</label> </b> &nbsp;
                           </li>
                           <li class="list-group-item" style="width: 64%">
                               考点 &nbsp;
                               <b> <input type="text" name="test_point" class="form-control" style="width: 90%" placeholder="在此输入一个考点关键词（一个）"> </b>
                           </li>
                           <li class="list-group-item" style="width: 94%;">
                               <button class="btn btn-info col-md-5 col-md-offset-3"> <i class="fa fa-check-square-o"></i> 确认添加</button>
                           </li>
                       </ul> 
        `
        var type44 = `
        <ul class="list-group answers type44">
                           <li class="list-group-item">
                               选项A
                               <b> <input type="text" name="answer_a"  class="form-control" style="width: 90%" placeholder="在此输入选项A内容"> </b>
                           </li>
                           <li class="list-group-item">
                               选项B
                               <b> <input type="text" name="answer_b" class="form-control" style="width: 90%" placeholder="在此输入选项B内容"> </b>
                           </li>
                           <br />
                           <li class="list-group-item">
                               选项C
                               <b> <input type="text" name="answer_c" class="form-control" style="width: 90%" placeholder="在此输入选项C内容"> </b>
                           </li>
                           <li class="list-group-item">
                               选项D
                               <b> <input type="text" name="answer_d" class="form-control" style="width: 90%" placeholder="在此输入选项D内容"> </b>
                           </li>
                           <li class="list-group-item text-success" style="width: 30%">
                               答案 &nbsp;
                               <b> <label><input type="checkbox" value="a" name="answer_ok[]"> A</label> </b> &nbsp;
                               <b> <label><input type="checkbox" value="b" name="answer_ok[]"> B</label> </b> &nbsp;
                               <b> <label><input type="checkbox" value="c" name="answer_ok[]"> C</label> </b> &nbsp;
                               <b> <label><input type="checkbox" value="d" name="answer_ok[]"> D</label> </b> &nbsp;
                           </li>
                           <li class="list-group-item" style="width: 64%">
                               考点 &nbsp;
                               <b> <input type="text" name="test_point" class="form-control" style="width: 90%" placeholder="在此输入一个考点关键词（一个）"> </b>
                           </li>
                           <li class="list-group-item" style="width: 94%;">
                               <button class="btn btn-info col-md-5 col-md-offset-3"> <i class="fa fa-check-square-o"></i> 确认添加</button>
                           </li>
                       </ul>
        `
        var type2 = `
         <ul class="list-group answers type2">
                           <li class="list-group-item text-success" style="width: 30%">
                               答案 &nbsp;
                               <b>
                                    <div class="radio">
                                        <label><input type="radio" value="y" name="answer_ok">
                                            <span class="fa fa-stack">
                                                <i class="fa fa-square-o fa-stack-2x"></i>
                                                <i class="fa fa-check fa-stack-1x"></i>
                                           </span>
                                        </label>
                                    </div>
                               </b> &nbsp;
                               <b>
                                   <div class="radio">
                                       <label><input type="radio" value="n" name="answer_ok">
                                           <span class="fa fa-stack">
                                                <i class="fa fa-square-o fa-stack-2x"></i>
                                                <i class="fa fa-close fa-stack-1x"></i>
                                           </span>
                                       </label>
                                   </div>
                               </b> &nbsp;
                           </li>
                           <li class="list-group-item" style="width: 65%">
                               考点 &nbsp;
                               <b> <input type="text" name="test_point" class="form-control" style="width: 90%" placeholder="在此输入一个考点关键词（一个）"> </b>
                           </li>
                           <br><br>
                           <li class="" style="width: 96%;">
                               <button class="btn btn-info col-md-5 col-md-offset-3"> <i class="fa fa-check-square-o"></i> 确认添加</button>
                           </li>
                       </ul>
        `
        switch(type){
            case '2':
               $('.type-box').html(type2)
                break;
            case '4':
                $('.type-box').html(type4)
                break;
            case '44':
                $('.type-box').html(type44)
                break;
            default:
                $('.type-box').html('')
        }
    })
    // ajax获取对应考点题目数
})