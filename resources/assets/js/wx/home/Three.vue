<template>
    <div>
        <div class="head">
            <x-header>工资查询<a :href="'/wx#/query/'+this.$route.params.job_num+'/'+this.$route.params.mobile" slot="right">查询</a></x-header>
            <div class="button-tab">
                <button-tab>
                    <button-tab-item @click.native="getCurrent"> 当月 </button-tab-item>
                    <button-tab-item @click.native="getPre"> 上月</button-tab-item>
                    <button-tab-item selected> 近三月 </button-tab-item>
                    <button-tab-item @click.native="getYear"> 全年 </button-tab-item>
                </button-tab>
            </div>
        </div>
        <div class="wages">
            <group v-for="(item,index) in list" :key="index" :title="item.pay_year+'-'+item.pay_month" @click.native="detail(item.job_num,item.pay_month)">
                <div class="list">
                    <span>工资实发额：</span>
                    <span class="gt-icon"><i>&gt;</i></span>
                    <span class="total"> {{ item.first_pay['工资实发额'] }} </span>
                </div>
            </group>
            <div style="margin-top: 130px;" v-if="more">>
                <load-more :show-loading="false" tip="暂无数据" background-color="#fbf9fe"</load-more>
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
        margin-top: 100px;
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
    import { XHeader, XButton,ButtonTab, ButtonTabItem,Cell,Group,XInput,LoadMore } from 'vux'
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
            getThree() {
                this.$vux.loading.show({
                    text: '加载中'
                })
                axios.post('/three/get',{job_num:this.$route.params.job_num}).then( res => {
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
            getCurrent() {
                this.$router.push({path:'/'+this.$route.params.job_num+'/'+this.$route.params.mobile})
            },
            getPre() {
                this.$router.push({path:'/pre/'+this.$route.params.job_num+'/'+this.$route.params.mobile})
            },
            getYear() {
                this.$router.push({path:'/year/'+this.$route.params.job_num+'/'+this.$route.params.mobile})
            },
        },
        mounted() {
            this.getThree()
        },
    }
</script>
