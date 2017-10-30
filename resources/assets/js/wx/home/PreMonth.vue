<template>
    <div>
        <div class="head">
            <x-header>工资查询<a :href="'/wx#/query/'+this.$route.params.job_num+'/'+this.$route.params.mobile" slot="right">查询</a></x-header>
            <div class="button-tab">
                <button-tab>
                    <button-tab-item  @click.native="getCurrent"> 当月 </button-tab-item>
                    <button-tab-item selected> 上月</button-tab-item>
                    <button-tab-item @click.native="getThreeMonth"> 近三月 </button-tab-item>
                    <button-tab-item @click.native="getYear"> 全年 </button-tab-item>
                </button-tab>
            </div>
        </div>
        <div class="wages">
            <group v-if="!more">
                <x-input title='岗位工资:' :value="wages['岗位工资']" readonly text-align="right"></x-input>
                <x-input title='薪级工资:' :value="wages['薪级工资']" readonly text-align="right"></x-input>
                <x-input title='保留福补:' :value="wages['保留福补']" readonly text-align="right"></x-input>
                <x-input title='基础绩效:' :value="wages['基础绩效']" readonly text-align="right"></x-input>
                <x-input title='奖励绩效:' :value="wages['奖励绩效']" readonly text-align="right"></x-input>
                <x-input title='预增发:' :value="wages['预增发']" readonly text-align="right"></x-input>
                <x-input title='文明奖:' :value="wages['文明奖']" readonly text-align="right"></x-input>
                <x-input title='预发(调):' :value="wages['预发（调)']" readonly text-align="right"></x-input>
                <x-input title='应发合计:' :value="wages['应发合计']" readonly text-align="right"></x-input>
                <x-input title='公积金:' :value="wages['公积金']" readonly text-align="right"></x-input>
                <x-input title='失业保险:' :value="wages['失业保险']" readonly text-align="right"></x-input>
                <x-input title='应纳税所得:' :value="wages['应纳税所得']" readonly text-align="right"></x-input>
                <x-input title='扣发合计:' :value="wages['扣发合计']" readonly text-align="right"></x-input>
                <x-input title='工资实发额:' :value="wages['工资实发额']" readonly text-align="right"></x-input>
            </group>
            <div style="margin-top: 130px;" v-if="more">
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
        margin-top: 100px;
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
                wages:[],
                more: false
            }
        },
        computed: {},
        methods: {
            getPre() {      //当月和上月的工资
                this.$vux.loading.show({
                    text: '加载中'
                })
                axios.post('/current/get',{job_num:this.$route.params.job_num,flag:2}).then( res => {
                    if(res.data.code == 0){
                        res.data.result.first_pay = JSON.parse(res.data.result.first_pay)
                        this.wages = res.data.result.first_pay
                    }else {
                        this.more = true
                    }
                    this.$vux.loading.hide()
                })
            },
            getCurrent() {
                this.$router.push({path:'/'+this.$route.params.job_num+'/'+this.$route.params.mobile})
            },
            getThreeMonth() {       //近三个月的工资
                this.$router.push({path:'/three/'+this.$route.params.job_num+'/'+this.$route.params.mobile})
            },
            getYear() {
                this.$router.push({path:'/year/'+this.$route.params.job_num+'/'+this.$route.params.mobile})
            },
        },
        mounted() {
            this.getPre()
        },
    }
</script>
