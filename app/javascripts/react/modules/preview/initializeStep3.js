import React from 'react';
import ReactDOM from 'react-dom';

import { 
    Provider 
} from 'react-redux'

import { createHashHistory } from 'history'

import { 
    syncHistoryWithStore
} from 'react-router-redux'

import configureStore from 'javascripts/react/modules/preview/stores/index'

import App from 'javascripts/react/modules/preview/containers/App'
import PreviewCampaignStep3 from 'javascripts/react/modules/preview/containers/PreviewCampaignStep3'

import Routes from 'javascripts/react/constants/Routes'
import * as _ from 'underscore'

document.addEventListener('DOMContentLoaded', () => {

    const element    = document.querySelector('#delipress-react-selector')
    const store      = configureStore()

    if(_.isEmpty(DELIPRESS_CAMPAIGN_ID)){
        return false
    }

    ReactDOM.render( 
        <Provider store={store}>
            <PreviewCampaignStep3 />
        </Provider>,
        element
    );
});


