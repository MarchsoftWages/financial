<template>
    <div class="el-table-self">
        <el-dialog title="覆盖上传" :visible.sync="updateVisible"
                   size="tiny"
                   :key="randomNumber">
            <el-upload
                    class="upload-demo" drag :show-file-list="false"
                    action="/admin/upload"
                    :data="upLoadData"
                    :headers="headers"
                    :on-error="upError"
                    :on-success="upSuccess"
                    :before-upload="beforeUp">
                <i class="el-icon-upload"></i>
                <div class="el-upload__text">将文件拖到此处，或<em>点击上传</em></div>
                <div class="el-upload__tip" slot="tip">只能上传xlsx/xls文件，且不超过2mb</div>
            </el-upload>
        </el-dialog>
        <el-breadcrumb class="el-breadcrumb">
            <el-radio-group v-model="radio" :change="getRadio()">
                <el-radio :label="0">全部</el-radio>
                <el-radio :label="1">第一批</el-radio>
                <el-radio :label="2">第二批</el-radio>
            </el-radio-group>
            <el-input v-model="input" placeholder="请输入文件名"></el-input>
            <div class="block">
                <el-date-picker
                        v-model="value"
                        type="month"
                        @change="dateChange"
                        :picker-options="pickerBeginDateBefore"
                        placeholder="选择月">
                </el-date-picker>
            </div>
            <el-button type="primary" icon="search" @click="searchLog">搜索</el-button>
        </el-breadcrumb>
        <el-table
            :data="changeSearch==0?pageData.data:search"
            border
            style="width: 100%"
            :default-sort = "{prop: 'upload_time', order: 'descending'}">
            <el-table-column
                prop="file_name"
                label="文件名"
                sortable
                width="300">
            </el-table-column>
            <el-table-column
                prop="operater"
                label="操作员"
                width="180">
            </el-table-column>
            <el-table-column
                    prop="upload_time"
                    label="上传时间"
                    sortable
                    :formatter="judgeDate"
                    width="220">
            </el-table-column>
            <el-table-column
                    prop="type"
                    label="批次"
                    sortable
                    :formatter="judgeType"
                    width="100">
            </el-table-column>
            <el-table-column
                    prop="state"
                    label="是否删除"
                    :formatter="judgeState"
                    width="100">
            </el-table-column>
            <el-table-column label="操作" >
                <template slot-scope="scope" v-if="scope.row.state==0">
                    <el-button
                            :plain="true"
                            size="small"
                            type="info"
                            @click="handleEdit(scope.row)">覆盖录入
                    </el-button>
                    <el-button
                            type="danger"
                            size="small"
                            @click="open(scope.row)">删除
                    </el-button>
                </template>
            </el-table-column>
        </el-table>
        <div align="center" v-show="changeSearch==0">
            <el-pagination
                    @current-change="handleCurrentChange"
                    :current-page.sync="pageData.current_page"
                    :page-size="5"
                    layout="prev, pager, next, jumper"
                    :total='pageData.total'>
            </el-pagination>
        </div>
    </div>
</template>
<style>
    .el-upload-dragger {
        width: 366px;
    }
    .el-breadcrumb {
        display: flex;
        justify-content: center;
    }
    .el-radio-group {
        display: flex;
        align-items: center;
    }
    .el-input {
        width: 193px;
        margin: 0 10px;
    }
</style>
<script type="text/ecmascript-6">
    import { Loading } from 'element-ui'
    export default {
        data(){
            return {
                updateVisible:false,
                randomNumber:0,
                headers:{
                    'X-CSRF-TOKEN':document.head.querySelector('meta[name="X-CSRF-TOKEN"]').content
                },
                upLoadData: {
                    cpyId: '',     //上传标记
                    flag: '',     //同批次标记
                    updateType: 2 //更新方式
                },
                pageData:{           //请求数据模式
                    current_page: 1,
                    total: 0,
                    data: [
                        {
                            file_name:'',
                            operater:'',
                            upload_time:'',
                            mark:'',
                            type:'',
                            state:''
                        }
                    ]
                },
                loadingInstance:'',   //loading
                changeSearch:0,
                radio:0,
                oldRadio:0,
                input:'',
                value:'',
                search:[
                    {
                        file_name:'',
                        operater:'',
                        upload_time:'',
                        mark:'',
                        type:'',
                        state:''
                    }
                ],
                pickerBeginDateBefore:{
                    disabledDate: (time) => {
                        return time.getTime() > Date.now() - 8.64e7;
                    }
                },
            }
        },
        methods: {
            /**
             * 返回格式化后的数据
             * return String
             */
            judgeDate(data){return  new Date(parseInt(data.upload_time) * 1000).toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ");},
            judgeType(data){return data.type?'第二批':'第一批';},
            judgeState(data){return data.state?'是':'否';},
            /**
             * @param val
             * 请求下一页的数据
             */
            handleCurrentChange(val) {
                this.getData('/getlogs?page='+val+'&type='+this.radio,0);
            },
            /**
             * @param row
             * 利用:key="随机数" 解决Dialog数据保持问题
             */
            handleEdit(row) {
                this.randomNumber = parseInt(Math.random()*200+1);
                this.updateVisible = true;
                this.upLoadData.cpyId = row.type;
                this.upLoadData.flag = row.mark;
            },
            /**
             * @param row
             * 删除数据提示框
             */
            open(row) {
                var this_=this;
                this.$confirm('此操作将删除该文件, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'info'
                }).then(() => {
                    this_.loadingInstance = Loading.service({ fullscreen: true });
                    axios.post('/admin/delete',{flag:row.mark}).then(function (response) {
                        this_.loadingInstance.close();
                        this_.getData('/getlogs?page='+this_.pageData.current_page+'&type='+this.radio,0);
                        if(response.data.code==3)
                            this_.$message.error(response.data.result);
                        else
                            this_.$message({type: 'success', message: response.data.result});
                    }).catch(function (response) {console.log(response.data);});

                }).catch(() => {
                    this.$message({type: 'info', message: '已取消删除'});
                });
            },
            /**
             * 数据请求方法
             * @param url
             * @param type  0 分页查询 ;1 条件查询
             */
            getData(url,type){
                var this_ = this;
                this.changeSearch = type;
                axios.get(url)
                .then(function (response) {
                    this_.loadingInstance.close();
                    this_.input="";
                    this_.value="";
                    if (response.data.code==0){
                        if(type==0)
                            this_.pageData=response.data.result;
                        if(type==1)
                            this_.search=response.data.result;
                    }else
                        this_.$message.error(response.data.result);
                }).catch(function (response) {
                    console.log(response.data);
                });

            },
            /**
             * 上传成功后的回调
             * @param response
             * @param file
             * @param fileList
             */
            upSuccess (response, file, fileList) {
                this.loadingInstance.close();
                this.updateVisible = false;
                this.getData('/getlogs?page='+this.pageData.current_page+'&type='+this.radio,0);
                if(response.code==1){
                    this.$message.error(response.result);
                    return;
                }
                if(response.code==2){
                    this.$message({message: response.result, type: 'warning'});
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
            upError (response, file, fileList) {
                this.$message.error('上传失败，请重试！');
            },
            /**
             * 上传前对文件的大小的判断
             * @param file
             * @returns {boolean}
             */
            beforeUp (file) {
                const extension = file.name.split('.')[1] === 'xls'
                const extension2 = file.name.split('.')[1] === 'xlsx'
                const isLt2M = file.size / 1024 / 1024 < 2
                if (!extension && !extension2) {
                    this.$message({message: '上传模板只能是 xls、xlsx 格式!',type: 'warning'});
                }
                if (!isLt2M) {
                    this.$message({message: '上传模板大小不能超过 2MB!',type: 'warning'});
                }
                this.loadingInstance = Loading.service({ fullscreen: true });
                return extension || extension2 && isLt2M
            },
            getRadio(){
                if(this.radio!=this.oldRadio){
                    this.loadingInstance = Loading.service({ fullscreen: true });
                    this.getData('/getlogs?page=1&type='+this.radio,0);
                }
                this.oldRadio=this.radio;

            },
            dateChange(val){
                this.value = val;
            },
            searchLog(){
                if(this.input!=""||this.value!=""){
                    this.loadingInstance = Loading.service({ fullscreen: true });
                    this.getData('/getlog?type='+this.radio+'&input='+this.input+'&value='+this.value,1);
                }
            },
        },
        mounted() {
            this.loadingInstance = Loading.service({ fullscreen: true });
            this.getData('/getlogs?page=1&type='+this.radio,0);
        },
    }
</script>
