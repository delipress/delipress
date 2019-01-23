import React from 'react';
import ReactDOM from 'react-dom';
import * as _ from 'underscore'
import {
    Provider
} from 'react-redux'

import configureStore from 'javascripts/react/modules/preview/stores/index'

import App from 'javascripts/react/modules/preview/containers/App'
import PreviewTemplate from 'javascripts/react/modules/preview/containers/PreviewTemplate'

document.addEventListener('DOMContentLoaded', () => {

    const element = document.querySelector('#delipress-react-selector')
    const store = configureStore()

    ReactDOM.render(
        <Provider store={store}>
            <PreviewTemplate />
        </Provider>,
        element
    );
});


