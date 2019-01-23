import {
    createTitleDefault
} from 'javascripts/react/helpers/structureToTemplate'

import * as _ from 'underscore'



export function createTitleFromImportWPPost(post, extras = {}){
    

    let _newItem = _.extend(
        {},
        extras,
        createTitleDefault({},post.post_title)
    )

    return _newItem
}

