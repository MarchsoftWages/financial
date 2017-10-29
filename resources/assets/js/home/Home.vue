<template>
    <div>
        <div class="head">
            <x-header>工资查询</x-header>
            <div class="button-tab">
                <button-tab>
                    <button-tab-item selected> 当月 </button-tab-item>
                    <button-tab-item> 上月</button-tab-item>
                    <button-tab-item> 近三月 </button-tab-item>
                    <button-tab-item> 全年 </button-tab-item>
                </button-tab>
            </div>
        </div>
        <div class="wages">
            <group>
                <x-input title='岗位工资:' value="123" readonly text-align="right"></x-input>
                <x-input title='薪级工资:' value="123" readonly text-align="right"></x-input>
                <x-input title='保留福补:' value="123" readonly text-align="right"></x-input>
                <x-input title='基础绩效:' value="123" readonly text-align="right"></x-input>
                <x-input title='奖励绩效:' value="123" readonly text-align="right"></x-input>
                <x-input title='预增发:' value="123" readonly text-align="right"></x-input>
                <x-input title='文明奖:' value="123" readonly text-align="right"></x-input>
                <x-input title='预发(调):' value="123" readonly text-align="right"></x-input>
                <x-input title='应发合计:' value="123" readonly text-align="right"></x-input>
                <x-input title='公积金:' value="123" readonly text-align="right"></x-input>
                <x-input title='公积金:' value="123" readonly text-align="right"></x-input>
                <x-input title='公积金:' value="123" readonly text-align="right"></x-input>
                <x-input title='公积金:' value="123" readonly text-align="right"></x-input>
                <x-input title='公积金:' value="123" readonly text-align="right"></x-input>
                <x-input title='公积金:' value="123" readonly text-align="right"></x-input>
                <x-input title='公积金:' value="123" readonly text-align="right"></x-input>
                <x-input title='公积金:' value="123" readonly text-align="right"></x-input>
                <x-input title='公积金:' value="123" readonly text-align="right"></x-input>
                <x-input title='公积金:' value="123" readonly text-align="right"></x-input>
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

            }
        },
        computed: {},
        methods: {

        },
        mounted() {

        },
    }
</script>
