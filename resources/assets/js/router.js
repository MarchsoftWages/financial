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
            component: resolve =>void(require(['./admin/Log.vue'], resolve))
        },
        {
            name: "index",
            path: '/change',
            component: resolve =>void(require(['./admin/Change.vue'], resolve))
        },
    ]
})