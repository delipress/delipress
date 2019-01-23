import * as _ from 'underscore'

import {
    createButtonDefault
} from 'javascripts/react/helpers/structureToTemplate'

export function createButtonReadMoreFromImportWPPost(params){

    let _txtBtn = translationDelipressReact.read_more
    if(params.post.post_type === "product"){
        _txtBtn = params.woocommerce.regular_price + " " + params.woocommerce.symbol
    }

    let _newItem = _.extend(
        {},
        createButtonDefault()
    )

    _newItem = _.extend(_newItem, {
        styles : _.extend(_newItem.styles, {
            href : params.post.guid,
            value : _txtBtn
        })
    })
    
    return _newItem
}

