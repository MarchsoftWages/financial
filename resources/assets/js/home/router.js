import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter)

export default new VueRouter({
    saveScrollPosition: true,
    routes: [
        {
            name: "index",
            path: '/',
            component: resolve =>void(require(['./Home.vue'], resolve))
        },
        {
            name: "index",
            path: '/log',
            component: resolve =>void(require(['./app/Log.vue'], resolve))
        },
        {
            name: "index",
            path: '/change',
            component: resolve =>void(require(['./app/Change.vue'], resolve))
        },
        //微信路由模块
        {
            name: "wx_home",
            path: '/wx/home',
            component: resolve =>void(require(['../home/Home.vue'], resolve))
        },
    ]
})