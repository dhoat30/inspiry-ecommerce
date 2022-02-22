const $ = jQuery

class UpdateCart {
    constructor(qty, cartItemKey) {
        this.qty = qty
        this.cartItemKey = cartItemKey
        this.events()
    }
    events() {

        $.ajax({
            beforeSend: (xhr) => {
                $('.overlay').show()

                xhr.setRequestHeader('X-WP-NONCE', inspiryData.nonce)
            },
            url: '/wp-admin/admin-ajax.php',
            type: 'POST',
            data: {
                qty: this.qty,
                cartItemKey: this.cartItemKey,
                action: 'woocommerce_ajax_update_cart'
            },
            complete: () => {
                console.log('completed ajax request ')
            },
            success: (response) => {
                if (response.code === 200) {
                    console.log(response);
                    $('.overlay').hide()
                    // refresh cart data 
                    $('.total-summary .subtotal-row .amount span').text(response.subtotal)
                    $('.total-summary .shipping-row .amount span').text(response.shipping)
                    $('.total-summary .tax-row .amount span').text(response.tax)
                    $('.total-summary .total-row .amount').html(response.total)
                    // if (response.regularPrice === response.productPrice) {
                    //     console.log(response.productPrice)
                    //     $('.price-column .sale-price').text(response.productPrice)
                    // }
                    // else {
                    //     $('.price-column .regular-price span').text(response.regularPrice)
                    //     $('.price-column .sale-price span').text(response.productPrice)

                    //     console.log(response.regularPrice)
                    //     console.log(response.productPrice)
                    // }
                }
                else {
                    $('.overlay').hide()
                    $('.error-modal .content').text('An error has occurred while updating cart. Please try again.')
                    $('.error-modal').show()
                }
            },
            error: (response) => {
                $('.overlay').hide()
                console.log('this is an error');
                console.log(response)
            }
        });
    }
}
export default UpdateCart