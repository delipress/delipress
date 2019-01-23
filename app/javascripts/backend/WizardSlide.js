const $ = jQuery
class WizardSlide {
    constructor() {
        this.$slider = $(".delipress__wizard__slider")
        this.$slideNextBtn = $(".js-wizard-slide-next")
        this.$slidePrevBtn = $(".js-wizard-slide-prev")

        this.init()
    }

    init() {
        const self = this
        this.$slideNextBtn.on("click", function(e) {
            const $this = $(this)
            if ($this.attr("href") == "#") {
                e.preventDefault()
            }
            self.$slider.addClass('step2')
        })

        this.$slidePrevBtn.on("click", function(e) {
            e.preventDefault()
            self.$slider.removeClass('step2')
        })
    }
}

export default WizardSlide
