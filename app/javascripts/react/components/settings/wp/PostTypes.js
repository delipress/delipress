import * as _ from "underscore"
import React, { Component, cloneElement } from "react"
import PropTypes from "prop-types"
import { connect } from "react-redux"

import SettingsItem from "javascripts/react/components/settings/SettingsItem"
import Select from "react-select"

class PostTypes extends Component {

    constructor(props){
        super(props)

        this._changeChoicePostType = this._changeChoicePostType.bind(this)
    }
    _changeChoicePostType(obj) {
        let _sendObj = {}
        if(!_.isNull(obj)){
            _sendObj = obj
        }
        this.props.changeChoicePostType(_sendObj)
    }

    render() {
        const {
            postTypes,
            posts,
            valueOption
        } = this.props

        const options = _.map(postTypes, postType => {
            return {
                value: postType.name,
                label: postType.label
            }
        })

        return (
            <SettingsItem label={translationDelipressReact.Builder.component_settings.wp_archive_post.settings_choose_article.post_type}>
                <Select
                    options={options}
                    value={valueOption}
                    onChange={this._changeChoicePostType}
                />
            </SettingsItem>
        )
    }

}

PostTypes.propTypes = {
    changeChoicePostType : PropTypes.func.isRequired,
    valueOption: PropTypes.object.isRequired
}

function mapStateToProps(state){
    return {
        postTypes : state.PostTypeReducer.postTypes
    }
}

export default connect(mapStateToProps)(PostTypes)
