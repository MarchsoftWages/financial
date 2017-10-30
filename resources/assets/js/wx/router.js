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
        },
        {
            name: "year",
            path: '/year/:job_num/:mobile',
            component: resolve =>void(require(['./home/Year.vue'], resolve))
        },
        {
            name: "detail",
            path: '/detail/:job_num/:month',
            component: resolve =>void(require(['./home/Detail.vue'], resolve))
        },
        {
            name: "query",
            path: '/query/:job_num/:mobile',
            component: resolve =>void(require(['./home/Query.vue'], resolve))
        },
        {
            name: "list",
            path: '/list/:job_num/:start/:end',
            component: resolve =>void(require(['./home/List.vue'], resolve))
        },
    ]
})