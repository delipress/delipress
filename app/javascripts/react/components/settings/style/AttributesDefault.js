import * as _ from "underscore"
import React, { Component, cloneElement } from 'react'
import { connect } from 'react-redux'
import { compose, bindActionCreators } from 'redux'

import ColorSelector from '../../ColorSelector'
import Padding from 'javascripts/react/components/settings/style/Padding'
import BaseNewSettings from 'javascripts/react/components/settings/base/BaseNewSettings'
import SettingsItem from 'javascripts/react/components/settings/SettingsItem'

class AttributesDefault extends BaseNewSettings  {

    constructor(props, ctx){
        super(props, ctx)

        const { item } = this.props

        this._handleChangePadding = this._handleChangePadding.bind(this)
        this.getColorItem         = this.getColorItem.bind(this)

    }

    _handleChangePadding(newStyles){

        this.styles = _.extend(this.styles, newStyles)

        this.saveEditor()
    }


    getColorItem(){
        const { item } = this.props

        if(_.isUndefined(item.styles) ){
            return {
                rgb : {
                    r : 255,
                    g : 255,
                    b : 255,
                    a : 1
                }
            }
        }

        if(_.isUndefined(item.styles.background) ){
            return {
                rgb : {
                    r : 255,
                    g : 255,
                    b : 255,
                    a : 1
                }
            }
        }

        return this.styles.background
    }

    render() {
        const { item } = this.props

        const index = `${item.keyRow}_${item.keyColumn}_${item._id}`

        return (
            <div className="container__settings__attributes settings__default">
                <span className="delipress__builder__side__title">
                    {translationDelipressReact.Builder.component_settings.style.attributes_default.background}
                </span>
                <SettingsItem label={translationDelipressReact.background_color}>
                    <ColorSelector
                            handleChangeComplete={(color) => {
                                this.saveOptionValue("background", color)
                            }}
                            idSelector={`.id_selector_${index}`}
                            typeColor="backgroundColor"
                            disabledAlpha={false}
                            picker="sketch"
                            color={this.getColorItem()}
                        />
                </SettingsItem>
                <Padding
                    onChangePadding={this._handleChangePadding}
                    item={item}
                />
            </div>
        )
    }
}



export default AttributesDefault
