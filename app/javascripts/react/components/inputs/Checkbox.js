import React from 'react'

const Checkbox = (props) => {
    const {
        id, 
        handleChange, 
        defaultChecked
    } = props
    
    return (
        <div className="delipress__checkbox__wrap">
            <input
                className="delipress__checkbox__input"
                type="checkbox"
                defaultChecked={defaultChecked}
                id={id}
                onChange={handleChange}
            />
            <label htmlFor={id} className="delipress__checkbox"></label>
        </div>
    )
}

export default Checkbox
