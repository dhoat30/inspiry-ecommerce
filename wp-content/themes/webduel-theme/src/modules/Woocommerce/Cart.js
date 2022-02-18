const $ = jQuery

class Cart {
    constructor() {
        this.plusBtn = $('.woocommerce-cart .quantity-container .plus')
        this.minusBtn = $('.woocommerce-cart .quantity-container .minus')
        this.qtyInputField = $('.woocommerce-cart .quantity-container #cart-quantity')
        this.events()
    }
    events() {
        this.plusBtn.on('click', this.incrementValue)
        this.minusBtn.on('click', this.decrementValue)
    }

    incrementValue(e) {
        let qty = $(this).siblings('#cart-quantity')
        let val = parseFloat(qty.val())
        var max = parseFloat(qty.attr('max'));
        var min = parseFloat(qty.attr('min'));
        var cart_item_key = qty.attr('data-cart_item_key')
        var step = 1
        if (max && (max <= val)) {
            qty.val(max);
        }
        else {
            qty.val(val + step);
        }
        this.updateCart(qty.val(), cart_item_key)
    }
    decrementValue() {
        let qty = $(this).siblings('#cart-quantity')
        let val = parseFloat(qty.val())
        var max = parseFloat(qty.attr('max'));
        var min = parseFloat(qty.attr('min'));
        var step = 1
        if (min && (min >= val)) {
            qty.val(min);
        }
        else if (val > 1) {
            qty.val(val - step);
        }
        this.updateCart(qty.val(), cart_item_key)
    }

    updateCart = (qty, cart_item_key) => {
        console.log('update cart')
        console.log(`this is qty ${qty}`)
        console.log(`this is key ${cart_item_key}`)
    }

}
export default Cart