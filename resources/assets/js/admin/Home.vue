<template>
    <div style="display: flex; justify-content: space-evenly;"
            v-loading.fullscreen = "loadingInstance"
            element-loading-text = "拼命加载中,请稍后...">
        <div @click="setCpyid(0)">
            <el-upload class="upload-demo" drag :show-file-list="false"
                       :action="importFileUrl"
                       :data="upLoadData"
                       :headers="headers"
                       :on-error="uploadError"
                       :on-success="uploadSuccess"
                       :before-upload="beforeAvatarUpload">
                <div class="el-upload__text"><h2 style="color:black;">正常工资导入</h2></div>
                <i class="el-icon-upload"></i>
                <div class="el-upload__text">将文件拖到此处，或<em>点击上传</em></div>
                <div class="el-upload__tip" slot="tip">只能上传xlsx/xls文件，且不超过2mb</div>
            </el-upload>
            <span class="download-span"><a class="download-a first-file">正常工资模板</a></span>
        </div>

        <div @click="setCpyid(1)">
            <el-upload class="upload-demo" drag :show-file-list="false"
                       :action="importFileUrl"
                       :data="upLoadData"
                       :headers="headers"
                       :on-error="uploadError"
                       :on-success="uploadSuccess"
                       :before-upload="beforeAvatarUpload">
                <div class="el-upload__text"><h2 style="color:black;">特殊发放导入</h2></div>
                <i class="el-icon-upload"></i>
                <div class="el-upload__text">将文件拖到此处，或<em>点击上传</em></div>
                <div class="el-upload__tip" slot="tip">只能上传xlsx/xls文件，且不超过2mb</div>
            </el-upload>
            <span class="download-span"><a class="download-a second-file">特殊发放模板</a></span>
        </div>
        <div v-bind:class="{ progress: loadingInstance }">
            <el-progress 
            :text-inside="true" 
            :stroke-width="20" 
            :percentage="percentage" 
            :status="status">
            </el-progress>
        </div>
    </div>
</template>
<style>
    .el-upload-dragger{
        height: 300px;
    }
    .el-upload-dragger .el-icon-upload {
        font-size: 180px;
        line-height: 180px;
        margin: 0 0 16px;
    }
    .download-span{
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }
    .download-a{
        color: #bdabab;
        font-size: 10px;
        text-decoration: none;
    }
    .progress{
        position: fixed;
        z-index: 10001;
        background-color: rgba(255,255,255,0);
        margin: 0;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
    }
    .el-progress{
        top:65%;
        width: 60%;
        margin:0 auto; 
    }
    .el-progress-bar__inner::after { 
        content: ''; 
        opacity: 0; 
        position: absolute; 
        top: 0; 
        right: 0; 
        bottom: 0; 
        left: 0; 
        background: #fff; 
        -moz-border-radius: 3px; 
        -webkit-border-radius: 3px; 
        border-radius: 3px; 
        -webkit-animation: animate-shine 3s ease-out infinite; 
        -moz-animation: animate-shine 3s ease-out infinite; 
    } 
    @-webkit-keyframes animate-shine { 
        0% {opacity: 0; width: 0;} 
        50% {opacity: .5;} 
        100% {opacity: 0; width: 95%;} 
    } 
    @-moz-keyframes animate-shine { 
        0% {opacity: 0; width: 0;} 
        50% {opacity: .5;} 
        100% {opacity: 0; width: 95%;} 
    } 
</style>
<script type="text/ecmascript-6">
    export default {
        data(){
            return {
                importFileUrl: '/admin/upload',
                upLoadData: {
                    cpyId: '',     //上传标记
                    updateType: 1  //更新方式
                },
                headers:{
                    'X-CSRF-TOKEN':document.head.querySelector('meta[name="X-CSRF-TOKEN"]').content
                },
                loadingInstance:false,   //loading
                progressCount:'',
                percentage:0,
                status:''
            }
        },
        computed: {

        },
        methods: {
            /**
             * 上传成功后的回调
             * @param response
             * @param file
             * @param fileList
             */
            uploadSuccess (response, file, fileList) {
                var this_ = this;
                if(response.code ==1 ){
                    this_.percentage = 0;
                    this_.status = "exception";
                }else{
                    this_.percentage = 100;
                    this_.status = "success";
                }
                setTimeout(function(){
                    this_.loadingInstance = false;
                    this_.status = "";
                    response.code==1?this_.$message.error(response.result):this_.$message({message: response.result, type: 'success'});
                },600);
                
            },
            /**
             * 上传错误
             * @param response
             * @param file
             * @param fileList
             */
            uploadError (response, file, fileList) {
                this.loadingInstance = false;
                this.$message.error('上传失败，请重试！');
            },
            /**
             * 上传前对文件的大小的判断
             * @param file
             * @returns {boolean}
             */
            beforeAvatarUpload (file) {
                let name = file.name.split('.')
                const extension = name[name.length-1] === 'xls';
                const extension2 = name[name.length-1] === 'xlsx';
                const isLt2M = file.size / 1024 / 1024 < 2;
                if (!extension && !extension2) {
                    this.$message({message: '上传模板只能是 xls、xlsx 格式!',type: 'warning'});
                }
                if (!isLt2M) {
                    this.$message({message: '上传模板大小不能超过 2MB!',type: 'warning'});
                }
                this.loadingInstance = true;
                return extension || extension2 && isLt2M
            },
            setCpyid(val){
                this.upLoadData.cpyId = val;
                var this_ = this;
                setTimeout(function(){
                    this_.percentage = parseInt(Math.random()*10*3+10,10);
                    setTimeout(function(){
                        this_.percentage = parseInt(Math.random()*10*2+37,10);
                        setTimeout(function(){
                            this_.percentage = parseInt(Math.random()*10*3+64,10);
                        },parseInt(Math.random()*100+2800,10));
                    },parseInt(Math.random()*100+2000,10));
                },parseInt(Math.random()*100+300,10));
            },
        },
        mounted() {
            this.$emit('path',this.$route.path);
            $('.first-file').attr('href',"http://"+ location.host +"/download?downloadType=0");
            $('.second-file').attr('href',"http://"+ location.host +"/download?downloadType=1");
        },
    }
</script>
