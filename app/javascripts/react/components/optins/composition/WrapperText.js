import React from 'react'

import classNames from 'classnames'

const WrapperText = (props) => {
    var textClass = classNames({
        "DELI-textBloc": true,
        'DELI-success': props.settings == 'success',
        'DELI-error': props.settings == 'error',
    });
    return (
        <div className={textClass}>
            {props.children}
        </div>
    )
}

export default WrapperText
