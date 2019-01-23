import * as _ from "underscore"
import React, { Component } from "react"
import PropTypes from "prop-types"
import { connect } from "react-redux"

import ColorSelector from "../../ColorSelector"
import ImageSettingsWordPressMedia from "./ImageSettingsWordPressMedia"
import BaseNewSettings from "javascripts/react/components/settings/base/BaseNewSettings"
import Align from "javascripts/react/components/settings/style/Align"
import InputNumber from "javascripts/react/components/inputs/InputNumber"
import InputRangeDP from "javascripts/react/components/inputs/InputRangeDP"
import SettingsItem from 'javascripts/react/components/settings/SettingsItem'
import ApplyAll from "javascripts/react/components/settings/ApplyAll"

function getNewHeight(width, srcWidth, srcHeight) {
    return Math.floor(srcHeight * width / srcWidth)
}

class ImageSettings extends BaseNewSettings {
    constructor(props) {
        super(props)

        this._handleOnChangeCompleteWidth      = this._handleOnChangeCompleteWidth.bind(this)
        this._handleUpdateImage                = this._handleUpdateImage.bind(this)
        this._handleUpdateImageSize            = this._handleUpdateImageSize.bind(this)

        const { item } = this.props

        if(!_.isNull(item)){
            this.state = {
                href : item.styles.href
            }
        }

    }

    _handleUpdateImageSize(event){
        this.styles[event.target.name] = event.target.value

        const sizeSelected = this.styles.sizes[event.target.value]

        const { item } = this.props

        const index = `${item.keyRow}_${item.keyColumn}_${item._id}`

        let widthMax = jQuery(`.id_selector_${index}`).width()

        let newHeight  = Number(sizeSelected.height)
        let newWidth   = Number(sizeSelected.width)

        if (sizeSelected.width > widthMax) {
            newWidth = widthMax
            newHeight = getNewHeight(widthMax, sizeSelected.width, sizeSelected.height)
        }


        this.styles = _.extend({}, this.styles, {
            src: sizeSelected.url,
            srcWidth: newWidth,
            srcHeight: newHeight,
            width: newWidth,
            valuePercent: 100
        })

        this.timeoutSave = 0
        this.saveEditor()
    }

    _handleUpdateImage(attachment) {
        if (_.isUndefined(attachment.url)) {
            return false
        }

        const { item } = this.props
        if(_.isNull(item) || _.isUndefined(item)){
            return false
        }

        const index = `${item.keyRow}_${item.keyColumn}_${item._id}`

        let newHeight  = Number(attachment.height)
        let newWidth   = Number(attachment.width)

        let widthMax = jQuery(`.id_selector_${index}`).width()

        if (attachment.width > widthMax) {
            newWidth = widthMax
            newHeight = getNewHeight(widthMax, attachment.width, attachment.height)
        }

        let _sizeSelectDefault = "full"
        if (!_.isUndefined(attachment.sizes.large)){
            _sizeSelectDefault = "large"
        }
        try{
            this.styles = _.extend({}, this.styles, {
                src: attachment.url,
                srcWidth: newWidth,
                srcHeight: newHeight,
                width: newWidth,
                sizes: attachment.sizes,
                sizeSelect: _sizeSelectDefault,
                valuePercent: 100
            })

            this.saveEditor()
        } catch(e){ }
    }

    _handleOnChangeCompleteWidth(newValue) {
        const   { item } = this.props

        const   index     =    `${item.keyRow}_${item.keyColumn}_${item._id}`
        const   _srcWidth =    this.styles.srcWidth

        const newWidth = (newValue * _srcWidth) / 100

        this.styles = _.extend({}, this.styles, {
            width: newWidth,
            valuePercent: newValue
        })

        this.saveEditor()
    }

    render() {

        if(_.isNull(this.props.item) || _.isUndefined(this.props.item)){
            return false
        }

        const { item } = this.props

        if (_.isEmpty(this.styles.src)) {
            return (
                <div className="container__settings__attributes settings__default">
                    <span className="delipress__builder__side__title">
                        {translationDelipressReact.Builder.component_settings.image.title_settings}
                    </span>
                    <SettingsItem>
                        <ImageSettingsWordPressMedia
                            updateImage={this._handleUpdateImage}
                        />
                    </SettingsItem>
                </div>
            )
        }

        const index = `${item.keyRow}_${item.keyColumn}_${item._id}`

        let _sizesOptions  = []
        _.mapObject(this.styles.sizes, (size, key) => {

            const txt = `${key} - ${size.width}x${size.height}`

            _sizesOptions.push(
                <option
                    key={`size_${key}`}
                    value={key}
                >
                    {txt}
                </option>
            )
        })

        return (
            <div className="container__settings__attributes settings__default">
                <span className="delipress__builder__side__title">
                    {translationDelipressReact.Builder.component_settings.image.title_settings}
                </span>
                <InputRangeDP
                    rangeValue={this.styles.valuePercent}
                    maxValue={100}
                    minValue={1}
                    type="pourcent"
                    handleOnChangeWidth={(rangeValue) => {

                        const   _srcWidth = this.styles.srcWidth

                        let selectorImg = jQuery(`.id_selector_${index} img`)
                        if(!_.isEmpty(this.styles.href)){
                            selectorImg = jQuery(`.id_selector_${index} a`)
                        }

                        const newWidth = (rangeValue * _srcWidth) / 100

                        selectorImg.attr("width", newWidth)
                        selectorImg.parent().css({"width" : newWidth + "px"})
                    }}
                    handleOnChangeCompleteWidth={(rangeValue) => {
                        this.timeoutSave = 0
                        this._handleOnChangeCompleteWidth(rangeValue)
                    }}
                />
                <SettingsItem
                    label={
                        translationDelipressReact.Builder.component_settings
                            .image.sizes
                    }
                >
                    <select
                        name="sizeSelect"
                        onChange={this._handleUpdateImageSize}
                        value={this.styles.sizeSelect}
                    >
                        {_sizesOptions}
                    </select>
                </SettingsItem>
                <ImageSettingsWordPressMedia
                    styles={this.styles}
                    src={this.styles.src}
                    updateImage={this._handleUpdateImage}
                />
                <SettingsItem label={translationDelipressReact.Builder.component_settings.image.link}>
                    <input
                        className="delipress__input"
                        name="href"
                        onChange={this._changeInputValueInputText}
                        type="text"
                        value={this.styles.href}
                    />
                </SettingsItem>
                <SettingsItem label={translationDelipressReact.Builder.component_settings.image.borderRadius}>
                    <InputNumber
                        name="border-radius"
                        nameValue={this.styles["border-radius"]}
                        placeholder="px"
                        saveRefValue={this.saveOptionValue}
                    />
                </SettingsItem>
                <Align
                    onChangeSettingsAlign={value => {
                        this.timeoutSave = 0
                        this.saveOptionValue("align", value)
                    }}
                    styles={this.styles}
                />
                <ApplyAll
                    text={translationDelipressReact.Builder.component_settings.apply_all.replace("%{s}", translationDelipressReact.Builder.component.image)}
                    handleApply={this.handleApply}
                />
            </div>
        )
    }
}

function mapStateToProps(state){
    if(_.isNull(state.EditorReducer.activeItem)){
        return {
            item : null
        }
    }

    const arr = state.EditorReducer.activeItem.split("_")

    return {
        item : state.TemplateReducer.config.items[arr[0]].columns[arr[1]].items[arr[2]]
    }
}

export default connect(mapStateToProps)(ImageSettings)
