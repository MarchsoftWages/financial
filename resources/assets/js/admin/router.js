import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter)

export default new VueRouter({
    saveScrollPosition: true,
    routes: [
        {
            name: "工资导入",
            path: '/',
            component: resolve =>void(require(['./Home.vue'], resolve)),
        },
        {
            name: "日志查看",
            path: '/log',
            component: resolve =>void(require(['./app/Log.vue'], resolve))
        },
        {
            name: "意见反馈",
            path: '/fb',
            component: resolve =>void(require(['./app/Feedback.vue'], resolve))
        },
        {
            name: "修改密码",
            path: '/change',
            component: resolve =>void(require(['./app/Change.vue'], resolve))
        },
        
    ]
})