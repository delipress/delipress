import * as _ from "underscore"
import React, { Component, cloneElement } from "react"
import PropTypes from "prop-types"

import { connect } from "react-redux"
import { compose, bindActionCreators } from "redux"
import SettingsItem from "javascripts/react/components/settings/SettingsItem"
import ColorSelector from "javascripts/react/components/ColorSelector"
import InputNumber from "javascripts/react/components/inputs/InputNumber"

class Border extends Component {
    constructor(props) {
        super(props)

        this._changeItemColor = this._changeItemColor.bind(this)
    }

    _changeItemColor(color) {
        const { item } = this.props

        const selector = item.selector
        let itemToUpdate

        if (selector != undefined) {
            itemToUpdate = jQuery(selector)
        } else {
            const index = `${item.keyRow}_${item.keyColumn}_${item._id}`
            const selectorP = jQuery(`.id_selector_${index} p`)
            itemToUpdate = selectorP.parent()
        }

        itemToUpdate.css({
            borderColor: `rgba(${color.rgb.r}, ${color.rgb.g}, ${color.rgb
                .b}, ${color.rgb.a})`
        })
    }

    render() {
        const {
            borderStyle,
            borderColor,
            borderWidth,
            item,
            borderTitle
        } = this.props

        const borderStyles = [
            "solid",
            "dashed",
            "dotted",
            "double",
            "groove",
            "ridge",
            "inset",
            "outset"
        ]

        const isBorderTitle = borderTitle == false ? false : true

        return (
            <div className="container__settings__attributes settings__default">
                {isBorderTitle &&
                    <span className="delipress__builder__side__title">
                        {
                            translationDelipressReact.Builder.component_settings
                                .button.border_settings
                        }
                    </span>}
                <SettingsItem
                    label={
                        translationDelipressReact.Builder.component_settings
                            .button.border
                    }
                    rootClassModifier="delipress__builder__side__setting--border"
                >
                    <ColorSelector
                        disabledAlpha={false}
                        picker="sketch"
                        color={borderColor}
                        handleChange={color => {
                            this._changeItemColor(color)
                        }}
                        handleChangeComplete={color => {
                            this.props.saveRefValue("borderColor", color)
                        }}
                    />
                    <select
                        name="border-style"
                        onChange={event => {
                            this.props.saveRefValue(
                                "borderStyle",
                                event.target.value
                            )
                        }}
                        value={borderStyle}
                    >
                        {borderStyles.map((option, key) => {
                            return (
                                <option
                                    key={`border_style_${key}`}
                                    value={option}
                                >
                                    {option}
                                </option>
                            )
                        })}
                    </select>
                    <InputNumber
                        name="borderWidth"
                        nameValue={borderWidth}
                        placeholder="px"
                        saveRefValue={this.props.saveRefValue}
                    />
                </SettingsItem>
            </div>
        )
    }
}

Border.propTypes = {
    saveRefValue: PropTypes.func.isRequired,
    borderStyle: PropTypes.string.isRequired,
    borderColor: PropTypes.object.isRequired,
    borderWidth: PropTypes.number.isRequired,
    item: PropTypes.object.isRequired
}

export default Border
