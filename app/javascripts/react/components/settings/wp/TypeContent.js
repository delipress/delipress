import * as _ from "underscore"
import React, { Component } from "react"
import PropTypes from "prop-types"

import SettingsItem from "javascripts/react/components/settings/SettingsItem"

class TypeContent extends Component {

    constructor(props){
        super(props)

        this._onChangeSettingsContent = this._onChangeSettingsContent.bind(this)
    }


    _onChangeSettingsContent(event) {
        const value = event.target.value
        
        this.props.toggleOption({
            full : (value === "full"),
            excerpt: (value !== "full")
        })
    }

    render() {
        const { item } = this.props

        return (
            <SettingsItem
                label={
                    translationDelipressReact.Builder.component_settings.wp_post
                        .settings_content.title
                }
                classModifier="delipress__builder__side__setting__input--align"
            >
                <div className="delipress__buttonsgroup">
                    <div className="delipress__buttonsgroup__cell">
                        <input
                            type="radio"
                            name="settings__content"
                            id="settings__content_full"
                            value="full"
                            checked={this.props.valueOption.full}
                            onChange={this._onChangeSettingsContent}
                        />
                        <label htmlFor="settings__content_full">
                            {
                                translationDelipressReact.Builder
                                    .component_settings.wp_post.settings_content
                                    .full
                            }
                        </label>
                    </div>
                    <div className="delipress__buttonsgroup__cell">
                        <input
                            type="radio"
                            name="settings__content"
                            id="settings__content_excerpt"
                            value="excerpt"
                            onChange={this._onChangeSettingsContent}
                            checked={this.props.valueOption.excerpt}
                        />
                        <label htmlFor="settings__content_excerpt">
                            {
                                translationDelipressReact.Builder
                                    .component_settings.wp_post.settings_content
                                    .excerpt
                            }
                        </label>
                    </div>
                </div>
            </SettingsItem>
        )
    }

}

TypeContent.propType = {
    toggleOption: PropTypes.func.isRequired,
    valueOption: PropTypes.object.isRequired
}

export default TypeContent
