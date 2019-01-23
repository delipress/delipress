import React, { Component } from 'react'
import classNames from "classnames"
import {
    WP_WOO_ARCHIVE_PRODUCT,
    WP_ARCHIVE_ARTICLE
} from 'javascripts/react/constants/TemplateContentConstants'

class WPArchiveArticle extends Component {
    
    render(){

        const _class = classNames({
            "dashicons-wordpress" : this.props.type === WP_ARCHIVE_ARTICLE,
            "dashicons-cart" : this.props.type === WP_WOO_ARCHIVE_PRODUCT
        }, "dashicons")

        let _text = translationDelipressReact.Builder.component.wp_archive_post
        if(this.props.type === WP_WOO_ARCHIVE_PRODUCT){
            _text = translationDelipressReact.Builder.component.wp_archive_post_woo
        }

        return (
            <div className="delipress__builder__side__component">
                <span className={_class}></span>
                {_text}
            </div>
        )
    }
}


export default WPArchiveArticle

