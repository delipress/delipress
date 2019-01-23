import * as _ from "underscore"
import React, { Component } from "react"
import { connect } from 'react-redux'

import { SOCIAL_LIST } from "../../constants/TemplateContentConstants"
import ColorSelector from "../ColorSelector"
import BaseNewSettings from "javascripts/react/components/settings/base/BaseNewSettings"
import InputNumber from "javascripts/react/components/inputs/InputNumber"
import Checkbox from "javascripts/react/components/inputs/Checkbox"
import Align from "javascripts/react/components/settings/style/Align"
import SettingsItem from "javascripts/react/components/settings/SettingsItem"
import ApplyAll from "javascripts/react/components/settings/ApplyAll"

class SocialSettings extends BaseNewSettings {
    constructor(props, ctx) {
        super(props, ctx)

        this.renderSocial      = this.renderSocial.bind(this)
        this.getIsChecked      = this.getIsChecked.bind(this)
        this._handleMonochrome = this._handleMonochrome.bind(this)
        this.getTextSocial     = this.getTextSocial.bind(this)
    }

    _handleChangeUrl(social, event) {
        this.styles["url_" + social] = event.target.value

        this.saveEditor()
    }

    _handleChangeContent(social, event) {
        this.styles["content_" + social] = event.target.value

        this.saveEditor()
    }

    _handleChangeActive(social, event) {
        this.styles["toggle_" + social] = event.target.checked

        this.saveEditor()
    }

    _handleMonochrome(event) {
        const { item } = this.props

        const index = `${item.keyRow}_${item.keyColumn}_${item._id}`

        let _attrs = {
            monochromeActive: event.target.checked
        }

        if (event.target.checked) {
            SOCIAL_LIST.map(social => {
                _attrs["color_" + social] = this.styles["monochromeColor"]
            })
        } else {
            SOCIAL_LIST.map(social => {
                _attrs["color_" + social] = this.getColorSocial(social)
            })
        }

        this.timeoutSave = 0
        this.styles      = _.extend(this.styles, _attrs)

        this.saveEditor()
    }

    getColorSocial(social) {
        switch (social) {
            case "facebook":
                return {
                    hex: "#3b5998",
                    rgb: {
                        r: 59,
                        g: 89,
                        b: 152,
                        a: 1
                    }
                }
            case "google":
                return {
                    hex: "#dc4e41",
                    rgb: {
                        r: 220,
                        g: 78,
                        b: 65,
                        a: 1
                    }
                }
            case "linkedin":
                return {
                    hex: "#0077b5",
                    rgb: {
                        r: 0,
                        g: 119,
                        b: 181,
                        a: 1
                    }
                }
            case "pinterest":
                return {
                    hex: "#bd081c",
                    rgb: {
                        r: 189,
                        g: 8,
                        b: 28,
                        a: 1
                    }
                }
            case "twitter":
                return {
                    hex: "#55acee",
                    rgb: {
                        r: 85,
                        g: 172,
                        b: 238,
                        a: 1
                    }
                }
            case "instagram":
                return {
                    hex: "#3f729b",
                    rgb: {
                        r: 63,
                        g: 114,
                        b: 155,
                        a: 1
                    }
                }
            case "youtube":
                return {
                    hex: "#cd201f",
                    rgb: {
                        r: 205,
                        g: 32,
                        b: 31,
                        a: 1
                    }
                }
            default:
                return "#FF0000"
        }
    }

    getTextSocial(social) {
        if (
            _.isUndefined(this.styles["content_" + social])
        ) {
            switch (social) {
                case "google":
                    return translationDelipressReact.Builder.default.plus
                case "twitter":
                    return translationDelipressReact.Builder.default.tweet
                case "pinterest":
                    return translationDelipressReact.Builder.default.pin
                case "youtube":
                    return translationDelipressReact.Builder.default.subscribe
                default:
                    return translationDelipressReact.Builder.default.share
            }
        } else {
            return this.styles["content_" + social]
        }
    }

    getIsChecked(social) {
        return _.isUndefined(this.styles["toggle_" + social])
            ? false
            : this.styles["toggle_" + social]
    }

    renderSocial(social) {
        const { item } = this.props

        const _activeItem = (
            <div key={"social_btn_" + social}>
                <span className="delipress__builder__side__title">
                    {social}
                </span>
                <SettingsItem
                    label={
                        translationDelipressReact.Builder.component_settings
                            .social.activate +
                        " " +
                        social
                    }
                >
                    <input
                        type="checkbox"
                        id={"rd_" + social}
                        className="delipress__switch__input"
                        checked={this.getIsChecked(social)}
                        name={"toggle_" + social}
                        onChange={this._changeInputValueCheckbox}
                    />
                    <label
                        htmlFor={"rd_" + social}
                        className="delipress__switch"
                    >
                        <div className="delipress__switch__slider" />
                        <div className="delipress__switch__on">I</div>
                        <div className="delipress__switch__off">0</div>
                    </label>
                </SettingsItem>
            </div>
        )

        const _configItem = (
            <span key="config_item_social">
                <SettingsItem
                    label={
                        translationDelipressReact.Builder.component_settings
                            .social.url
                    }
                >
                    <input
                        type="text"
                        className="delipress__input"
                        placeholder={
                            translationDelipressReact.Builder.component_settings
                                .social.url + ' ' + social
                        }
                        onChange={this._changeInputValueInputText}
                        name={"url_" + social}
                        value={
                            _.isUndefined(this.styles)
                                ? ""
                                : this.styles["url_" + social]
                        }
                    />
                </SettingsItem>

                <SettingsItem
                    label={
                        translationDelipressReact.Builder.component_settings
                            .social.text
                    }
                >
                    <input
                        type="text"
                        className="delipress__input"
                        placeholder={
                            translationDelipressReact.Builder.component_settings
                                .social.text + ' ' + social
                        }
                        onChange={this._changeInputValueInputText}
                        name={"content_" + social}
                        value={this.getTextSocial(social)}
                    />
                </SettingsItem>
            </span>
        )

        let _renderItem = []
        _renderItem.push(_activeItem)
        if (this.getIsChecked(social)) {
            _renderItem.push(_configItem)
        }

        return (
            <div key={"social_btn_" + social}>
                {_renderItem}
            </div>
        )
    }

    render() {

        if(_.isNull(this.props.item) || _.isUndefined(this.props.item)){
            return false
        }

        const { item } = this.props

        const index = `${item.keyRow}_${item.keyColumn}_${item._id}`

        return (
            <div className="container__settings__attributes settings__default">
                <span className="delipress__builder__side__title">
                    {
                        translationDelipressReact.Builder.component_settings
                            .social.title_settings
                    }
                </span>
                <SettingsItem label={translationDelipressReact.font_size}>
                    <InputNumber
                        name="font-size"
                        nameValue={this.styles["font-size"]}
                        min={0}
                        step={1}
                        saveRefValue={this.saveOptionValue}
                        placeholder="px"
                    />
                </SettingsItem>
                <SettingsItem label={translationDelipressReact.font_size_icon}>
                    <InputNumber
                        name="icon-size"
                        nameValue={this.styles["icon-size"]}
                        min={0}
                        step={1}
                        saveRefValue={this.saveOptionValue}
                        placeholder="px"
                    />
                </SettingsItem>
                <Align
                    onChangeSettingsAlign={value => {
                        this.timeoutSave = 0
                        this.saveOptionValue("align", value)
                    }}
                    styles={this.styles}
                />
                <SettingsItem
                    label={
                        translationDelipressReact.Builder.component_settings
                            .social.colorSelector
                    }
                >
                    <ColorSelector
                        handleChange={color => {
                            const $selectorP = jQuery(
                                `.id_selector_${index} img`
                            )

                            $selectorP.each((key, value) => {
                                jQuery(value)
                                    .parents("table:first").parent().next().find("a")
                                    .css("color", color.hex)
                            })
                        }}
                        handleChangeComplete={color => {
                            this.timeoutSave = 0
                            this.saveOptionValue("textColor", color)
                        }}
                        disabledAlpha={false}
                        picker="sketch"
                        color={this.styles.textColor}
                    />
                </SettingsItem>
                <SettingsItem
                    label={
                        translationDelipressReact.Builder.component_settings
                            .social.backgroundColor
                    }
                    classModifier="delipress__flex-center"
                >
                    <Checkbox
                        id="inputMonochrome"
                        defaultChecked={this.styles["monochromeActive"]}
                        handleChange={this._handleMonochrome}
                    />
                    {this.styles.monochromeActive
                        ? <ColorSelector
                              key={"bg_color_selector"}
                              handleChangeComplete={color => {
                                  let _attrs = {
                                      monochromeColor: color
                                  }

                                  SOCIAL_LIST.map(social => {
                                      _attrs["color_" + social] = color
                                  })

                                  this.timeoutSave = 0
                                  this.styles = _.extend(this.styles, _attrs)
                                  this.saveEditor()
                              }}
                              handleChange={color => {
                                  const $selectorP = jQuery(
                                      `.id_selector_${index} img`
                                  )

                                  $selectorP.each((key, value) => {
                                      jQuery(value)
                                          .parents("table:first")
                                          .css("backgroundColor", color.hex)
                                  })
                              }}
                              disabledAlpha={false}
                              picker="sketch"
                              color={this.styles["monochromeColor"]}
                          />
                        : null}

                </SettingsItem>
                {SOCIAL_LIST.map(social => {
                    return this.renderSocial(social)
                })}

                <ApplyAll
                    text={translationDelipressReact.Builder.component_settings.apply_all.replace("%{s}", translationDelipressReact.Builder.component.social)}
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

export default connect(mapStateToProps)(SocialSettings)
