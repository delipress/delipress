import { createStore, applyMiddleware, compose } from 'redux'
import thunkMiddleware from 'redux-thunk'
import createLogger from 'redux-logger'
import { configureAxios, configureAxiosOptions } from 'javascripts/react/api/axios'
import axiosMiddleware from 'redux-axios-middleware';

import moduleOptinReducer from 'javascripts/react/modules/optin/reducers/index'

export default function configureStore(initialState) {

    let store = false
    if(DELIPRESS_ENV === "DEV"){
        const composeEnhancers = window.__REDUX_DEVTOOLS_EXTENSION_COMPOSE__ || compose;
        store = createStore(
            moduleOptinReducer,
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
            moduleOptinReducer,
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
