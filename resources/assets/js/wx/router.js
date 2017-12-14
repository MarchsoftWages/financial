import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter)

export default new VueRouter({
    saveScrollPosition: true,
    routes: [
        //微信路由模块
        {
            name: "wx_home",
            path: '/:job_num/:month',
            component: resolve =>void(require(['./home/Home.vue'], resolve))
        },
        {
            name: "fb_home",
            path: '/job_fb',
            component: resolve =>void(require(['./home/Feedback.vue'], resolve))
        }
    ]
})