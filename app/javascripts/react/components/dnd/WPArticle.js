import React, { Component } from 'react'
import classNames from "classnames"
import {
    WP_ARTICLE,
    WP_WOO_PRODUCT
} from 'javascripts/react/constants/TemplateContentConstants'

class WPArticle extends Component {

    render(){

        const _class = classNames({
            "dashicons-wordpress" : this.props.type === WP_ARTICLE,
            "dashicons-cart" : this.props.type === WP_WOO_PRODUCT
        }, "dashicons")

        let _text = translationDelipressReact.Builder.component.wp_article
        if(this.props.type === WP_WOO_PRODUCT){
            _text = translationDelipressReact.Builder.component.wp_product
        }

        return (
            <div className="delipress__builder__side__component">
                <span className={_class}></span>
                {_text}
            </div>
        )
    }
}


export default WPArticle
