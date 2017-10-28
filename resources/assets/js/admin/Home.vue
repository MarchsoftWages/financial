<template>
    <div style="display: flex; justify-content: space-evenly;">
        <el-upload class="upload-demo" drag action="https://jsonplaceholder.typicode.com/posts/"
          multiple>
            <div class="el-upload__text"><h2>第一批次工资报表上传</h2></div>
            <i class="el-icon-upload"></i>
            <div class="el-upload__text">将文件拖到此处，或<em>点击上传</em></div>
            <div class="el-upload__tip" slot="tip">只能上传xlsx/xls文件，且不超过200kb</div>
        </el-upload>
        <el-upload class="upload-demo" drag action="https://jsonplaceholder.typicode.com/posts/"
          multiple>
            <div class="el-upload__text"><h2>第二批次工资报表上传</h2></div>
            <i class="el-icon-upload"></i>
            <div class="el-upload__text">将文件拖到此处，或<em>点击上传</em></div>
            <div class="el-upload__tip" slot="tip">只能上传xlsx/xls文件，且不超过200kb</div>
        </el-upload>
    </div>
</template>
<style>
    .el-upload-dragger{
        height: 360px;
    }
    .el-upload-dragger .el-icon-upload {
        font-size: 180px;
        line-height: 180px;
    }
</style>
<script type="text/ecmascript-6">
    import { Loading } from 'element-ui'
    export default {
        data(){
            return {
                numberValidateForm: {
                    age: ''
                }
            }
        },
        computed: {},
        methods: {
            submitForm(formName) {
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        alert('submit!');
                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                });
            },
            resetForm(formName) {
                this.$refs[formName].resetFields();
            }
        },
        mounted() {

        },
    }
</script>
