let $ = jQuery;
//Design board save button
window.productID = 0
window.productName = 0
class DesignBoardSaveBtn {
    constructor() {
        this.heartBtn = document.querySelectorAll('.design-board-save-btn-container');
        this.events();
    }
    //events
    events() {
        $(this.heartBtn).on('click', this.showDesignBoardModal)
        $(document).on('click', '.design-board-selection-modal .footer-container .cancel', this.hideDesignBoardModal)
        $(document).on('click', '.dark-overlay', this.hideDesignBoardModal)
        $(document).on('click', '.design-board-selection-modal .board-list .list-item .save-btn', this.addToBoard)
        $(document).on('click', '.design-board-selection-modal .fa-xmark', this.hideDesignBoardModal)

    }

    // show design board list modal 
    showDesignBoardModal(e) {
        $('.design-board-selection-modal').show()
        $('.dark-overlay').show()


        window.productID = $(this).attr('data-id')
        window.productName = $(this).attr('data-name')
    }

    // hide design board modal 
    hideDesignBoardModal() {
        $('.design-board-selection-modal').hide()
        $('.dark-overlay').hide()
    }

    // add to board 
    addToBoard(e) {

        const boardID = $(e.target).attr('data-boardid')
        const boardPostStatus = $(e.target).attr('data-poststatus')

        $(e.target).html('<i class="fa-duotone fa-loader fa-spin"></i>')

        //add to board
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-NONCE', inspiryData.nonce)
            },
            url: inspiryData.root_url + '/wp-json/inspiry/v1/add-to-board',
            type: 'POST',
            data: {
                'boardID': boardID,
                'productID': window.productID,
                'postTitle': window.productName,
                'status': boardPostStatus
            },
            complete: () => {
                console.log('saved')
            },
            success: (response) => {
                console.log('this is a success area')
                if (response) {
                    console.log(response);
                    // $('.design-board-save-btn-container i').attr('data-exists', 'yes');
                    // //fill heart
                    // $('.design-board-save-btn-container i').addClass('fas fa-heart');
                    $(e.target).html('Saved')
                }
            },
            error: (response) => {
                console.log('this is an error');
                console.log(response)
                $(e.target).html('Error')
            }
        });
    }
}

export default DesignBoardSaveBtn;