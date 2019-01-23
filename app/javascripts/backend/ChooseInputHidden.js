import * as _ from 'underscore'

class ChooseInputHidden {
    constructor(idSelectorHidden, classSelectorItems, containerItems){
        this._selectorHidden          = jQuery(idSelectorHidden)
        this._items                   = jQuery(classSelectorItems)
        this._containerItems          = jQuery(containerItems)
        this._nameClassActive         = "delipress--is-active"
    }

    init(){

        if(
            this._items.length          === 0 ||
            this._selectorHidden.length === 0 ||
            this._items.length          !== this._containerItems.length
        ){
            throw "Items or selector hidden or container items is missing"
        }

        this._items.on("click", this._onClickChoose.bind(this))
    }

    _onClickChoose(e){
        e.preventDefault()

        const id = e.currentTarget.getAttribute("data-id")

        if(_.isNull(id)){
            throw "Attribute data-id is missing"
        }

        this._selectorHidden.val(id)

        this._containerItems.removeClass(this._nameClassActive)
        this._containerItems.each((key, container) => {
            const el = jQuery(container)

            if(el.data("id") == id){
                el.addClass(this._nameClassActive)
            }
        })

    }
}

module.exports = ChooseInputHidden