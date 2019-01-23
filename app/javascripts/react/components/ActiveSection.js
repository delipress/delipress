import * as _ from "underscore"
import React, { Component } from "react"
import { DragSource } from 'react-dnd';
import { bindActionCreators, compose } from "redux"
import { connect } from "react-redux"

import EditorActions from "../services/actions/EditorActions"
import TemplateActions from "../services/actions/TemplateActions"
import { SETTINGS_STYLE } from "javascripts/react/constants/EditorConstants"
import HandleButton from 'javascripts/react/components/dnd/HandleButton'
import {
    SECTION_EMAIL_ONLINE,
    SECTION_UNSUBSCRIBE,
    SECTION,
    ItemTypes
} from "javascripts/react/constants/TemplateContentConstants"

import DeliAlert from 'javascripts/backend/DeliAlert'


const cardDrag = {
    beginDrag(props) {
        return {
            "index" : props.keyRow,
            "item" : props.row
        }
    }
}

function collectDrag(connect, monitor) {
    return {
        connectDragSource: connect.dragSource(),
        connectDragPreview: connect.dragPreview(),
        isDragging: monitor.isDragging()
    }
}

class ActiveSection extends Component {

    constructor(props){
        super(props)

        this.activeSettingsSection = this.activeSettingsSection.bind(this)
        this.deleteContent         = this.deleteContent.bind(this)
        this.duplicateSection      = this.duplicateSection.bind(this)
    }

    componentDidMount() {
        const {
            typeActiveSection,
        } = this.props

        const img = new Image();
        img.onload = () => this.props.connectDragPreview(img);

        switch(typeActiveSection){
            default:
                img.src = DELIPRESS_PATH_PUBLIC_IMG + "/preview/column-1.svg";
                break
        }
    }

    activeSettingsSection(){
        const {
            actionsEditor,
            row,
            keyRow
        } = this.props

        if(_.isUndefined(row)){
            return;
        }

        actionsEditor.activeSection(keyRow).then(() => {
            actionsEditor.changeItemOnSettingsContainer(
                _.extend(row, {
                    keyRow : keyRow
                }),
                SETTINGS_STYLE
            )
        })

    }

    changeItem() {
        const { actionsEditor, keyRow, row, typeActiveSection } = this.props

        actionsEditor.changeItemOnSettingsContainer(
            _.extend(row, {
                keyRow: keyRow,
                type: typeActiveSection
            }),
            SETTINGS_STYLE
        )
    }

    duplicateSection() {
        const {
            actionsTemplate,
            actionsEditor,
            keyRow,
            row,
            paramsSettingsComponent,
            typeActiveSection
        } = this.props

        const deferred = actionsTemplate.duplicateSection({
            keyRow: keyRow
        })

        const _typeActiveSection = _.isUndefined(typeActiveSection)
            ? SECTION
            : typeActiveSection

        deferred.then(paramsSettingsComponent.changeItemSuccess).then(() => {
            const _newKeyRow = Number(keyRow + 1)

            actionsEditor.activeSection(null)
            // actionsEditor.changeItemOnSettingsContainer(
            //     _.extend({}, row, {
            //         keyRow: _newKeyRow,
            //         type: _typeActiveSection
            //     }),
            //     SETTINGS_STYLE
            // )
        })
    }

    deleteContent() {
        const {
            actionsTemplate,
            actionsEditor,
            keyRow,
            paramsSettingsComponent,
            typeActiveSection
        } = this.props

        let deferred = null

        const deliAlert = new DeliAlert()
        deliAlert.handle(() => {
            if (typeActiveSection === SECTION_EMAIL_ONLINE) {
                deferred = actionsEditor.deleteSectionEmailOnline(keyRow)
            } else {
                deferred = actionsTemplate.deleteSection(keyRow)
            }

            deferred.then(paramsSettingsComponent.changeItemSuccess)
        })

        deliAlert.show(translationDelipressReact.Builder.section.delete, translationDelipressReact.Builder.section.delete_warning, 'delete')
    }

    render() {
        const {
            fixSection,
            typeActiveSection,
            connectDragSource
        } = this.props

        if (!_.isUndefined(fixSection) && fixSection) {
            return false
        }


        return (
            <div className="delipress__builder__main__preview__actions">
                <div className="delipress__builder__main__preview__actions__wrap">
                    {!_.find([SECTION_EMAIL_ONLINE, SECTION_UNSUBSCRIBE], (val) => { return val === typeActiveSection } )
                        ? <div className="delipress__builder__main__preview__actions__title">{translationDelipressReact.Builder.ui.section}</div>
                        : false}
                    {!_.find([SECTION_EMAIL_ONLINE, SECTION_UNSUBSCRIBE], (val) => { return val === typeActiveSection } )
                        ?
                        connectDragSource(
                            <a
                                title={translationDelipressReact.Builder.actions.move}
                                className="delipress__builder__main__preview__actions__button"
                            >
                                <span className="dashicons dashicons-move" />
                            </a>
                        )
                        : false}
                    {!_.find([SECTION_EMAIL_ONLINE, SECTION_UNSUBSCRIBE], (val) => { return val === typeActiveSection } )
                        ? <a
                              title={translationDelipressReact.Builder.actions.configure}
                              className="delipress__builder__main__preview__actions__button"
                              onClick={this.activeSettingsSection}
                          >
                              <span className="dashicons dashicons-admin-generic" />
                          </a>
                        : false}
                    {!_.find([SECTION_EMAIL_ONLINE, SECTION_UNSUBSCRIBE], (val) => { return val === typeActiveSection } )
                        ? <a
                              title={translationDelipressReact.Builder.actions.duplicate}
                              className="delipress__builder__main__preview__actions__button"
                              onClick={this.duplicateSection}
                          >
                              <span className="dashicons dashicons-admin-page" />
                          </a>
                        : false}
                    {!_.find([SECTION_EMAIL_ONLINE, SECTION_UNSUBSCRIBE], (val) => { return val === typeActiveSection } )
                        ? <a
                              title={translationDelipressReact.Builder.actions.delete}
                              className="delipress__builder__main__preview__actions__button"
                              onClick={this.deleteContent}
                          >
                              <span className="dashicons dashicons-trash" />
                          </a>
                        : false}
                </div>
            </div>
        )
    }
}

function mapDispatchToProps(dispatch, context) {
    const actionsEditor = new EditorActions()
    const actionsTemplate = new TemplateActions()

    return {
        actionsEditor: bindActionCreators(actionsEditor, dispatch),
        actionsTemplate: bindActionCreators(actionsTemplate, dispatch)
    }
}

export default compose(
    connect(null, mapDispatchToProps),
    DragSource(ItemTypes.MOVE_SECTION, cardDrag, collectDrag),
)(ActiveSection)
