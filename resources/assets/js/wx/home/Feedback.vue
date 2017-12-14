<template>
    <div class="header">
        <x-header>反馈</x-header>
        <div style="margin-top: -1.33em">
            <group >
                <x-textarea v-model="qu_detail" placeholder="请输入遇到的问题或建议" :max="400" :rows="3"></x-textarea>
                <div style="padding: 0 5px 5px 8px">
                    <li class="show" v-for="(iu, index) in imgUrls">
                        <div class="picture" @click="delImage(index)" :style="'backgroundImage:url('+iu+')'"></div>
                    </li>
                    <div style="display: inline-flex" v-show="isShow">
                        <div class="add" @click.stop="addPic">
                            <input type="file" id="upload" accept="image" @change="upload" style="display: none">
                        </div>
                        <span style="justify-content: center;line-height: 3.3em;padding-left: 15px;color:#B2B2B2;" >添加图片说明</span>
                    </div>
                </div>
            </group>
            <group >
                <cell
                    is-link
                    :title="qu_type"
                    :border-intent="false"
                    :arrow-direction="showContent ? 'up' : 'down'"
                    @click.native="showContent = !showContent"></cell>
                <template v-if="showContent" v-for="option  in opTions">
                    <cell-box :border-intent="false" class="sub-item" @click.native="qu_type=option;showContent=false">{{option}}</cell-box>
                </template>
            </group>
            <group>
                <x-input title="姓名：" v-model="name" placeholder="选填，请输入姓名" is-type="china-name"></x-input>
            </group>
            <group >
                <x-input title="联系电话：" v-model="phone" keyboard="number" is-type="china-mobile" placeholder="选填，便于我们联系你"></x-input>
            </group>
            <x-button type="primary" @click.native="postImg" action-type="button">提交</x-button>
        </div>
    </div>
</template>
<style>
    .header p{
        margin: 0;
    }
</style>
<style scoped>
    .header{
        z-index: 9;
        width: 100%;
        height: 100%;
        top: 0;
        background: white;
        overflow: hidden;
    }
    .weui-btn {
        margin-top: 1.71em;
        width: 90%;
    }
    .weui-cells{
        margin: 0;
    }
    .show, .add{
        display: inline-block;
        width: 60px;
        height: 60px;
        overflow: hidden;
    }
    .show{
        margin: 0 10px 0;
    }
    .add{
        background-image:url('../../../img/wx/add.png');
        background-repeat: no-repeat;
        background-size:100%;
    }
    .picture {
        display: inline-block;
        width: 100%;
        height: 100%;
        overflow: hidden;
        background-position: center center;
        background-repeat: no-repeat;
        background-size: cover;
    }
</style>
<script>
    import { XButton, Cell, CellBox, XHeader, XTextarea, Group, XInput, Confirm, ToastPlugin} from 'vux'
    import Exif from 'exif-js'
    export default {
        components:{
            Cell,
            XHeader,
            XTextarea,
            Group,
            XInput,
            Confirm,
            ToastPlugin,
            XButton,
            CellBox
        },
        data () {
            return {
                picValue:'',
                imgUrls: [],
                count:0,
                isShow:true,
                showContent: false,
                opTions:[
                    '工资加载不出来',
                    '页面卡顿',
                    '工资显示错误'
                ],
                name:'',
                phone:'',
                qu_type:'查不出工资',
                qu_detail:'',
            }
        },
        mounted () {
        },
        methods: {
            addPic: function(e) {
                $('input[type=file]').trigger('click');
                return false;
            },
            upload (e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length||this.count>2) {
                    return;
                }
                this.imgPreview(files[0],e);
                this.count++;
                this.count>=3?this.isShow = false:this.isShow;
                this.$vux.toast.text('图片添加成功', 'top');
            },
            delImage: function(index) {
                let vm = this;
                vm.$vux.confirm.show({
                    content: '取消选择？',
                    onConfirm () {
                        vm.imgUrls.splice(index, 1);
                        vm.count--;
                        vm.count<3?vm.isShow = true:vm.isShow;
                        vm.$vux.toast.text('图片删除成功', 'top');
                    }
                });
            },
            getMobile(){
                //判断数组中是否包含某字符串
                Array.prototype.contains = function(needle) {
                    for (i in this) {
                        if (this[i].indexOf(needle) > 0)
                            return i;
                    }
                    return -1;
                }
                var device_type = navigator.userAgent;//获取userAgent信息
                var md = new MobileDetect(device_type);//初始化mobile-detect
                var os = md.os();//获取系统
                var model = "";
                if (os == "iOS") {//ios系统的处理
                    os = md.os() + md.version("iPhone");
                    model = md.mobile();
                } else if (os == "AndroidOS") {//Android系统的处理
                    os = md.os() + md.version("Android");
                    var sss = device_type.split(";");
                    var i = sss.contains("Build/");
                    if (i > -1) {
                        model = sss[i].substring(0, sss[i].indexOf("Build/"));
                    }
                }
                return model;
            },
            imgPreview (file,e) {
                let self = this;
                let Orientation;
                //去获取拍照时的信息，解决拍出来的照片旋转问题
                Exif.getData(file, function(){

                    Orientation = Exif.getTag(this, 'Orientation');
                });
                // 看支持不支持FileReader
                if (!file || !window.FileReader) return;

                if (/^image/.test(file.type)) {
                    // 创建一个reader
                    let reader = new FileReader();
                    // 将图片2将转成 base64 格式
                    reader.readAsDataURL(file);
                    // 读取成功后的回调
                    reader.onloadend = function () {
                        let result = this.result;
                        let img = new Image();
                        img.src = result;
                        //判断图片是否大于100K,是就直接上传，反之压缩图片
                        if (this.result.length <= (100 * 1024)) {
                            self.imgUrls.push(this.result);
                        }else {
                            img.onload = function () {
                                let data = self.compress(img,Orientation);
                                self.imgUrls.push(data);
                            }
                        }
                        e.target.value = null;
                    }
                }

            },
            postImg () {
                let vm = this;
                axios.post('/feedback', {
                    job_num: vm.$route.query.jobNumber,
                    name: vm.name,
                    phone:vm.phone,
                    phone_model:vm.getMobile(),
                    qu_type:vm.qu_type,
                    qu_detail:vm.qu_detail,
                    img_path:vm.imgUrls
                })
                .then(function (response) {
                    if(response.data.code==0){
                        vm.$vux.toast.show({
                            text: response.data.result,
                            onHide () {
                                vm.$router.push({path: '/'+vm.$route.query.jobNumber+'/'+vm.$route.query.jobYear});
                            }
                        })
                    }else {
                        vm.$vux.toast.test(response.data.result);
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            rotateImg (img, direction,canvas) {
                //最小与最大旋转方向，图片旋转4次后回到原方向
                const min_step = 0;
                const max_step = 3;
                if (img == null)return;
                //img的高度和宽度不能在img元素隐藏后获取，否则会出错
                let height = img.height;
                let width = img.width;
                let step = 2;
                if (step == null) {
                    step = min_step;
                }
                if (direction == 'right') {
                    step++;
                    //旋转到原位置，即超过最大值
                    step > max_step && (step = min_step);
                } else {
                    step--;
                    step < min_step && (step = max_step);
                }
                //旋转角度以弧度值为参数
                let degree = step * 90 * Math.PI / 180;
                let ctx = canvas.getContext('2d');
                switch (step) {
                    case 0:
                        canvas.width = width;
                        canvas.height = height;
                        ctx.drawImage(img, 0, 0);
                        break;
                    case 1:
                        canvas.width = height;
                        canvas.height = width;
                        ctx.rotate(degree);
                        ctx.drawImage(img, 0, -height);
                        break;
                    case 2:
                        canvas.width = width;
                        canvas.height = height;
                        ctx.rotate(degree);
                        ctx.drawImage(img, -width, -height);
                        break;
                    case 3:
                        canvas.width = height;
                        canvas.height = width;
                        ctx.rotate(degree);
                        ctx.drawImage(img, -width, 0);
                        break;
                }
            },
            compress(img,Orientation) {
                let canvas = document.createElement("canvas");
                let ctx = canvas.getContext('2d');
                //瓦片canvas
                let tCanvas = document.createElement("canvas");
                let tctx = tCanvas.getContext("2d");
                let initSize = img.src.length;
                let width = img.width;
                let height = img.height;
                //如果图片大于四百万像素，计算压缩比并将大小压至400万以下
                let ratio;
                if ((ratio = width * height / 4000000) > 1) {
                    console.log("大于400万像素")
                    ratio = Math.sqrt(ratio);
                    width /= ratio;
                    height /= ratio;
                } else {
                    ratio = 1;
                }
                canvas.width = width;
                canvas.height = height;
                //        铺底色
                ctx.fillStyle = "#fff";
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                //如果图片像素大于100万则使用瓦片绘制
                let count;
                if ((count = width * height / 1000000) > 1) {
                    count = ~~(Math.sqrt(count) + 1); //计算要分成多少块瓦片
                    //            计算每块瓦片的宽和高
                    let nw = ~~(width / count);
                    let nh = ~~(height / count);
                    tCanvas.width = nw;
                    tCanvas.height = nh;
                    for (let i = 0; i < count; i++) {
                        for (let j = 0; j < count; j++) {
                            tctx.drawImage(img, i * nw * ratio, j * nh * ratio, nw * ratio, nh * ratio, 0, 0, nw, nh);
                            ctx.drawImage(tCanvas, i * nw, j * nh, nw, nh);
                        }
                    }
                } else {
                    ctx.drawImage(img, 0, 0, width, height);
                }
                //修复ios上传图片的时候 被旋转的问题
                if(Orientation != "" && Orientation != 1){
                    switch(Orientation){
                        case 6://需要顺时针（向左）90度旋转
                            this.rotateImg(img,'left',canvas);
                            break;
                        case 8://需要逆时针（向右）90度旋转
                            this.rotateImg(img,'right',canvas);
                            break;
                        case 3://需要180度旋转
                            this.rotateImg(img,'right',canvas);//转两次
                            this.rotateImg(img,'right',canvas);
                            break;
                    }
                }
                //进行最小压缩
                let ndata = canvas.toDataURL('image/jpeg', 0.1);
                tCanvas.width = tCanvas.height = canvas.width = canvas.height = 0;
                return ndata;
            },
        }
    }
</script>