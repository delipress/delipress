import React from 'react'

const WrapperImage = (props) => {
    
    if(!props.defaultConfig.wrapper_image.attrs.active){
        return false
    }

    return (
        <div className="DELI-wrapper-image">
            {props.children}
        </div>
    )
}

export default WrapperImage