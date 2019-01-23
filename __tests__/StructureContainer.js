import React from 'react';
import ReactDOM from 'react-dom'
import { getStructureState } from '../__helpers__/StructureReducer'
import { getTemplateState } from '../__helpers__/TemplateReducer'

import configureStore from 'redux-mock-store';
import renderer from 'react-test-renderer';
import { Provider } from 'react-redux';
import StructureContainer from 'javascripts/react/modules/template/containers/StructureContainer'

const middlewares = [];
const mockStore   = configureStore(middlewares);

const getState    = {
    StructureReducer : getStructureState(),
    TemplateReducer : getTemplateState()
}

const store        = mockStore(getState)


describe('Structure container', () => {
    
    it('render structure container', () => {
        const wrapper = renderer.create(
            <Provider store={store}>
                <StructureContainer />
            </Provider>
        ).toJSON();

        expect(wrapper).toMatchSnapshot();
    });

});
