import {
    REQUEST_GET_OPTIN_SUCCESS,
    CHANGE_OPTIN,
    CONTENT_OPTIN,
    CHANGE_SETTINGS_OPTIN
} from 'javascripts/react/constants/OptinConstants'

function optin(
    state = {
        type: null,
        config : null,
        settings : "default",
        fromAction : null
    }, 
    action
) {

    switch (action.type) {
        case CHANGE_SETTINGS_OPTIN:
            return _.extend({}, state, {
                settings : action.payload,
                fromAction : CHANGE_SETTINGS_OPTIN
            })
        case CHANGE_OPTIN:
            return _.extend({}, state, {
                config : action.payload,
                fromAction : CHANGE_OPTIN
            })
        case REQUEST_GET_OPTIN_SUCCESS:
            return _.extend({}, state,{
                fromAction: REQUEST_GET_OPTIN_SUCCESS,
                config : action.payload.data.data.results.config,
                type : action.payload.data.data.results.type
            })
        default:
            return state
    }

}

export default optin