import React, { Component, cloneElement } from 'react'
import { DragDropContextProvider } from 'react-dnd';
import HTML5Backend from 'react-dnd-html5-backend';
import CreateCampaignStepTwo from 'javascripts/react/modules/campaign/containers/CreateCampaignStepTwo'
import { Link, useRouterHistory } from 'react-router'
import { createHashHistory } from 'history'


class DragAndDropContext extends Component  {

    constructor(props) {
        super(props);
        this.appHistory = useRouterHistory(createHashHistory)({ queryKey: false })
    }

    componentWillMount(){
        this.element = document.querySelector('#delipress-react-selector')
    }

    render() {
        return (
            <DragDropContextProvider backend={HTML5Backend} window={this.element}>
                <CreateCampaignStepTwo/>
            </DragDropContextProvider>
        )
    }
}

export default DragAndDropContext