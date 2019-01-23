import DeliAlert from "javascripts/backend/DeliAlert"
import { contrastRatio } from "./misc/ContrastRatio"
import * as _ from "lodash"
// ==============================================
// Every ajax/async actions in the app go here ==
// ==============================================

const AJAXURL = window.ajaxurl
const $ = jQuery

const loaderSvg = function(btn) {
    var contrast = contrastRatio(btn[0])
    const color = contrast == 0 ? "#32373C" : "#FFF"
    return $(`<svg width="14" viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg" stroke="${color}">
      <g fill="none" fill-rule="evenodd">
        <g transform="translate(1 1)" stroke-width="2">
          <circle stroke-opacity=".5" cx="18" cy="18" r="18"/>
          <path d="M36 18c0-9.94-8.06-18-18-18">
            <animateTransform
                attributeName="transform"
                type="rotate"
                from="0 18 18"
                to="360 18 18"
                dur="1s"
                repeatCount="indefinite"/>
          </path>
        </g>
      </g>
    </svg>`)
}

class ButtonLoader {
    constructor(el, cb) {
        this.el = el
        this.cb = cb

        this.$btn = $(this.el)
        this.oldBtnHtml = this.$btn.html()
        this.btnH = this.$btn.outerHeight()
        this.btnW = this.$btn.outerWidth()

        this.allowDone = false

        this.attachEvent()
    }

    attachEvent() {
        this.$btn.on("click", e => {
            e.preventDefault()
            this.loaderTimeOut = setTimeout(() => {
                this.allowDone ? this.clearLoader() : (this.allowDone = true)
            }, 1000)
            this.loadStart()
        })
    }

    loadStart() {
        // Fix width and height to stop button from changing size
        this.$btn.css({
            minWidth: this.btnW,
            maxHeight: this.btnH
        })
        // Check ratio and add loader
        this.$btn.html(loaderSvg(this.$btn))
        this.$btn.prop("disabled", true)
        this.cb()
    }

    loadDone() {
        this.allowDone ? this.clearLoader() : (this.allowDone = true)
    }

    clearLoader() {
        this.$btn.prop("disabled", false)
        this.$btn.html(this.oldBtnHtml)
        clearTimeout(this.loaderTimeOut)
        this.allowDone = false
    }
}

// ===========================
// Send test email Campaign ==
// ===========================
const sendTest = new ButtonLoader("#delipress-send-test", function() {
    const self = this
    const action = this.$btn.data("action")
    const campaignId = this.$btn.data("campaignId")
    $.post(
        AJAXURL,
        {
            action: action,
            campaign_id: campaignId,
            send_to: $("#delipress_send_to").val()
        },
        function(data) {
            const deliAlert = new DeliAlert()
            if(data.success){
                deliAlert.show(
                    translationDelipressReact.general.send_test_title,
                    translationDelipressReact.general.send_test_text
                )
            }
            else{
                let _text = ""
                if (_.isString(data.results)){
                    _text = data.results
                }else{
                    _text = data.results.api
                }

                deliAlert.show(
                    translationDelipressReact.general.send_test_title_fail,
                    translationDelipressReact.general.send_test_text_fail.replace("%{s}", _text),
                    "warning"
                )
            }

            self.loadDone()
        }
    )
})

// =========================
// Send test email Wizard ==
// =========================

const sendTestWizard = new ButtonLoader("#send-test-email-wizard", function() {
    const self = this
    this.$btn.parents('form').trigger('submit')
})

// ==================
// Delete confirm  ==
// ==================

$(document).ready(function($) {
    $('.delipress').on('click', 'a.js-prevent-delete-action', function(e){
        e.preventDefault()

        const $this      = $(this)
        const href       = $this.attr("href")
        const title      = $this.data("title") || ""
        const message    = $this.data("message") || ""

        const deliAlert = new DeliAlert()

        deliAlert.handle(() => {
            window.location.href = href
        })

        deliAlert.show(title, message, "delete")
    

    })
})
