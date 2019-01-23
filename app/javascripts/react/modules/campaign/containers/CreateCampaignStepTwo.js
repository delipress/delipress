import React, { Component, cloneElement } from 'react'
import { connect } from 'react-redux'
import { compose, bindActionCreators } from 'redux'
import * as _ from 'underscore'

import { shallowEqual } from 'javascripts/react/helpers/shallowEqual'
import ListContentsComponent from 'javascripts/react/components/settings/ListContentsComponent'

import Routes from 'javascripts/react/constants/Routes'

import {
    SETTINGS_STYLE,
    SETTINGS_EDITOR,
    SETTINGS_LIST_CONTENTS
} from 'javascripts/react/constants/EditorConstants'

import TemplateActions from 'javascripts/react/services/actions/TemplateActions'
import PostTypeActions from 'javascripts/react/services/actions/PostTypeActions'
import EditorActions from 'javascripts/react/services/actions/EditorActions'
import { transformToMjml } from 'javascripts/react/helpers/transformToMjml'

import PreviewContainer from 'javascripts/react/containers/PreviewContainer'
import SettingsContainer from 'javascripts/react/containers/SettingsContainer'
import ComponentSetting from 'javascripts/react/containers/campaigns/ComponentSetting'
import HeaderSettingsContainer from 'javascripts/react/components/settings/HeaderSettingsContainer'

class CreateCampaignStepTwo extends Component  {

    constructor(props){
        super(props)

        this._writeCSS                = this._writeCSS.bind(this)
        this._handleChangeItemSuccess = this._handleChangeItemSuccess.bind(this)
        this.cleanTemplate            = this.cleanTemplate.bind(this)
        this.moveItem                 = this.moveItem.bind(this)
        this.addItem                  = this.addItem.bind(this)
        this.moveSection              = this.moveSection.bind(this)
        this.addSection               = this.addSection.bind(this)
        this.addItemOnEmpty           = this.addItemOnEmpty.bind(this)
    }

    _writeCSS(){

         const {
            theme
        } = this.props

        if(_.isNull(theme)){
            return;
        }

        let _textColor = "#000000"
        if(!_.isUndefined(theme["mj-attributes"]["mj-text"].color.rgb)){
            const color = theme["mj-attributes"]["mj-text"].color.rgb
            _textColor = `rgba(${color.r}, ${color.g}, ${color.b}, ${color.a})`
        }
        else{
            _textColor = theme["mj-attributes"]["mj-text"].color.hex
        }

        let _linkColor = "#000000"
        if(!_.isUndefined(theme["mj-styles"]["link-color"].rgb)){
            const color = theme["mj-styles"]["link-color"].rgb
            _linkColor = `rgba(${color.r}, ${color.g}, ${color.b}, ${color.a})`
        }
        else{
            _linkColor = theme["mj-styles"]["link-color"].hex
        }


        let css = "\n#delipress-react-selector .delipress__builder__main { font-family:Ubuntu, Helvetica, Arial, sans-serif; }"
        css += "\n#delipress-react-selector .delipress__builder__main .content__text { font-size:13px; }"

        css += `\n#delipress-react-selector .delipress__content__text p,
        #delipress-react-selector .delipress__content__text p,
        #delipress-react-selector .delipress__content__text ul,
        #delipress-react-selector .delipress__content__text li,
        #delipress-react-selector .delipress__content__text ol,
        #delipress-react-selector .delipress__content__social a,
        #delipress-react-selector .delipress__content__text a,
        #delipress-react-selector .delipress__content__text h1,
        #delipress-react-selector .delipress__content__text h2,
        #delipress-react-selector .delipress__content__text h3{
            color: ${_textColor};
        }`

        css += `\n#delipress-react-selector .delipress__content__text * { color: currentcolor; }`

        css += `\n#delipress-react-selector .delipress__content__text a,
            #delipress-react-selector .delipress__content__header_footer a{
            text-decoration:underline;
            text-decoration-color:${_linkColor};
            color: ${_linkColor};
        }`

        // Fix header / footer link in builder
        css += `\n#delipress-react-selector .email_online a,
            #delipress-react-selector .unsubscribe a {
            color: inherit !important;
            text-decoration-color: inherit !important;
        }`


        return css
    }


    componentWillMount(){
        const {
            actionsTemplate,
            actionsPostType
        } = this.props

        if(!_.isEmpty(DELIPRESS_CAMPAIGN_ID)){
            actionsTemplate.getCampaign(DELIPRESS_CAMPAIGN_ID)
        }

        actionsPostType.getPostTypes()

        this.paramsSettingsComponent = {
            changeItemSuccess : this._handleChangeItemSuccess
        }
    }

    _handleChangeItemSuccess(){
        const {
            config,
            actionsTemplate
        } = this.props

        clearTimeout(this._handleChangeItemTimeout)

        this._handleChangeItemTimeout = setTimeout(() => {
            actionsTemplate.saveCampaignTemplate(DELIPRESS_CAMPAIGN_ID, {
                "config" : config,
            })
        }, 1000)
    }


    transform(){
        const { config } = this.props

        transformToMjml(config)
    }

    deferredAddItem(deferred, newItem){
         const {
            actionsEditor
        } = this.props

        deferred.then(this.paramsSettingsComponent.changeItemSuccess)
                .then(() => {
                    const keyComponent = `${newItem.keyRow}_${newItem.keyColumn}_${newItem._id}`
                    actionsEditor.activeItem(keyComponent).then(() => {
                        actionsEditor.changeItemOnSettingsContainer(newItem, SETTINGS_EDITOR)
                    })
                })
    }

    addItem(newItem){
        const {
            actionsTemplate
        } = this.props

        const deferred = actionsTemplate.addTemplateContent(newItem)

        this.deferredAddItem(deferred, newItem)
    }

    addItemOnEmpty(newItem){
        const {
            actionsTemplate,
            actionsEditor
        } = this.props

        const deferred = actionsTemplate.addTemplateContentOnEmpty(newItem)

        this.deferredAddItem(deferred, newItem)
    }

    cleanTemplate(){
        const {
            actionsTemplate
        } = this.props

        const deferred = actionsTemplate.cleanTemplate()

        deferred.then(this.paramsSettingsComponent.changeItemSuccess)
    }

    moveItem(oldItem, newItem) {
        const {
            actionsTemplate,
            actionsEditor
        } = this.props


        const payload = {
            "old" : oldItem,
            "new" : newItem
        }

        actionsEditor.activeItem(null).then(() => {

            const deferred = actionsTemplate.changePositionTemplateContent(payload)
            deferred.then(this.paramsSettingsComponent.changeItemSuccess)
        })
    }

    moveSection(oldSection, newSection){
        const {
            actionsTemplate,
            actionsEditor
        } = this.props


        const payload = {
            "old" : oldSection,
            "new" : newSection
        }

        actionsEditor.activeSection(null).then(() => {

            const deferred = actionsTemplate.changePositionTemplateSection(payload)
            deferred.then(this.paramsSettingsComponent.changeItemSuccess)
        })

    }

    addSection(newSection, addComponent = null){
        const {
            actionsTemplate,
            actionsEditor
        } = this.props

        const deferred = actionsTemplate.addSection(newSection)

        deferred.then(this.paramsSettingsComponent.changeItemSuccess)

        if(addComponent !== null){
            deferred.then(() => { this.addItemOnEmpty(addComponent) })
        }
        // .then(() => { actionsEditor.changeItemOnSettingsContainer(newSection, SETTINGS_STYLE) })
    }



    saveTemplate(){
        const {
            actionsTemplate,
            template,
            config
        } = this.props

        const result = transformToMjml(config)

        actionsTemplate.saveTemplate({
            template: template,
            config : config,
            html: result.html
        }, this.templateId)
    }

    componentDidMount(){
        jQuery(document).on("click", ".delipress__builder__main__preview a", function(e){
            e.preventDefault();
        })
    }

    render() {
        const {
            actionsEditor,
            component,
            item,
            isOpen
        } = this.props

        return (
            <div className="delipress__builder" onClick={(e) => {
                    const srcTarget = e.target || e.srcElement

                    if(
                        jQuery(srcTarget).has("#email__online__component").length > 0 ||
                        jQuery(srcTarget).has("#unsubscribe__component").length > 0 ||
                        jQuery(srcTarget).hasClass("row") ||
                        jQuery(srcTarget).hasClass("delipress__builder__main__preview__addcomponent") ||
                        jQuery(srcTarget).parent().hasClass("content__empty") ||
                        jQuery(srcTarget).hasClass("span__content__empty") ||
                        jQuery(srcTarget).hasClass("delipress__builder__main__preview__dropzone") ||
                        jQuery(srcTarget).hasClass("delipress__builder__main__preview__scroll")

                    ){
                        actionsEditor.changeSettingsComponent(SETTINGS_LIST_CONTENTS)
                        actionsEditor.activeItem(null)
                        actionsEditor.activeSection(null)
                    }
                }}>
                <style>{this._writeCSS()}</style>
                <SettingsContainer
                    paramsSettingsComponent={this.paramsSettingsComponent}
                >
                    <HeaderSettingsContainer
                        component={component}
                        item={item}
                        actionsEditor={actionsEditor}
                    />
                    <ComponentSetting
                        component={component}
                        item={item}
                        paramsSettingsComponent={this.paramsSettingsComponent}
                        actionsEditor={actionsEditor}
                    />
                </SettingsContainer>
                <PreviewContainer
                    cleanTemplate={this.cleanTemplate}
                    moveItem={this.moveItem}
                    addItem={this.addItem}
                    moveSection={this.moveSection}
                    addSection={this.addSection}
                    addItemOnEmpty={this.addItemOnEmpty}
                    paramsSettingsComponent={this.paramsSettingsComponent}
                />
            </div>
        )
    }
}

function mapStateToProps(state){
    return { 
        "template" : state.TemplateReducer.template,
        "config" : state.TemplateReducer.config,
        "theme" : state.TemplateReducer.config.theme,
        "isOpen" : state.EditorReducer.isOpen,
        "component" : state.EditorReducer.component,
        "item" : state.EditorReducer.item
    }
}

function mapDispatchToProps(dispatch, context){
    const actionsTemplate = new TemplateActions()
    const actionsPostType = new PostTypeActions()
    const actionsEditor   = new EditorActions()

    return {
        "actionsTemplate": bindActionCreators(actionsTemplate, dispatch),
        "actionsPostType": bindActionCreators(actionsPostType, dispatch),
        "actionsEditor": bindActionCreators(actionsEditor, dispatch)
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(CreateCampaignStepTwo)
