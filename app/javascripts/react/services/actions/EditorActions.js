import {
    CHANGE_SETTINGS_COMPONENT,
    CHANGE_COMPONENT,
    CHANGE_ITEM,
    CHANGE_ITEM_TEXT,
    ACTIVE_ITEM,
    ACTIVE_SECTION,
    CHANGE_STYLE_SECTION,
    CHANGE_STYLE_SECTION_FIX,
    CHANGE_STYLE_COMPONENT_FIX,
    DELETE_SECTION_EMAIL_ONLINE,
    CHANGE_STYLE_COLUMN,
    CHANGE_STYLE_COLUMNS,
    UPDATE_SECTION_EMAIL_ONLINE,
    UPDATE_ALL_STYLES
} from 'javascripts/react/constants/EditorConstants'


class EditorActions  {

    constructor(){
        
        this.changeSettingsComponent       = this.changeSettingsComponent.bind(this)
        this.changeItem                    = this.changeItem.bind(this)
        this.changeItemText                = this.changeItemText.bind(this)
        this.changeItemOnSettingsContainer = this.changeItemOnSettingsContainer.bind(this)
        this.activeItem                    = this.activeItem.bind(this)
        this.activeSection                 = this.activeSection.bind(this)
        this.changeStyleSection            = this.changeStyleSection.bind(this)
        this.changeStyleSectionFix         = this.changeStyleSectionFix.bind(this)
        this.changeStyleComponentFix       = this.changeStyleComponentFix.bind(this)
        this.deleteSectionEmailOnline      = this.deleteSectionEmailOnline.bind(this)
        this.changeStyleColumn             = this.changeStyleColumn.bind(this)
        this.changeStyleColumns            = this.changeStyleColumns.bind(this)
        this.changeEmailOnlineActive       = this.changeEmailOnlineActive.bind(this)
        this.updateAllStyles               = this.updateAllStyles.bind(this)

    }
    
    updateAllStyles(payload){

        return (dispatch) => {
            dispatch({
                "type" : UPDATE_ALL_STYLES,
                "payload" : payload
            })
            
            return Promise.resolve()
        }
    }

    
    changeEmailOnlineActive(payload){
        return (dispatch) =>{
            dispatch({
                "type" : UPDATE_SECTION_EMAIL_ONLINE,
                "payload" : payload
            })
            return Promise.resolve()
        }
    }


    changeItemOnSettingsContainer(item, component){
        return (dispatch) =>{
            dispatch({
                "type" : CHANGE_SETTINGS_COMPONENT,
                "payload" : {
                    "component" : component,
                    "item" : item
                }
            })
            return Promise.resolve()
        }
    }

    changeSettingsComponent(component) {
        return (dispatch) =>{
            dispatch({
                "type" : CHANGE_COMPONENT,
                "payload" : component
            })
            return Promise.resolve()
        }
    }

    changeItem(item){
        return (dispatch) =>{
            dispatch({
                "type" : CHANGE_ITEM,
                "payload" : item
            })
            return Promise.resolve()
        }
    }

    changeItemText(item){
        return (dispatch) =>{
            dispatch({
                "type" : CHANGE_ITEM_TEXT,
                "payload" : item
            })
            return Promise.resolve()
        }
    }

    changeStyleColumn(payload){
        return (dispatch) =>{
            dispatch({
                "type" : CHANGE_STYLE_COLUMN,
                "payload" : payload
            })
            return Promise.resolve()
        }
    }

    changeStyleColumns(payload){
        return (dispatch) =>{
            dispatch({
                "type" : CHANGE_STYLE_COLUMNS,
                "payload" : payload
            })
            return Promise.resolve()
        }
    }

    changeStyleSection(payload){
        return (dispatch) =>{
            dispatch({
                "type" : CHANGE_STYLE_SECTION,
                "payload" : payload
            })
            return Promise.resolve()
        }
    }

    changeStyleSectionFix(section){
        return (dispatch) =>{
            dispatch({
                "type" : CHANGE_STYLE_SECTION_FIX,
                "payload" : section
            })
            return Promise.resolve()
        }
    }

    deleteSectionEmailOnline(section){
        return (dispatch) =>{
            dispatch({
                "type" : DELETE_SECTION_EMAIL_ONLINE,
                "payload" : section
            })
            return Promise.resolve()
        }
    }

    changeStyleComponentFix(item){
        return (dispatch) =>{
            dispatch({
                "type" : CHANGE_STYLE_COMPONENT_FIX,
                "payload" : item
            })
            return Promise.resolve()
        }
    }

    activeItem(payload){
        return (dispatch) =>{
            dispatch({
                "type" : ACTIVE_ITEM,
                "payload" : payload
            })
            return Promise.resolve()
        }
    }

    activeSection(payload){
        return (dispatch) =>{
            dispatch({
                "type" : ACTIVE_SECTION,
                "payload" : payload
            })

            return Promise.resolve()
        }
    }

}

export default EditorActions
