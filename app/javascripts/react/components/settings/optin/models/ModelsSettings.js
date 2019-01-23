import * as _ from "underscore"
import React, { Component } from "react"

class ModelsSettings extends Component {
    render() {
        return (
            <div className="container__settings__attributes settings__default">
                <span className="delipress__builder__side__title">
                    {
                        translationDelipressReact.Builder.component_settings
                            .optin.models.title
                    }
                </span>
                <div className="delipress__models">
                    <div className="delipress__models__list">
                        <div className="delipress__models__item">
                            <div className="delipress__models__wrap">
                                <img
                                    src="/wp-content/plugins/delipress/public/images/opt-in/presets/letter.svg"
                                    alt=""
                                    className="delipress__models__image"
                                />
                                <div className="delipress__models__text">
                                    <span className="delipress__models__text-title" />
                                    <span className="delipress__models__text-p" />
                                    <span className="delipress__models__text-p" />
                                    <span className="delipress__models__text-p" />
                                </div>
                                <div className="delipress__models__form">
                                    <div className="delipress__models__input" />
                                    <div className="delipress__models__button" />
                                </div>
                            </div>
                        </div>
                        <div className="delipress__models__item">
                            <div className="delipress__models__wrap">
                                <img
                                    src="/wp-content/plugins/delipress/public/images/opt-in/presets/letter.svg"
                                    alt=""
                                    className="delipress__models__image"
                                />
                                <div className="delipress__models__text">
                                    <span className="delipress__models__text-title" />
                                    <span className="delipress__models__text-p" />
                                    <span className="delipress__models__text-p" />
                                    <span className="delipress__models__text-p" />
                                </div>
                                <div className="delipress__models__form">
                                    <div className="delipress__models__input" />
                                    <div className="delipress__models__button" />
                                </div>
                            </div>
                        </div>
                        <div className="delipress__models__item">
                            <div className="delipress__models__wrap">
                                <img
                                    src="/wp-content/plugins/delipress/public/images/opt-in/presets/letter.svg"
                                    alt=""
                                    className="delipress__models__image"
                                />
                                <div className="delipress__models__text">
                                    <span className="delipress__models__text-title" />
                                    <span className="delipress__models__text-p" />
                                    <span className="delipress__models__text-p" />
                                    <span className="delipress__models__text-p" />
                                </div>
                                <div className="delipress__models__form">
                                    <div className="delipress__models__input" />
                                    <div className="delipress__models__button" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        )
    }
}

export default ModelsSettings
