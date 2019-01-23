import {
    CHANGE_THEME,
    SETTINGS_GENERAL,
    TEXT_VIEW,
    REQUEST_GET_THEME_SUCCESS
} from 'javascripts/react/constants/ThemeConstants'




function theme(
    state = {
        "theme" : {
            "mj-attributes": {
                "mj-all": {
                    "color" : "#58384C",
                },
                "mj-container": {
                    "background-color" : "#E0E0E0"
                }
            },
            "mj-styles":{
                "link-color" : "#F5656A"
            },
            "header" : null,
            "footer" : null
        },
        "settings_component": SETTINGS_GENERAL,
        "view_component": TEXT_VIEW
    }, 
    action
) {
    switch (action.type) { 
        case REQUEST_GET_THEME_SUCCESS:
            if(_.isNull(action.payload.data.data.results.theme)){
                return _.extend({}, state, {
                    "post" : action.payload.data.data.results.post
                })
            }
            
            return _.extend({}, state, {
                "theme" : action.payload.data.data.results.theme,
                "post" : action.payload.data.data.results.post
            })
        case CHANGE_THEME:
            return _.extend({}, state, {
                "theme" : action.payload
            })
        default:
            return state
    }
}

export default theme