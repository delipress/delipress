const $ = jQuery

class DeliAlert {
    constructor(options = {}) {
        this.opts = {
            deleteButtonColor: "#ED6657",
            cancelButtonColor: "#eaeef0",
            confirmButtonColor: "#59C9A5",
            deleteButtonText: translationDelipressReact.delete,
            cancelButtonText: translationDelipressReact.cancel,
            confirmButtonText: "Ok",
            warningButtonText: "Ok"
        }

        Object.assign(this.opts, options)

        this.body = document.querySelector("body")

        if ($(".delialert-wrap").length > 0) {
            $(".delialert-wrap").remove()
        }
    }

    /**
     *
     * @param {*} title
     * @param {*} text
     * @param {type|null} type
     */
    createMarkup(title, text = "", type, cancelOnly = false) {
        const opts = this.opts

        const Alert = $(`
            <div class="delialert-wrap">
                <div class="delialert-overlay" />
                <div class="delialert">
                <div class="delialert-Icon"></div>
                <span class="delialert-Title">${title}</span>
                <p class="delialert-Text">${text}</p>
                <div class="delialert-Actions" />
                </div>
            </div>
        `)

        const ConfirmBtn = $(`<button
            style="background-color: ${opts.confirmButtonColor}"
            class="delialert-Confirm delialert-Btn"
            >
            ${opts.confirmButtonText}
            </button>
        `)

        let WarningBtn = $(`
            <button
                style="background-color: ${opts.cancelButtonColor}"
                class="delialert-Cancel delialert-Btn"
            >
                ${opts.cancelButtonText}
            </button>
            <button
                style="background-color: ${opts.confirmButtonColor}"
                class="delialert-Confirm delialert-Btn"
            >
                ${opts.warningButtonText}
            </button>
        `)

        if (cancelOnly) {
            WarningBtn = $(`
                <button
                    style="background-color: ${opts.cancelButtonColor}"
                    class="delialert-Cancel delialert-Btn"
                >
                    ${opts.cancelButtonText}
                </button>
            `)
        }

        const DeleteBtn = $(`
            <button
                style="background-color: ${opts.cancelButtonColor}"
                class="delialert-Cancel delialert-Btn"
            >
                ${opts.cancelButtonText}
            </button>
            <button
                style="background-color: ${opts.deleteButtonColor}"
                class="delialert-Delete delialert-Btn"
            >
                ${opts.deleteButtonText}
            </button>
        `)

        const WarningIcon = $(`
            <div class="sa-icon sa-warning">
                <span class="sa-body"></span>
                <span class="sa-dot"></span>
            </div>
        `)
        const ConfirmIcon = $(`
            <div class="sa-icon sa-success">
                <span class="sa-line sa-tip animateSuccessTip"></span>
                <span class="sa-line sa-long animateSuccessLong"></span>

                <div class="sa-placeholder"></div>
                <div class="sa-fix"></div>
            </div>
        `)

        const delialertActions = Alert.find(".delialert-Actions")
        const delialertIcon = Alert.find(".delialert-Icon")

        switch (type) {
            case "warning":
                Alert.find(".delialert").addClass("delialert--icon")
                delialertIcon.html(WarningIcon)
                delialertActions.html(WarningBtn)
                break
            case "delete":
                delialertActions.html(DeleteBtn)
                break
            default:
                Alert.find(".delialert").addClass("delialert--icon")
                delialertActions.html(ConfirmBtn)
                delialertIcon.html(ConfirmIcon)
                break
        }

        $("body").append(Alert)
        this.captureEvents()
        this.alert = Alert
        return Alert
    }

    handle(cb) {
        this.cb = cb
    }

    captureEvents() {
        const self = this

        $(".delialert-Cancel").on("click", e => {
            e.preventDefault()

            self.close()
        })

        $(".delialert-Confirm, .delialert-Delete").on("click", e => {
            e.preventDefault()

            if (self.cb) {
                self.cb()
            }
            self.close()
        })

        $(document).on("keydown", function(e) {
            if (e.keyCode == 27) {
                self.close()
            }
        })
    }

    show(title, text, type, cancelOnly) {
        const alertEl = this.createMarkup(title, text, type, cancelOnly)
        alertEl.addClass("is-visible")
        $(".delialert-Delete").focus()
    }

    close() {
        $(".delialert").fadeOut(300)
        $(".delialert-overlay")
            .delay(200)
            .fadeOut(300)
        window.setTimeout(() => {
            this.alert.remove()
            $(document).off("click", ".delialert-Actions button")
        }, 500)
    }
}

module.exports = DeliAlert
