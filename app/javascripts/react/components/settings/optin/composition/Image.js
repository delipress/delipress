import React, { Component, cloneElement } from "react"
import PropTypes from "prop-types"

import Checkbox from "javascripts/react/components/inputs/Checkbox"
import ColorSelector from "javascripts/react/components/ColorSelector"
import SettingsItem from "javascripts/react/components/settings/SettingsItem"
import InputRangeDP from "javascripts/react/components/inputs/InputRangeDP"
import ImageOrientation from "javascripts/react/components/optins/ImageOrientation"
import ImageSettingsWordPressMedia from "javascripts/react/components/settings/image/ImageSettingsWordPressMedia"

class Image extends Component {
    constructor(props) {
        super(props)

        this._handleUpdateImage = this._handleUpdateImage.bind(this)
        this._handleUpdateImageSize = this._handleUpdateImageSize.bind(this)
        this._handleUpdateImageOrientation = this._handleUpdateImageOrientation.bind(
            this
        )
    }

    _handleUpdateImageSize(event) {
        const sizeSelected = this.props.config.wrapper_image.attrs.sizes[
            event.target.value
        ]

        this.props.saveValues({
            wrapper_image: {
                attrs: {
                    width: sizeSelected.width,
                    height: sizeSelected.height,
                    range: 100,
                    sizeSelect: event.target.value,
                    url: this.props.config.wrapper_image.attrs.sizes[
                        event.target.value
                    ].url
                },
                styling: {
                    width: "100%"
                }
            }
        })
    }

    _handleUpdateImage(attachment) {
        if (_.isUndefined(attachment.url)) {
            return
        }

        this.props.saveValues({
            wrapper_image: {
                attrs: {
                    url: attachment.url,
                    srcWidth: attachment.width,
                    srcHeight: attachment.height,
                    width: attachment.width,
                    sizes: attachment.sizes,
                    sizeSelect: "full",
                    range: 100
                }
            }
        })
    }

    _handleUpdateImageOrientation(e) {
        this.props.saveValues({
            wrapper: {
                attrs: {
                    orientation: e.target.value
                }
            }
        })
    }

    render() {
        const { config } = this.props

        let alignEls = []
        const alignLoop = [
            {
                key: "top",
                class: ""
            },
            {
                key: "left",
                class: "DELI-wrapper--left-image"
            },
            {
                key: "right",
                class: "DELI-wrapper--right-image"
            },
            {
                key: "bottom",
                class: "DELI-wrapper--image-last"
            }
        ]

        _.each(
            alignLoop,
            (el, i) => {
                alignEls.push(
                    <div className="delipress__buttonsgroup__cell" key={i}>
                        <input
                            type="radio"
                            name="settings__align"
                            id={"settings__align_" + el.key}
                            name="align"
                            value={el.class}
                            checked={
                                config.wrapper.attrs.orientation === el.class
                            }
                            onChange={this._handleUpdateImageOrientation}
                        />

                        <label
                            htmlFor={"settings__align_" + el.key}
                            className="delipress__buttonsgroup__cell"
                        >
                            <ImageOrientation orientation={el.key} />
                        </label>
                    </div>
                )
            },
            this
        )

        let _sizesOptions = []

        _.mapObject(
            this.props.config.wrapper_image.attrs.sizes,
            (size, key) => {
                const txt = `${key} - ${size.width}x${size.height}`

                _sizesOptions.push(
                    <option key={`size_${key}`} value={key}>
                        {txt}
                    </option>
                )
            }
        )

        const _activeImage = (
            <SettingsItem
                label={translationDelipressReact.Optin.wrapper_image.active}
                id="email_input_form-attrs-active"
            >
                <Checkbox
                    id="email_input_form-attrs-active"
                    defaultChecked={config.wrapper_image.attrs.active}
                    handleChange={e => {
                        this.props.saveValues({
                            wrapper_image: {
                                attrs: {
                                    active: e.target.checked
                                }
                            }
                        })
                    }}
                />
            </SettingsItem>
        )

        if (!config.wrapper_image.attrs.active) {
            return _activeImage
        }

        return (
            <div className="container__settings__attributes settings__default">
                {_activeImage}

                <ImageSettingsWordPressMedia
                    updateImage={this._handleUpdateImage}
                    autoOpen={false}
                    src={config.wrapper_image.attrs.url}
                />
                {!_.isEmpty(_sizesOptions)
                    ? <SettingsItem
                          label={
                              translationDelipressReact.Builder
                                  .component_settings.image.sizes
                          }
                      >
                          <select
                              name="sizeSelect"
                              onChange={this._handleUpdateImageSize}
                              value={config.wrapper_image.attrs.sizeSelect}
                          >
                              {_sizesOptions}
                          </select>
                      </SettingsItem>
                    : false}
                {!this.props.naked &&
                    <InputRangeDP
                        rangeValue={config.wrapper_image.attrs.range}
                        maxValue={100}
                        type="pourcent"
                        handleOnChangeWidth={width => {
                            jQuery(".DELI-image").css({
                                width: width + "%"
                            })
                        }}
                        handleOnChangeCompleteWidth={width => {
                            this.props.saveValues({
                                wrapper_image: {
                                    attrs: {
                                        range: width
                                    },
                                    styling: {
                                        width: width + "%"
                                    }
                                }
                            })
                        }}
                    />}
                {!this.props.naked &&
                    <SettingsItem
                        label={
                            translationDelipressReact.Optin.wrapper_image
                                .image_orientation
                        }
                    />}
                {!this.props.naked &&
                    <SettingsItem
                        label={
                            translationDelipressReact.Optin.Settings
                                .image_orientation
                        }
                    >
                        <div className="delipress__buttonsgroup">
                            {alignEls}
                        </div>
                    </SettingsItem>}
            </div>
        )
    }
}

Image.propTypes = {
    saveValues: PropTypes.func.isRequired,
    saveValue: PropTypes.func.isRequired,
    config: PropTypes.object.isRequired
}

export default Image
