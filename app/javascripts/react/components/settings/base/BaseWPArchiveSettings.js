import * as _ from "underscore"
import React, { Component, cloneElement } from "react"
import PropTypes from "prop-types"


class BaseWPArchiveSettings extends Component {
    
    constructor(props){
        super(props)

        this.saveOptionValue        = this.saveOptionValue.bind(this)
        this.saveOptionValueInArray = this.saveOptionValueInArray.bind(this)
        this.removeImportPost       = this.removeImportPost.bind(this)
        this.saveEditor             = this.saveEditor.bind(this)
        
        const { item } = this.props
        
        if(!_.isNull(item)){
            this.styles = _.clone(item.styles)
        }

        this.verifyStyles()
    }

    verifyStyles(){
        if(_.isUndefined(this.styles.options.type_content) ) {
            this.styles = _.extend(this.styles, {
                options : _.extend(this.styles.options, {
                    type_content : {
                        full: false,
                        excerpt: true
                    }
                })
            })
        }
    }

    componentWillReceiveProps(){
        const { item } = this.props
        
        if(!_.isNull(item)){
            this.styles = _.clone(item.styles)
        }

        this.verifyStyles()
    }

    saveOptionValue(name, value){

        this.styles.options[name] = value
        this.props.saveEditor(this.styles)
    }

    saveOptionValueInArray(name, value){
        this.styles.options[name].push(value)
        this.props.saveEditor(this.styles)
    }

    saveEditor(){
        this.props.saveEditor(this.styles)
    }

    removeImportPost(ind){
        this.styles.options.choicePosts.splice(ind, 1)
        this.props.saveEditor(this.styles)
    }

}

export default BaseWPArchiveSettings
