
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import App from "./Admin.vue"
import router from './router'
import ElementUI from "element-ui"
import { filters } from './filter'
import 'element-ui/lib/theme-default/index.css'

Object.keys(filters).forEach(key => {
    Vue.filter(key, filters[key])
})

require("es6-promise").polyfill()

Vue.use(ElementUI);

Vue.prototype.send_request = function (meth,url,callback,data=null) {
    var self = this;
    axios({
        'method':meth,
        'url':url,
        'data':data
    }).then(function (response) {
        callback(response,self);
    })
}

Vue.component('remote-script', {

    render: function (createElement) {
        var self = this;
        return createElement('script', {
            attrs: {
                type: 'text/javascript',
                src: this.src
            },
            on: {
                load: function (event) {
                    self.$emit('load', event);
                },
                error: function (event) {
                    self.$emit('error', event);
                },
                readystatechange: function (event) {
                    if (this.readyState == 'complete') {
                        self.$emit('load', event);
                    }
                }
            }
        });
    },

    props: {
        src: {
            type: String,
            required: true
        }
    }
});
/*window.Vue = require('vue');*/

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

/*Vue.component('example', require('./components/Example.vue'));*/

const app = new Vue({
    el: '#app',
    render: h => h(App),
    router
});
