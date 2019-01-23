import * as _ from "underscore"
import {
    REQUEST_GET_POST_TYPES_SUCCESS,
    REQUEST_GET_POSTS_SUCCESS
} from 'javascripts/react/constants/PostTypeConstants'

function postType(
    state = {
        postTypes: [],
        posts: []
    }, 
    action
) {
    switch (action.type) { 
        case REQUEST_GET_POST_TYPES_SUCCESS:
            return _.extend({}, state, {
                "postTypes" : action.payload.data.data.results
            })
        
        case REQUEST_GET_POSTS_SUCCESS:
            return _.extend({}, state, {
                "posts" : action.payload.data.data.results
            })
        
        default:
            return state
    }
}

export default postType