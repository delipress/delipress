import React, { Component } from 'react'
import PropTypes from 'prop-types'

import classNames from 'classnames'

class Column extends Component {
    
    render(){
        const { number } = this.props
        
        let _classNames = classNames({
                "delipress__builder__side__column__schema--two" : number == 2,
                "delipress__builder__side__column__schema--three" : number == 3,
                "delipress__builder__side__column__schema--four" : number == 4,
            },
            "delipress__builder__side__column__schema"
        )

        return (
            <div className={_classNames} />
        )
    }
}

Column.propTypes = {
    number: PropTypes.number.isRequired
}

export default Column
