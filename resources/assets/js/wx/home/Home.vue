<template>
    <div>
        <div class="head">
            <x-header :left-options="{showBack: false}">工资查询
                <a style="text-decoration: none" slot="right" @click="feedUrl">反馈</a>
            </x-header>
            <div class="button-tab">
                <i class="self-icon" @click="leftChange">&lt;</i>
                <scroller lock-y :scrollbar-x=false>
                    <div class="box" id="test1" >
                        <checker v-model="demo1Required" @on-change="selectChange" radio-required default-item-class="demo1-item" selected-item-class="demo1-item-selected">
                            <span  v-for="i in end-start+1">
                                <checker-item :value="start + i - 1"> {{ start + i -1 }} </checker-item>
                            </span>
                        </checker>
                    </div>
                </scroller>
            </div>
            <div content="show" v-if="data_list.length != 0">
                <div class="total">
                    <div class="year-total">
                        <span class="total-title"> {{ total }} </span><br>
                        <span class="title-font">全年实发额(总):</span>
                    </div>
                    <div class="year-should">
                        <span class="should-title"> {{ should }} </span><br>
                        <span class="title-font">全年应发合计(总):</span>
                    </div>
                    <div class="year-except">
                        <span class="should-title"> {{ except }} </span><br>
                        <span class="title-font">全年扣发合计(总):</span>
                    </div>
                </div>
                <div class="content">
                    <div class="wages-list" v-for="(item,index) in data_list" :key="index" :id="'index-'+index">
                        <group>
                            <x-input :title=" item['first'].pay_year + '-' + item['first'].pay_month + '工资实发额:'" :value="item['first'].wages['工资实发额']" readonly text-align="right"></x-input>
                            <div class="test">
                                    <cell
                                        :title="'应发合计： '+ item['first'].wages['应发合计']"
                                        is-link
                                        :border-intent="false"
                                        :arrow-direction="item.showContent ? 'up' : 'down'"
                                        @click.native="clickShould(item,'#index-'+index)"></cell>
                                    <template v-if="item.showContent">
                                        <cell-form-preview :border-intent="false" :list="item['first'].list"></cell-form-preview>
                                    </template>
                                <cell
                                    :id="'second-'+index"
                                    :title="'扣发合计：'+item['first'].wages['扣发合计']"
                                    is-link
                                    :border-intent="false"
                                    :arrow-direction="item.showContent1 ? 'up' : 'down'"
                                    @click.native="clickExcept(item,'#second-'+index)"></cell>
                                <template v-if="item.showContent1">
                                    <cell-form-preview :border-intent="false" :list="item['first'].list1"></cell-form-preview>
                                </template>
                                <cell
                                    :id="'special-'+index"
                                    v-if="item['second'] != undefined"
                                    :title="item['second'] == undefined ? '特殊发放： 无' : '特殊发放： ' + item['second'].wages['合计']"
                                    is-link
                                    :border-intent="false"
                                    :arrow-direction="item.showContent2 ? 'up' : 'down'"
                                    @click.native="clickSpecial(item,'#special-'+index)"></cell>
                                <template v-if="item.showContent2 && item['second'] != undefined">
                                    <cell-form-preview :border-intent="false" :list="item['second'].list"></cell-form-preview>
                                </template>
                            </div>
                        </group>
                    </div>
                </div>
            </div>
            <div style="margin-top: 40px;" v-if="data_list.length == 0">
                <load-more :show-loading="false" tip="还未导入数据" background-color="#fbf9fe"></load-more>
            </div>
        </div>
    </div>
</template>
<style>
    .total .weui-label{
        width: 150px;
    }
    .wages-list .weui-label{
        width: 160px;
    }
    .wages-list p{
        margin: 0;
    }
    .test .weui-cell{
        padding: 10px 15px 10px 40px;
    }
    .wages-list .weui-input{
        color: #eea729;
    }
    .demo1-item-selected{
        border-color: #ff4a00;
        background-color: #fee4d5!important;
        color: #ff5001;
    }
</style>
<style scoped>
    .head{
        z-index: 9;
        width: 100%;
        top: 0;
        background: white;
        overflow: hidden;
    }
    .button-tab{
        position: relative;
        margin-top: 100px;
        width: 85%;
        margin: 0 auto;
        margin-top: 13px;
    }
    .self-icon{
        position: absolute;
        left: -23px;
        top: -15%;
        font-size: 2rem;
        color: #ff4a00;
    }
    .button-tab a{
        text-decoration: none;
    }
    .wages{
        margin-top: 170px;
    }
    .demo1-item {
        width: 100px;
        height: 28px;
        line-height: 26px;
        text-align: center;
        border-radius: 3px;
        border: 1px solid #ccc;
        background-color: #fff;
        margin-right: 6px;
    }
    .demo1-item-selected {
        background: #ffffff url('../../../img/wx/bg.png') no-repeat right bottom;
        border-color: #ff4a00;
    }
    .box {
        white-space:nowrap;
        position: relative;
        height: 30px;
    }
    .total {
        padding: 10px;
    }
    .year-total{
        width: 50%;
        height: 121px;
        border:1px solid #e3e3e3;
        border-right: none;
        float: left;
        text-align: center;
    }
    .year-should{
        width: 48%;
        height: 60px;
        border:1px solid #e3e3e3;
        border-bottom: none;
        overflow: hidden;
        text-align: center;
    }
    .year-except{
        width: 48%;
        height: 60px;
        border:1px solid #e3e3e3;
        overflow: hidden;
        text-align: center;
        color: #eea729;
    }
    .total-title{
        margin-top: 30px;
        font-size: 30px;
        display: inline-block;
        color: #eea729;
    }
    .should-title{
        font-size: 24px;
        display: inline-block;
        color: #eea729;
        padding-top: 4px;
    }
    .title-font{
        color: #666;
        font-size: 12px;
    }
    .test{
        /*display: none;*/
    }
</style>
<script type="text/ecmascript-6">
    import { XHeader, XButton,Cell,Group,XInput,LoadMore,Scroller,Checker, CheckerItem,CellFormPreview } from 'vux'
    export default {
        components:{
            XHeader,
            XButton,
            Cell,
            Group,
            XInput,
            LoadMore,
            Scroller,
            Checker,
            CheckerItem,
            CellFormPreview
        },
        data(){
            return {
                lists:[],
                more: false,
                stringValue: '0',
                demo1Required: new Date().getFullYear(),
                start: 2010,
                end: new Date().getFullYear(),
                total: 0,
                should: 0,
                except: 0,
                data_list: [],
            }
        },
        computed: {},
        methods: {
            get_year() {
                this.$vux.loading.show({
                    text: '加载中'
                })
                axios.post('/year/get',{
                    job_num:this.$route.params.job_num,
                    year: this.demo1Required
                }).then( res => {
                    document.getElementById('test1').style.transform = 'translate(-'+(this.end - this.start + 1 - 3)*110+'px, 0)';
                    if(res.data.code == 0){
                        let data1 = []
                        let data = []
                        for (let i in res.data.result){
                            data.push(res.data.result[i]['pay_month'])
                        }
                        let year_total = 0
                        let year_should = 0
                        let year_except = 0

                        for(let i in data){
                            if(data[i]['first'] != undefined) {
                                data[i]['first'].wages = JSON.parse(data[i]['first'].wages)
                            }
                            if(data[i]['second'] != undefined){
                                data[i]['second'].wages = JSON.parse(data[i]['second'].wages)
                            }
                            if(data[i]['first']==undefined) break;
                            //正常工资发放
                            year_total = year_total + data[i]['first'].wages['工资实发额']
                            year_should = year_should + data[i]['first'].wages['应发合计']
                            year_except = year_except + data[i]['first'].wages['扣发合计']
                            data[i]['first'].list = []        //实发金额详情
                            data[i]['first'].list.push({label:'岗位工资',value: data[i]['first'].wages['岗位工资']})
                            data[i]['first'].list.push({label:'薪级工资',value: data[i]['first'].wages['薪级工资']})
                            data[i]['first'].list.push({label:'教补',value: data[i]['first'].wages['教补']})
                            data[i]['first'].list.push({label:'绩效工资',value: data[i]['first'].wages['绩效工资']})
                            data[i]['first'].list.push({label:'住房补',value: data[i]['first'].wages['住房补']})
                            data[i]['first'].list.push({label:'保留福补',value: data[i]['first'].wages['保留福补']})
                            data[i]['first'].list.push({label:'基础绩效',value: data[i]['first'].wages['基础绩效']})
                            data[i]['first'].list.push({label:'预增发',value: data[i]['first'].wages['预增发']})
                            data[i]['first'].list.push({label:'奖励绩效',value: data[i]['first'].wages['奖励绩效']})
                            data[i]['first'].list.push({label:'离退休费',value: data[i]['first'].wages['离退休费']})
                            data[i]['first'].list.push({label:'历次增离退休费',value: data[i]['first'].wages['历次增离退休费']})
                            data[i]['first'].list.push({label:'生活补贴',value: data[i]['first'].wages['生活补贴']})
                            data[i]['first'].list.push({label:'护理',value: data[i]['first'].wages['护理']})
                            data[i]['first'].list.push({label:'文明奖',value: data[i]['first'].wages['文明奖']})
                            data[i]['first'].list.push({label:'遗补',value: data[i]['first'].wages['遗补']})
                            data[i]['first'].list.push({label:'预发(调)',value: data[i]['first'].wages['预发调']})

                            data[i]['first'].list1 = []       //扣发金额详情
                            data[i]['first'].list1.push({label:'房租',value: data[i]['first'].wages['房租']})
                            data[i]['first'].list1.push({label:'暖气费',value: data[i]['first'].wages['暖气费']})
                            data[i]['first'].list1.push({label:'暖气开口费',value: data[i]['first'].wages['暖气开口费']})
                            data[i]['first'].list1.push({label:'水电费',value: data[i]['first'].wages['水电费']})
                            data[i]['first'].list1.push({label:'公积金',value: data[i]['first'].wages['公积金']})
                            data[i]['first'].list1.push({label:'失业保险',value: data[i]['first'].wages['失业保险']})
                            data[i]['first'].list1.push({label:'贷款本息',value: data[i]['first'].wages['贷款本息']})
                            data[i]['first'].list1.push({label:'其他扣发',value: data[i]['first'].wages['其他扣发']})
                            data[i]['first'].list1.push({label:'个人所得税',value: data[i]['first'].wages['个人所得税']})

                            //特殊发放工资
                            if(data[i]['second'] != undefined){
                                data[i]['second'].list = []
                                data[i]['second'].list.push({label:'教学奖励',value:data[i]['second'].wages['教学奖励']})
                                data[i]['second'].list.push({label:'科研奖励',value:data[i]['second'].wages['科研奖励']})
                                data[i]['second'].list.push({label:'岗位津贴、慰问金',value:data[i]['second'].wages['岗位津贴慰问金']})
                                data[i]['second'].list.push({label:'补生活补贴',value:data[i]['second'].wages['补生活补贴']})
                                data[i]['second'].list.push({label:'薪级补发',value:data[i]['second'].wages['薪级补发']})
                                data[i]['second'].list.push({label:'补发文明奖',value:data[i]['second'].wages['补发文明奖']})
                                data[i]['second'].list.push({label:'发13年暖气补',value:data[i]['second'].wages['发13年暖气补']})
                                data[i]['second'].list.push({label:'补发住房补贴',value:data[i]['second'].wages['补发住房补贴']})
                                data[i]['second'].list.push({label:'扣12年暖气费',value:data[i]['second'].wages['扣12年暖气费']})
                                data[i]['second'].list.push({label:'精神文明奖',value:data[i]['second'].wages['精神文明奖']})
                                data[i]['second'].list.push({label:'保留福补补发',value:data[i]['second'].wages['保留福补补发']})
                                data[i]['second'].list.push({label:'岗位绩效、超教学科研',value:data[i]['second'].wages['岗位绩效超教学科研']})
                                data[i]['second'].list.push({label:'慰问费',value:data[i]['second'].wages['慰问费']})
                                data[i]['second'].list.push({label:'发14津补贴、绩效调整',value:data[i]['second'].wages['发14津补贴绩效调整']})
                                data[i]['second'].list.push({label:'补增加离退休费',value:data[i]['second'].wages['补增加离退休费']})
                                data[i]['second'].list.push({label:'补预增发',value:data[i]['second'].wages['补预增发']})
                                data[i]['second'].list.push({label:'13年平安奖',value:data[i]['second'].wages['13年平安奖']})
                                data[i]['second'].list.push({label:'14年平安奖',value:data[i]['second'].wages['14年平安奖']})
                                data[i]['second'].list.push({label:'2016预发',value:data[i]['second'].wages['2016预发']})
                                data[i]['second'].list.push({label:'目标考核奖',value:data[i]['second'].wages['目标考核奖']})
                                data[i]['second'].list.push({label:'平安奖',value:data[i]['second'].wages['平安奖']})
                                data[i]['second'].list.push({label:'健康休养费',value:data[i]['second'].wages['健康休养费']})
                                data[i]['second'].list.push({label:'暖气补',value:data[i]['second'].wages['暖气补']})
                                data[i]['second'].list.push({label:'绩效补发',value:data[i]['second'].wages['绩效补发']})
                                data[i]['second'].wages['合计'] = this.toDecimal(data[i]['second'].wages['教学奖励']+data[i]['second'].wages['科研奖励']+
                                data[i]['second'].wages['岗位津贴慰问金']+data[i]['second'].wages['补生活补贴']+
                                data[i]['second'].wages['薪级补发']+data[i]['second'].wages['补发文明奖']+
                                data[i]['second'].wages['发13年暖气补']+data[i]['second'].wages['补发住房补贴']+
                                data[i]['second'].wages['扣12年暖气费']+data[i]['second'].wages['精神文明奖']+
                                data[i]['second'].wages['保留福补补发']+data[i]['second'].wages['岗位绩效超教学科研']+
                                data[i]['second'].wages['慰问费']+data[i]['second'].wages['发14津补贴绩效调整']+
                                data[i]['second'].wages['补增加离退休费']+data[i]['second'].wages['补预增发']+
                                data[i]['second'].wages['13年平安奖']+data[i]['second'].wages['14年平安奖']+
                                data[i]['second'].wages['2016预发']+data[i]['second'].wages['目标考核奖']+
                                data[i]['second'].wages['平安奖']+data[i]['second'].wages['健康休养费'])
                            }

                        }
                        this.total = this.toDecimal(year_total)
                        this.should = this.toDecimal(year_should)
                        this.except = this.toDecimal(year_except)
                        this.data_list = data
                    }else{
                        this.total = 0
                        this.should = 0
                        this.except = 0
                        this.data_list = []
                    }
                    this.$vux.loading.hide()
                })
            },
            selectChange(value) {
                this.demo1Required = value
                this.get_year()
            },
            feedUrl(){
                let vm = this;
                vm.$router.push({
                    path: '/job_fb',
                    query: {
                        jobNumber:vm.$route.params.job_num,
                        jobYear:this.demo1Required
                    }
                });
            },

            //格式化两位小数
            toDecimal(x) {
                var f = parseFloat(x);
                if (isNaN(f)) {
                    return;
                }
                f = Math.round(x*100)/100;
                return f;
            },
            leftChange() {
                document.getElementById('test1').style.transform = 'translateX(0px)';
            },
            //点击应发合计展开的锚点链接
            clickShould(item,el) {
                item.showContent = !item.showContent
                //var anchor = this.$el.querySelector(el)
                setTimeout(()=>{
                    //$(document).scrollTop(anchor.offsetTop)
                    //$(document).scrollTop($(el).offset().top)
                    $('html, body').animate({
                        scrollTop: $(el).offset().top
                    }, 500);
                },0)

            },
            //点击扣发合计展开的锚点链接
            clickExcept(item,el) {
                item.showContent1 = !item.showContent1
                //var anchor = this.$el.querySelector(el)
                setTimeout(()=>{
                    //$(document).scrollTop($(el).offset().top)
                    $('html, body').animate({
                        scrollTop: $(el).offset().top
                    }, 500);
                },0)
            },
            //点击特殊发放展开的锚点链接
            clickSpecial(item,el) {
                item.showContent2 = !item.showContent2
                setTimeout(()=>{
                    $('html, body').animate({
                        scrollTop: $(el).offset().top
                    }, 500);
                },0)
            },
        },
        mounted() {
            this.get_year();
            $('.box').css('width',(this.end - this.start + 1)*110 + 'px');
        },
    }
</script>