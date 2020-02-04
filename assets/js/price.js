import Vue from 'vue'

console.log('hello');

const vue = new Vue({
    delimiters: ['{', '}'],
    el: '#vue-price',
    data: {
        message: 'test vue',
    }
})
