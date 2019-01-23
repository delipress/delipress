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
    EMAIL_ONLINE,
    UNSUBSCRIBE,
    WP_ARTICLE,
    TITLE,
    WP_ARCHIVE_CUSTOM_POST,
    WP_ARCHIVE_ARTICLE,
    WP_WOO_ARCHIVE_PRODUCT,
    WP_WOO_PRODUCT
} from 'javascripts/react/constants/TemplateContentConstants'

import SpacerComponent from 'javascripts/react/components/contents/SpacerComponent'
import TextComponent from 'javascripts/react/components/contents/TextComponent'
import ImageComponent from 'javascripts/react/components/contents/ImageComponent'
import DividerComponent from 'javascripts/react/components/contents/DividerComponent'
import SocialComponent from 'javascripts/react/components/contents/SocialComponent'
import ButtonComponent from 'javascripts/react/components/contents/ButtonComponent'
import EmptyComponent from 'javascripts/react/components/contents/EmptyComponent'
import WPPostComponent from 'javascripts/react/components/contents/WPPostComponent'
import WPArticleComponent from 'javascripts/react/components/contents/WPArticleComponent'
import WPArchivePostComponent from 'javascripts/react/components/contents/wp/WPArchivePostComponent'
import WPArchiveArticleComponent from 'javascripts/react/components/contents/wp/WPArchiveArticleComponent'
import TitleComponent from 'javascripts/react/components/contents/TitleComponent'
import EmailOnlineComponent from 'javascripts/react/components/contents/EmailOnlineComponent'
import UnsubscribeComponent from 'javascripts/react/components/contents/UnsubscribeComponent'

export default class ContentFactory{

    static getContentComponent(item = null, params = []) {
        if(_.isNull(item)){
            return (
                <EmptyComponent />
            )
        }
        const key = `item_type_${item.type}`

        switch(item.type){
            case WP_CUSTOM_POST:
                return (
                    <WPPostComponent
                        key={key} 
                        item={item}
                        {...params}
                    />
                )
            case WP_ARCHIVE_CUSTOM_POST:
                return (
                    <WPArchivePostComponent
                        key={key} 
                        item={item}
                        {...params}
                    />
                )
            case WP_ARCHIVE_ARTICLE:
            case WP_WOO_ARCHIVE_PRODUCT:
                return (
                    <WPArchiveArticleComponent
                        key={key} 
                        item={item}
                        {...params}
                    />
                )
            case WP_ARTICLE:
            case WP_WOO_PRODUCT:
                return (
                    <WPArticleComponent
                        key={key} 
                        item={item}
                        {...params}
                    />
                )
            case TEXT:
                return (
                    <TextComponent 
                        key={key} 
                        item={item}
                        {...params}
                    />
                )
            case IMAGE:
                return (
                    <ImageComponent 
                        key={key} 
                        item={item} 
                        {...params}
                    />
                )
            case DIVIDER:
                return (
                    <DividerComponent 
                        key={key} 
                        item={item}
                        {...params}
                    />
                )
            case SPACER:
                return (
                    <SpacerComponent 
                        key={key}
                        item={item}
                        {...params}
                    />
                )
            case SOCIAL_BUTTON:
                return (
                    <SocialComponent 
                        key={key} 
                        item={item}
                        {...params}
                    />
                )
            case EMAIL_ONLINE:
                return (
                    <EmailOnlineComponent 
                        key={key} 
                        item={item}
                        {...params}
                    />
                )
            case UNSUBSCRIBE:
                return (
                    <UnsubscribeComponent
                        key={key}
                        item={item}
                        {...params}
                    />
                )
            case BUTTON:
                return (
                    <ButtonComponent 
                        key={key} 
                        item={item}
                        {...params}
                    />
                )
            case TITLE:
                return (
                    <TitleComponent 
                        key={key} 
                        item={item}
                        {...params}
                    />
                )
            default:
                return (
                    <EmptyComponent />
                )

            
        }
    }
}
