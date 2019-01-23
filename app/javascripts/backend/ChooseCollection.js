class ChooseCollection {
    constructor(containerClassCollection, classCollection, inputHidden) {
        this._containerClassCollection = jQuery(containerClassCollection)
        this._classCollection = jQuery(classCollection)
        this._inputHidden = jQuery(inputHidden)

        this.collectionItems = jQuery(
            ".js-delipress__collection__item__choice-value",
            this.containerClassCollection
        )

        this.init()
    }

    init() {
        const _self = this
        const $ = jQuery

        this._classCollection.on(
            "click",
            this._containerClassCollection,
            function(e) {
                e.preventDefault()
                e.stopPropagation()
                const $this = $(this)

                if ($this.hasClass("delipress__soon")) {
                    return
                }

                let itemSelected = $this

                if (
                    !$this.hasClass(
                        "js-delipress__collection__item__choice-value"
                    )
                ) {
                    itemSelected = $this.parents(
                        ".js-delipress__collection__item__choice-value"
                    )
                }


                // Change btn label
                const btnLabel = $(".js-delipress__collection__item__choice-label", itemSelected)
                let oldBtnText = btnLabel.text()
                btnLabel.text(btnLabel.data("altText")).data("altText", oldBtnText)

                if (
                    $(".js-delipress__collection__item__choice-value:visible")
                        .length < 2
                ) {
                    $(
                        ".js-delipress__collection__item__choice-value",
                        _self.containerClassCollection
                    ).fadeIn("300")

                    _self._inputHidden.val("")
                } else {
                    _self.collectionItems.not(itemSelected).fadeOut("300")

                    _self._inputHidden.val(itemSelected.data("value"))
                }
            }
        )
    }
}

module.exports = ChooseCollection
