import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter)

export default new VueRouter({
    saveScrollPosition: true,
    routes: [
        //微信路由模块
        {
            name: "wx_home",
            path: '/wx/home',
            component: resolve =>void(require(['../home/Home.vue'], resolve))
        },
    ]
})