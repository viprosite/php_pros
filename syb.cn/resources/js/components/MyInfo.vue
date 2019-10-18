<template>
    <div>
        <div class="panel panel-info center-block" >
            <div class="panel-heading">
                <span class="panel-title">
                    <i class="fa fa-user-circle-o"></i> 查看或修改个人信息
                </span>
            </div>
            <br>
            <form onsubmit="return false" class="form-horizontal">
                <div class="form-group" style="position: relative;">
                    <label class="col-md-3 control-label">学号:</label>
                    <div class="col-md-8 input-group">
                        <span class="input-group-addon"> <i class="fa fa-fw fa-sort-numeric-asc"></i></span>
                        <span class="form-control" readonly>{{info.student_id}}</span>
                    </div>
                    <span class="checkname">  </span>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">姓名:</label>
                    <div class="col-md-8 input-group">
                        <span class="input-group-addon"> <i class="fa fa-fw fa-id-card"></i></span>
                        <span class="form-control" readonly>{{info.student_name}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">注册时间:</label>
                    <div class="col-md-8 input-group">
                        <span class="input-group-addon"> <i class="fa fa-fw fa-hourglass-2"></i></span>
                        <span class="form-control" readonly>{{info.register_at}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">邮箱:</label>
                    <div class="col-md-8 input-group">
                        <span class="input-group-addon"> <i class="fa fa-fw fa-envelope"></i></span>
                        <input id="email" type="email" name="email" class="form-control" placeholder="邮箱地址" required v-model="info.email">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">原密码:</label>
                    <div class="col-md-8 input-group">
                        <span class="input-group-addon"> <i class="fa fa-fw fa-key"></i></span>
                        <input id="o_password" type="password" name="o_password" class="form-control" placeholder="请输入原密码进行个人信息更改" required >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">新密码:</label>
                    <div class="col-md-8 input-group">
                        <span class="input-group-addon"> <i class="fa fa-fw fa-key"></i></span>
                        <input id="n_password" type="password" name="n_password" class="form-control" placeholder="请输入并牢记新密码，下次登录需使用新密码" required>
                    </div>
                    <span class="help-block col-md-offset-4 pass-help">6-12位 字母[a-z A-Z] 数字[0-9] 下划线_ 的组合</span>
                </div>

                <div class="form-group">
                    <div class="col-md-10 col-md-offset-4 col-sm-10 col-sm-offset-3 col-xs-10 col-xs-offset-2">
                        <button id="update_btn" type="submit" class="btn btn-success" @click="update_info"> <i class="fa fa-check-square-o"></i> 确认修改 </button>
                        <span class="col-xs-offset-1 col-sm-offset-2 col-md-offset-1"></span>
                        <button id="reset_btn" type="reset" class="btn btn-warning col-md-offset-2"> <i class="fa fa-mail-reply"></i> 重置 </button>
                    </div>
                </div>
            </form>
            <br>
            <div class="panel-footer">
               <span class="text-center">
                    更新 <mark>邮箱地址</mark> 和 <mark>个人密码</mark> 需要确保原密码正确！
               </span>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'
    export default{
        name:'info',
        data(){
            return {
                info:{},
            }
        },
        props:['id'],
        mounted(){
            this.getInfo()
        },
        methods:{
            getInfo:function () {
                let _this = this
                axios({
                    method:'get',
                    url:'api/info/'+this.id
                }).then(function (res) {
                    if (res) {
                        _this.$data.info = res.data[0]
                    }
                })
            },
            update_info:function () {
                let n_info = {
                     email : document.getElementById('email').value,
                     o_password : document.getElementById('o_password').value,
                     n_password : document.getElementById('n_password').value,
                     id:this.id
                }
//                console.log(n_info)
                axios({
                    method:'post',
                    url:'my_info',
                    data:n_info
                }).then(function (res) {
                    switch (res.data){
                        case 'y':
                            $.growl.notice({
                                title: "个人信息修改提示",
                                message: "信息更新成功!"
                            });
                            $('#o_password, #n_password, #email').attr('readonly','readonly')
                            $('#update_btn, #reset_btn').remove()

                            break;
                        case 'n':
                            $.growl.warning({
                                title: "个人信息修改提示",
                                message: "发生错误，请确保填写完整后重试!"
                            });
                            break;
                        case 'err':
                            $.growl.error({
                                title: "个人信息修改提示",
                                message: "原密码错误，请重试!"
                            });
                    }
                })
            }
        }

    }
</script>