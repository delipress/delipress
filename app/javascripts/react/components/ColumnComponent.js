import React, { Component } from "react"
import PropTypes from "prop-types"
import { bindActionCreators } from "redux"
import { connect } from "react-redux"

import { shallowEqual } from "javascripts/react/helpers/shallowEqual"

import EditorActions from "javascripts/react/services/actions/EditorActions"
import ContentFactory from "../services/ContentFactory"
import ActiveContentComponent from "./ActiveContentComponent"
import MoveItem from "./dnd/MoveItem"
import NoMoveItem from "./dnd/NoMoveItem"
import EmptyItem from "./dnd/EmptyItem"

class ColumnComponent extends Component {
    constructor(props) {
        super(props)

        this.renderNoFixItem = this.renderNoFixItem.bind(this)
        this.renderFixItem = this.renderFixItem.bind(this)
    }

    renderFixItem() {
        const { column, keyRow, keyColumn } = this.props

        let { paramsSettingsComponent } = this.props

        let _listItems = false

        if (column.items.length > 0) {
            _listItems = column.items.map((item, key) => {
                const keyComponent = `${keyRow}_${keyColumn}_${key}`
                paramsSettingsComponent.keyComponent = keyComponent

                return (
                    <NoMoveItem
                        key={`key_${keyComponent}`}
                        index={keyComponent}
                        item={item}
                    >
                        <ActiveContentComponent
                            item={item}
                            paramsSettingsComponent={paramsSettingsComponent}
                        />
                        {ContentFactory.getContentComponent(
                            item,
                            paramsSettingsComponent
                        )}
                    </NoMoveItem>
                )
            })
        }

        return _listItems
    }

    getContentComponentMoveItem(item, key) {
        const { 
            moveItem, 
            keyRow,
            keyColumn, 
            addItem, 
            activeItem,
         } = this.props

        let { paramsSettingsComponent } = this.props

        const keyComponent = `${keyRow}_${keyColumn}_${key}`
        paramsSettingsComponent.keyComponent = keyComponent

        return (
            <MoveItem
                key={`key_${keyComponent}`}
                moveItem={moveItem}
                addItem={addItem}
                activeItem={activeItem}
                index={keyComponent}
                item={item}
                activeItem={activeItem}
            >
                <ActiveContentComponent
                    item={item}
                    index={keyComponent}
                    activeItem={activeItem}
                    paramsSettingsComponent={paramsSettingsComponent}
                />
                {ContentFactory.getContentComponent(
                    item,
                    paramsSettingsComponent
                )}
            </MoveItem>
        )
    }

    renderNoFixItem() {
        const {
            column,
            moveItem,
            keyRow,
            keyColumn,
            addItem,
            addItemOnEmpty
        } = this.props

        let { paramsSettingsComponent } = this.props

        let _listItems = false

        if (column.items.length > 0) {
            _listItems = column.items.map((item, key) => {
                const keyComponent = `${keyRow}_${keyColumn}_${key}`
                paramsSettingsComponent.keyComponent = keyComponent
                return this.getContentComponentMoveItem(item, key)
            })
        } else {
            _listItems = (
                <EmptyItem
                    key={`key_${keyRow}_${keyColumn}`}
                    addItemOnEmpty={addItemOnEmpty}
                    moveItem={moveItem}
                    index={`${keyRow}_${keyColumn}`}
                >
                    {ContentFactory.getContentComponent()}
                </EmptyItem>
            )
        }

        return _listItems
    }

    render() {
        const { 
            fixItem, 
            column, 
            keyColumn,
            keyRow,
            activeItem
        } = this.props

        let _zIndex = 1 + keyColumn
        if(!_.isNull(activeItem)){
            const splitItem = activeItem.split("_")
            if(splitItem[0] == keyRow && splitItem[1] == keyColumn){
                _zIndex = 5000
            }
        }
        
        const _style = {
            width: `${column.styles.width}%`,
            textAlign: "left",
            boxSizing: "border-box",
            verticalAlign: "top",
            display: "inline-block",
            position: "relative",
            zIndex: _zIndex,
            alignSelf: `${column.styles.alignSelf}`
        }

        let _listItems = false
        if (!fixItem) {
            _listItems = this.renderNoFixItem()
        } else {
            _listItems = this.renderFixItem()
        }

        return (
            <div
                className="delipress__builder__main__preview__section__col"
                style={_style}
            >
                {_listItems}
            </div>
        )
    }
}

ColumnComponent.propTypes = {
    column: PropTypes.object.isRequired,
    keyColumn: PropTypes.number.isRequired,
    fixItem: PropTypes.bool
}

function mapStateToProps(state) {
    return {
        activeItem: state.EditorReducer.activeItem
    }
}

export default connect(mapStateToProps)(ColumnComponent)
