import * as _ from "underscore"
import {
    CHANGE_ITEM,
    CHANGE_COMPONENT,
    CHANGE_SETTINGS_COMPONENT,
    SETTINGS_LIST_CONTENTS,
    ACTIVE_ITEM,
    ACTIVE_SECTION,
    HOVER_SECTION,
    HOVER_ITEM
} from '../constants/EditorConstants'

function editor(
    state = {
        isOpen: false,
        item: null,
        component: SETTINGS_LIST_CONTENTS,
        activeItem: null,
        activeSection: null
    }, 
    action
) {
    switch (action.type) { 
        case CHANGE_ITEM:
            return _.extend({}, state, {
                "item" : action.payload
            })
        case CHANGE_COMPONENT:
            return _.extend({}, state, {
                "component" : action.payload
            })
        case CHANGE_SETTINGS_COMPONENT:
            return _.extend({}, state, {
                "component" : action.payload.component,
                "item" : action.payload.item
            })
        case ACTIVE_ITEM:
            return _.extend({}, state, {
                "activeItem" : action.payload,
                "activeSection" : null,
                "component": SETTINGS_LIST_CONTENTS
            })
        case ACTIVE_SECTION:
            return _.extend({}, state, {
                "activeSection" : action.payload,
                "activeItem" : null,
                "component": SETTINGS_LIST_CONTENTS
            })
        default:
            return state
    }
}

export default editor