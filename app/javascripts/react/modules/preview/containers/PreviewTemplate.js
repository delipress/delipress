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
import EndpointTemplateActions from "javascripts/react/services/actions/EndpointTemplateActions";
import { transformToMjml } from "javascripts/react/helpers/transformToMjml";
import MjmlContainer from "javascripts/react/containers/MjmlContainer"
import PreviewCampaign from "javascripts/react/modules/preview/containers/PreviewCampaign"

class PreviewTemplate extends Component {
    constructor(props) {
        super(props)

        this.changeTemplatePreview = this.changeTemplatePreview.bind(this)
    }

    componentDidMount() {
        document.addEventListener('onChangeTeampltePreview', (e) => {
            this.changeTemplatePreview(e.detail.template_id)
        })
    }

    changeTemplatePreview(templateId){
        this.props.endpointActionsTemplate.getTemplate(templateId)

    }

    componentDidUpdate(nextProps, nextState) {
        const iframe    = this.refs.iframe;
        const document  = iframe.contentDocument;
        document.body.innerHTML = this._transformToMjml();
    }

    _transformToMjml() {
        const { template } = this.props;

        const result = transformToMjml(template.config, template.theme);

        return result.html;
    }

    render() {
        return (
            <iframe 
                ref="iframe" 
                className="preview_template" 
            />
        );
    }
}


function mapStateToProps(state){
    return {
        template : state.EndpointTemplateReducer.template
    }
}

function mapDispatchToProps(dispatch, context) {
    const endpointActionsTemplate = new EndpointTemplateActions();

    return {
        endpointActionsTemplate: bindActionCreators(endpointActionsTemplate, dispatch)
    };
}

export default connect(mapStateToProps, mapDispatchToProps)(PreviewTemplate)
