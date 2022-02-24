const $ = jQuery
class Checkout {
    constructor() {
        this.events()
    }
    events() {
        $('#pay-button').on('click', this.showPaymentOptions)
    }
    showPaymentOptions(e) {
        e.preventDefault()
        $('#customer_details').hide()
        $('.total-summary').hide()
        $('#payment').show()
    }
}
export default Checkout