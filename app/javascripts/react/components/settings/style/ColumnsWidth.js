import * as _ from "underscore"
import React, { Component, cloneElement } from "react"
import PropTypes from "prop-types"
import { connect } from "react-redux"
import { compose, bindActionCreators } from "redux"
import classNames from 'classnames'

import InputNumber from "javascripts/react/components/inputs/InputNumber"

class ColumnsWidth extends Component {
    constructor(props) {
        super(props)

        this.columnSizes = {
            2: {
                half: [50, 50],
                two_third_left: [33.3333333333, 66.6666666666],
                two_third_right: [66.6666666666, 33.3333333333],
                one_quarter_left: [25, 75],
                one_quarter_right: [75, 25]
            },
            3: {
                third: [33.3333333333, 33.3333333333, 33.3333333333],
                half_middle: [25, 50, 25],
                half_left: [50, 25, 25],
                half_right: [25, 25, 50]
            },
            4: {
                quarter: [25, 25, 25, 25]
            }
        }

        this.sizeNames = {
            "50": "1/2",
            "25": "1/4",
            "33.3333333333": "1/3",
            "75": "3/4",
            "66.6666666666": "2/3"
        }

        let _sizeColumnChoice = ""
        
        const {
            columnNumber,
            item 
        } = this.props

        if(_.isUndefined(item.styles.sizeColumnChoice)){
            switch(columnNumber){
                case 2 : 
                    _sizeColumnChoice = "half"
                    break;
                case 3: 
                    _sizeColumnChoice = "third"
                    break;
                case 4: 
                    _sizeColumnChoice = "quarter"
                    break;
            }
        }
        else{
            _sizeColumnChoice = item.styles.sizeColumnChoice
        }

        this.state = {
            sizeColumnChoice: _sizeColumnChoice
        }

    }

    componentWillReceiveProps(nextProps){
        
        let _sizeColumnChoice = ""
        
        const {
            columnNumber,
            item 
        } = nextProps

        if(_.isUndefined(item.styles.sizeColumnChoice)){
            switch(columnNumber){
                case 2 : 
                    _sizeColumnChoice = "half"
                    break;
                case 3: 
                    _sizeColumnChoice = "third"
                    break;
                case 4: 
                    _sizeColumnChoice = "quarter"
                    break;
            }
        }
        else{
            _sizeColumnChoice = item.styles.sizeColumnChoice
        }

        this.setState({
            sizeColumnChoice: _sizeColumnChoice
        })
    }


    _handleSelectColumn(value, key){
        this.setState({sizeColumnChoice: key})
        this.props.handleChangeColumn(value, key)
    }


    _getCurrentColumnArr(columns){
        var arr = []
        _.each(columns, (v) => {
            arr.push(v.styles.width)
        })
        return arr
    }

    render() {
        const {
            columnNumber,
            columns
        } = this.props

        const columnChoice     =        this.columnSizes[columnNumber]
        const currentColumnArr =        this._getCurrentColumnArr(columns)

        const columnsHTML = _.map(columnChoice, (value, key) => {

            const classBuilderColumn = classNames({
                "is-active": key == this.state.sizeColumnChoice,
                [`delipress__builder__column--${key}`]: true,
                "delipress__builder__column": true
            })

            const spans = _.map(value, (v, ind) => {
                return (
                    <span key={ind}>
                        {this.sizeNames[v]}
                    </span>
                )
            })


            return (
                <div 
                    key={"c_" + key} 
                    className={classBuilderColumn} 
                    onClick={() => this._handleSelectColumn(value, key) }
                >
                    <div className="delipress__builder__column__wrap"></div>
                    <div className="delipress__builder__sizes">
                        {spans}
                    </div>
                </div>
            )
        })

        return (
            <div className="delipress__builder__columns__choice">
                {columnsHTML}
            </div>
        )
    }
}

ColumnsWidth.propTypes = {
    handleChangeColumn: PropTypes.func.isRequired,
    columnNumber: PropTypes.number.isRequired,
    columns: PropTypes.array.isRequired
}

export default ColumnsWidth
