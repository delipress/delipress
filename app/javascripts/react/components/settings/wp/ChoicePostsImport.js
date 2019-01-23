import * as _ from "underscore"
import React, { Component, cloneElement } from "react"
import PropTypes from "prop-types"

import SettingsItem from "javascripts/react/components/settings/SettingsItem"

class ChoicePostsImport extends Component {

    constructor(props){
        super(props)

        this._importPosts = this._importPosts.bind(this)
    }

    componentWillMount(){
        this.setState({importLoad : false})
    }

    _importPosts() {
        if(this.state.importLoad){
            return;
        }

        this.setState({importLoad: true})

        const config = _.pick(this.props.config, "title","image","type_content")

        this.props.importPostsWP(
            this.props.choicePosts,
            config
        )
    }

    render() {
        const {
            choicePosts
        } = this.props

        return (
            <SettingsItem>
                <ul className="delipress__selection">
                    {
                        choicePosts.map((post, key) => {
                            return (
                                <li className="delipress__selection__item" key={"post_" + key}>
                                    <div className="delipress__selection__item__name">
                                        {post.label}
                                    </div>
                                    <div className="delipress__selection__item__actions">
                                        <span 
                                            className="dashicons dashicons-no delipress-js-selection-remove"
                                            onClick={() => { 
                                                this.props.removeImportPost(key) 
                                            }}
                                        />
                                    </div>
                                </li>
                            )
                        })
                    }
                </ul>
                <a
                    className="delipress__button delipress__button--main"
                    onClick={this._importPosts}
                >
                    {
                        this.state.importLoad &&
                        <span className="dashicons dashicons-update dashicons--roll"></span>
                    }
                    {  
                        !this.state.importLoad &&
                        translationDelipressReact.Builder.component_settings.wp_archive_post.import_posts
                    }
                </a>
            </SettingsItem>

        )
    }

}

ChoicePostsImport.propTypes = {
    choicePosts : PropTypes.array.isRequired,
    importPostsWP : PropTypes.func.isRequired,
    removeImportPost : PropTypes.func.isRequired,
    config : PropTypes.object.isRequired
}

export default ChoicePostsImport
