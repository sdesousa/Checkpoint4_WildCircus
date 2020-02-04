import Vue from 'vue'

const vue = new Vue({
    delimiters: ['{', '}'],
    el: '#vue-price',
    data: {
        plk: 0,
        pla: 0,
        pls: 0,
        price: null,
        prk: 0,
        pra: 0,
        prs: 0,
    },
    computed: {
        totalPlaces: function () {
            return this.plk+this.pla+this.pls;
        },
        totalPrice: function () {
            return this.plk*this.prk+this.pla*this.pra+this.pls*this.prs;
        }
    },
    beforeMount: function() {
        this.price = JSON.parse(this.$el.attributes['data-prices'].value);
        this.prk = this.price.kid;
        this.pra = this.price.adult;
        this.prs = this.price.senior;
    }
})

