import { createStore, applyMiddleware, compose } from 'redux'
import thunkMiddleware from 'redux-thunk'
import createLogger from 'redux-logger'
import { configureAxios, configureAxiosOptions } from 'javascripts/react/api/axios'
import axiosMiddleware from 'redux-axios-middleware';

import modulePreviewReducer from 'javascripts/react/modules/preview/reducers/index'

export default function configureStore(initialState) {

    let store = false
    if(DELIPRESS_ENV === "DEV"){
        const composeEnhancers = window.__REDUX_DEVTOOLS_EXTENSION_COMPOSE__ || compose;
            store = createStore(
                modulePreviewReducer,
                composeEnhancers(
                    applyMiddleware(
                        createLogger(),
                        axiosMiddleware(configureAxios(), configureAxiosOptions()),
                        thunkMiddleware
                    )
                )
            )
    }
    else{
        store = createStore(
            modulePreviewReducer,
            compose(
                applyMiddleware(
                    axiosMiddleware(configureAxios(), configureAxiosOptions()),
                    thunkMiddleware
                )
            )
        )
    }

    return store
}
