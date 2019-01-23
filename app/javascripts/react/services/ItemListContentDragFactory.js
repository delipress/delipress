import * as _ from "underscore"
import React, { Component } from 'react'

import {
    TEXT,
    DIVIDER,
    IMAGE, 
    BUTTON,
    SOCIAL_BUTTON,
    SPACER,
    WP_CUSTOM_POST,
    TITLE,
    WP_ARTICLE,
    WP_ARCHIVE_CUSTOM_POST,
    WP_WOO_ARCHIVE_PRODUCT,
    WP_WOO_PRODUCT,
    WP_ARCHIVE_ARTICLE
} from 'javascripts/react/constants/TemplateContentConstants'

import Text from 'javascripts/react/components/dnd/Text'
import Image from 'javascripts/react/components/dnd/Image'
import Divider from 'javascripts/react/components/dnd/Divider'
import Social from 'javascripts/react/components/dnd/Social'
import Button from 'javascripts/react/components/dnd/Button'
import Spacer from 'javascripts/react/components/dnd/Spacer'
import Title from 'javascripts/react/components/dnd/Title'
import WPArticle from 'javascripts/react/components/dnd/WPArticle'
import WPPost from 'javascripts/react/components/dnd/WPPost'
import WPArchivePost from 'javascripts/react/components/dnd/wp/WPArchivePost'
import WPArchiveArticle from 'javascripts/react/components/dnd/wp/WPArchiveArticle'

export default class ItemListContentDragFactory{

    static getListContentByType(type) {
        switch(type){
            case SPACER:
                return (
                    <Spacer />
                )
            case TEXT:
                return (
                    <Text />
                )
            case IMAGE:
                return (
                    <Image />
                )
            case DIVIDER:
                return (
                    <Divider />
                )
            case SOCIAL_BUTTON:
                return (
                    <Social />
                )
            case BUTTON:
                return(
                    <Button />
                )
            case WP_CUSTOM_POST:
                return (
                    <WPPost />
                )
            case WP_ARCHIVE_CUSTOM_POST:
                return (
                    <WPArchivePost />
                )
            case WP_ARCHIVE_ARTICLE:
            case WP_WOO_ARCHIVE_PRODUCT:
                return (
                    <WPArchiveArticle type={type} />
                )
            case WP_ARTICLE:
            case WP_WOO_PRODUCT:
                return (
                    <WPArticle type={type} />
                )
            case TITLE:
                return (
                    <Title />
                )
            default:
                return false
        }
    }
}
