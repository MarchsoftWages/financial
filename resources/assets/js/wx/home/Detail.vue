<template>
    <div>
        <div class="head">
            <x-header> <span v-if="lists.pay_year">{{ lists.pay_year + '-' + lists.pay_month }} {{ this.$route.params.type == 0 ? '第一批' : '第二批' }} 工资</span></x-header>
        </div>
        <div class="wages">
            <group v-if="!more && lists.type == 0">
                <x-input title='岗位工资:' :value="lists.wages['岗位工资']" readonly text-align="right"></x-input>
                <x-input title='薪级工资:' :value="lists.wages['薪级工资']" readonly text-align="right"></x-input>
                <x-input title='保留福补:' :value="lists.wages['保留福补']" readonly text-align="right"></x-input>
                <x-input title='基础绩效:' :value="lists.wages['基础绩效']" readonly text-align="right"></x-input>
                <x-input title='奖励绩效:' :value="lists.wages['奖励绩效']" readonly text-align="right"></x-input>
                <x-input title='预增发:' :value="lists.wages['预增发']" readonly text-align="right"></x-input>
                <x-input title='文明奖:' :value="lists.wages['文明奖']" readonly text-align="right"></x-input>
                <x-input title='预发(调):' :value="lists.wages['预发调']" readonly text-align="right"></x-input>
                <x-input title='应发合计:' :value="lists.wages['应发合计']" readonly text-align="right"></x-input>
                <x-input title='公积金:' :value="lists.wages['公积金']" readonly text-align="right"></x-input>
                <x-input title='失业保险:' :value="lists.wages['失业保险']" readonly text-align="right"></x-input>
                <x-input title='应纳税所得:' :value="lists.wages['应纳税所得']" readonly text-align="right"></x-input>
                <x-input title='扣发合计:' :value="lists.wages['扣发合计']" readonly text-align="right"></x-input>
                <x-input title='工资实发额:' :value="lists.wages['工资实发额']" readonly text-align="right"></x-input>
            </group>
            <group v-if="!more && lists.type == 1">
                <x-input title='教学奖励:' :value="lists.wages['教学奖励']" readonly text-align="right"></x-input>
                <x-input title='科研奖励:' :value="lists.wages['科研奖励']" readonly text-align="right"></x-input>
                <x-input title='岗位津贴、慰问金:' :value="lists.wages['岗位津贴慰问金']" readonly text-align="right"></x-input>
                <x-input title='绩效补发:' :value="lists.wages['绩效补发']" readonly text-align="right"></x-input>
                <x-input title='暖气补:' :value="lists.wages['暖气补']" readonly text-align="right"></x-input>
                <x-input title='补生活补贴:' :value="lists.wages['补生活补贴']" readonly text-align="right"></x-input>
                <x-input title='薪级补发:' :value="lists.wages['薪级补发']" readonly text-align="right"></x-input>
                <x-input title='补发文明奖:' :value="lists.wages['补发文明奖']" readonly text-align="right"></x-input>
                <x-input title='发13年暖气补:' :value="lists.wages['发13年暖气补']" readonly text-align="right"></x-input>
                <x-input title='补发住房补贴:' :value="lists.wages['补发住房补贴']" readonly text-align="right"></x-input>
                <x-input title='扣12年暖气费:' :value="lists.wages['扣12年暖气费']" readonly text-align="right"></x-input>
                <x-input title='精神文明奖:' :value="lists.wages['精神文明奖']" readonly text-align="right"></x-input>
                <x-input title='保留福补补发:' :value="lists.wages['保留福补补发']" readonly text-align="right"></x-input>
                <x-input title='岗位绩效、超教学科研:' :value="lists.wages['岗位绩效超教学科研']" readonly text-align="right"></x-input>
                <x-input title='慰问费:' :value="lists.wages['慰问费']" readonly text-align="right"></x-input>
                <x-input title='发14津补贴、绩效调整:' :value="lists.wages['发14津补贴绩效调整']" readonly text-align="right"></x-input>
                <x-input title='补增加离退休费:' :value="lists.wages['补增加离退休费']" readonly text-align="right"></x-input>
                <x-input title='补预增发:' :value="lists.wages['补预增发']" readonly text-align="right"></x-input>
                <x-input title='13年平安奖:' :value="lists.wages['13年平安奖']" readonly text-align="right"></x-input>
                <x-input title='14年平安奖:' :value="lists.wages['14年平安奖']" readonly text-align="right"></x-input>
                <x-input title='2016预发:' :value="lists.wages['13年平安奖']" readonly text-align="right"></x-input>
                <x-input title='目标考核奖:' :value="lists.wages['目标考核奖']" readonly text-align="right"></x-input>
                <x-input title='平安奖:' :value="lists.wages['平安奖']" readonly text-align="right"></x-input>
                <x-input title='健康休养费:' :value="lists.wages['健康休养费']" readonly text-align="right"></x-input>
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
    .wages{
        margin-top: 60px;
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
                lists: [],
                more: false
            }
        },
        computed: {},
        methods: {
            getDetail() {      //当月和上月的工资
                this.$vux.loading.show({
                    text: '加载中'
                })
                axios.post('/detail/get',{
                    job_num:this.$route.params.job_num,
                    year: this.$route.params.year,
                    month:this.$route.params.month,
                    type: this.$route.params.type,
                }).then( res => {
                    if(res.data.code == 0){
                        res.data.result.wages = JSON.parse(res.data.result.wages)
                        this.lists = res.data.result
                        this.more = false
                    }else {
                        this.more = true
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
