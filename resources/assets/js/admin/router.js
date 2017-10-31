import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter)

export default new VueRouter({
    saveScrollPosition: true,
    routes: [
        {
            name: "index",
            path: '/index',
            component: resolve =>void(require(['./Home.vue'], resolve))
        },
        {
            name: "log",
            path: '/log',
            component: resolve =>void(require(['./app/Log.vue'], resolve))
        },
        {
            name: "change",
            path: '/change',
            component: resolve =>void(require(['./app/Change.vue'], resolve))
        },
    ]
})