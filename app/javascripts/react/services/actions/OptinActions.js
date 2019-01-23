import {
    REQUEST_GET_OPTIN,
    REQUEST_SAVE_OPTIN,
    CHANGE_OPTIN,
    CHANGE_TYPE,
    CHANGE_SETTINGS_OPTIN
} from 'javascripts/react/constants/OptinConstants'

import {
    transformRequestToAjaxWordPress
} from 'javascripts/react/helpers/transformRequestToAjaxWordPress'


class TemplateActions  {

    constructor(){
        this.changeOptin                   = this.changeOptin.bind(this)
        this.saveOptin                     = this.saveOptin.bind(this)
        this.getOptin                      = this.getOptin.bind(this)
        this.changeSettingsOptin           = this.changeSettingsOptin.bind(this)
    }

    changeSettingsOptin(payload){
        return (dispatch) =>{
            dispatch({
                "type" : CHANGE_SETTINGS_OPTIN,
                "payload" : payload
            })
            return Promise.resolve()
        }
    }

    saveOptin(optinId, params){
        
        return {
            type : REQUEST_SAVE_OPTIN,
            payload: {
                client: 'default',
                request:{
                    method: 'POST',
                    url: "/admin-ajax.php",
                    data:  {
                        action : "delipress_save_optin",
                        config : JSON.stringify(params["config"]),
                        type : params["type"],
                        _wpnonce_ajax: WPNONCE_AJAX,
                        optin_id: optinId
                    },
                    headers : { 
                        'Content-Type':'application/x-www-form-urlencoded; charset=UTF-8',
                        'Accept' : '*/*'
                    },
                    transformRequest : transformRequestToAjaxWordPress
                }
            }
        }
    }

    changeOptin(payload){
        return (dispatch) =>{
            dispatch({
                "type" : CHANGE_OPTIN,
                "payload" : payload
            })
            return Promise.resolve()
        }
    }


    getOptin(optinId, onSuccess = function(){}) {
        return {
            type : REQUEST_GET_OPTIN,
            payload: {
                client: 'default',
                request:{
                    method: 'POST',
                    url: "/admin-ajax.php",
                    data:  {
                        action : "delipress_get_optin",
                        optin_id : optinId,
                        _wpnonce_ajax: WPNONCE_AJAX
                    },
                    headers : { 
                        'Content-Type':'application/x-www-form-urlencoded; charset=UTF-8',
                        'Accept' : '*/*'
                    },
                    transformRequest : transformRequestToAjaxWordPress,
                    onSuccess: onSuccess
                }
            }
        }
    }

}

export default TemplateActions
