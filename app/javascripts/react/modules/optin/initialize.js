import React from 'react';
import ReactDOM from 'react-dom';

import { 
    Provider 
} from 'react-redux'

import configureStore from 'javascripts/react/modules/optin/stores/index'

import App from 'javascripts/react/modules/optin/containers/App'
import CreateOptinStepTwo from 'javascripts/react/modules/optin/containers/CreateOptinStepTwo'

import * as _ from 'underscore'

document.addEventListener('DOMContentLoaded', () => {

    const element    = document.querySelector('#delipress-react-selector')
    const store      = configureStore()

    if(_.isEmpty(DELIPRESS_OPTIN_ID)){
        return false
    }

    ReactDOM.render( 
        <Provider store={store}>
            <App>
                <CreateOptinStepTwo />
            </App>
        </Provider>,
        element
    );
});


