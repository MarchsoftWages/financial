<template>
    <div class="el-table-self">
        <el-dialog title="覆盖上传" :visible.sync="updateVisible" :key="randomNumber">
            <el-upload
                    class="upload-demo" drag
                    action=""
                    multiple>
                <i class="el-icon-upload"></i>
                <div class="el-upload__text">将文件拖到此处，或<em>点击上传</em></div>
                <div class="el-upload__tip" slot="tip">只能上传xlsx/xls文件，且不超过2mb</div>
            </el-upload>
        </el-dialog>
        <el-table
            :data="pageData.data"
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
                    width="180">
            </el-table-column>
            <el-table-column
                    prop="type"
                    label="批次"
                    sortable
                    width="120">
            </el-table-column>
            <el-table-column
                    prop="state"
                    label="是否删除"
                    width="120">
            </el-table-column>
            <el-table-column label="操作" >
                <template slot-scope="scope">
                    <el-button
                            :plain="true"
                            size="small"
                            type="info"
                            @click="handleEdit()">覆盖录入
                    </el-button>
                    <el-button
                            type="danger"
                            size="small"
                            @click="handleDelete()">删除
                    </el-button>
                </template>
            </el-table-column>
        </el-table>
        <div align="center">
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

</style>
<script type="text/ecmascript-6">
    import { Loading } from 'element-ui'
    export default {
        data(){
            return {
                updateVisible:false,
                randomNumber:0,
                pageData:{
                    current_page: 1,
                    total: 0,
                    data: [
                        {
                            file_name:'',
                            operater:'',
                            upload_time:'',
                            type:'',
                            state:''
                        }
                    ]
                }
            }
        },
        computed: {},
        methods: {
            handleCurrentChange(val) {
                this.getData('/getlogs?page='+val);
            },
            handleEdit() {
                this.randomNumber = parseInt(Math.random()*200+1);
                this.updateVisible = true;
            },
            handleDelete() {
                console.log();
            },
            getData(url){
                var this_ = this;
                axios.get(url)
                .then(function (response) {
                    if (response.data.code==0){
                        this_.pageData=response.data.result;
                    }else
                        this_.$message.error(response.data.result);
                    console.log(response.data.result)
                })
                .catch(function (response) {
                    console.log(response.data);
                });

            }
        },
        mounted() {
            this.getData('/getlogs?page=1');
        },
    }
</script>
