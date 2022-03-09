const $ = jQuery

class Header {
    constructor() {
        this.events()
    }
    events() {
        $('.useful-links-container .sign-in-container').hover(this.showSignInModal)
    }
    showSignInModal() {
        console.log('show modal')
        $('.useful-links-container .sign-in-modal').toggle()
    }
}
export default Header