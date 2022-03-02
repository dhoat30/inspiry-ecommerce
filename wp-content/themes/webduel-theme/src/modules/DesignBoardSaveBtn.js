let $ = jQuery;
//Design board save button
class DesignBoardSaveBtn {
    constructor() {
        this.heartBtn = document.querySelectorAll('.design-board-save-btn-container');
        this.events();

    }
    //events
    events() {
        $(this.heartBtn).on('click', this.showDesignBoardModal)
    }

    // show design board list modal 
    showDesignBoardModal(e) {
        console.log('show design board modal ')

    }
}

export default DesignBoardSaveBtn;