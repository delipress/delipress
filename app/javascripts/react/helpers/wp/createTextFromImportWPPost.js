import {
    createTextDefault
} from 'javascripts/react/helpers/structureToTemplate'

import * as _ from 'underscore'


export function createTextFromImportWPPost(params, extras){
    
    let txt = params.post.post_content
    if(params.type_content.excerpt == "true"){
        if(!_.isEmpty(params.attrs_post.content.extended)){
            txt = params.attrs_post.content.main
        }
        else if(!_.isEmpty(params.attrs_post.real_excerpt) ) {
            txt = params.attrs_post.real_excerpt
        }
    }

    let _newItem = _.extend(
        {},
        extras,
        createTextDefault({},txt)
    )

    return _newItem
}

