import React, { Component, cloneElement } from "react"
import * as _ from "underscore"

import ColorSelector from "javascripts/react/components/ColorSelector"
import SettingsItem from "javascripts/react/components/settings/SettingsItem"

class BaseSettingsOptin extends Component {
    constructor(props, ctx) {
        super(props, ctx)

        const { config } = this.props

        this.config = _.clone(config[this.props.settings + "_settings"])
        this.allConfig = _.clone(config)

        this._changeInputValueText = this._changeInputValueText.bind(this)
        this._changeInputValueCheckbox = this._changeInputValueCheckbox.bind(
            this
        )
        this._changeInputValueNumber = this._changeInputValueNumber.bind(this)
        this._changeInputValue = this._changeInputValue.bind(this)
        this._changeInputValueInputText = this._changeInputValueInputText.bind(
            this
        )
        this._saveValue = this._saveValue.bind(this)
        this._saveValues = this._saveValues.bind(this)
        this._saveValuesOptin = this._saveValuesOptin.bind(this)

        this.state = {
            buttonText: config.buttonText
        }
    }

    componentWillReceiveProps(nextProps) {
        const { config } = nextProps

        this.config = _.clone(config[this.props.settings + "_settings"])
        this.allConfig = _.clone(config)
    }

    _saveValuesOptin() {
        const { updateOptin } = this.props

        updateOptin(this.allConfig)
    }

    _changeInputValueInputText(event) {
        this.setState({
            [event.target.name]: event.target.value
        })

        this._saveValue(event.target.name, event.target.value)
    }

    _changeInputValueText(event) {
        this._changeInputValue(event, event.target.value)
    }

    _changeInputValueCheckbox(event) {
        this._changeInputValue(event, event.target.checked)
    }

    _changeInputValueNumber(event) {
        this._changeInputValue(event, Number(event.target.value))
    }

    _changeInputValue(event, value) {
        const name = event.target.name

        this._saveValue(name, value)
    }

    _saveValues(params) {
        const settingsSave = this.props.settings + "_settings"

        _.mapObject(params, (val, key) => {
            if(_.isUndefined(this.props.config[settingsSave][key]) ){
                this.props.config[settingsSave] = _.extend(this.props.config[settingsSave], {
                    [key] : {
                        attrs : {},
                        styling: {}
                    }
                })
            }


            if(_.isUndefined(this.props.config[settingsSave][key]) ){
                this.props.config[settingsSave] = _.extend(this.props.config[settingsSave], {
                    [key] : {
                        attrs : {},
                        styling: {}
                    }
                })
            }

            this.allConfig = _.extend({}, this.allConfig, {
                [settingsSave]: _.extend({}, this.props.config[settingsSave], {
                    [key]: _.extend({}, this.props.config[settingsSave][key], {
                        attrs: !_.isUndefined(val.attrs)
                            ? _.extend(
                                  {},
                                  this.props.config[settingsSave][key].attrs,
                                  val.attrs
                              )
                            : this.props.config[settingsSave][key].attrs,
                        styling: !_.isUndefined(val.styling)
                            ? _.extend(
                                  {},
                                  this.props.config[settingsSave][key].styling,
                                  val.styling
                              )
                            : this.props.config[settingsSave][key].styling
                    })
                })
            })
        })

        this._saveValuesOptin()
    }

    _saveValue(key, value, saveValue = true) {
        const splitKey = key.split("-")
        const settingsSave = this.props.settings + "_settings"

        let _newStyling = {}

        if (splitKey.length === 3) {
            this.allConfig = _.extend({}, this.allConfig, {
                [settingsSave]: _.extend({}, this.props.config[settingsSave], {
                    [splitKey[0]]: _.extend(
                        {},
                        this.props.config[settingsSave][splitKey[0]],
                        {
                            [splitKey[1]]: _.extend(
                                {},
                                this.props.config[settingsSave][splitKey[0]][
                                    splitKey[1]
                                ],
                                {
                                    [splitKey[2]]:
                                        _.isBoolean(value) ||
                                        _.isString(value) ||
                                        _.isNumber(value)
                                            ? value
                                            : _.extend(
                                                  {},
                                                  this.props.config[
                                                      settingsSave
                                                  ][splitKey[0]][splitKey[1]][
                                                      splitKey[2]
                                                  ],
                                                  value
                                              )
                                }
                            )
                        }
                    )
                })
            })
        }

        this.config = _.clone(this.allConfig[settingsSave])

        if (saveValue) {
            this._saveValuesOptin()
        }

        return Promise.resolve()
    }
}

export default BaseSettingsOptin
