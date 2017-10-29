import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter)

export default new VueRouter({
    saveScrollPosition: true,
    routes: [
        //微信路由模块
        {
            name: "wx_home",
            path: '/:job_num/:mobile',
            component: resolve =>void(require(['./home/Home.vue'], resolve))
        },
        {
            name: "wx_pre",
            path: '/pre/:job_num/:mobile',
            component: resolve =>void(require(['./home/PreMonth.vue'], resolve))
        },
        {
            name: "three_month",
            path: '/three/:job_num/:mobile',
            component: resolve =>void(require(['./home/Three.vue'], resolve))
        }
    ]
})