const $ = jQuery

class Windcave {
    constructor() {
        this.events()
    }
    events() {
        $('.windcave-btn').on('click', this.validateWindcave)
    }
    validateWindcave(e) {


        WindcavePayments.Seamless.validate({

            onProcessed: function (isValid) {

                if (isValid) {
                    WindcavePayments.Seamless.submit({

                        onProcessed: function (response) {
                            console.log(response)
                            $(".woocommerce-checkout").trigger("submit");

                        },

                        onError: function (error) {
                            console.log(error)
                        }

                    });
                }

            },

            onError: function (error) {
                console.log(error)
            }

        });
    }
}
export default Windcave