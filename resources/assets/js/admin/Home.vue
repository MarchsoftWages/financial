<template>
    <div style="display: flex; justify-content: space-evenly;">
        <el-upload class="upload-demo" drag :action="importFileUrl" :data="upLoadData" :onError="uploadError" :onSuccess="uploadSuccess" :beforeUpload="beforeAvatarUpload"
          multiple>
            <div class="el-upload__text"><h2 style="color:black;">第一批次工资报表上传</h2></div>
            <i class="el-icon-upload"></i>
            <div class="el-upload__text">将文件拖到此处，或<em>点击上传</em></div>
            <div class="el-upload__tip" slot="tip">只能上传xlsx/xls文件，且不超过40mb</div>
        </el-upload>
        <el-upload class="upload-demo" drag :action="importFileUrl" :data="upLoadData" :onError="uploadError" :onSuccess="uploadSuccess" :beforeUpload="beforeAvatarUpload"
          multiple>
            <div class="el-upload__text"><h2 style="color:black;">第二批次工资报表上传</h2></div>
            <i class="el-icon-upload"></i>
            <div class="el-upload__text">将文件拖到此处，或<em>点击上传</em></div>
            <div class="el-upload__tip" slot="tip">只能上传xlsx/xls文件，且不超过40mb</div>
        </el-upload>
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
</style>
<script type="text/ecmascript-6">
    import { Loading } from 'element-ui'
    export default {
        data(){
            return {
                importFileUrl: '/admin/upload',
                upLoadData: {
                    cpyId: '', 
                    occurTime: new Date().toLocaleString()
                }
            }
        },
        computed: {

        },
        methods: {
            // 上传成功后的回调
            uploadSuccess (response, file, fileList) {
                console.log('上传文件', response)
            },
            // 上传错误
            uploadError (response, file, fileList) {
                this.$message.error('上传失败，请重试！');
                console.log(this.upLoadData.occurTime)
            },
            // 上传前对文件的大小的判断
            beforeAvatarUpload (file) {
                const extension = file.name.split('.')[1] === 'xls'
                const extension2 = file.name.split('.')[1] === 'xlsx'
                const isLt2M = file.size / 1024 / 1024 < 40
                if (!extension && !extension2) {
                    this.$message({message: '上传模板只能是 xls、xlsx 格式!',type: 'warning'});
                }
                if (!isLt2M) {
                    this.$message({message: '上传模板大小不能超过 40MB!',type: 'warning'});
                }
                return extension || extension2 && isLt2M
            }
        },
        mounted() {

        },
    }
</script>
