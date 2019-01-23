import { combineReducers } from 'redux'

import TemplateReducer from 'javascripts/react/reducers/TemplateReducer'
import EditorReducer from 'javascripts/react/reducers/EditorReducer'
import SavingReducer from 'javascripts/react/reducers/SavingReducer'
import PostTypeReducer from 'javascripts/react/reducers/PostTypeReducer'
import EndpointTemplateReducer from 'javascripts/react/reducers/EndpointTemplateReducer'

const moduleReducers = combineReducers({
    SavingReducer,
    EditorReducer,
    TemplateReducer,
    PostTypeReducer,
    EndpointTemplateReducer
})

export default moduleReducers
