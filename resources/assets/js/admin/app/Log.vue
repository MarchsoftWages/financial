<template>
    <div>
        <el-table
            :data="tableData"
            border
            style="width: 100%"
            :default-sort = "{prop: 'upload_time', order: 'descending'}">
            <el-table-column
                prop="file_name"
                label="文件名"
                sortable
                width="180">
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
                    width="180">
            </el-table-column>
            <el-table-column
                    prop="state"
                    label="是否删除"
                    width="180">
            </el-table-column>
            <el-table-column label="操作" >
                <template scope="scope">
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
    </div>
</template>
<style scoped>

</style>
<script type="text/ecmascript-6">
    import { Loading } from 'element-ui'
    export default {
        data(){
            return {
                currentPage:"5",
                tableData: [
                    {
                        file_name:'',
                        operater:'',
                        upload_time:'',
                        type:'',
                        state:''
                    }
                ]

            }
        },
        computed: {},
        methods: {
            handleSizeChange(val) {
                console.log(`每页 ${val} 条`);
            },
            handleCurrentChange(val) {
                console.log(`当前页: ${val}`);
            },
            handleEdit() {
                console.log();
            },
            handleDelete() {
                console.log();
            },
            getData(url){
                var this_ = this;
                axios.get(url)
                .then(function (response) {
                    if (response.data.code==0)
                        this_.tableData=response.data.result.data;
                    else
                        this_.$message.error(response.data.result);

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
