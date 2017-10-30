<template>
    <div>
        <div class="head">
            <x-header> <span v-if="info.pay_year">{{ info.pay_year + '-' + info.pay_month }} 工资详情</span></x-header>
        </div>
        <div class="wages">
            <group>
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
    .wages{
        margin-top: 60px;
    }
</style>
<script type="text/ecmascript-6">
    import { XHeader, XButton,ButtonTab, ButtonTabItem,Cell,Group,XInput } from 'vux'
    export default {
        components:{
            XHeader,
            XButton,
            ButtonTab,
            ButtonTabItem,
            Cell,
            Group,
            XInput
        },
        data(){
            return {
                wages: [],
                info: []
            }
        },
        computed: {},
        methods: {
            getDetail() {      //当月和上月的工资
                this.$vux.loading.show({
                    text: '加载中'
                })
                axios.post('/detail/get',{job_num:this.$route.params.job_num,month:this.$route.params.month}).then( res => {
                    if(res.data.code == 0){
                        res.data.result.first_pay = JSON.parse(res.data.result.first_pay)
                        this.wages = res.data.result.first_pay
                        this.info = res.data.result
                    }
                    this.$vux.loading.hide()
                })
            },
            getPre() {
                this.$router.push({path:'/pre/'+this.$route.params.job_num+'/'+this.$route.params.mobile})
            },
            getThreeMonth() {       //近三个月的工资
                this.$router.push({path:'/three/'+this.$route.params.job_num+'/'+this.$route.params.mobile})
            },
        },
        mounted() {
            this.getDetail()
        },
    }
</script>
