<template>
    <div style="height: 100%;background-color: white;">
        <table style="width: 100%;height: 100%;" cellpadding="0" cellspacing="0">
            <tr>
                <td class="sidebar" style="width: 200px;" id="navleft">
                    <div style="background-color:#2F4050;height: 700px;width: 200px;">
                        <div class="logo">
                            工资管理系统
                        </div>
                        <div class="user">
                            <img :src="user.avarar">
                            <span class="info">
                                {{user.name}}
                            </span>
                        </div>
                        <ul class="menu">
                            <template v-for="link in links">
                                <li @click="selected(link.url)"
                                    :class="{active: activeUrl == link.url}">
                                    <router-link :to='link.url'><i :class="link.className"></i>{{link.name}}</router-link>
                                </li>
                            </template>
                        </ul>
                        <div class="logout">
                            <a @click="loginout()"><i class="ion-log-out"></i>&nbsp;退出</a>
                        </div>
                    </div>
                </td>
                <td style="background-color: #EEEEEE;height: 100%;" valign="center">
                    <router-view v-on:path="selected"></router-view>
                </td>
            </tr>
        </table>
    </div>
</template>
<style>
    .sidebar {
        display: table-cell;
    }
    img {
        max-width: 100%;
    }
</style>
<style scoped>
    .user {
        text-align: center;
        padding: 30px 0;
        height: 185px;
        overflow: hidden;
    }

    .user > img {
        width: 100px;
        border-radius: 100%;
        height: 100px;
        display: block;
        margin: 0 auto;
    }

    .info {
        cursor: pointer;
        display: inline-block;
        color: white;
        font-size: 14px;
        border-radius: 20px;
        padding: 5px 20px;
        border: 1px solid white;
        min-width: 60px;
        min-height: 21px;
        margin: 0px auto;
        margin-top: 10px;
    }

    .logo {
        background: url('../../img/admin/logo-bg.png');
        height: 100px;
        color: white;
        font-weight: bolder;
        text-align: center;
        line-height: 30px;
        padding-top: 30px;
        font-size: 16px;
    }

    .logout {
        border-top: 2px solid #1b6d85;
        position: fixed;
        left: 0;
        bottom: 0px;
        height: 50px;
        width: 200px;
        line-height: 50px;
        background-color: #263A4A;
    }

    .menu {
        list-style: none;
        padding: 0px;
        margin: 0px;
    }

    .menu > li > a > i {
        font-size: 17px;
        position: relative;
        top: 2px;
        padding-right: 5px;
    }

    .menu > li > a {
        color: #a7b1c2;
        text-decoration: none;
        height: 50px;
        line-height: 50px;
        margin-left: 60px;
        display: block;
        width: 100%;
    }

    .menu > li.active {
        background-color: #293846;
        border-left: 2px solid #19AA8D;
    }

    .menu > li.active > a {
        color: white;

    }

    .menu > li:hover {
        background-color: #1b6d85;
    }

    .menu > li:hover > a {
        color: white;
    }

    .logout > a {
        color: white;
        text-align: center;
        font-size: 14px;
        text-decoration: none;
        display: block;
    }

    div.logout > a:hover, .info:hover {
        background-color: #1b6d85;
    }
</style>
<script >
    export default {
        data(){
            return {
                user: {
                    name:'username',
                    avarar:'../../img/admin/user-male-icon.png'
                },
                links:[
                    {
                        url:'/',
                        name:"工资导入",
                        className:'ion-ios-folder'
                    },
                    {
                        url:'/log',
                        name:"日志查看",
                        className:'el-icon-date'
                    },
                    {
                        url:'/fb',
                        name:"意见反馈",
                        className:'el-icon-message'
                    },
                    {
                        url:'/change',
                        name:"修改密码",
                        className:'el-icon-setting'
                    }
                ],
                activeUrl:"",
            }
        },
        computed: {},
        methods: {
            selected: function(url) {
                this.activeUrl = url;
            },
            /**
             * 退出的登陆
             */
            loginout(){
                this.$confirm('是否确认退出系统', '提示', {
                      confirmButtonText: '确定',
                      cancelButtonText: '取消',
                      type: 'warning'
                 }).then(() => {
                   
                     axios.post('/loginout').then(res=>{
                        if(res.data.code == 0){
                            this.$message({
                            type: 'success',
                            message: '退出成功!'
                            });
                            window.location="/login";
                        }else{
                            this.$message({
                            type: 'error',
                            message: '退出失败!'
                            });
                        }
                     })
                }).catch(() => {
                      this.$message({
                        type: 'info',
                        message: '取消退出'
                      });
                });
            }
        },
        mounted() {
            var this_ = this;
            axios.get('/getname')
                .then(function (response) {
                    if(response.data.code == 0)
                        this_.user.name = response.data.result;
                }).catch(function (response) {
                console.log(response.data);
            });
        },
    }
</script>