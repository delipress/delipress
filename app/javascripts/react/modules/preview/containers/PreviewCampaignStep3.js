import React, { Component, cloneElement } from "react";
import { connect } from "react-redux";
import { compose, bindActionCreators } from "redux";
import * as _ from "underscore";

import {
    LAYOUT_DESKTOP,
    LAYOUT_IPAD,
    LAYOUT_IPHONE
} from "javascripts/react/constants/LayoutConstants";
import { shallowEqual } from "javascripts/react/helpers/shallowEqual";
import TemplateActions from "javascripts/react/services/actions/TemplateActions";
import { transformToMjml } from "javascripts/react/helpers/transformToMjml";
import MjmlContainer from "javascripts/react/containers/MjmlContainer"
import PreviewCampaign from "javascripts/react/modules/preview/containers/PreviewCampaign"

class PreviewCampaignStep3 extends Component {
    constructor(props){
        super(props)

        this._togglePreview = this._togglePreview.bind(this)
    }

    componentWillMount() {
        const { actionsTemplate } = this.props;

        this.setState({
            preview: false
        })

        actionsTemplate.getCampaign(DELIPRESS_CAMPAIGN_ID);
    }

    componentDidMount() {
        this._updateIframe();
    }

    componentDidUpdate() {
        this._updateIframe();
    }

    componentWillUpdate(nextProps, nextState) {
        const { actionsTemplate, config, theme } = this.props;

        if (
            !shallowEqual(nextProps.config, config) ||
            !shallowEqual(nextProps.theme, theme)
        ) {
            const result = transformToMjml(nextProps.config, nextProps.theme);
            actionsTemplate.saveCampaignTemplateHtml(DELIPRESS_CAMPAIGN_ID, {
                html: result.html
            });
        }
    }

    _transformToMjml() {
        const { config, theme } = this.props;

        const result = transformToMjml(config, theme);

        return result.html;
    }

    _updateIframe() {
        const iframe = this.refs.iframe;
        const document = iframe.contentDocument;
        document.body.innerHTML = this._transformToMjml();
    }

    _togglePreview(e) {
        e.preventDefault()
        this.setState({
            preview: !this.state.preview
        })
        jQuery("body").toggleClass("mjml-preview-on")
    }

    render() {
        return (
            <div className="delipress__preview">
                <div className="delipress__center">
                    <a
                        className="delipress__button delipress__button--soft"
                        href="#"
                        onClick={this._togglePreview}
                    >
                        {
                            translationDelipressReact.Preview.desktop_preview
                        }
                    </a>
                </div>
                <div className="delipress__preview__device delipress__preview__device--smartphone">
                    <iframe ref="iframe" />
                </div>
                <MjmlContainer togglePreview={this._togglePreview} visible={this.state.preview}>
                    <PreviewCampaign standalone={true} />
                </MjmlContainer>
            </div>
        );
    }
}

function mapStateToProps(state) {
    return {
        template: state.TemplateReducer.template,
        config: state.TemplateReducer.config,
        theme: state.TemplateReducer.config.theme
    };
}

function mapDispatchToProps(dispatch, context) {
    const actionsTemplate = new TemplateActions();

    return {
        actionsTemplate: bindActionCreators(actionsTemplate, dispatch)
    };
}

export default connect(mapStateToProps, mapDispatchToProps)(
    PreviewCampaignStep3
);
