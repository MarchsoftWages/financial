<template>
    <div style="display: flex; justify-content: space-evenly;">
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
</style>
<script type="text/ecmascript-6">
    import { Loading } from 'element-ui'
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
                loadingInstance:'',   //loading
                progressCount:''
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
                this.loadingInstance.close();
                if(response.code==1){
                    this.$message.error(response.result);
                    return;
                }
                this.$message({message: response.result, type: 'success'});
            },
            /**
             * 上传错误
             * @param response
             * @param file
             * @param fileList
             */
            uploadError (response, file, fileList) {
                this.loadingInstance.close();
                this.$message.error('上传失败，请重试！');
            },
            /**
             * 上传前对文件的大小的判断
             * @param file
             * @returns {boolean}
             */
            beforeAvatarUpload (file) {
                const extension = file.name.split('.')[1] === 'xls';
                const extension2 = file.name.split('.')[1] === 'xlsx';
                const isLt2M = file.size / 1024 / 1024 < 2;
                if (!extension && !extension2) {
                    this.$message({message: '上传模板只能是 xls、xlsx 格式!',type: 'warning'});
                }
                if (!isLt2M) {
                    this.$message({message: '上传模板大小不能超过 2MB!',type: 'warning'});
                }
                this.loadingInstance = Loading.service({ fullscreen: true });
                return extension || extension2 && isLt2M
            },
            setCpyid(val){
                this.upLoadData.cpyId = val;
            },
        },
        mounted() {
            this.$emit('path',this.$route.path);
            $('.first-file').attr('href',"http://"+ location.host +"/download?downloadType=0");
            $('.second-file').attr('href',"http://"+ location.host +"/download?downloadType=1");
        },
    }
</script>
