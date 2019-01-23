import * as _ from "underscore"
import {
    ADD_TEMPLATE_CONTENT,
    CHANGE_POSITION_CONTENT,
    DELETE_CONTENT,
    ADD_TEMPLATE_CONTENT_EMPTY,
    DELETE_SECTION,
    CHANGE_POSITION_SECTION,
    ADD_TEMPLATE_SECTION,
    REQUEST_SAVE_TEMPLATE,
    REQUEST_GET_TEMPLATE,
    REQUEST_SAVE_CAMPAIGN_TEMPLATE,
    REQUEST_GET_CAMPAIGN_TEMPLATE,
    REQUEST_GET_CAMPAIGN,
    REQUEST_SAVE_CAMPAIGN_TEMPLATE_HTML,
    DUPLICATE_CONTENT,
    DUPLICATE_SECTION,
    CLEAN_TEMPLATE,
    SECTION
} from 'javascripts/react/constants/TemplateContentConstants'

import {
    CHANGE_THEME
} from 'javascripts/react/constants/ThemeConstants'

import {
    createDefaultItemByType
} from 'javascripts/react/helpers/structureToTemplate'

import {
    transformRequestToAjaxWordPress
} from 'javascripts/react/helpers/transformRequestToAjaxWordPress'

import axios from 'axios'

class TemplateActions  {

    constructor(){
        this.addTemplateContent                     = this.addTemplateContent.bind(this)
        this.changePositionTemplateContent          = this.changePositionTemplateContent.bind(this)
        this.deleteContent                          = this.deleteContent.bind(this)
        this.deleteSection                          = this.deleteSection.bind(this)
        this.addTemplateContentOnEmpty              = this.addTemplateContentOnEmpty.bind(this)
        this.saveCampaignTemplate                   = this.saveCampaignTemplate.bind(this)
        this.getCampaignTemplate                    = this.getCampaignTemplate.bind(this)
        this.changePositionTemplateSection          = this.changePositionTemplateSection.bind(this)
        this.addSection                             = this.addSection.bind(this)
        this.saveCampaignTemplateHtml               = this.saveCampaignTemplateHtml.bind(this)
        this.getCampaign                            = this.getCampaign.bind(this)
        this.changeTheme                            = this.changeTheme.bind(this)
        this.cleanTemplate                          = this.cleanTemplate.bind(this)
        this.duplicateContent                       = this.duplicateContent.bind(this)
        this.duplicateSection                       = this.duplicateSection.bind(this)
    }

    duplicateSection(payload){
        return (dispatch) =>{
            dispatch({
                "type" : DUPLICATE_SECTION,
                "payload" : payload
            })
            return Promise.resolve()
        }
    }

    duplicateContent(payload){
        return (dispatch) =>{
            dispatch({
                "type" : DUPLICATE_CONTENT,
                "payload" : payload
            })
            return Promise.resolve()
        }
    }

    changePositionTemplateSection(payload){
        return (dispatch) =>{
            dispatch({
                "type" : CHANGE_POSITION_SECTION,
                "payload" : payload
            })
            return Promise.resolve()
        }
    }

    cleanTemplate(){
        return (dispatch) =>{
            dispatch({
                "type" : CLEAN_TEMPLATE,
            })
            return Promise.resolve()
        }
    }

    addSection(payload) {
        let _newRows = {
            "columns" : [],
            "styles" : {
                "background" : {
                    "hex" : "#ffffff",
                    "rgb" : {
                        "r" : 255,
                        "g" : 255,
                        "b" : 255,
                        "a" : 1,
                    }
                },
                "background-url"   : "",
                "padding-top"      : 0,
                "padding-bottom"   : 0,
                "padding-left"     : 0,
                "padding-right"    : 0,
            }
        }

        for(let i = 0; i < payload.number ; i++){
            _newRows.columns.push({"items": []})
        }

        payload = _.extend( payload, _newRows, {
            columns : _.map(_newRows.columns, (column, key) => {
                return _.extend(column, {
                    styles: {
                        width : 100 / payload.number,
                        "vertical-align": "top",
                        "border-radius" : 0,
                    },
                    type: SECTION
                })
            })
        })

        return (dispatch) =>{
            dispatch({
                "type" : ADD_TEMPLATE_SECTION,
                "payload": payload
            })
            return Promise.resolve()
        }
    }

    deleteContent(item){
        return (dispatch) =>{
            dispatch({
                "type" : DELETE_CONTENT,
                "payload": item
            })
            return Promise.resolve()
        }
    }


    deleteSection(keyRow){
        return (dispatch) =>{
            dispatch({
                "type" : DELETE_SECTION,
                "payload": keyRow
            })
            return Promise.resolve()
        }
    }



    changePositionTemplateContent(payload){
        return (dispatch) =>{
            dispatch({
                "type" : CHANGE_POSITION_CONTENT,
                "payload" : payload
            })
            return Promise.resolve()
        }
    }

    addTemplateContent(payload) {

        payload = _.extend( payload, createDefaultItemByType(payload.type) )

        return (dispatch) =>{
            dispatch({
                "type" : ADD_TEMPLATE_CONTENT,
                "payload": payload
            })
            return Promise.resolve()
        }
    }

    addTemplateContentOnEmpty(payload){
        payload = _.extend( payload, createDefaultItemByType(payload.type) )

        return (dispatch) =>{
            dispatch({
                "type" : ADD_TEMPLATE_CONTENT_EMPTY,
                "payload": payload
            })
            return Promise.resolve()
        }
    }

    saveCampaignTemplate(campaignId, params){

        return {
            type : REQUEST_SAVE_CAMPAIGN_TEMPLATE,
            payload: {
                client: 'default',
                request:{
                    method: 'POST',
                    url: "/admin-ajax.php",
                    data:  {
                        action : "delipress_save_campaign_template",
                        config : JSON.stringify(params["config"]),
                        _wpnonce_ajax: WPNONCE_AJAX,
                        campaignId: campaignId
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

    saveCampaignTemplateHtml(campaignId, params){
        return {
            type : REQUEST_SAVE_CAMPAIGN_TEMPLATE_HTML,
            payload: {
                client: 'default',
                request:{
                    method: 'POST',
                    url: "/admin-ajax.php",
                    data:  {
                        action : "delipress_save_campaign_template_html",
                        html : params["html"],
                        _wpnonce_ajax: WPNONCE_AJAX,
                        campaignId: campaignId
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


    getCampaignTemplate(campaignId, onSuccess = function(){}) {
        return {
            type : REQUEST_GET_CAMPAIGN_TEMPLATE,
            payload: {
                client: 'default',
                request:{
                    method: 'POST',
                    url: "/admin-ajax.php",
                    data:  {
                        action : "delipress_get_campaign_template",
                        campaignId : campaignId,
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

    getCampaign(campaignId, onSuccess = function(){}) {
        return {
            type : REQUEST_GET_CAMPAIGN,
            payload: {
                client: 'default',
                request:{
                    method: 'POST',
                    url: "/admin-ajax.php",
                    data:  {
                        action : "delipress_get_campaign",
                        campaignId : campaignId,
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
