
class SelectAll {
    constructor(idSelectorAll, classSelectorAll, containerSelectAll){
        this._idSelectorAll          = jQuery(idSelectorAll)
        this._classSelectorAll       = classSelectorAll
        this._containerSelectAll     = containerSelectAll
        this._check = false
    }

    init(){
        const _self = this

        jQuery(document).ready(function($){
            const action = (_self._idSelectorAll.is("input")) ? "change" : "click";

            _self._idSelectorAll.on(action, function(e) {
                e.preventDefault();
                this._check = !this._check
                $(_self._classSelectorAll, _self._containerSelectAll).prop('checked', this._check);
            })
        })
    }

}

module.exports = SelectAll