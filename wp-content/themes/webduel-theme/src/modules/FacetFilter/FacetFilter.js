const $ = jQuery

class FacetFilter {
    constructor() {
        // mobile and desktop filter show/hide
        this.mobileFilterButton = $('.mobile .filter-title')
        this.fixedFilterButton = $('.fixed-filter-button')
        this.closeButton = $('.mobile-filter-container .close-button')
        this.closeIcon = $('.mobile-filter-container .close-icon')

        // desktop filter show 
        this.filterButton = $('.filter-sort-container .filter-button')
        // facet label button
        this.labelButton = $('.facet-product-container .facet-wp-container .desktop .facet-label-button')
        this.events()

    }
    events() {
        $(window).scroll(function (event) {
            var scroll = $(window).scrollTop();
            // Do something
            if (scroll > 300) {
                $('.fixed-filter-button').slideDown()
            }
            else {
                $('.fixed-filter-button').slideUp()
            }
        });
        // this.mobileFilterButton.on('click', this.showMobileFilterContainer)
        // this.fixedFilterButton.on('click', this.showMobileFilterContainer)
        // this.closeButton.on('click', this.closeMobileFilterContainer)
        // this.closeIcon.on('click', this.closeMobileFilterContainer)
        // show filter container when button is clicked 
        this.filterButton.on('click', this.showDesktopContainer)
        // show filter when clicked on label desktop 

        this.labelButton.on('click', this.showFilter)
    }

    showMobileFilterContainer() {
        console.log('filter button clicked')
        $('.facet-wp-container').slideDown()
    }
    closeMobileFilterContainer() {
        $('.facet-wp-container').slideUp()
    }

    // show desktop filter container on button click
    showDesktopContainer() {
        $('.facet-wp-container').slideToggle('slow')
        if ($('.filter-sort-container .filter-button span').text() === 'Show Filters') {
            $('.filter-sort-container .filter-button span').text('Hide Filters')
        }
        else {
            $('.filter-sort-container .filter-button span').text('Show Filters')
        }
    }



    showFilter(e) {
        console.log(e)
        $(this).siblings('.facetwp-facet').slideToggle('fast')
        $(this).find('i').toggleClass('fa-plus')
        $(this).find('i').toggleClass('fa-minus')
    }
}

export default FacetFilter