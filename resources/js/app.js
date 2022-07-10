import './bootstrap';
window.Vue = require('vue').default;

import Vue from 'vue'

Vue.component('app', require('./components/App').default)

const app = new Vue({
    el: '#app',
})
