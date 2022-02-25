import Windcave from "./Windcave";

const $ = jQuery
class Checkout {
    constructor() {
        this.onPaymentSelectionChange
        this.windcavePaymentSelected = $("input[type='radio'][name='payment_method']:checked").val();
        this.events()
    }
    events() {
        $('#pay-button').on('click', this.showPaymentOptions)
        // on payment selection change value 
        $(document).on('change', '.wc_payment_methods .input-radio', () => {
            this.onPaymentSelectionChange = $("input[type='radio'][name='payment_method']:checked").val()
            this.windcavePaymentSelected = $("input[type='radio'][name='payment_method']:checked").val();
            console.log(this.onPaymentSelectionChange)
            console.log(this.windcavePaymentSelected)
        })

        // show windcave iframe conditionaly
        this.showWindcaveIframe()

        // hide windcave iframe
        $(document).on('click', '.payment-gateway-container .cancel-payment', () => {
            $('.payment-gateway-container').hide();
            $('.overlay').hide();
        })

    }
    showPaymentOptions(e) {
        e.preventDefault()
        $('.error').remove()
        // check the required values 
        let firstName = $('.woocommerce-checkout #billing_first_name')
        let lastName = $('.woocommerce-checkout #billing_last_name')
        let address1 = $('.woocommerce-checkout #billing_address_1')
        let city = $('.woocommerce-checkout #billing_city')
        let postCode = $('.woocommerce-checkout #billing_postcode')
        let phone = $('.woocommerce-checkout #billing_phone')
        let emailAddress = $('.woocommerce-checkout #billing_email')


        const validateInputField = (selector, errorText, selectorID) => {
            if (selector.val().length < 1) {
                selector.closest('.woocommerce-input-wrapper').append(`<div class="error">${errorText}</div>`)
                $('html, body').animate({
                    scrollTop: $(selectorID).offset().top
                }, 100);
                return false
            }
            else {
                return true
            }
        }

        // validate first name
        const isFirstNameValid = validateInputField(firstName, 'Please enter your first name', '#billing_first_name')
        // validate last name
        const isLastNameValid = validateInputField(lastName, 'Please enter your last name', '#billing_last_name')
        // validate address1
        const isAddress1Valid = validateInputField(address1, 'Please enter your street address', '#billing_address_1')
        // validate city
        const isCityValid = validateInputField(city, 'Please enter your city', '#billing_city')
        // validate post code
        const isPostCodeValid = validateInputField(postCode, 'Please enter your post Code', '#billing_postcode')
        // validate phone
        const isPhoneValid = validateInputField(phone, 'Please enter your phone number', '#billing_phone')
        // validate email address
        const isEmailAddressValid = validateInputField(emailAddress, 'Please enter your phone number', '#billing_email')
        if (isFirstNameValid && isLastNameValid && isAddress1Valid && isCityValid && isPostCodeValid && isPhoneValid && isEmailAddressValid) {


            $('#payment').show()
            // hide the pay now button 
            $(this).hide()
        }
    }
    showWindcaveIframe(e) {
        $(document).on('click', '#place_order', (e) => {
            e.preventDefault();
            if (this.onPaymentSelectionChange === 'webduel_windcave_gateway' || this.windcavePaymentSelected === 'webduel_windcave_gateway') {
                // check if the terms and conditions is checked 
                let termsConditionsCheckbox = $('.validate-required .woocommerce-form__input-checkbox')
                // check if the validation is true
                if (termsConditionsCheckbox.is(':checked')) {
                    $('.payment-gateway-container').show();
                    $('.overlay').show();
                    const windcave = new Windcave()
                }
                else if (!termsConditionsCheckbox.is(':checked')) {
                    $('#payment').append(`<div class="error">*Please check the terms & conditions</div>`)
                }
            }
            else {
                $('#place_order').unbind('click');
            }
        })
    }
}
export default Checkout