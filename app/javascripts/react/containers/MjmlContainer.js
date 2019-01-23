import React from "react";
import classNames from "classnames";

const MjmlContainer = props => {
    const { visible, togglePreview, children } = props

    const _classPreview = classNames({
        "is-visible": visible,
        delipress__mjml: true
    })

    return (
        <div className={_classPreview}>
            <div
                className="delipress__mjml__overlay"
                onClick={togglePreview}
            />
            <div className="delipress__mjml__preview">
                <div className="delipress__mjml__close">
                    <span
                        className="dashicons dashicons-no"
                        onClick={togglePreview}
                    />
                </div>
                {children}
            </div>
        </div>
    );
};

export default MjmlContainer;
