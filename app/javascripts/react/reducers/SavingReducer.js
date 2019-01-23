import {
    REQUEST_SAVE_CAMPAIGN_TEMPLATE,
    REQUEST_SAVE_CAMPAIGN_TEMPLATE_SUCCESS
} from 'javascripts/react/constants/TemplateContentConstants'

import {
    REQUEST_SAVE_OPTIN,
    REQUEST_SAVE_OPTIN_SUCCESS
} from 'javascripts/react/constants/OptinConstants'

function saving(
    state = {
        isSaving: false
    }, 
    action
) {

    switch (action.type) { 
        case REQUEST_SAVE_CAMPAIGN_TEMPLATE:
        case REQUEST_SAVE_OPTIN:
            return _.extend({},{
                isSaving : true
            })
        case REQUEST_SAVE_CAMPAIGN_TEMPLATE_SUCCESS:
        case REQUEST_SAVE_OPTIN_SUCCESS:
            return _.extend({},{
                isSaving : false
            })
        default:
            return state
    }
}

export default saving
