import React from 'react';
import ReactDOM from 'react-dom';

import { 
    Provider 
} from 'react-redux'

import { 
    Router, 
    Route,
    IndexRoute, 
    Link, 
    IndexLink, 
    browserHistory,
    useRouterHistory
} from 'react-router'

import { createHashHistory } from 'history'

import { 
    syncHistoryWithStore
} from 'react-router-redux'

import configureStore from 'javascripts/react/modules/preview/stores/index'

import App from 'javascripts/react/modules/preview/containers/App'
import PreviewCampaign from 'javascripts/react/modules/preview/containers/PreviewCampaign'

import Routes from 'javascripts/react/constants/Routes'
import * as _ from 'underscore'

document.addEventListener('DOMContentLoaded', () => {

    const element    = document.querySelector('#delipress-react-selector')
    const store      = configureStore()
    const history    = syncHistoryWithStore(browserHistory, store)
    const appHistory = useRouterHistory(createHashHistory)({ queryKey: false })

    if(_.isEmpty(DELIPRESS_CAMPAIGN_ID)){
        return false
    }

    ReactDOM.render( 
        <Provider store={store}>
            <Router history={appHistory} >
                <Route path={Routes.PREVIEW_CAMPAIGN.link} component={App}>
                    <IndexRoute component={PreviewCampaign} />
                </Route>          
            </Router>
        </Provider>,
        element
    );
});


