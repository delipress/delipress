import * as _ from "underscore"

import {
    CHOICE_ITEMS_TEMPLATE,
    CHOICE_STRUCTURE_TEMPLATE
} from '../constants/StructureConstants'

import {
    CHANGE_THEME
} from 'javascripts/react/constants/ThemeConstants'

import {
    CHANGE_POSITION_CONTENT,
    CHANGE_POSITION_SECTION,
    ADD_TEMPLATE_CONTENT,
    ADD_TEMPLATE_SECTION,
    ADD_TEMPLATE_CONTENT_EMPTY,
    DELETE_CONTENT,
    DELETE_SECTION,
    CLEAN_TEMPLATE,
    REQUEST_SAVE_TEMPLATE_SUCCESS,
    REQUEST_GET_TEMPLATE_SUCCESS,
    REQUEST_GET_CAMPAIGN_TEMPLATE_SUCCESS,
    REQUEST_GET_CAMPAIGN,
    REQUEST_GET_CAMPAIGN_SUCCESS,
    DUPLICATE_CONTENT,
    DUPLICATE_SECTION,
    WP_CUSTOM_POST,
    TEXT,
    BUTTON,
    SOCIAL_LIST,
    IMAGE,
    TITLE,
    SOCIAL_BUTTON,
    EMAIL_ONLINE,
    UNSUBSCRIBE,
    SECTION_EMAIL_ONLINE,
    SECTION_UNSUBSCRIBE
} from 'javascripts/react/constants/TemplateContentConstants'

import {
    REQUEST_GET_POST_TO_WP_POST_SUCCESS,
    REQUEST_IMPORT_POSTS_WP_SUCCESS
} from 'javascripts/react/constants/PostTypeConstants'

import {
    CHANGE_ITEM,
    CHANGE_ITEM_TEXT,
    CHANGE_STYLE_SECTION,
    CHANGE_STYLE_SECTION_FIX,
    CHANGE_STYLE_COMPONENT_FIX,
    CHANGE_STYLE_SECTION_UNSUBSCRIBE,
    CHANGE_STYLE_UNSUBSCRIBE,
    DELETE_SECTION_EMAIL_ONLINE,
    UPDATE_SECTION_EMAIL_ONLINE,
    CHANGE_STYLE_COLUMN,
    CHANGE_STYLE_COLUMNS,
    ACTIVE_ITEM,
    UPDATE_ALL_STYLES
} from 'javascripts/react/constants/EditorConstants'

import {
    createWPPostContent,
    createTextDefault,
    createImageDefault
} from 'javascripts/react/helpers/structureToTemplate'

import { createTextFromImportWPPost } from 'javascripts/react/helpers/wp/createTextFromImportWPPost'
import { createTitleFromImportWPPost } from 'javascripts/react/helpers/wp/createTitleFromImportWPPost'
import { createImageFromImportWPPost } from 'javascripts/react/helpers/wp/createImageFromImportWPPost'
import { createButtonReadMoreFromImportWPPost } from 'javascripts/react/helpers/wp/createButtonReadMoreFromImportWPPost'


function createTextFromWPPost(action){ 

    const options = action.payload.extras.wp_post.options
    const results = action.payload.data.data.results

    let txt = results.post.post_content
    if(options.content.excerpt){
        if(!_.isEmpty(results.attrs_post.content.extended)){
            txt = results.attrs_post.content.main
        }
        else if(!_.isEmpty(results.attrs_post.real_excerpt) ) {
            txt = results.attrs_post.real_excerpt
        }
    }

    let  _newItem = _.extend(
        action.payload.extras,
        createTextDefault(
            {},
            txt
        )
    )

    return _newItem
}


function createImageFromWPPost(action){

    let  _newItem = _.extend(
        {},
        action.payload.extras,
        createImageDefault({}, action.payload.data.data.results.image)
    )

    _newItem = _.extend(_newItem, {
        "styles" : _.extend(_newItem.styles, {
            "sizes" : action.payload.data.data.results.attrs_image.sizes,
            "srcWidth" : action.payload.data.data.results.attrs_image.srcWidth,
            "srcHeight" : action.payload.data.data.results.attrs_image.srcHeight,
            "width" : action.payload.data.data.results.attrs_image.width
        })
    })

    return _newItem
}


function customize(
    state = {
        config :{
            theme: null,
            items: [],
            header:[],
            footer: [],
            loaded: false,
            saving: false,
            email_online: [
                {
                    "columns": [
                        {
                            "items": [
                                {
                                    _id: 0,
                                    keyRow: "email_online",
                                    keyColumn: 0,
                                    value : "[view_email_online]",
                                    type: EMAIL_ONLINE,
                                    styles: {
                                        "font-size" : 12,
                                        "color" : {
                                            "hex" : "#000000"
                                        },
                                        "font-family": "Arial",
                                        "align" : "right",
                                        "padding-top" : 10,
                                        "padding-bottom" : 10,
                                        "padding-left" : 10,
                                        "padding-right" : 10,
                                    }
                                }
                            ],
                            "styles" : {
                                "width" : 100
                            }
                        }
                    ]
                }
            ],
            email_online_active: true,
            unsubscribe: [
                {
                    "columns": [
                        {
                            "items": [
                                {
                                    _id: 0,
                                    keyRow: "unsubscribe",
                                    keyColumn: 0,
                                    value : "[delipress_link_unsubscribe]",
                                    type: UNSUBSCRIBE,
                                    styles: {
                                        "font-size" : 11,
                                        "color" : {
                                            "hex" : "#CCC"
                                        },
                                        "font-family": "Arial",
                                        "align" : "center",
                                        "padding-top" : 2,
                                        "padding-bottom" : 2,
                                        "padding-left" : 10,
                                        "padding-right" : 10,
                                    }
                                }
                            ],
                            "styles" : {
                                "padding-top" : 0,
                                "padding-bottom" : 0,
                                "padding-left" : 0,
                                "padding-right" : 0
                            }
                        }
                    ]
                }
            ]
        }
    },
    action
) {
    let _configWork = ""

    switch (action.type) {

        case UPDATE_ALL_STYLES:
            return _.extend({}, state, {
                config: _.extend({}, state.config, {
                    items: _.map(state.config.items, (row, keyRow) => {
                        return _.extend({}, row, {
                            columns : _.map(row.columns, (column, keyColumn) => {

                                let _items =  column["items"]

                                _items = _.map(_items, (itm,key) => {
                                    
                                    itm.fromAction = UPDATE_ALL_STYLES
                                    
                                    if(_.isUndefined(itm.styles.presetChoice)){
                                        if(itm.type === action.payload.type){
                                            switch(itm.type){
                                                case SOCIAL_BUTTON:
                                                    let _colorSocial = {}
                                                    _.each(SOCIAL_LIST, (social) => {
                                                        _colorSocial["color_" + social] = action.payload.styles["color_" + social]
                                                    })
                                                   
                                                    itm = _.extend({}, itm, {
                                                        styles : _.extend({}, itm.styles,{
                                                            "background"   : action.payload.styles["background"],
                                                            "font-family"   : action.payload.styles["font-family"],
                                                            "font-size"   : action.payload.styles["font-size"],
                                                            "icon-size"   : action.payload.styles["icon-size"],
                                                            "padding-bottom"   : action.payload.styles["padding-bottom"],
                                                            "padding-left"   : action.payload.styles["padding-left"],
                                                            "padding-right"   : action.payload.styles["padding-right"],
                                                            "padding-top"   : action.payload.styles["padding-top"],
                                                            "textColor"   : action.payload.styles["textColor"],
                                                            "monochromeActive"   : action.payload.styles["monochromeActive"],
                                                            "monochromeColor"   : action.payload.styles["monochromeColor"],
                                                            "align"   : action.payload.styles["align"],
                                                        }, _colorSocial)
                                                    })
                                                    break;
                                                case IMAGE:
                                                    itm = _.extend({}, itm, {
                                                        styles : _.extend({}, itm.styles, action.payload.styles, {
                                                            href: itm.styles.href,
                                                            sizeSelect: itm.styles.sizeSelect,
                                                            src: itm.styles.src,
                                                            srcHeight: itm.styles.srcHeight,
                                                            srcWidth: itm.styles.srcWidth,
                                                            width: itm.styles.width,
                                                            valuePercent: itm.styles.valuePercent,
                                                            sizes: itm.styles.sizes,
                                                            height: itm.styles.height,
                                                        })
                                                    })
                                                    break;
                                                case TEXT:
                                                    itm = _.extend({}, itm, {
                                                        styles : _.extend({}, itm.styles, action.payload.styles, {
                                                            value: itm.styles.value
                                                        })
                                                    })
                                                    break;
                                                case BUTTON:
                                                    itm = _.extend({}, itm, {
                                                        styles : _.extend({}, itm.styles, action.payload.styles, {
                                                            value: itm.styles.value,
                                                            href: itm.styles.href
                                                        })
                                                    })
                                                    break;
                                                default:
                                                    itm = _.extend({}, itm, {
                                                        styles : _.extend({}, itm.styles, action.payload.styles)
                                                    })
                                                    break;
                                            }
                                        }
                                    }
                                    else{
                                        const index = _.findIndex(action.payload.styles.presets, {"type" : action.payload.styles.presetChoice})
                                        if(index < 0){
                                            return itm
                                        }
                                        
                                        if(itm.type === action.payload.type){
                                            
                                            switch(itm.type){
                                                case TITLE:
                                                    itm = _.extend({}, itm, {
                                                        styles : _.extend({}, itm.styles, {
                                                            presets: _.map(itm.styles.presets, (val, ind) => {
                                                                if(ind == index){
                                                                    return _.extend({}, val, {
                                                                        "font-size"   : action.payload.styles.presets[index]["font-size"],
                                                                        "font-family" : action.payload.styles.presets[index]["font-family"],
                                                                        "font-weight" : action.payload.styles.presets[index]["font-weight"],
                                                                        "line-height" : action.payload.styles.presets[index]["line-height"],
                                                                        "align"       : action.payload.styles.presets[index]["align"],
                                                                        "color"       : action.payload.styles.presets[index]["color"]
                                                                    })
                                                                }
    
                                                                return val
                                                            })
                                                        })
                                                    })
                                                    break;
                                                default:
                                                    itm = _.extend({}, itm, {
                                                        styles : _.extend({}, itm.styles, {
                                                            presets: _.map(itm.styles.presets, (val, ind) => {
                                                                if(ind == index){
                                                                    return _.extend({}, val,  action.payload.styles.presets[index])
                                                                }
                                                                return val
                                                            })
                                                        })
                                                    })
                                                    break;
                                            }
                                        }
                                    }

                                    
                                    
                                    return  itm
                                })

                                return _.extend({}, column, {
                                    items: _.extend([], _items)
                                })
                            })
                        })
                    })
                })
            })

        case CLEAN_TEMPLATE:
            return _.extend({}, state, {
                config: _.extend({}, state.config,{
                    items: _.extend([]),
                    header:_.extend([]),
                    footer: _.extend([])
                })
            })
        case UPDATE_SECTION_EMAIL_ONLINE:
            return _.extend({}, state, {
                config: _.extend(state.config, {
                    email_online_active: action.payload
                })
            })

        case DELETE_SECTION_EMAIL_ONLINE:
            return _.extend({}, state, {
                config: _.extend(state.config, {
                    email_online_active: false
                })
            })
        case CHANGE_STYLE_SECTION:
            return _.extend({}, state, {
                config: _.extend({}, state.config, {
                    items: _.map(state.config.items, function(row, keyRow){
                        if( keyRow == action.payload.keyRow){
                            return _.extend({}, row, {
                                styles : action.payload.styles
                            })
                        }

                        return row
                    })
                })
            })
        case CHANGE_STYLE_COLUMN:
            return _.extend({}, state, {
                config: _.extend({}, state.config, {
                    items: _.map(state.config.items, function(row, keyRow){
                        if( keyRow == action.payload.keyRow){
                            return _.extend({}, row, {
                                columns : _.map(row.columns, function(column, keyColumn) {
                                    if(keyColumn == action.payload.keyColumn){
                                        return _.extend({}, column, {
                                            styles: _.clone(action.payload.styles)
                                        })
                                    }

                                    return column
                                })
                            })
                        }

                        return row
                    })
                })
            })
        case CHANGE_STYLE_COLUMNS:
            return _.extend({}, state, {
                config: _.extend({}, state.config, {
                    items: _.map(state.config.items, function(row, keyRow){
                        if( keyRow == action.payload.keyRow){
                            return _.extend({}, row, {
                                styles : action.payload.styles,
                                columns : _.map(row.columns, function(column, keyColumn) {
                                    return _.extend({}, column, {
                                        styles: _.clone(action.payload.columns[keyColumn].styles)
                                    })

                                })
                            })
                        }

                        return row
                    })
                })
            })
        case CHANGE_STYLE_COMPONENT_FIX:

            switch(action.payload.type){
                case EMAIL_ONLINE:
                    _configWork = "email_online"
                    break;
                case UNSUBSCRIBE:
                    _configWork = "unsubscribe"
                    break;
            }

            return _.extend({}, state, {
                config: _.extend({}, state.config, {
                    [_configWork]: _.map(state.config[_configWork], function(row, keyRow){
                        return _.extend({}, row, {
                           columns: _.map(row.columns, function(column, keyColumn){
                               column.items[0] = _.extend({}, action.payload)

                               return _.extend({}, column)
                            })
                        })
                    })
                })
            })
        case CHANGE_STYLE_SECTION_FIX:
            switch(action.payload.type){
                case SECTION_EMAIL_ONLINE:
                    _configWork = "email_online"
                    break;
                case SECTION_UNSUBSCRIBE:
                    _configWork = "unsubscribe"
                    break;
            }

            return _.extend({}, state, {
                config: _.extend({}, state.config, {
                    [_configWork] : _.map(state.config[_configWork], function(row, keyRow){
                        return _.extend({}, row, {
                            "styles" : action.payload.styles
                        })
                    })
                })
            })

        case ADD_TEMPLATE_CONTENT_EMPTY:
            return _.extend({}, state,{
                config: _.extend({}, state.config,{
                    items: _.map(state.config.items, function(row, keyRow){

                        if(keyRow == action.payload.keyRow){
                            return _.extend({}, row, {
                                columns: _.map(row.columns, function(column, keyColumn){
                                    if(keyColumn == action.payload.keyColumn){

                                        let _items =  column["items"]
                                        _items.push(action.payload)

                                        _items = _.map(_items, (value,key) => {
                                            value._id        = key
                                            value.fromAction = ADD_TEMPLATE_CONTENT_EMPTY
                                            return _.clone(value)
                                        })

                                        return _.extend({}, column, {
                                            items: _.extend([], _items)
                                        })

                                    }

                                    return column
                                })
                            })
                        }

                        return row
                    })
                })
            })
        case ADD_TEMPLATE_CONTENT:
            return _.extend({}, state,{
                config: _.extend({}, state.config,{
                    items: _.map(state.config.items, function(row, keyRow){

                        if(keyRow == action.payload.keyRow){
                            return _.extend({}, row, {
                                columns: _.map(row.columns, function(column, keyColumn){
                                    if(keyColumn == action.payload.keyColumn){

                                        let _items =  column["items"]

                                        if(action.payload.abItmId == 0 && action.payload.before){
                                            _items.unshift(action.payload)
                                        }
                                        else{
                                            if(action.payload.before){
                                                _items.splice(action.payload.abItmId, 0, action.payload);
                                            }
                                            else if(action.payload.after){
                                                _items.splice(Number(action.payload.abItmId + 1), 0, action.payload);
                                            }
                                        }

                                        _items = _.map(_items, (value,key) => {
                                            value._id        = key
                                            value.fromAction = ADD_TEMPLATE_CONTENT
                                            return _.clone(value)
                                        })

                                        return _.extend({}, column, {
                                            items: _.extend([], _items)
                                        })

                                    }

                                    return column
                                })
                            })
                        }

                        return row
                    })
                })
            })

        case ADD_TEMPLATE_SECTION:

            return (() => {
                let _rows =  state.config.items

                if(_rows.length == 0){
                    _rows.push(action.payload)
                }
                else{
                    if(action.payload.abItmId == 0 && action.payload.before){
                        _rows.unshift(action.payload)
                    }
                    else{
                        if(action.payload.before){
                            _rows.splice(action.payload.abItmId, 0, action.payload);
                        }
                        else if(action.payload.after){
                            _rows.splice(Number(action.payload.abItmId + 1), 0, action.payload);
                        }
                    }
                }

                _rows = _.map(_rows, (section, keyRow) => {
                    section.columns = _.map(section.columns, (column, keyColumn) =>{
                        column.items = _.map(column.items, (item, key) => {
                            item._id        = key
                            item.keyRow     = keyRow
                            item.fromAction = ADD_TEMPLATE_SECTION
                            return _.clone(item)
                        })
                        return column
                    })

                    return section
                })

                return _.extend({}, state, {
                    config: _.extend({}, state.config, {
                        items: _.extend([], _rows)
                    })
                })

            })()

        case DUPLICATE_CONTENT:
            let _duplicateContent = state.config.items[action.payload.keyRow].columns[action.payload.keyColumn].items[action.payload._id]

            return _.extend({}, state, {
                config: _.extend({}, state.config, {
                    items: _.map(state.config.items, function(row, keyRow){
                        if(keyRow == action.payload.keyRow){
                            row = _.extend({}, row, {
                                columns: _.map(row.columns, function(column,keyColumn){
                                    if(keyColumn == action.payload.keyColumn){
                                        let _items              = column["items"]

                                        _items.splice(Number(action.payload._id + 1), 0, _.clone(_duplicateContent) );

                                        _items = _.map(_items, (value,key) => {
                                            value._id        = key
                                            value.fromAction = DUPLICATE_CONTENT
                                            return _.clone(value)
                                        })

                                        return _.extend({}, column, {
                                            items: _.extend([], _items)
                                        })
                                    }

                                    return _.clone(column)
                                })
                            })
                        }

                        return _.clone(row)
                    })
                })
            })
        case DUPLICATE_SECTION:
            let _duplicateSection = state.config.items[action.payload.keyRow]

            let _sectionsDuplicate = state.config.items

            _sectionsDuplicate.splice(Number(action.payload.keyRow + 1), 0, _.clone(_duplicateSection) )

            _sectionsDuplicate = _.map(_sectionsDuplicate, (section, keyRow) => {
                section.columns = _.map(section.columns, (column, keyColumn) =>{
                    column.items = _.map(column.items, (item, key) => {
                        const _newItem = _.extend({}, item, {
                            _id: key,
                            keyColumn: keyColumn,
                            keyRow: keyRow,
                            fromAction: DUPLICATE_SECTION
                        })

                        return _newItem
                    })

                    return _.extend({}, column, {
                        keyRow: keyRow,
                        styles : _.extend({}, column.styles)
                    })
                })

                return _.extend({}, section, {
                    styles : _.extend({}, section.styles, {
                        sizeColumnChoice: (!_.isUndefined(_duplicateSection.styles.sizeColumnChoice) ) ? _.clone(_duplicateSection.styles.sizeColumnChoice) : null
                    })
                })
            })

            return _.extend({}, state, {
                config: _.extend({}, state.config, {
                    items: _.extend([], _sectionsDuplicate)
                })
            })


        case CHANGE_POSITION_CONTENT:
            let _oldObject = _.clone(state.config.items[action.payload.old.keyRow].columns[action.payload.old.keyColumn].items[action.payload.old._id])

            return _.extend({}, state, {
                config: _.extend({}, state.config, {
                    items: _.map(state.config.items, function(row, keyRow){
                        // Splice Old Object
                        if(keyRow == action.payload.old.keyRow){
                            row = _.extend({}, row, {
                                columns: _.map(row.columns, function(column,keyColumn){
                                    if(keyColumn == action.payload.old.keyColumn){
                                        let _items = column["items"]
                                        _items.splice(action.payload.old._id, 1)

                                        _items = _.map(_items, (value,key) => {
                                            value._id        = key
                                            value.keyColumn  = keyColumn
                                            value.fromAction = CHANGE_POSITION_CONTENT
                                            return _.clone(value)
                                        })

                                        return _.extend({}, column, {
                                            items: _.extend([], _items)
                                        })
                                    }

                                    return column
                                })
                            })
                        }

                        // Push object
                        if(keyRow == action.payload.new.keyRow){
                            row = _.extend({}, row, {
                                columns: _.map(row.columns, function(column,keyColumn){
                                    if(keyColumn == action.payload.new.keyColumn){
                                        let _items              = column["items"]
                                        _oldObject.keyRow    = action.payload.new.keyRow
                                        _oldObject.keyColumn = action.payload.new.keyColumn
                                        
                                        if(_.isUndefined(action.payload.new.abItmId)){
                                            _items.push(_oldObject)
                                        }
                                        else if(action.payload.new.abItmId == 0 && action.payload.new.before){
                                            _items.unshift(_oldObject)
                                        }
                                        else{
                                            const _replaceIndex = _.findIndex(_items, (v, k) => { 
                                                return v._id == action.payload.new.abItmId
                                            })

                                            if(action.payload.new.before){
                                                _items.splice(Number(_replaceIndex), 0, _oldObject);
                                            }
                                            else if(action.payload.new.after){
                                                if(_replaceIndex >= 0 && _replaceIndex != (_items.length-1) ){
                                                    _items.splice(Number(_replaceIndex+1), 0, _oldObject);
                                                }
                                                else if(_replaceIndex >= 0){
                                                    _items.splice(Number(_replaceIndex), 0, _oldObject);
                                                }
                                                else{
                                                    _items.push(_oldObject)
                                                }
                                            }
                                        }

                                        _items = _.map(_items, (value,key) => {
                                            value._id        = key
                                            value.keyColumn  = keyColumn
                                            value.keyRow     = keyRow
                                            value.fromAction = CHANGE_POSITION_CONTENT
                                            return _.clone(value)
                                        })


                                        return _.extend({}, column, {
                                            items: _.extend([], _items)
                                        })
                                    }

                                    return column
                                })
                            })
                        }

                        return row
                    })
                })
            })

        case CHANGE_POSITION_SECTION:
            let _oldSection = state.config.items[action.payload.old]

            let _sections = state.config.items
            _sections.splice(action.payload.old, 1)

            if(_.isUndefined(action.payload.new.abItmId)){
                _sections.push(_oldSection)
            }
            else if(action.payload.new.abItmId == 0 && action.payload.new.before){
                _sections.unshift(_oldSection)
            }
            else{
                if(action.payload.new.before){
                    _sections.splice(action.payload.new.abItmId, 0, _oldSection);
                }
                else if(action.payload.new.after){
                    _sections.splice(Number(action.payload.new.abItmId + 1), 0, _oldSection);
                }
            }

            _sections = _.map(_sections, (section, keyRow) => {
                section.columns = _.map(section.columns, (column, keyColumn) =>{
                    column.items = _.map(column.items, (item, key) => {
                        item.keyRow     = keyRow
                        item.fromAction = CHANGE_POSITION_SECTION
                        return _.clone(item)
                    })
                    return column
                })

                return section
            })

            return _.extend({}, state, {
                config: _.extend({}, state.config, {
                    items: _.extend([], _sections)
                })
            })

        case DELETE_CONTENT:
            return _.extend({}, state, {
                config: _.extend({}, state.config, {
                    items: _.map(state.config.items, function(row, keyRow){
                        if(keyRow == action.payload.keyRow){
                            return _.extend({}, row, {
                                columns: _.map(row.columns, function(column,keyColumn){
                                    if(keyColumn == action.payload.keyColumn){
                                        let _items = _.clone(column["items"])

                                        _items.splice(action.payload._id, 1)

                                        _items = _.map(_items, (value, key) => {
                                            value.fromAction = DELETE_CONTENT
                                            value._id     = key
                                            return _.clone(value)
                                        })

                                        return _.extend({}, column, {
                                            items: _.extend([], _items)
                                        })
                                    }

                                    return column
                                })
                            })
                        }

                        return row
                    })
                })
            })

        case DELETE_SECTION:
            let _rows = state.config.items
            _rows.splice(action.payload, 1)

            _rows = _.map(_rows, (section, keyRow) => {
                section.columns = _.map(section.columns, (column, keyColumn) =>{
                    column.items = _.map(column.items, (item, key) => {
                        item.keyRow     = keyRow
                        item.fromAction = DELETE_SECTION
                        return _.clone(item)
                    })
                    return column
                })

                return section
            })

            return _.extend({}, state, {
                config: _.extend({}, state.config, {
                    items: _.extend([], _rows)
                })
            })

        case CHANGE_ITEM:
            return _.extend({}, state,{
                config: _.extend({}, state.config, {
                    items :  _.map(state.config.items, function(row, keyRow){
                        if(keyRow == action.payload.keyRow){

                            return _.extend({}, row, {
                                columns: _.map(row.columns, function(column, keyColumn){

                                    if(keyColumn == action.payload.keyColumn){

                                        return _.extend({}, column, {
                                            items: _.map(column.items, function(item, key){
                                                if(item._id == action.payload._id){
                                                    let _newItem  = _.extend(item, _.omit(action.payload, "value") )
                                                    _newItem.fromAction = CHANGE_ITEM
                                                    return _.clone(_newItem)
                                                }

                                                return item
                                            })

                                        })
                                    }

                                    return column
                                })

                            })

                        }

                        return row
                    })
                })
            })
        case CHANGE_ITEM_TEXT:
            return _.extend({}, state,{
                config: _.extend({}, state.config, {
                    items :  _.map(state.config.items, function(row, keyRow){
                        if(keyRow == action.payload.keyRow){

                            return _.extend({}, row, {
                                columns: _.map(row.columns, function(column, keyColumn){

                                    if(keyColumn == action.payload.keyColumn){

                                        return _.extend({}, column, {
                                            items: _.map(column.items, function(item, key){
                                                if(item._id == action.payload._id){
                                                    let _newItem        = item
                                                    _newItem.value      = action.payload.value
                                                    _newItem.fromAction = CHANGE_ITEM_TEXT

                                                    return _.clone(_newItem)
                                                }

                                                return item
                                            })

                                        })
                                    }

                                    return column
                                })

                            })

                        }

                        return row
                    })
                })
            })
        case ACTIVE_ITEM:
            if(_.isNull(action.payload)){
                return state
            }
            
            const arr = action.payload.split("_")

            switch(arr[0]){
                case "unsubscribe":
                    state.config.unsubscribe[0].columns[arr[1]].items[arr[2]].fromAction = ACTIVE_ITEM
                    break;
                case "email":
                    state.config.email_online[0].columns[arr[2]].items[arr[3]].fromAction = ACTIVE_ITEM
                    break;
                default:
                    state.config.items[arr[0]].columns[arr[1]].items[arr[2]].fromAction = ACTIVE_ITEM
                    break;
            }

            return _.extend({}, state)
            
        case CHOICE_ITEMS_TEMPLATE:
            return _.extend({}, state, {
                config: _.extend({}, state.config, {
                    items: _.extend({}, action.payload)
                })
            })

        case CHOICE_STRUCTURE_TEMPLATE:
            return _.extend({}, state, {
                template: _.extend({}, action.payload)
            })
        case REQUEST_SAVE_TEMPLATE_SUCCESS:
            return _.extend({}, state, {
                templateId: _.extend({}, action.payload.data.data.results.id),
            })
        case REQUEST_GET_CAMPAIGN_SUCCESS:
            
            if(_.isNull(action.payload.data.data.results.config)){
                return _.extend({}, state, {
                    config: _.extend({},state.config, {
                        loaded : true
                    }),
                    campaignId: action.payload.data.data.results.post.ID
                })
            }

            let _config = _.extend({}, action.payload.data.data.results.config,{
                items: _.map(action.payload.data.data.results.config.items, (section, keyRow) => {
                    section.columns = _.map(section.columns, (column, keyColumn) =>{
                        column.items = _.map(column.items, (item, key) => {
                            item._id        = key
                            item.fromAction = REQUEST_GET_CAMPAIGN
                            return item
                        })

                        return column
                    })

                    return section
                })
            })

            // Everything loaded
            _config.loaded = true

            return _.extend({}, state, {
                config: _config,
                campaignId: action.payload.data.data.results.post.ID
            })
        case CHANGE_THEME:
            return _.extend({}, state, {
                config : _.extend({}, state.config, {
                    theme : action.payload
                })
            })

        case REQUEST_GET_POST_TO_WP_POST_SUCCESS:
            return _.extend({}, state,{
                config: _.extend({}, state.config, {
                    items: _.map(state.config.items, function(row, keyRow){

                        if(keyRow == action.payload.extras.keyRow){

                            return _.extend({}, row, {
                                columns: _.map(row.columns, function(column, keyColumn){

                                    if(keyColumn == action.payload.extras.keyColumn){

                                        const wpPost = action.payload.data.data.results.post    

                                        let _items        = column.items
                                        
                                        let _paramsButton = {
                                            post: wpPost
                                        }
                                        if(wpPost.post_type === "product"){
                                            _paramsButton["woocommerce"] = action.payload.data.data.results.woocommerce
                                        }

                                        if(
                                            action.payload.extras.wp_post.options.image &&
                                            action.payload.data.data.results.image
                                        ){
                                            
                                            
                                            let _newItemImage = createImageFromWPPost(action)
                                            let _newItemTitle = createTitleFromImportWPPost(wpPost)
                                            let _newItem      = createTextFromWPPost(action)
                                            let _newButton    = createButtonReadMoreFromImportWPPost(_paramsButton)

                                            _.each(column.items, (item, key) => {
                                               if(key == action.payload.extras._id){
                                                   _items[key] = _.extend({}, _newItemImage)
                                                   _items.splice(Number(key+1), 0, _newItemTitle);
                                                   _items.splice(Number(key+2), 0, _newItem);
                                                   _items.splice(Number(key+3), 0, _newButton);
                                               }
                                            })
                                        }
                                        else{
                                            let _newItemTitle = createTitleFromImportWPPost(wpPost)
                                            let _newItem      = createTextFromWPPost(action)
                                            let _newButton    = createButtonReadMoreFromImportWPPost(_paramsButton)                              

                                            _.each(column.items, (item, key) => {
                                               if(key == action.payload.extras._id){
                                                   _items[key] = _.extend({}, _newItemTitle)
                                                   _items.splice(Number(key+1), 0, _newItem);
                                                   _items.splice(Number(key+2), 0, _newButton);
                                               }
                                            })
                                        }

                                        _items = _.map(_items, (item, key) =>{
                                            item._id        = key
                                            item.keyColumn  = keyColumn
                                            item.keyRow     = keyRow
                                            item.fromAction = REQUEST_GET_POST_TO_WP_POST_SUCCESS
                                            return item
                                        })

                                        return _.extend({}, column, {
                                            items: _items
                                        })
                                    }

                                    return column
                                })

                            })

                        }

                        return row
                    })
                })
            })
        case REQUEST_IMPORT_POSTS_WP_SUCCESS:
            return _.extend({}, state,{
                config: _.extend({}, state.config, {
                    items: _.map(state.config.items, function(row, keyRow){

                        if(keyRow == action.payload.extras.keyRow){

                            return _.extend({}, row, {
                                columns: _.map(row.columns, function(column, keyColumn){

                                    if(keyColumn == action.payload.extras.keyColumn){

                                        let _items        = _.clone(column.items)
                                        let _newItems     = []

                                        _.each(action.payload.data.data.results.posts, (value, key) => {
                                            if(action.payload.data.data.results.config.image != "false" && value.image){
                                                _newItems.push(createImageFromImportWPPost(value.image, value.attrs_image, action.payload.extras))
                                            }
                                            
                                            _newItems.push(createTitleFromImportWPPost(value.post, action.payload.extras) )

                                            _newItems.push(createTextFromImportWPPost({ 
                                                post : value.post,
                                                attrs_post : value.attrs_post,
                                                type_content: action.payload.data.data.results.config.type_content
                                            }, action.payload.extras))

                                            let _paramsButton = {
                                                post: value.post
                                            }
                                            if(value.post.post_type === "product"){
                                                _paramsButton["woocommerce"] = value.woocommerce
                                            }
    
                                            _newItems.push(
                                                createButtonReadMoreFromImportWPPost(_paramsButton)
                                            )

                                        })

                                        _.each(_newItems, (newItm, key) => {
                                            if(key === 0){
                                                _items[action.payload.extras._id] = newItm
                                            }
                                            else{
                                                const onId = Number(action.payload.extras._id + key)
                                                _items.splice(onId, 0, newItm)

                                            }
                                        })

                                        _items = _.map(_items, (item, key) =>{
                                            item._id            = key
                                            item.keyColumn      = keyColumn
                                            item.keyRow         = keyRow
                                            item.fromAction     = REQUEST_IMPORT_POSTS_WP_SUCCESS
                                            return item
                                        })

                                        return _.extend({}, column, {
                                            items: _items
                                        })
                                    }

                                    return column
                                })

                            })

                        }

                        return row
                    })
                })
            })
        default:
            return state
    }
}

export default customize
