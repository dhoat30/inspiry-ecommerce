const $ = jQuery

class SingleProductAccordion {
    constructor() {
        this.events()
    }
    events() {
        $('.single-product .accordion-container .item .title').on('click', this.toggleAccordion)
    }
    toggleAccordion(e) {
        // console.log($(e.target).closest('.title').siblings('.content'))
        $(e.target).closest('.title').siblings('.content').slideToggle()
        let currentIcon = $(e.target).find('span').html()
        $(e.target).find('span').html(currentIcon === "+" ? "–" : "+")
        console.log($(e.target))
    }
}

export default SingleProductAccordion