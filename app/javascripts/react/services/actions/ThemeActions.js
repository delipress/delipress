import {
    CHANGE_THEME,
    CHANGE_VIEW_COMPONENT,
    CHANGE_SETTINGS_COMPONENT,
    REQUEST_SAVE_THEME,
    REQUEST_GET_THEME
} from 'javascripts/react/constants/ThemeConstants'

import {
    transformRequestToAjaxWordPress
} from 'javascripts/react/helpers/transformRequestToAjaxWordPress'

class ThemeActions  {

    constructor(){
        this.changeTheme             = this.changeTheme.bind(this)
        this.changeViewComponent     = this.changeViewComponent.bind(this)
        this.changeSettingsComponent = this.changeSettingsComponent.bind(this)
        this.saveTheme               = this.saveTheme.bind(this)
        this.getTheme                = this.getTheme.bind(this)
    }

    saveTheme(themeId, params){
        
        return {
            type : REQUEST_SAVE_THEME,
            payload: {
                client: 'default',
                request:{
                    method: 'POST',
                    url: "/admin-ajax.php",
                    data:  {
                        action : "delipress_save_theme",
                        theme : JSON.stringify(params["theme"]),
                        _wpnonce_ajax: WPNONCE_AJAX,
                        theme_id: themeId
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

    getTheme(themeId){

        return {
            type : REQUEST_GET_THEME,
            payload: {
                client: 'default',
                request:{
                    method: 'POST',
                    url: "/admin-ajax.php",
                    data:  {
                        action : "delipress_get_theme",
                        theme_id: themeId,
                        _wpnonce_ajax: WPNONCE_AJAX
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

    changeTheme(payload){
        return (dispatch) =>{
            dispatch({
                "type" : CHANGE_THEME,
                "payload" : payload
            })
            return Promise.resolve()
        }
    }

    changeViewComponent(payload){
        return (dispatch) =>{
            dispatch({
                "type" : CHANGE_VIEW_COMPONENT,
                "payload" : payload
            })
            return Promise.resolve()
        }
    }

    changeSettingsComponent(payload){
        return (dispatch) =>{
            dispatch({
                "type" : CHANGE_SETTINGS_COMPONENT,
                "payload" : payload
            })
            return Promise.resolve()
        }
    }


}

export default ThemeActions
