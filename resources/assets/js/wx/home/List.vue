<template>
    <div>
        <div class="head">
            <x-header>查询结果</x-header>
        </div>
        <div class="wages">
            <group v-for="(item,index) in list" :key="index" :title="item.pay_year+'-'+item.pay_month" @click.native="detail(item.job_num,item.pay_month)">
                <div class="list">
                    <span>工资实发额：</span>
                    <span class="gt-icon"><i>&gt;</i></span>
                    <span class="total"> {{ item.first_pay['工资实发额'] }} </span>
                </div>
            </group>
            <div style="margin-top: 80px;" v-if="more">
                <load-more :show-loading="false" tip="暂无数据" background-color="#fbf9fe"></load-more>
            </div>
        </div>
    </div>
</template>
<style scoped>
    .head{
        position: fixed;
        z-index: 9;
        width: 100%;
        top: 0;
        background: white;
        overflow: hidden;
    }
    .button-tab{
        margin-top: 100px;
        width: 90%;
        margin: 0 auto;
        margin-top: 13px;
    }
    .button-tab a{
        text-decoration: none;
    }
    .wages{
        margin-top: 60px;
    }
    .list{
        height: 25px;
        width: 100%;
        padding: 13px 9px;
    }
    .total{
        float: right;
        margin-right: 20px;
    }
    .gt-icon{
        float: right;
        margin-right: 19px;
        font-size: 24px;
        margin-top: -7px;
    }
</style>
<script type="text/ecmascript-6">
    import { XHeader, XButton,ButtonTab, ButtonTabItem,Cell,Group,XInput,LoadMore  } from 'vux'
    export default {
        components:{
            XHeader,
            XButton,
            ButtonTab,
            ButtonTabItem,
            Cell,
            Group,
            XInput,
            LoadMore
        },
        data(){
            return {
                list:[],
                more: false
            }
        },
        computed: {},
        methods: {
            getQuery() {
                this.$vux.loading.show({
                    text: '加载中'
                })
                axios.post('/query/get',{
                    job_num:this.$route.params.job_num,
                    start:this.$route.params.start,
                    end:this.$route.params.end,
                }).then( res => {
                    if(res.data.code == 0){
                        this.list = res.data.result
                        for (let i in this.list){
                            this.list[i].first_pay = JSON.parse(this.list[i].first_pay)
                        }
                    }else {
                        this.more = true
                    }
                    this.$vux.loading.hide()
                })
            },
            detail(job_num,month) {
                this.$router.push({path:'/detail/'+job_num+'/'+month})
            },
        },
        mounted() {
            this.getQuery()
        },
    }
</script>
