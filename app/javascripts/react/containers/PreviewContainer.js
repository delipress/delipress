import * as _ from "underscore"
import React, { Component, cloneElement } from "react"
import PropTypes from "prop-types"
import classNames from "classnames"
import { connect } from "react-redux"
import { compose, bindActionCreators } from "redux"

import DeliAlert from "javascripts/backend/DeliAlert"

import RowComponent from "javascripts/react/components/RowComponent"
import ColumnComponent from "javascripts/react/components/ColumnComponent"
import TemplateActions from "javascripts/react/services/actions/TemplateActions"
import MoveSection from "javascripts/react/components/dnd/MoveSection"
import EmptySection from "javascripts/react/components/dnd/EmptySection"
import Loader from "javascripts/react/components/misc/Loader"
import SavingIndicator from "javascripts/react/components/misc/SavingIndicator"
import PreviewCampaign from "javascripts/react/modules/preview/containers/PreviewCampaign"
import MjmlContainer from "javascripts/react/containers/MjmlContainer"

import {
    SECTION_EMAIL_ONLINE,
    SECTION_UNSUBSCRIBE
} from "javascripts/react/constants/TemplateContentConstants"

class PreviewContainer extends Component {
    constructor(props) {
        super(props)

        this._toggleFullscreen = this._toggleFullscreen.bind(this)
        this._cleanTemplate = this._cleanTemplate.bind(this)
        this._togglePreview = this._togglePreview.bind(this)
    }

    componentWillMount() {
        this.setState({
            fullscreen: false,
            preview: false
        })
    }

    _toggleFullscreen(e) {
        e.preventDefault()
        const $ = jQuery
        this.setState({
            fullscreen: !this.state.fullscreen
        })

        $(".delipress").toggleClass("delipress--fullscreen")
    }

    _cleanTemplate(e) {
        e.preventDefault()
        const deliAlert = new DeliAlert()
        deliAlert.handle(() => {
            this.props.cleanTemplate()
        })
        deliAlert.show(
            translationDelipressReact.Builder.actions.clear_warning,
            "",
            "delete"
        )
    }

    _togglePreview(e) {
        e.preventDefault()
        this.setState({
            preview: !this.state.preview
        })
        jQuery("body").toggleClass("mjml-preview-on")
    }

    render() {
        const {
            items,
            moveItem,
            addItem,
            addItemOnEmpty,
            moveSection,
            addSection,
            paramsSettingsComponent,
            theme,
            email_online,
            unsubscribe,
            email_online_active
        } = this.props

        let _styleArea = {}

        if (!_.isNull(theme)) {
            let _bg = "transparent"
            if (
                !_.isUndefined(
                    theme["mj-attributes"]["mj-container"]["background-color"]
                        .rgb
                )
            ) {
                const color =
                    theme["mj-attributes"]["mj-container"]["background-color"]
                        .rgb
                _bg = `rgba(${color.r}, ${color.g}, ${color.b}, ${color.a})`
            } else {
                _bg =
                    theme["mj-attributes"]["mj-container"]["background-color"]
                        .hex
            }
            _styleArea = {
                backgroundColor: _bg
            }
        }

        let _rowsEmailOnline = false

        if (email_online_active) {
            _rowsEmailOnline = _.map(email_online, (row, key) => {
                return (
                    <RowComponent
                        key={`email_online_${key}`}
                        row={row}
                        keyRow="email_online"
                        paramsSettingsComponent={paramsSettingsComponent}
                        fixItem={true}
                        typeActiveSection={SECTION_EMAIL_ONLINE}
                    />
                )
            })
        }

        let _rows = false

        if (items.length > 0) {
            _rows = _.map(items, (row, key) => {
                return (
                    <MoveSection
                        key={"row_" + key}
                        index={`${Number(key)}`}
                        item={row}
                        moveSection={moveSection}
                        addSection={addSection}
                    >
                        <RowComponent
                            moveItem={moveItem}
                            addItem={addItem}
                            addItemOnEmpty={addItemOnEmpty}
                            row={row}
                            keyRow={Number(key)}
                            fixItem={false}
                            paramsSettingsComponent={paramsSettingsComponent}
                        />
                    </MoveSection>
                )
            })
        } else {
            _rows = (
                <EmptySection
                    key={"row_empty"}
                    index={0}
                    moveSection={moveSection}
                    addSection={addSection}
                />
            )
        }

        let _rowsUnsubscribe = _.map(unsubscribe, (row, key) => {
            return (
                <RowComponent
                    key={`unsubscribe_${key}`}
                    row={row}
                    keyRow="unsubscribe"
                    paramsSettingsComponent={paramsSettingsComponent}
                    fixItem={true}
                    typeActiveSection={SECTION_UNSUBSCRIBE}
                />
            )
        })

        const _classFullscreen = classNames({
            "dashicons-editor-contract": this.state.fullscreen,
            "dashicons-editor-expand": !this.state.fullscreen,
            dashicons: true
        })

        const _classLoader = classNames({
            "is-visible": !this.props.loaded,
            delipress__builder__main__preview__loader: true
        })

        const fullscreenText = this.state.fullscreen
            ? translationDelipressReact.Builder.containers.actions.exit_fullscreen
            : translationDelipressReact.Builder.containers.actions.fullscreen

        return (
            <div className="delipress__builder__main" style={_styleArea}>
                <div className="delipress__builder__main__actions">
                    <a href="#" onClick={this._toggleFullscreen}>
                        <span className={_classFullscreen} />
                        {fullscreenText}
                    </a>
                    <a href="#" onClick={this._cleanTemplate}>
                        <span className="dashicons dashicons-editor-removeformatting" />
                        {
                            translationDelipressReact.Builder.containers.actions
                                .clear
                        }
                    </a>
                    <SavingIndicator saving={this.props.saving} />
                    <a
                        href={DELIPRESS_URLS.PREVIEW_CAMPAIGN}
                        onClick={this._togglePreview}
                    >
                        <span className="dashicons dashicons-visibility" />
                        {
                            translationDelipressReact.Builder.containers.actions
                                .preview_campaign
                        }
                    </a>
                </div>
                <div className="delipress__builder__main__preview">
                    <div className={_classLoader}>
                        <Loader />
                    </div>
                    <div className="delipress__builder__main__preview__scroll">
                        {_rowsEmailOnline}
                        {_rows}
                        {_rowsUnsubscribe}
                    </div>
                </div>
                <MjmlContainer togglePreview={this._togglePreview} visible={this.state.preview}>
                    <PreviewCampaign />
                </MjmlContainer>
            </div>
        )
    }
}

function mapStateToProps(state) {
    return {
        items: state.TemplateReducer.config.items,
        theme: state.TemplateReducer.config.theme,
        loaded: state.TemplateReducer.config.loaded,
        unsubscribe: state.TemplateReducer.config.unsubscribe,
        email_online: state.TemplateReducer.config.email_online,
        email_online_active: state.TemplateReducer.config.email_online_active,
        saving: state.SavingReducer.isSaving
    }
}

export default connect(mapStateToProps)(PreviewContainer)
