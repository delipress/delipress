import React from 'react';
import ReactDOM from 'react-dom';

import {
    Provider
} from 'react-redux'

import { createHashHistory } from 'history'

import configureStore from 'javascripts/react/modules/campaign/stores/index'

import App from 'javascripts/react/modules/campaign/containers/App'
import DragAndDropContext from 'javascripts/react/modules/campaign/containers/DragAndDropContext'

import Routes from 'javascripts/react/constants/Routes'
import * as _ from 'underscore'

document.addEventListener('DOMContentLoaded', () => {

    const element    = document.querySelector('#delipress-react-selector')
    const store      = configureStore()

    ReactDOM.render(
        <Provider store={store}>
            <App>
                <DragAndDropContext />
            </App>
        </Provider>,
        element
    );
});


