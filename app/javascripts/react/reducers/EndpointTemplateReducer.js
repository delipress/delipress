import * as _ from "underscore"
import { REQUEST_GET_TEMPLATES_SUCCESS, REQUEST_GET_TEMPLATE_SUCCESS } from "../constants/EndpointTemplateConstants";


function endpointTemplate(
    state = {
        templates : [],
        templateConfig : null
    },
    {type, payload}
) {
    switch (type) {
        case REQUEST_GET_TEMPLATES_SUCCESS:
            return _.extend({}, state, {
                templates: payload.data.data.results,
            })
        case REQUEST_GET_TEMPLATE_SUCCESS:
            return _.extend({}, state, {
                template : payload.data.data.results
            })
        default:
            return state
    }
}

export default endpointTemplate