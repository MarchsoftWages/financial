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
            <el-input v-model="input" placeholder="请输入工号|手机号|姓名"></el-input>
            <el-button type="primary" icon="search" @click="searchFb">搜索</el-button>
        </el-breadcrumb>
        <el-table
                height="460"
                :data="processData()"
                border
                style="width: 100%"
                :default-sort = "{prop: 'id', order: 'descending'}">
            <el-table-column
                    prop="job_num"
                    label="工号"
                    width="100">
            </el-table-column>
            <el-table-column
                    prop="name"
                    label="姓名"
                    width="140">
            </el-table-column>
            <el-table-column
                    prop="phone"
                    label="电话"
                    width="140">
            </el-table-column>
            <el-table-column
                    prop="phone_model"
                    label="系统|手机型号"
                    width="200">
            </el-table-column>
            <el-table-column
                    prop="qu_type"
                    label="问题类型"
                    width="160">
            </el-table-column>
            <el-table-column
                    prop="qu_detail"
                    label="问题详情"
                    width="200">
            </el-table-column>
            <el-table-column label="操作" >
                <template slot-scope="scope" v-if="scope.row.id!=''">
                    <el-button
                            :plain="true"
                            size="small"
                            type="info"
                            @click="handleRead(scope.row.id)">详情
                    </el-button>
                    <el-button
                            type="danger"
                            size="small"
                            @click="deleteFb(scope.row.id)">删除
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
    .el-input {
        width: 333px;
        margin: 0 10px;
    }
    .el-select .el-input {
        width: 180px;
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
                            img_path:''
                        }
                    ]
                },
                loading:'',   //loading
                size:5,
                input:'',
                value:'',
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
                            arr[key] = this.suBstr(data[index][key],16);
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
                if(this.input!=""||this.value!=""){
                    this.getFbdata('/searFbinfo?size='+this.size+'&page=1&input='+this.input+'&value='+this.value);
                }else {
                    this.getFbdata('/getFbinfo?size='+this.size+'&page='+this.pageData.current_page);
                }
            },
            handleRead(id){
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
                vm.$router.push({
                    path: '/fb/fb_read',
                    query: {
                        read_info:JSON.stringify(vm.read_det),
                    }
                });
            },
            deleteFb(id){
                console.log(id);
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

