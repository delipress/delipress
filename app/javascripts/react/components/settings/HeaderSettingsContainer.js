import React, { Component, cloneElement } from 'react'
import { connect } from 'react-redux'
import { compose, bindActionCreators } from 'redux'

import {
    SETTINGS_LIST_CONTENTS,
    SETTINGS_STYLE,
    SETTINGS_CONTENT,
    SETTINGS_EDITOR,
    SETTINGS_GENERAL,
    SETTINGS_TEMPLATE
} from "javascripts/react/constants/EditorConstants"

import {
    TEXT,
    SECTION,
    SECTION_EMAIL_ONLINE,
    SECTION_UNSUBSCRIBE,
    EMAIL_ONLINE,
    WP_CUSTOM_POST,
    WP_ARTICLE,
    WP_ARCHIVE_CUSTOM_POST,
    WP_ARCHIVE_ARTICLE,
    WP_WOO_ARCHIVE_PRODUCT,
    WP_WOO_PRODUCT,
    SPACER
} from 'javascripts/react/constants/TemplateContentConstants'

import EditorActions from 'javascripts/react/services/actions/EditorActions'

import classNames from 'classnames'

class HeaderSettingsContainer extends Component {
    constructor(props, ctx) {
        super(props, ctx)

        this.renderListComponentsTab = this.renderListComponentsTab.bind(this)
        this.renderSettingsGeneralTab = this.renderSettingsGeneralTab.bind(this)
        this.renderEditorTab = this.renderEditorTab.bind(this)
        this.renderStyleTab = this.renderStyleTab.bind(this)
    }

    renderListComponentsTab() {
        const { component, actionsEditor } = this.props

        let _classes = classNames({
            delipress__tabs__item: true,
            delipress__isactive: component == SETTINGS_LIST_CONTENTS
        })

        return (
            <div
                key="tab_list-component"
                className={_classes}
                onClick={() =>
                    actionsEditor.changeSettingsComponent(
                        SETTINGS_LIST_CONTENTS
                    )
                }
            >
                <span className="dashicons dashicons-screenoptions" />
                {translationDelipressReact.Builder.header_settings.content_tab}
            </div>
        )
    }

    renderSettingsGeneralTab() {
        const { component, actionsEditor } = this.props

        let _classes = classNames({
            delipress__tabs__item: true,
            delipress__isactive: component == SETTINGS_GENERAL
        })

        return (
            <div
                key="tab_settings-general"
                className={_classes}
                onClick={() =>
                    actionsEditor.changeSettingsComponent(SETTINGS_GENERAL)
                }
            >
                <span className="dashicons dashicons-admin-generic" />
                {translationDelipressReact.Builder.header_settings.settings_tab}
            </div>
        )
    }

    renderSettingsTemplateTab() {
        const { component, actionsEditor } = this.props

        let _classes = classNames({
            delipress__tabs__item: true,
            delipress__isactive: component == SETTINGS_TEMPLATE
        })

        return (
            <div
                key="tab_settings-template"
                className={_classes}
                onClick={() =>
                    actionsEditor.changeSettingsComponent(SETTINGS_TEMPLATE)
                }
            >
                <span className="dashicons dashicons-art" />
                {translationDelipressReact.Builder.header_settings.template_tab}
            </div>
        )
    }

    renderHeaderListComponents() {
        let _arr = []

        _arr.push(this.renderListComponentsTab())
        _arr.push(this.renderSettingsGeneralTab())
        _arr.push(this.renderSettingsTemplateTab())

        return _arr
    }

    renderEditorTab() {
        const { component, actionsEditor } = this.props

        let _classesE = classNames({
            delipress__tabs__item: true,
            delipress__isactive: component == SETTINGS_EDITOR
        })

        return (
            <div
                key="tab_editor"
                className={_classesE}
                onClick={() =>
                    actionsEditor.changeSettingsComponent(SETTINGS_EDITOR)
                }
            >
                {translationDelipressReact.Builder.header_settings.editor_tab}
            </div>
        )
    }

    renderStyleTab() {
        const { component, actionsEditor } = this.props

        let _classesS = classNames({
            delipress__tabs__item: true,
            delipress__isactive: component == SETTINGS_STYLE
        })

        return (
            <div
                key="tab_style"
                className={_classesS}
                onClick={() =>
                    actionsEditor.changeSettingsComponent(SETTINGS_STYLE)
                }
            >
                {translationDelipressReact.Builder.header_settings.style_tab}
            </div>
        )
    }

    renderCloseTab() {
        const { actionsEditor } = this.props

        return (
            <div
                key="tab_close"
                className="delipress__tabs__item delipress__tabs__item--close"
                onClick={() => {
                    actionsEditor.changeSettingsComponent(
                        SETTINGS_LIST_CONTENTS
                    )
                    actionsEditor.activeItem(null)
                }}
            >
                <span className="dashicons dashicons-arrow-left-alt2" />
            </div>
        )
    }

    renderHeaderDefault() {
        const { item } = this.props

        let _tabs = []
        switch (item.type) {
            case SECTION:
            case SECTION_EMAIL_ONLINE:
            case SECTION_UNSUBSCRIBE:
                _tabs.push(this.renderCloseTab())
                _tabs.push(this.renderStyleTab())
                break
            case WP_CUSTOM_POST:
            case WP_ARTICLE:
            case WP_ARCHIVE_CUSTOM_POST:
            case WP_ARCHIVE_ARTICLE:
            case WP_WOO_ARCHIVE_PRODUCT:
            case WP_WOO_PRODUCT:
            case SPACER:
                _tabs.push(this.renderCloseTab())
                _tabs.push(this.renderEditorTab())
                break
            default:
                _tabs.push(this.renderCloseTab())
                _tabs.push(this.renderEditorTab())
                _tabs.push(this.renderStyleTab())
                break
        }

        return _tabs
    }

    render() {
        const { component } = this.props

        let _tabComponent = false
        let _classNames = false
        switch (component) {
            case SETTINGS_LIST_CONTENTS:
            case SETTINGS_GENERAL:
            case SETTINGS_TEMPLATE:
                _tabComponent = this.renderHeaderListComponents()
                _classNames = classNames({ "elements-1": _tabComponent.length == 1, "elements-2": _tabComponent.length == 2, "elements-3": _tabComponent.length == 3 }, "delipress__tabs", "delipress__tabs--small")
                break
            default:
                _tabComponent = this.renderHeaderDefault()
                _classNames = classNames({ "elements-1": _tabComponent.length == 1, "elements-2": _tabComponent.length == 2, "elements-3": _tabComponent.length == 3 }, "delipress__tabs", "delipress__tabs--small")
                break
        }

        return (
            <div id="tabs__settings " className={_classNames}>
                {_tabComponent}
            </div>
        )
    }
}

function mapDispatchToProps(dispatch, context){
    let actionsEditor = new EditorActions()
    return {
        "actionsEditor" : bindActionCreators(actionsEditor, dispatch)
    }
}


export default connect(null, mapDispatchToProps)(HeaderSettingsContainer)
