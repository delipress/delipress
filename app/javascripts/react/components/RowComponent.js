import React, { Component } from 'react'
import { connect } from 'react-redux'
import { bindActionCreators } from 'redux'
import classNames from 'classnames'
import * as _ from 'underscore'

import { shallowEqual } from 'javascripts/react/helpers/shallowEqual'
import EditorActions from 'javascripts/react/services/actions/EditorActions'
import ColumnComponent from './ColumnComponent'
import ActiveSection from 'javascripts/react/components/ActiveSection'
import {
    SECTION,
} from 'javascripts/react/constants/TemplateContentConstants'

import {
    SETTINGS_STYLE,
} from 'javascripts/react/constants/EditorConstants'

import { transformStyleSectionToTemplate } from 'javascripts/react/helpers/transformStyleSectionToTemplate'

class RowComponent extends Component {

    constructor(props){
        super(props)

        this._activeSection = this._activeSection.bind(this)

    }

    componentWillUnmount(){
        const {
            actionsEditor,
            keyRow,
            activeSection
        } = this.props

        if(keyRow === activeSection){
            actionsEditor.activeSection(null)
        }
    }


    _activeSection(event){

        const {
            actionsEditor,
            row,
            keyRow,
            fixSection,
            typeActiveSection
        } = this.props

        if(!_.isUndefined(fixSection) && fixSection){
            return false;
        }

        const _typeActiveSection = (_.isUndefined(typeActiveSection)) ? SECTION : typeActiveSection

        if(jQuery(event.target).hasClass("delipress__builder__main__preview__section")){
            actionsEditor.activeSection(keyRow)
            // IF YOU WANT ACTIVE SECTION SETTINGS AUTOMATICALLY
            actionsEditor.changeItemOnSettingsContainer(
                _.extend(row, {
                    keyRow : keyRow,
                    type : _typeActiveSection
                }),
                SETTINGS_STYLE
            )
        }
    }

    render(){
        const {
            row,
            keyRow,
            moveItem,
            addItem,
            addItemOnEmpty,
            paramsSettingsComponent,
            activeSection,
            actionsEditor,
            fixItem,
            fixSection,
            typeActiveSection
        } = this.props

        const _typeActiveSection = (_.isUndefined(typeActiveSection)) ? SECTION : typeActiveSection
        const _styles            = transformStyleSectionToTemplate(row.styles)

        // Fix : no BG color
        if ( keyRow == "email_online" ) {
            _styles.backgroundColor = ""
        }

        const _classNames = classNames({
            "delipress__builder__main__preview__section" : true,
            "delipress--is-active" : keyRow === activeSection
        })

        const index = `delipress__builder__main__preview__section__row__${keyRow}`
        const _classNamesRow = classNames({
            "delipress__builder__main__preview__section__row" : true
        }, index)

        return (
            <div
                className={_classNames}
                onClick={this._activeSection}
            >
                <ActiveSection
                    row={row}
                    keyRow={keyRow}
                    fixSection={fixSection}
                    typeActiveSection={_typeActiveSection}
                    paramsSettingsComponent={paramsSettingsComponent}
                />

                    <div
                        className={_classNamesRow}
                        style={_styles}
                    >
                    {
                        row.columns.map((column, key) => {
                            return (
                                <ColumnComponent
                                    key={"column_" + key}
                                    column={column}
                                    keyRow={keyRow}
                                    keyColumn={key}
                                    moveItem={moveItem}
                                    addItem={addItem}
                                    addItemOnEmpty={addItemOnEmpty}
                                    fixItem={fixItem}
                                    paramsSettingsComponent={paramsSettingsComponent}
                                />
                            )
                        })
                    }
                </div>
            </div>
        )
    }
}


function mapDispatchToProps(dispatch, context){
    const actionsEditor   = new EditorActions()

    return {
        "actionsEditor"  : bindActionCreators(actionsEditor, dispatch)
    }
}

function mapStateToProps(state){
    return {
        "activeSection" : state.EditorReducer.activeSection,
        "activeItem" : state.EditorReducer.activeItem
    }
}


export default connect(mapStateToProps, mapDispatchToProps)(RowComponent)
