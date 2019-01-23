import { combineReducers } from 'redux'
import { syncHistoryWithStore, routerReducer } from 'react-router-redux'

import TemplateReducer from 'javascripts/react/reducers/TemplateReducer'
import EndpointTemplateReducer from 'javascripts/react/reducers/EndpointTemplateReducer'

const moduleReducers = combineReducers({
    TemplateReducer,
    EndpointTemplateReducer,
    routing: routerReducer
})

export default moduleReducers
