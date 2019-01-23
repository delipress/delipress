import React, { Component, cloneElement } from "react"
import { connect } from "react-redux"
import { bindActionCreators } from "redux"

import {
    LIST_TEMPLATE_CONTENT,
    LIST_TEMPLATE_CONTENT_LIKE_SECTION,
    WOO_LIST_TEMPLATE_CONTENT_LIKE_SECTION,
    TEXT,
    DIVIDER,
    IMAGE,
    BUTTON,
    SOCIAL_BUTTON,
    EMAIL_ONLINE,
    UNSUBSCRIBE,
    SPACER,
    TITLE,
    WP_ARTICLE,
    WP_ARCHIVE_ARTICLE,
    WP_CUSTOM_POST,
    WP_ARCHIVE_CUSTOM_POST
} from "javascripts/react/constants/TemplateContentConstants"

import AddItem from "javascripts/react/components/dnd/AddItem"
import AddColumn from "javascripts/react/components/dnd/AddColumn"
import Column from "javascripts/react/components/dnd/Column"
import ItemListContentDragFactory from "javascripts/react/services/ItemListContentDragFactory"

class ListContentsComponent extends Component {
    constructor(props) {
        super(props)
        this.showPremiumWoocommerceModal = this.showPremiumWoocommerceModal.bind(
            this
        )
    }
    componentWillMount() {
        this.setState({
            premiumWoocommerceModal: false
        })
    }
    showPremiumWoocommerceModal() {
        this.setState({
            premiumWoocommerceModal: !this.state.premiumWoocommerceModal
        })
    }
    render() {
        return (
            <span>
                <span className="delipress__builder__side__title">
                    {translationDelipressReact.component_generals}
                </span>
                <div className="delipress__builder__side__content">
                    <div className="delipress__builder__side__components">
                        {_.filter(LIST_TEMPLATE_CONTENT, value => {
                            return (
                                [
                                    TITLE,
                                    TEXT,
                                    IMAGE,
                                    BUTTON,
                                    DIVIDER,
                                    SOCIAL_BUTTON,
                                    SPACER
                                ].indexOf(value) >= 0
                            )
                        }).map((value, key) => {
                            return (
                                <AddItem
                                    key={"list__content" + key}
                                    type={value}
                                >
                                    {ItemListContentDragFactory.getListContentByType(
                                        value
                                    )}
                                </AddItem>
                            )
                        })}
                    </div>
                </div>
                <span className="delipress__builder__side__title">
                    {translationDelipressReact.component_wordpress}
                </span>
                <div className="delipress__builder__side__content">
                    <div className="delipress__builder__side__components">
                        {LIST_TEMPLATE_CONTENT_LIKE_SECTION.map(
                            (value, key) => {
                                return (
                                    <AddItem
                                        key={"list__content" + key}
                                        type={value}
                                        number={1}
                                    >
                                        {ItemListContentDragFactory.getListContentByType(
                                            value
                                        )}
                                    </AddItem>
                                )
                            }
                        )}
                    </div>
                </div>
                {DELIPRESS_WOOCOMMERCE_ACTIVE &&
                    <span className="delipress__builder__side__title">
                        {translationDelipressReact.component_woocommerce}
                        {!DELIPRESS_LICENSE_STATUS &&
                            <span
                                onClick={this.showPremiumWoocommerceModal}
                                className="delipress__builder__premium"
                            >
                                <i className="dashicons dashicons-awards" />
                                <span>
                                    {translationDelipressReact.premium_only}
                                </span>
                            </span>}
                    </span>}
                {DELIPRESS_WOOCOMMERCE_ACTIVE &&
                    <div className="delipress__builder__side__content">
                        <div className="delipress__builder__side__components">
                            {!DELIPRESS_LICENSE_STATUS &&
                                this.state.premiumWoocommerceModal &&
                                <div className="delipress__builder__premium-incentive">
                                    <span
                                        onClick={
                                            this.showPremiumWoocommerceModal
                                        }
                                        className="dashicons dashicons-no-alt"
                                    />
                                    <span className="delipress__builder__premium-badge">
                                        <i className="dashicons dashicons-awards" />
                                        <span>
                                            {
                                                translationDelipressReact.premium_only
                                            }
                                        </span>
                                    </span>
                                    <p>
                                        {
                                            translationDelipressReact.premium_woocommerce
                                        }
                                    </p>
                                    <a
                                        href={DELIPRESS_PREMIUM_URL}
                                        className="delipress__button delipress__button--main delipress__button--small"
                                        target="_blank"
                                    >
                                        {translationDelipressReact.view_pricing}
                                    </a>
                                </div>}
                            {WOO_LIST_TEMPLATE_CONTENT_LIKE_SECTION.map(
                                (value, key) => {
                                    return (
                                        <AddItem
                                            number={1}
                                            type={value}
                                            key={"list__content" + key}
                                        >
                                            {ItemListContentDragFactory.getListContentByType(
                                                value
                                            )}
                                        </AddItem>
                                    )
                                }
                            )}
                        </div>
                    </div>}
                <span className="delipress__builder__side__title">
                    {translationDelipressReact.columns}
                </span>
                <div className="delipress__builder__side__content">
                    <div className="delipress__builder__side__columns">
                        <AddColumn
                            number={1}
                            className={"delipress__builder__side__column"}
                        >
                            <Column number={1} />
                        </AddColumn>
                        <AddColumn
                            number={2}
                            className={"delipress__builder__side__column"}
                        >
                            <Column number={2} />
                        </AddColumn>
                        <AddColumn
                            number={3}
                            className={"delipress__builder__side__column"}
                        >
                            <Column number={3} />
                        </AddColumn>
                        <AddColumn
                            number={4}
                            className={"delipress__builder__side__column"}
                        >
                            <Column number={4} />
                        </AddColumn>
                    </div>
                </div>
            </span>
        )
    }
}

export default ListContentsComponent
