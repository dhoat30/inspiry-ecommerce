jQuery(function ($) {
    //  call this windcave function 
    console.log(misha_params.seamlessValue)
    WindcavePayments.Seamless.prepareIframe({
        url: misha_params.seamlessValue,
        containerId: "payment-iframe-container",
        loadTimeout: 30,
        width: 400,
        height: 500,
        onProcessed: function () {
            console.log('iframes is loaded properly ')
        },
        onError: function (error) {
            console.log(error)
            console.log('this is an error event after loading ')
        }
    });
    console.log('loading seamless ')

    // validation 
    $('.woocommerce-checkout').on('submit', (e) => {
        e.preventDefault()
        console.log('validate')
    });

});