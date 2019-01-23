import {
    createImageDefault
} from 'javascripts/react/helpers/structureToTemplate'

import * as _ from 'underscore'

export function createImageFromImportWPPost(image, attrs, extras = {}){

    let  _newItem = _.extend(
        {},
        extras,
        createImageDefault({}, image)
    )
    
    _newItem = _.extend(_newItem, {
        "styles" : _.extend(_newItem.styles, {
            "sizes" : attrs.sizes,
            "srcWidth" : attrs.srcWidth,
            "srcHeight" : attrs.srcHeight,
            "width" : attrs.width
        })
    })

    return _newItem
}