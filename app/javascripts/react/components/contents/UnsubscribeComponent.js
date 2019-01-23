import React, { Component } from 'react'
import { connect } from 'react-redux'
import classNames from 'classnames'
import { compose, bindActionCreators } from 'redux'

import BaseHeaderFooter from 'javascripts/react/components/contents/base/BaseHeaderFooter'
import EditorActions from '../../services/actions/EditorActions'

class UnsubscribeComponent extends Component {

    writeCSS(){
        const {
            item
        } = this.props

        let itemStyles = {}
        if(!_.isUndefined(item.styles.presetChoice)){
            itemStyles = _.clone(
                _.find(item.styles.presets, {"type" : item.styles.presetChoice})
            )
        }
        else{
            itemStyles = _.clone(item.styles)
        }

        const idStyle  = `delipress-component-${item.keyRow}${item.keyColumn}${item["_id"]}`

        let css = `\n#delipress-react-selector #${idStyle} p {
            line-height:${itemStyles["line-height"]};
            font-size:${itemStyles["font-size"]}px;
            font-family:${itemStyles["font-family"]} , Helvetica, Arial, sans-serif;
        }`

        return css
    }

   render(){

        return (
            <BaseHeaderFooter
                metaReplace="link_unsubscribe"
                {...this.props}
            >
                <style>{this.writeCSS()}</style>
            </BaseHeaderFooter>
        )
    }

}

function mapDispatchToProps(dispatch, context){

    let actionsEditor = new EditorActions()

    return {
        "actionsEditor" : bindActionCreators(actionsEditor, dispatch)
    }
}

function mapStateToProps(state){
    return {
        "activeItem" : state.EditorReducer.activeItem
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(UnsubscribeComponent)
