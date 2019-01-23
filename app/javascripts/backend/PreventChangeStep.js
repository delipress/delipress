import * as _ from "underscore"
import DeliAlert from "./DeliAlert"

class PreventChangeStep {
    constructor(idInputHiddenSelector, classStepsSelector) {
        this._idInputHiddenSelector = jQuery(idInputHiddenSelector)
        this._stepsSelector = jQuery(classStepsSelector)
    }

    init() {
        var _self = this
        const $ = jQuery
        $(document).on("ready", () => {
            this._stepsSelector.on("click", function(event) {
                event.preventDefault()

                // Check for warning in the optin builder
                if (
                    $(".delipress_page_delipress-optin-forms").length > 0 &&
                    _self._idInputHiddenSelector[0].value == 3
                ) {
                    const deliAlert = new DeliAlert()
                    var warningPresent = $("#warning-present")
                    var warningPresentContent = $("#warning-present p").text()
                    if (warningPresent.length > 0) {
                        deliAlert.show(
                            "",
                            warningPresentContent,
                            "warning",
                            true
                        )
                        //alert(warningPresentContent)
                        return false
                    }

                    var inputRedirect = $(
                        '.delipress__input[name="redirect_url"]'
                    )[0]
                    if (
                        inputRedirect &&
                        inputRedirect.value.length > 0 &&
                        !inputRedirect.checkValidity()
                    ) {
                        const contentAlert = $(
                            '.delipress__input[name="redirect_url"]'
                        ).data("notValid")
                        deliAlert.show("", contentAlert, "warning", true)
                        return false
                    }
                }

                const nextStep = jQuery(this).data("next-step")

                _self._idInputHiddenSelector.val(nextStep)
                _self._stepsSelector.off("click")
                jQuery(this)
                    .parents("form")
                    .submit()
            })
        })
    }
}

module.exports = PreventChangeStep
