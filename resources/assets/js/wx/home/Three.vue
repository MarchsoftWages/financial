<template>
    <div>
        <div class="head">
            <x-header>工资查询</x-header>
            <div class="button-tab">
                <button-tab>
                    <button-tab-item @click.native="getCurrentPrev(1)"> 当月 </button-tab-item>
                    <button-tab-item @click.native="getCurrentPrev(2)"> 上月</button-tab-item>
                    <button-tab-item selected> 近三月 </button-tab-item>
                    <button-tab-item> 全年 </button-tab-item>
                </button-tab>
            </div>
        </div>
        <div class="wages">
            <group title="七月工资">
                <div class="list">
                    <span>工资实发额：</span>
                    <span class="gt-icon"><i>&gt;</i></span>
                    <span class="total">1234.5</span>
                </div>
            </group>
            <group title="七月工资">
                <div class="list">
                    <span>工资实发额：</span>
                    <span class="gt-icon"><i>&gt;</i></span>
                    <span class="total">1234.5</span>
                </div>
            </group>
            <group title="七月工资">
                <div class="list">
                    <span>工资实发额：</span>
                    <span class="gt-icon"><i>&gt;</i></span>
                    <span class="total">1234.5</span>
                </div>
            </group>
            <group title="七月工资">
                <div class="list">
                    <span>工资实发额：</span>
                    <span class="gt-icon"><i>&gt;</i></span>
                    <span class="total">1234.5</span>
                </div>
            </group>
            <group title="七月工资">
                <div class="list">
                    <span>工资实发额：</span>
                    <span class="gt-icon"><i>&gt;</i></span>
                    <span class="total">1234.5</span>
                </div>
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
                wages:[],
            }
        },
        computed: {},
        methods: {
            getThree() {
                this.$vux.loading.show({
                    text: '加载中'
                })
                axios.post('/three/get',{job_num:this.$route.params.job_num,}).then( res => {
                    if(res.data.code == 0){
                        res.data.result.first_pay = JSON.parse(res.data.result.first_pay)
                        this.wages = res.data.result.first_pay
                        console.log(this.wages)
                    }
                    this.$vux.loading.hide()
                })
            },
            getCurrentPrev(flag) {
                if(flag == 1){
                    this.$router.push({path:'/'+this.$route.params.job_num+'/'+this.$route.params.mobile})
                }else {
                    this.$router.push({path:'/pre/'+this.$route.params.job_num+'/'+this.$route.params.mobile})
                }
            }
        },
        mounted() {
            this.getThree()
        },
    }
</script>
