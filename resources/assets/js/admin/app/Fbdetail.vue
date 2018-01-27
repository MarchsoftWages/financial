<template>
    <div class="show-content">
        <div class="show-item">
            <el-card class="box-card">
                <div class="clearfix">
                    <span class="head-card" >反馈详情</span>
                    <el-button size="mini" @click="backFb" style="float: right;border-color:#7DA0B1; background-color: #7DA0B1" type="primary">
                        <i class="el-icon-arrow-left"></i>
                    </el-button>
                </div>
                <ul class="text">
                    <li>
                        <span class="text-item">工号：</span>
                        <span>{{infoArr['job_num']}}</span>
                    </li>
                    <li>
                        <span class="text-item">姓名：</span>
                        <span>{{infoArr['name']?infoArr['name']:'无'}}</span>
                    </li>
                    <li>
                        <span class="text-item">电话：</span>
                        <span>{{infoArr['phone']?infoArr['phone']:'无'}}</span>
                    </li>
                </ul>
                <ul class="text">
                    <li>
                        <span class="text-item">手机型号：</span>
                        <span>{{infoArr['phone_model']?infoArr['phone_model']:'无'}}</span>
                    </li>
                    <li>
                        <span class="text-item">问题类型：</span>
                        <span>{{infoArr['qu_type']}}</span>
                    </li>
                </ul>
                <el-card class="box-card item-card">
                    <div class="clearfix">
                        <span style="line-height: 28px;font-weight: 700">问题详情</span>
                    </div>
                    <div >
                        {{infoArr['qu_detail']?infoArr['qu_detail']:'无'}}
                    </div>
                </el-card>
                <div v-if="img?true:false">
                    <el-card class="box-card img-card" >
                        <template v-for="i in img">
                            <img :src="getImg(i)" class="image" @click="clickImg($event)">
                            <big-img v-if="showImg" @clickit="viewImg" :imgSrc="imgSrc"></big-img>
                        </template>
                    </el-card>
                </div>
            </el-card>
        </div>
    </div>
</template>
<style>
    .show-content li{
        text-decoration: none;
        display: inline;
    }
    .show-content{
        display: flex;
        justify-content: center;
    }
    .text-item{
        font-weight: 700;
    }
    .text {
        display: flex;
        justify-content: space-around;
        font-size: 16px;
        margin-top: 30px;
        padding: 0;
        padding-bottom: 5px;

    }
    .head-card{
        line-height: 30px;
        font-size: 2.1em;
        padding-left: 10px;
        border-left: 10px solid #7D7DFF;
    }
    .clearfix:before,
    .clearfix:after {
        display: table;
        content: "";
    }
    .clearfix:after {
        clear: both
    }
    .image {
        width: 150px;
        margin: 0 10px;
        cursor: pointer;
    }
    .box-card {
        width: 782px;

    }
    .item-card{
        margin: 20px 0;
        width: 741px;
        min-height: 215px;
    }
    .img-card{
        width: 741px;
    }
</style>
<script>
    import BigImg from './BigImg.vue';
    export default{
        components: {
            'big-img':BigImg
        },
        data(){
            return {
                infoArr:[],
                img:[],
                showImg:false,
                imgSrc: ''
            }
        },
        methods: {
            backFb(){
                this.$router.push({path: '/fb'});
            },
            getImg(img){
                let path;
                $.ajax({
                    url: '/getimg/'+img,
                    async: false,
                    success : function(data){
                        path = data;
                    }
                });
                return path;
            },
            clickImg(e) {
                this.showImg = true;
                // 获取当前图片地址
                this.imgSrc = e.currentTarget.src;
            },
            viewImg(){
                this.showImg = false;
            },
        },
        mounted() {
            this.infoArr=JSON.parse(this.$route.query.read_info);
            this.$emit('path','/fb');
            if(this.infoArr['img_path']){
                this.img = JSON.parse(this.infoArr['img_path'])
            }
        },

    }
</script>