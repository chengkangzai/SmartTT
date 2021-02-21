class Modal {
    constructor(title, message, mode = 'success') {
        this.title = title;
        this.message = message;
        this.mode = mode;
        this.id = Math.floor(Math.random() * 100000000);
        this.dom = () => {
            return `
            <div class="modal fade" id="modal-${this.id}" role="dialog" aria-labelledby="${this.title}" >
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="${this.title}">${this.title}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            ${this.message}

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-${this.mode}" id="modalBtn-${this.id}" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            `;
        }
    }
    show() {
        $('body').append(this.dom);
        $(`#modal-${this.id}`).modal('show');
    }
}
