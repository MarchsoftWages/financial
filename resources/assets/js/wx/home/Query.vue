<template>
    <div>
        <div class="head">
            <x-header>工资查询</x-header>
        </div>
        <div class="wages">
            <group>
                <datetime v-model="start" format="YYYY-MM" title="开始时间"></datetime>
                <datetime v-model="end" format="YYYY-MM" title="结束时间"></datetime>
                    <selector ref="defaultValueRef" title="批次" :options="select" v-model="value"></selector>
            </group>
            <div class="query-button">
                <x-button type="primary" :disabled="dis" :show-loading="submit_loading" @click.native="query">查询</x-button>
            </div>
        </div>
    </div>
</template>
<style>
    .wages .weui-cell_access{
        height:40px;
        padding: 5px 15px;
    }
    .wages a{
        text-decoration: none;
    }
</style>
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
    .query-button{
        padding: 25px 10px;
    }
</style>
<script type="text/ecmascript-6">
    import { XHeader, XButton,Datetime,Group,Selector  } from 'vux'
    export default {
        components:{
            XHeader,
            XButton,
            Datetime,
            Group,
            Selector
        },
        data(){
            return {
                start: '',
                end: '',
                submit_loading: false,
                dis: true,
                select: [{key:0,value:'第一批'},{key: 1,value: '第二批'}],
                value: 0,
            }
        },
        watch: {
            start:function (value,old) {
                if(value != '' && this.end != ''){
                    this.dis = false
                }else {
                    this.dis = true
                }
            },
            end:function (value,old) {
                if(value != '' && this.start != ''){
                    this.dis = false
                }else {
                    this.dis = true
                }
            }
        },
        computed: {},
        methods: {
            query() {
                this.$router.push({path:'/list/'+this.$route.params.job_num+'/'+this.$route.params.mobile+'/'+this.start+'/'+this.end+'/'+this.value})
            },
        },
        mounted() {

        },
    }
</script>
