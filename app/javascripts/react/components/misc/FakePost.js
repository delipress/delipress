import React from 'react'

const FakePost = () => {
    return (
        <div className="delipress__fake">
            <div className="delipress__fake__container">
                <div className="delipress__fake__title"></div>
                <div className="delipress__fake__image">
                    <span className="dashicons dashicons-format-image"></span>
                </div>
                <span className="delipress__fake__text"></span>
                <span className="delipress__fake__text" style={{width: '80%'}}></span>
                <span className="delipress__fake__text" style={{width: '90%'}}></span>
            </div>
        </div>
    )
}

export default FakePost
