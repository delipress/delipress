import * as _ from "underscore"
import React, { Component } from "react"
import PropTypes from "prop-types"
import { bindActionCreators, compose } from "redux"
import { getEmptyImage } from 'react-dnd-html5-backend'
import { DragSource, DragLayer } from 'react-dnd';
import { connect } from "react-redux"

import EditorActions from "javascripts/react/services/actions/EditorActions"
import TemplateActions from "javascripts/react/services/actions/TemplateActions"
import DeliAlert from 'javascripts/backend/DeliAlert'
import { SETTINGS_EDITOR } from "javascripts/react/constants/EditorConstants"
import HandleButton from 'javascripts/react/components/dnd/HandleButton'

import {
    TEXT,
    DIVIDER,
    IMAGE,
    BUTTON,
    SOCIAL_BUTTON,
    SPACER,
    WP_CUSTOM_POST,
    TITLE,
    WP_ARTICLE,
    EMAIL_ONLINE,
    WP_ARCHIVE_CUSTOM_POST,
    WP_ARCHIVE_ARTICLE,
    UNSUBSCRIBE,
    LIST_TEMPLATE_CONTENT_LIKE_SECTION,
    ItemTypes
} from "javascripts/react/constants/TemplateContentConstants"



const cardDrag = {
    beginDrag(props) {
        return {
            "index" : props.index,
            "item" : props.children
        }
    }
}

function collectDragLayer(monitor) {
    return {
        itemType: monitor.getItemType(),
        initialOffset: monitor.getInitialSourceClientOffset(),
        currentOffset: monitor.getSourceClientOffset(),
        isDragging: monitor.isDragging(),
    };
}

function collectDrag(connect, monitor) {
    return {
        connectDragSource: connect.dragSource(),
        isDragging: monitor.isDragging(),
        connectDragPreview: connect.dragPreview()
    }
}


function snapToGrid(x, y) {
    const snappedX = Math.round(x / 32) * 32;
    const snappedY = Math.round(y / 32) * 32;

    return [snappedX, snappedY];

}


class ActiveContentComponent extends Component {

    constructor(props){
        super(props)
        this.changeItem       = this.changeItem.bind(this)
        this.activeSettings   = this.activeSettings.bind(this)
        this.deleteContent    = this.deleteContent.bind(this)
        this.duplicateContent = this.duplicateContent.bind(this)
    }

    componentDidUpdate() {
        const {
            item
        } = this.props

        if(_.isNull(item) || _.isUndefined(item)){
            return;
        }

        const img = new Image();
        img.onload = () => this.props.connectDragPreview(img);

        switch(item.type){
            case TEXT:
                img.src = DELIPRESS_PATH_PUBLIC_IMG + "/preview/text.svg";
                break;
            case IMAGE:
                img.src = DELIPRESS_PATH_PUBLIC_IMG + "/preview/image.svg";
                break
            case TITLE:
                img.src = DELIPRESS_PATH_PUBLIC_IMG + "/preview/title.svg";
                break
            case BUTTON:
                img.src = DELIPRESS_PATH_PUBLIC_IMG + "/preview/button.svg";
                break
            case DIVIDER:
                img.src = DELIPRESS_PATH_PUBLIC_IMG + "/preview/divider.svg";
                break
            case SPACER:
                img.src = DELIPRESS_PATH_PUBLIC_IMG + "/preview/spacer.svg";
                break
            case SOCIAL_BUTTON:
                img.src = DELIPRESS_PATH_PUBLIC_IMG + "/preview/share.svg";
                break
            case WP_ARTICLE:
                img.src = DELIPRESS_PATH_PUBLIC_IMG + "/preview/wordpress.svg";
                break
            case WP_ARCHIVE_ARTICLE:
                img.src = DELIPRESS_PATH_PUBLIC_IMG + "/preview/wordpress.svg";
                break
            case WP_CUSTOM_POST:
                img.src = DELIPRESS_PATH_PUBLIC_IMG + "/preview/posttype.svg";
                break
            case WP_ARCHIVE_CUSTOM_POST:
                img.src = DELIPRESS_PATH_PUBLIC_IMG + "/preview/posttype.svg";
                break
            default:
                img.src = DELIPRESS_PATH_PUBLIC_IMG + "/preview/posttype.svg";
                break
        }
    }

    changeItem() {
        const { actionsEditor, item } = this.props

        actionsEditor.changeItemOnSettingsContainer(item, SETTINGS_EDITOR)
    }

    activeSettings(){
        const {
            actionsEditor,
            item,
        } = this.props

        const _id = `${item.keyRow}_${item.keyColumn}_${item._id}`
        actionsEditor.activeSection(null).then(() => {
            actionsEditor.activeItem(_id).then(() => {
                actionsEditor.changeItemOnSettingsContainer(
                    item,
                    SETTINGS_EDITOR
                )
            })
        })

    }

    deleteContent() {
        const {
            actionsTemplate,
            item,
            paramsSettingsComponent,
            actionsEditor
        } = this.props

        const deliAlert = new DeliAlert()

        deliAlert.handle(() => {
            if (
                !_.isUndefined(paramsSettingsComponent) &&
                !_.isUndefined(paramsSettingsComponent.changeItemSuccess)
            ) {
                const deferred = actionsTemplate.deleteContent(item)
                deferred.then(paramsSettingsComponent.changeItemSuccess)
                deferred.then(() => {
                    actionsEditor.activeItem(null)
                })
            }
        })
        let componentName = 'component'
        let componentWarning = translationDelipressReact.Builder.component.delete_warning

        switch (item.type) {
            case TEXT:
                componentName = translationDelipressReact.Builder.component.text
                break;
            case DIVIDER:
                componentName = translationDelipressReact.Builder.component.divider
                break;
            case IMAGE:
                componentName = translationDelipressReact.Builder.component.image
                break;
            case BUTTON:
                componentName = translationDelipressReact.Builder.component.button
                break;
            case SOCIAL_BUTTON:
                componentName = translationDelipressReact.Builder.component.social
                break;
            case WP_CUSTOM_POST:
                componentName = translationDelipressReact.Builder.component.wp_post
                break;
            case UNSUBSCRIBE:
                componentName = translationDelipressReact.Builder.component.unsubscribe
                break;
            case TITLE:
                componentName = translationDelipressReact.Builder.component.title_alt
                break;
            case WP_ARTICLE:
                componentName = translationDelipressReact.Builder.component.wp_article
                break;
            default:
                componentName = ''
        }

        deliAlert.show(translationDelipressReact.Builder.component.delete +' '+ componentName, componentWarning, 'delete')
    }

    duplicateContent() {
        const {
            actionsTemplate,
            actionsEditor,
            item,
            paramsSettingsComponent
        } = this.props

        const deferred = actionsTemplate.duplicateContent({
            _id: item._id,
            keyRow: item.keyRow,
            keyColumn: item.keyColumn
        })

        deferred.then(paramsSettingsComponent.changeItemSuccess).then(() => {
            const _newId = Number(item._id + 1)

            actionsEditor.activeItem(
                `${item.keyRow}_${item.keyColumn}_${_newId}`
            )
            actionsEditor.changeItemOnSettingsContainer(
                _.extend({}, item, {
                    _id: _newId,
                    keyRow: item.keyRow,
                    keyColumn: item.keyColumn
                }),
                SETTINGS_EDITOR
            )
        })
    }

    render() {
        const {
            item,
            connectDragSource,
            connectDragPreview,
            isDragging
        } = this.props

        if(_.isNull(item) || _.isUndefined(item)){
            return false
        }

        if (
            _.find([EMAIL_ONLINE, UNSUBSCRIBE], value => {
                return value == item.type
            })
        ) {
            return false
        }

        return (
            <div className="delipress__builder__main__preview__actions">
                <div className="delipress__builder__main__preview__actions__wrap">
                    {
                        connectDragSource(
                            <span>
                                <HandleButton />
                            </span>
                        )
                    }
                     <a
                        title={translationDelipressReact.Builder.actions.configure}
                        className="delipress__builder__main__preview__actions__button"
                        onClick={this.activeSettings}
                    >
                        <span className="dashicons dashicons-admin-generic" />
                    </a>
                    <a
                        title={translationDelipressReact.Builder.actions.duplicate}
                        className="delipress__builder__main__preview__actions__button"
                        onClick={this.duplicateContent}
                    >
                        <span className="dashicons dashicons-admin-page" />
                    </a>
                    <a
                        title={translationDelipressReact.Builder.actions.delete}
                        className="delipress__builder__main__preview__actions__button"
                        onClick={this.deleteContent}
                    >
                        <span className="dashicons dashicons-trash" />
                    </a>
                </div>
            </div>
        )
    }
}

ActiveContentComponent.propTypes = {
    item: PropTypes.object
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
    DragSource(ItemTypes.MOVE_ITEM, cardDrag, collectDrag),
)(ActiveContentComponent)
