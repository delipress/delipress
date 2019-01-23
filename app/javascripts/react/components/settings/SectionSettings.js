import * as _ from "underscore"
import React, { Component } from "react"
import { connect } from 'react-redux'

import ImageSettingsWordPressMedia from "javascripts/react/components/settings/image/ImageSettingsWordPressMedia"
import ColorSelector from "javascripts/react/components/ColorSelector"
import Padding from "javascripts/react/components/settings/style/Padding"
import ColumnsWidth from "javascripts/react/components/settings/style/ColumnsWidth"
import VerticalAlignColumns from "javascripts/react/components/settings/style/VerticalAlignColumns"
import BaseNewSettings from "javascripts/react/components/settings/base/BaseNewSettings"
import SettingsItem from "javascripts/react/components/settings/SettingsItem"

class SectionSettings extends BaseNewSettings {
    constructor(props, ctx) {
        super(props, ctx)

        this._handleUpdateImage      = this._handleUpdateImage.bind(this)
        this._handleChangePadding    = this._handleChangePadding.bind(this)
        this._handleClickRemoveImage = this._handleClickRemoveImage.bind(this)
    }

    _handleChangePadding(newStyles) {
        this.styles = _.extend(this.styles, newStyles)

        this.saveEditor()
    }

    _handleUpdateImage(attachment) {
        if (_.isUndefined(attachment.url)) {
            return false
        }

        this.styles = _.extend({}, this.styles, {
            sizes: attachment.sizes,
            sizeSelect: "full",
            "background-url": attachment.url
        })

        this.saveEditor()
    }

    _handleClickRemoveImage(e) {
        e.preventDefault()

        this.styles = _.extend({}, this.styles, {
            "background-url": ""
        })

        this.saveEditor()
    }

    renderColumnWidth() {
        const { item } = this.props

        if (item.columns.length < 2) {
            return false
        }

        return (
            <span>
                <span className="delipress__builder__side__title">
                    {
                        translationDelipressReact.Builder.component_settings
                            .section.column
                    }
                </span>
                <ColumnsWidth
                    item={item}
                    columns={item.columns}
                    columnNumber={item.columns.length}
                    handleChangeColumn={(value, key) => {
                        let _item = _.extend({}, item)
                        
                        _.each(value, (v, ind) => {
                            _item.columns[ind].styles.width = _.clone(v)
                        })

                        _item.styles.sizeColumnChoice = _.clone(key)

                        this.props.saveEditorColumns(_item)
                    }}
                />
            </span>
        )
    }

    renderVerticalAlign() {
        const { item } = this.props

        return (
            <VerticalAlignColumns
                styles={item.styles}
                onChangeSettingsVerticalAlign={value => {
                    let _flexValue
                    if (value == "top") {
                        _flexValue = "flex-start"
                    } else if (value == "middle") {
                        _flexValue = "center"
                    } else if (value == "bottom") {
                        _flexValue = "flex-end"
                    }
                    
                    let _item = _.extend({}, item, {
                        styles : _.extend({}, item.styles, {
                            display: "flex",
                            "vertical-align" : value
                        }),
                        columns : _.map(item.columns, (column, ind) => {
                            return _.extend({}, column, {
                                styles : _.extend({}, column.styles, {
                                    "vertical-align" : value,
                                    "alignSelf" : _flexValue
                                })
                            })
                        })
                    })
                    
                    this.props.saveEditorColumns(_item)
                }}
            />
        )
    }

    render() {
        
        if(_.isNull(this.props.item) || _.isUndefined(this.props.item)){
            return false
        }

        const { item } = this.props

        return (
            <div className="container__settings__attributes settings__default">
                <span className="delipress__builder__side__title">
                    {
                        translationDelipressReact.Builder.component_settings
                            .section.title_settings
                    }
                </span>

                <SettingsItem
                    label={
                        translationDelipressReact.Builder.component_settings
                            .section.background_color
                    }
                >
                    <ColorSelector
                        handleChangeComplete={color => {
                            this.saveOptionValue("background", color)
                        }}
                        disabledAlpha={false}
                        idSelector={`.delipress__builder__main__preview__section__row__${item.keyRow}`}
                        typeColor="backgroundColor"
                        picker="sketch"
                        color={this.styles.background}
                    />
                </SettingsItem>
                <span className="delipress__builder__side__title">
                    {
                        translationDelipressReact.Builder.component_settings
                            .section.background_image
                    }
                </span>
                <ImageSettingsWordPressMedia
                    styles={this.styles}
                    autoOpen={false}
                    src={this.styles["background-url"]}
                    updateImage={this._handleUpdateImage}
                />
                <SettingsItem
                    label={
                        !_.isEmpty(this.styles["background-url"])
                            ? <div
                                  className="delipress__builder__side__setting__input__preview__image"
                                  style={{
                                      backgroundImage: `url(${this.styles[
                                          "background-url"
                                      ]})`
                                  }}
                              />
                            : false
                    }
                >
                    <button
                        className="delipress__button delipress__button--soft delipress__button--small"
                        onClick={this._handleClickRemoveImage}
                    >
                        <span className="dashicons dashicons-dismiss" />
                        <span>
                            {
                                translationDelipressReact.Builder
                                    .component_settings.section.remove_image
                            }
                        </span>
                    </button>
                </SettingsItem>
                <span className="delipress__builder__side__title">
                    {
                        translationDelipressReact.Builder.component_settings
                            .section.vertical_align
                    }
                </span>
                {this.renderVerticalAlign()}
                <Padding
                    onChangePadding={this._handleChangePadding}
                    item={item}
                />
                {this.renderColumnWidth()}
            </div>
        )
    }
}

function mapStateToProps(state){

    if(_.isNull(state.EditorReducer.activeSection)){
        return {
            item : null
        }
    }

    return {
        item : state.TemplateReducer.config.items[state.EditorReducer.activeSection]
    }
}

export default connect(mapStateToProps)(SectionSettings)
