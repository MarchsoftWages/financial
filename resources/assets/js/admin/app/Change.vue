<template>
 <div class="border">
    <div class="main">
        <div class="title">
            <h2>修改密码</h2>
        </div>

        <el-form :model="ruleForm2" status-icon :rules="rules2" ref="ruleForm2" label-width="100px" class="demo-ruleForm" >
          <el-form-item label="旧密码" prop="oldpass">
                 <el-input type="password" v-model="ruleForm2.oldpass" auto-complete="off"></el-input>
          </el-form-item>
          <el-form-item label="新密码" prop="Pass">
                 <el-input type="password" v-model="ruleForm2.Pass" auto-complete="off"></el-input>
          </el-form-item>
          <el-form-item label="确认密码" prop="checkPass">
                 <el-input type="password" v-model="ruleForm2.checkPass"></el-input>
          </el-form-item>
          <el-form-item>
                <el-button  type="primary" @click="submitForm('ruleForm2')">提交</el-button>
                <el-button @click="resetForm('ruleForm2')">重置</el-button>
          </el-form-item>
       </el-form>
   </div>
   </div>
</template>
<style scoped>
   .border{
    /*border: 1px solid #000;*/
    background-color: #fff;
   }
  .main{
    width: 600px;
    padding: 20px;
  }
  .title{
    margin-bottom: 40px;
    margin-left: 12%;
    padding: 0px 10px;
    border-left: 2px solid #7D7DFF;
  }
  form{
    margin-left: 12%;
  }
  .vertical{
    width: 10px;
    height: 200px;
    background: red;
    position:fixed;
  }
</style>
<script type="text/ecmascript-6">
    import { Loading } from 'element-ui'
    export default {
        data(){
       
        var vlidatePass=(rule,value,callback)=>{
        if(value===''){
            callback(new Error('请输入旧密码'));
        }else{
            if (this.ruleForm2.checkPass !== '') {
            this.$refs.ruleForm2.validateField('checkPass');
          }
          callback();
        }
        };
         var  pass=(rule,value,callback)=>{
        if(value ===''){
              callback(new Error('密码不能为空'));
        }else{
            if(value.length<6){
            callback(new Error('密码必须大于六位'));
            }else{
                callback();
            }
        }
        };
        var validatePass2=(rule,value,callback)=>{
            if(value===''){
            callback(new Error('请再次输入密码'));
            }else if( value !==this.ruleForm2.Pass){
                callback(new Error('两次输入密码不一致'));
            }else{
            callback();
            }
        };

            return {
                ruleForm2:{
                oldpass:'',
                Pass:'',
                checkPass:''
                },
                rules2:{
                oldpass:[
                    { validator: vlidatePass,trigger:'blur' }
                ],
                Pass:[
                    {validator: pass,trigger: 'blur'}
                ],
                checkPass:[
                    {validator: validatePass2,trigger: 'blur'}
                ]
                },
                numberValidateForm: {
                    age: ''
                },

            };
          
        },
        computed: {},
        methods: {
           submitForm(formName) {
          /*  var self = this
           axios.post('/modify_password',{}).then( res => {
                this.$message({
                      message: '恭喜你，这是一条成功消息',
                      type: 'success'
                    });
           })*/
            this.$refs[formName].validate((valid)=>{
            if(valid){
             const postData={
              oldpass:this.ruleForm2.oldpass,
              pass:this.ruleForm2.Pass,
              newpass:this.ruleForm2.checkPass
             }
             axios.post('/modify_password',postData).then( res =>{
                if(res.data.code == 0){
                    this.$message({
                      message: res.data.result,
                      type: 'success'
                    });
                }else{
                this.$message({
                      message: res.data.result,
                      type: 'error'
                    });
                }
             })
             .catch(function(response){
                this.$message({
                      message: '提交失败',
                      type: 'error'
                    });
             })
            }else{
             console.log('error submit');
             return false;
            }
            });
            },
            resetForm(formName){
             this.$refs[formName].resetFields();
            }
        },
        mounted() {
        },
    }
</script>
