import React from 'react'

const Loader = () => {
    return (
        <div className="delipress__loader">
            <div className="delipress__loader__dot delipress__loader__dot--1" />
            <div className="delipress__loader__dot delipress__loader__dot--2" />
            <div className="delipress__loader__dot delipress__loader__dot--3" />
            <svg xmlns="http://www.w3.org/2000/svg" version="1.1">
                <defs>
                    <filter id="goo">
                        <feGaussianBlur
                            in="SourceGraphic"
                            stdDeviation="10"
                            result="blur"
                        />
                        <feColorMatrix
                            in="blur"
                            mode="matrix"
                            values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 21 -7"
                        />
                    </filter>
                </defs>
            </svg>
        </div>
    )
}

export default Loader
