
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import Vue from 'vue';

import exam from './components/ExampleComponent'        //示例组件
import MyInfo from './components/MyInfo'

Vue.component('my-info',MyInfo)
const app = new Vue({
    el: '#app',

});
