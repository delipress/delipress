import * as _ from "underscore"


import {
    transformRequestToAjaxWordPress
} from 'javascripts/react/helpers/transformRequestToAjaxWordPress'

import { REQUEST_SAVE_TEMPLATE, REQUEST_GET_TEMPLATES, REQUEST_UPDATE_TEMPLATE, REQUEST_GET_TEMPLATE } from "../../constants/EndpointTemplateConstants";


class EndpointTemplateActions {

    constructor() {
        this.updateTemplate = this.updateTemplate.bind(this)
        this.saveTemplate   = this.saveTemplate.bind(this)
        this.getTemplate    = this.getTemplate.bind(this)
        this.getTemplates   = this.getTemplates.bind(this)
    }

    updateTemplate(templateId, params, onSuccess = function(){}){
        return {
            type: REQUEST_UPDATE_TEMPLATE,
            payload: {
                client: 'default',
                request: {
                    method: 'POST',
                    url: "/admin-ajax.php",
                    data: {
                        action: "delipress_update_template",
                        config: JSON.stringify(params.config),
                        _wpnonce_ajax: WPNONCE_AJAX,
                        template_id: templateId
                    },
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                        'Accept': '*/*'
                    },
                    transformRequest: transformRequestToAjaxWordPress,
                    onSuccess: onSuccess
                }
            }
        }
    }

    saveTemplate(params, onSuccess = function () { }) {

        return {
            type: REQUEST_SAVE_TEMPLATE,
            payload: {
                client: 'default',
                request: {
                    method: 'POST',
                    url: "/admin-ajax.php",
                    data: {
                        action: "delipress_save_template",
                        config: JSON.stringify(params.config),
                        name: params.name,
                        _wpnonce_ajax: WPNONCE_AJAX
                    },
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                        'Accept': '*/*'
                    },
                    transformRequest: transformRequestToAjaxWordPress,
                    onSuccess: onSuccess
                }
            }
        }
    }

    getTemplate(templateId, onSuccess = function () { }){
        return {
            type: REQUEST_GET_TEMPLATE,
            payload: {
                client: 'default',
                request: {
                    method: 'POST',
                    url: "/admin-ajax.php",
                    data: {
                        action: "delipress_get_template",
                        _wpnonce_ajax: WPNONCE_AJAX,
                        template_id: templateId
                    },
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                        'Accept': '*/*'
                    },
                    transformRequest: transformRequestToAjaxWordPress,
                    onSuccess: onSuccess
                }
            }
        }
    }

    getTemplates(params, onSuccess = function(){}) {
        return {
            type: REQUEST_GET_TEMPLATES,
            payload: {
                client: 'default',
                request: {
                    method: 'POST',
                    url: "/admin-ajax.php",
                    data: {
                        action: "delipress_get_templates",
                        _wpnonce_ajax: WPNONCE_AJAX
                    },
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                        'Accept': '*/*'
                    },
                    transformRequest: transformRequestToAjaxWordPress,
                    onSuccess: onSuccess
                }
            }
        }
    }

}

export default EndpointTemplateActions
