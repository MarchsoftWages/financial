<template>
    <div class="el-table-self">
        <el-breadcrumb class="el-breadcrumb">
            <el-select v-model="value" @change="searchFb" clearable placeholder="请选择问题类型">
                <el-option
                        v-for="item in options"
                        :key="item.key"
                        :value="item.value">
                </el-option>
            </el-select>
            <el-date-picker
                    v-model="timeData"
                    type="datetime"
                    @change="dateChange"
                    :picker-options="pickerBeginDateBefore"
                    placeholder="选择日期时间">
            </el-date-picker>
            <div class="serach">
                <el-input v-model="input" placeholder="请输入工号|手机号|姓名"></el-input>
            </div>
            <el-button type="primary" icon="search" @click="searchFb">搜索</el-button>
        </el-breadcrumb>
        <el-table
                height="460"
                :data="processData()"
                border
                style="width: 100%"
                :default-sort = "{prop: 'submit_time', order: 'descending'}">
            <el-table-column
                    prop="job_num"
                    label="工号"
                    width="90">
            </el-table-column>
            <el-table-column
                    prop="name"
                    label="姓名"
                    width="110">
            </el-table-column>
            <el-table-column
                    prop="phone"
                    label="电话"
                    width="130">
            </el-table-column>
            <el-table-column
                    prop="phone_model"
                    label="手机型号"
                    >
            </el-table-column>
            <el-table-column
                    prop="qu_type"
                    label="问题类型"
                    width="140">
            </el-table-column>
            <el-table-column
                    prop="qu_detail"
                    label="问题详情"
                    >
            </el-table-column>
            <el-table-column
                    prop="submit_time"
                    label="上传时间"
                    :formatter="judgeDate"
                    width="200">
            </el-table-column>
            <el-table-column
                    label="操作"
                    width="140" >
                <template slot-scope="scope" v-if="scope.row.id!=''">
                    <el-button
                            :plain="scope.row.is_look==1?false:true"
                            size="small"
                            type="info"
                            @click="handleRead(scope.row.id,scope.row.is_look)">{{scope.row.is_look==1?'已看':'详情'}}
                    </el-button>
                    <el-button
                            type="danger"
                            size="small"
                            @click="deleteFb(scope.row.id,scope.row.img_path)">删除
                    </el-button>
                </template>
            </el-table-column>
        </el-table>
        <div class="pagination">
            <el-pagination
                    @size-change="handleSizeChange"
                    @current-change="handleCurrentChange"
                    :current-page.sync="pageData.current_page"
                    :page-sizes="[5, 10, 50]"
                    :page-size="this.size"
                    layout="total, sizes, prev, pager, next, jumper"
                    :total='pageData.total'>
            </el-pagination>
        </div>
    </div>
</template>
<style>
    .el-table-self{
        min-height: 560px;
        background-color: #fff;
        margin: 0 20px;
        border-radius: 1%;
    }
    .el-breadcrumb {
        display: flex;
        justify-content: center;
        padding: 15px 0;
    }
    .serach .el-input {
        width: 333px;
        margin: 0 10px;
    }
    .el-select {
        margin: 0 10px;
    }
    .pagination{
        padding: 10px 0;
    }
    .el-pagination {
        display: flex;
        justify-content: center;
    }
</style>
<script type="text/ecmascript-6">
    import { Loading } from 'element-ui'
    export default {
        data(){
            return {
                pageData:{           //请求数据模式
                    current_page: 1,
                    total: 0,
                    data: [
                        {
                            id:'',
                            job_num:'',
                            name:'',
                            phone:'',
                            phone_model:'',
                            qu_type:'',
                            qu_detail:'',
                            submit_time:'',
                            img_path:'',
                            is_look:'',
                        }
                    ]
                },
                loading:'',   //loading
                size:5,
                input:'',
                value:'',
                timeData:'',
                options:[{
                    key: 1,
                    value: '页面卡顿'
                }, {
                    key: 2,
                    value: '工资加载不出来'
                }, {
                    key: 3,
                    value: '查不出工资'
                }, {
                    key: 4,
                    value: '工资显示错误'
                }],
                read_det:{},
                pickerBeginDateBefore:{
                    disabledDate: (time) => {
                        return time.getTime() > Date.now();
                    }
                },
            }
        },
        methods: {
            /**
             * @param page
             * 请求下一页的数据
             */
            handleCurrentChange(page) {
                this.getFbdata('/getFbinfo?page='+page+'&size='+this.size);
            },
            /**
             * 显示页数
             * @param size
             */
            handleSizeChange(size){
                this.size = size;
                this.getFbdata('/getFbinfo?page='+this.pageData.current_page+'&size='+this.size);
            },
            judgeDate(data){
                return  new Date(parseInt(data.submit_time) * 1000).toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ");
            },
            dateChange(val){
                if(typeof(val)!="undefined"){
                    this.timeData = val;
                }
                this.searchFb();
            },
            /**
             * 数据请求方法
             * @param url
             */
            getFbdata(url){
                var this_ = this;
                axios.get(url)
                    .then(function (response) {
                        this_.loading.close();
                        if (response.data.code==0){
                            this_.pageData=response.data.result;
                        }else
                            this_.$message.error(response.data.result);
                    }).catch(function (response) {
                        console.log(response.data);
                        this_.loading.close();
                });

            },
            processData(){
                let data = this.pageData.data;
                let feedArr = [];
                for(let index in data) {
                    let arr = [];
                    for(let key in data[index]) {
                        if (key == "qu_detail"&&data[index][key]!=null)
                            arr[key] = this.suBstr(data[index][key],14);
                        else
                            arr[key] = data[index][key];
                    }
                    feedArr[index]=arr;
                }
                return feedArr;
            },
            /**
             * 根据条件查找
             */
            searchFb(){
                this.loading = Loading.service({ fullscreen: true });
                if(this.input!=""||this.value!=""||this.timeData!=""){
                    this.getFbdata('/searFbinfo?size='+this.size+'&page=1&input='+this.input+'&value='+this.value+'&timeData='+this.timeData);
                }else {
                    this.getFbdata('/getFbinfo?size='+this.size+'&page='+this.pageData.current_page);
                }
            },
            handleRead(id,isLook){
                let vm = this;
                let data = vm.pageData.data;
                for(let index in data) {
                    if (data[index]["id"]==id) {
                        for(let key in data[index]) {
                            vm.read_det[key] = data[index][key];
                        }
                        break;
                    }
                }
                if(!isLook){
                    axios.post('/update_type', {
                        id:id,
                        type: {is_look:1}
                    })
                    .then(function (response) {
                        if(response.data.code == 1){
                            this.$message({message: '页面打不开',type: 'warning'});
                            return;
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                }
                vm.$router.push({
                    path: '/fb/fb_read',
                    query: {
                        read_info:JSON.stringify(vm.read_det),
                    }
                });
            },
            deleteFb(id,imgArr){
                let vm = this;
                vm.$confirm('此操作将删除该记录, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'info'
                }).then(() => {
                    axios.post('/delete_Fb', {
                        id:id,
                        imgPath: JSON.parse(imgArr)
                    }).then(function (response) {
                        if(response.data.code == 1){
                            vm.$message({message: response.data.result,type: 'warning'});
                            return;
                        }
                        vm.searchFb();
                        vm.$message({type: 'success', message: response.data.result});
                    }).catch(function (response) {
                        console.log(response.data);
                    });
                }).catch(() => {
                    vm.$message({type: 'info', message: '已取消删除'});
                });
            },
            suBstr(str, n) {//字符串截取 包含对中文处理
                if (str.replace(/[\u4e00-\u9fa5]/g, "**").length <= n)
                    return str;
                else {
                    var len = 0;
                    var tmpStr = "";
                    for (var i = 0; i < str.length; i++) {//遍历字符串
                        if (/[\u4e00-\u9fa5]/.test(str[i])) //中文 长度为两字节
                            len += 2;
                        else
                            len += 1;
                        if (len > n)
                            break;
                        else
                            tmpStr += str[i];
                    }
                    return tmpStr + " ...";
                }
            },

        },
        mounted() {
            this.loading = Loading.service({ fullscreen: true });
            this.getFbdata('/getFbinfo?size=5&page=1');
            this.$emit('path',this.$route.path);
        },
    }
</script>

