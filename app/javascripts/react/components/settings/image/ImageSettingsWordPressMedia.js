import * as _ from "underscore"
import React, { Component } from "react"
import PropTypes from "prop-types"
import { connect } from "react-redux"

import { getFrame } from "javascripts/react/helpers/wordpressMedia"

import SettingsItem from 'javascripts/react/components/settings/SettingsItem'

import {
    ADD_TEMPLATE_CONTENT,
    ADD_TEMPLATE_CONTENT_EMPTY
} from "javascripts/react/constants/TemplateContentConstants"

class ImageSettingsWordPressMedia extends Component {
    constructor(props) {
        super(props)
        this._handleClickMediaLibrary = this._handleClickMediaLibrary.bind(this)
        this._handleSelectFrame       = this._handleSelectFrame.bind(this)
        this.autoOpen                 = (_.isUndefined(this.props.autoOpen)) ? true : this.props.autoOpen
    }

    // componentDidMount() {
    //     const { styles } = this.props

    //     const frame = getFrame()

    //     if (_.isUndefined(styles) && this.autoOpen) {
    //         frame.on("select", this._handleSelectFrame)
    //         frame.open()
    //     }
    // }

    componentDidUpdate() {
        const { styles } = this.props

        const frame = getFrame()

        if (_.isUndefined(styles) && this.autoOpen) {
            frame.on("select", this._handleSelectFrame)
            frame.open()
        }

    }

    _handleSelectFrame() {
        const { updateImage } = this.props

        const frame = getFrame()
        
        const attachment = frame.state().get("selection").first().toJSON()

        if (_.isUndefined(attachment.url)) {
            return
        }

        updateImage(attachment)
        frame.close()
    }

    _handleClickMediaLibrary(event) {
        event.preventDefault()

        const frame = getFrame()
        
        frame.on("select", this._handleSelectFrame)
        frame.open()
    }


    componentWillUnmount(){
        const frame = getFrame()
        frame.off("select", this._handleSelectFrame)
    }

    render() {
        const { styles } = this.props
        let _txt =
            translationDelipressReact.Builder.component_settings.image.wp_library_src

        if(!_.isUndefined(styles)){
            if(!_.isEmpty(styles.src)){
                _txt =
                translationDelipressReact.Builder.component_settings.image.wp_library_src_have_src
            }
        }
        else if (!_.isEmpty(this.props.src) ){
            _txt = translationDelipressReact.Builder.component_settings.image.wp_library_src_have_src
        }

        return (
            <SettingsItem label={translationDelipressReact.Builder.component_settings.image.action}>
                <button
                    className="delipress__button delipress__button--soft delipress__button--small"
                    onClick={this._handleClickMediaLibrary}
                >
                    <span className="dashicons dashicons-format-image" />
                    <span>{_txt}</span>
                </button>
            </SettingsItem>
        )
    }
}

ImageSettingsWordPressMedia.propTypes = {
    updateImage: PropTypes.func.isRequired,
    autoOpen: PropTypes.bool,
    src: PropTypes.string,
    styles: PropTypes.object
}

export default connect()(ImageSettingsWordPressMedia)
