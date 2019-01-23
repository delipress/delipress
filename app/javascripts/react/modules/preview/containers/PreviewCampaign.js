import React, { Component, cloneElement } from 'react'
import { connect } from 'react-redux'
import { compose, bindActionCreators } from 'redux'
import * as _ from 'underscore'

import {
    LAYOUT_DESKTOP,
    LAYOUT_IPAD,
    LAYOUT_IPHONE
} from 'javascripts/react/constants/LayoutConstants'
import { shallowEqual } from 'javascripts/react/helpers/shallowEqual'
import TemplateActions from 'javascripts/react/services/actions/TemplateActions'
import { transformToMjml } from 'javascripts/react/helpers/transformToMjml'

class PreviewCampaign extends Component  {

    componentWillMount(){

        const {
            standalone,
            actionsTemplate
        } = this.props

        this.setState({
            "layout" : LAYOUT_IPHONE
        })

        if(!_.isUndefined(standalone) && standalone){
            return;
        }

        actionsTemplate.getCampaign(DELIPRESS_CAMPAIGN_ID)
    }

    componentDidMount() {
        this._updateIframe();
    }

    componentDidUpdate() {
        this._updateIframe();
    }

    componentWillUpdate(nextProps, nextState){
        const {
            standalone,
            actionsTemplate,
            config,
            theme
        } = this.props

        if(!_.isUndefined(standalone) && standalone){
            return;
        }

        if(
            !shallowEqual(nextProps.config, config) ||
            !shallowEqual(nextProps.theme, theme)
        ){
            const result = transformToMjml(nextProps.config, nextProps.theme)
            actionsTemplate.saveCampaignTemplateHtml(DELIPRESS_CAMPAIGN_ID, {
                "html" : result.html
            })
        }
    }

    _transformToMjml(){
        const {
            config,
            theme
        } = this.props
        const result = transformToMjml(config, theme)

        return result.html;
    }

     _updateIframe() {
        const iframe            = this.refs.iframe;
        const document          = iframe.contentDocument;

        document.body.innerHTML = this._transformToMjml();
    }

    _getStylesContainer(){


        let _styles = {
            "border": "none",
            "marginBottom": "10px",
            "marginTop": "10px"
        }

        switch(this.state.layout){
            case LAYOUT_DESKTOP:
                _styles = _.extend(_styles, {
                    "width" : "800px",
                    "height" : "1000px"
                })
                break
            case LAYOUT_IPAD:
                _styles = _.extend(_styles, {
                    "height": "1250px",
                    "width": "840px",
                    "padding": "111px 34px",
                    "backgroundImage": `url(${DELIPRESS_PATH_PUBLIC_IMG}/ipad-layout.png)`
                })
                break
            case LAYOUT_IPHONE:
                _styles = _.extend(_styles, {
                    "height": "780px",
                    "width": "369px",
                    "padding": "106px 24px 106px 25px",
                    "backgroundImage": `url(${DELIPRESS_PATH_PUBLIC_IMG}/iphone-layout.png)`
                })
                break
        }

        return _styles
    }

    _onClickChangeLayout(layout, e){
        e.preventDefault()
        this.setState({"layout" : layout})
    }

    _getIframe(){
         switch(this.state.layout){
            case LAYOUT_DESKTOP:
                return (
                    <div className="delipress__preview__device delipress__preview__device--desktop">
                        <iframe ref="iframe" />
                    </div>
                )
            case LAYOUT_IPAD:
                return (
                    <div className="delipress__preview__device delipress__preview__device--tablet">
                        <iframe ref="iframe" />
                    </div>
                )
            case LAYOUT_IPHONE:
                return (
                    <div className="delipress__preview__device delipress__preview__device--smartphone">
                        <iframe ref="iframe" />
                    </div>
                )
        }
    }
    render() {
        return (
            <div className="delipress__preview">
                <div className="delipress__preview__devices">
                    <a href="" className="delipress__preview__devices__smartphone" onClick={this._onClickChangeLayout.bind(this, LAYOUT_IPHONE)}>
                        <img src={DELIPRESS_PATH_PUBLIC_IMG + "/devices/smartphone-icon.svg"} alt={translationDelipressReact.Preview.smartphone} />
                        {translationDelipressReact.Preview.smartphone}
                    </a>
                    <a href="" className="delipress__preview__devices__tablet" onClick={this._onClickChangeLayout.bind(this, LAYOUT_IPAD)}>
                        <img src={DELIPRESS_PATH_PUBLIC_IMG + "/devices/tablet-icon.svg"} alt={translationDelipressReact.Preview.tablet} />
                        {translationDelipressReact.Preview.tablet}
                    </a>
                    <a href="" className="delipress__preview__devices__desktop" onClick={this._onClickChangeLayout.bind(this, LAYOUT_DESKTOP)}>
                        <img src={DELIPRESS_PATH_PUBLIC_IMG + "/devices/desktop-icon.svg"} alt={translationDelipressReact.Preview.desktop} />
                        {translationDelipressReact.Preview.desktop}
                    </a>
                </div>

                {this._getIframe()}
            </div>
        )
    }
}




function mapStateToProps(state){
    return { 
        "template" : state.TemplateReducer.template,
        "config" : state.TemplateReducer.config,
        "theme" : state.TemplateReducer.config.theme
    }
}

function mapDispatchToProps(dispatch, context){
    const actionsTemplate = new TemplateActions()

    return {
        "actionsTemplate": bindActionCreators(actionsTemplate, dispatch)
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(PreviewCampaign)
