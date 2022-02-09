const $ = jQuery
import Splide from '@splidejs/splide';
import Drift from 'drift-zoom';

class WooGallery {
    constructor() {
        // this.events()
    }
    events() {
        this.slideShow()
    }
    slideShow() {
        var main = new Splide('#main-slider', {
            type: 'fade',
            rewind: true,
            pagination: false,
            arrows: true,
            lazyLoad: 'sequential',
            breakpoints: {
                1100: {
                    pagination: true,
                },
            }
        });

        var thumbnails = new Splide('#thumbnail-slider', {
            direction: 'ttb',
            height: '10rem',
            width: 150,
            height: '70vh',
            perPage: 7,
            gap: 10,
            rewind: true,
            pagination: false,
            cover: true,
            isNavigation: true,
            arrows: false,
            pagination: true,
            lazyLoad: 'nearby',
            breakpoints: {
                1520: {
                    perPage: 6,
                    height: '60vh',
                },
                1300: {
                    perPage: 5,
                    height: '50vh',
                },
                1100: {
                    direction: 'ltr',
                    perPage: 5,
                    height: '50vh',
                    destroy: true
                },
            }

        });
        main.sync(thumbnails);
        main.mount();
        thumbnails.mount();


        // drift 
        let drift
        var triggerEl = document.querySelectorAll(".single-product-image");
        if (triggerEl.length > 1) {

            $(triggerEl).each(function (i, el) {
                drift = new Drift(el, {
                    zoomFactor: 1.5,
                    containInline: true,
                    handleTouch: true,

                });

                main.on('moved', function (e) {
                    let newSrc
                    if (e === i) {
                        console.log(i)
                        console.log(e)
                        newSrc = $(el).attr('src')
                    }

                    drift.setZoomImageURL(newSrc);
                    el.setAttribute("data-zoom", newSrc);
                });

            })
        }

    }
}
export default WooGallery