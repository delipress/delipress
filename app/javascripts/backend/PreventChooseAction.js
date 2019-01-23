import * as _ from "underscore"
import DeliAlert from "javascripts/backend/DeliAlert"

class PreventChooseAction {
    constructor(idFormSelector, classButtonSelector) {
        this._idFormSelector      = jQuery(idFormSelector)
        this._classButtonSelector = jQuery(classButtonSelector)
    }

    init() {
        const _self = this

        jQuery(document).ready(function($) {
            _self._classButtonSelector.on("click", function(event) {
                event.preventDefault()

                const $this = $(this)
                const action  = $this.data("action")
                const title   = $this.data("title") || ""
                const message = $this.data("message") || ""

                const deliAlert = new DeliAlert()

                deliAlert.handle(() => {
                    _self._idFormSelector.attr("action", action)
                    _self._idFormSelector.submit()
                })

                deliAlert.show(title, message, "delete")
            })
        })
    }

    initEmptyForCampaign() {
        const _self = this

        jQuery(document).ready(function ($) {
            _self._classButtonSelector.on("click", function (event) {
                event.preventDefault()

                const $this = $(this)
                const action = $this.data("action")
                const confirm = $this.data("confirm")
                const oldText = $this.text()

                if (confirm) {
                    $this.html(translationDelipressReact.remove_confirm)
                    $this.find("span").on("click", function (e) {
                        e.stopPropagation()

                        if ($(this).data("action") == true) {
                            submitAction(action)
                        } else {
                            $this.html(oldText)
                        }
                    })
                } else {
                    submitAction(action)
                }

                function submitAction(action) {
                    _self._idFormSelector.attr("action", action)
                    _self._idFormSelector.submit()
                }
            })
        })
    }

    initTemplateIfNotEmpty() {
        const _self = this

        jQuery(document).ready(function($) {
            _self._classButtonSelector.on("click", function(event) {
                event.preventDefault()

                const $this = $(this)
                const action = $this.data("action")
                const title = $this.data("title") || ""
                const message = $this.data("message") || ""

                const deliAlert = new DeliAlert()

                deliAlert.handle(() => {
                    _self._idFormSelector.attr("action", action)
                    _self._idFormSelector.submit()
                })

                deliAlert.show(title, message, "warning")
            })
        })
    }
}

module.exports = PreventChooseAction
