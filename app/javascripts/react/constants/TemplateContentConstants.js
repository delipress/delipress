import * as _ from 'underscore'

export const ADD_TEMPLATE_CONTENT                          = "ADD_TEMPLATE_CONTENT"
export const ADD_TEMPLATE_SECTION                          = "ADD_TEMPLATE_SECTION"
export const ADD_TEMPLATE_CONTENT_EMPTY                    = "ADD_TEMPLATE_CONTENT_EMPTY"
export const CHANGE_POSITION_CONTENT                       = "CHANGE_POSITION_CONTENT"
export const CHANGE_POSITION_SECTION                       = "CHANGE_POSITION_SECTION"
export const DELETE_CONTENT                                = "DELETE_CONTENT"
export const DELETE_SECTION                                = "DELETE_SECTION"
export const DUPLICATE_CONTENT                             = "DUPLICATE_CONTENT"
export const DUPLICATE_SECTION                             = "DUPLICATE_SECTION"
export const CLEAN_TEMPLATE                                = "CLEAN_TEMPLATE"

export const REQUEST_SAVE_TEMPLATE                         = "REQUEST_SAVE_TEMPLATE"
export const REQUEST_SAVE_TEMPLATE_SUCCESS                 = "REQUEST_SAVE_TEMPLATE_SUCCESS"

export const REQUEST_SAVE_CAMPAIGN_TEMPLATE                = "REQUEST_SAVE_CAMPAIGN_TEMPLATE"
export const REQUEST_SAVE_CAMPAIGN_TEMPLATE_SUCCESS        = "REQUEST_SAVE_CAMPAIGN_TEMPLATE_SUCCESS"

export const REQUEST_GET_TEMPLATE                          = "REQUEST_GET_TEMPLATE"
export const REQUEST_GET_TEMPLATE_SUCCESS                  = "REQUEST_GET_TEMPLATE_SUCCESS"

export const REQUEST_GET_CAMPAIGN                          = "REQUEST_GET_CAMPAIGN"
export const REQUEST_GET_CAMPAIGN_SUCCESS                  = "REQUEST_GET_CAMPAIGN_SUCCESS"

export const REQUEST_GET_CAMPAIGN_TEMPLATE                 = "REQUEST_GET_CAMPAIGN_TEMPLATE"
export const REQUEST_GET_CAMPAIGN_TEMPLATE_SUCCESS         = "REQUEST_GET_CAMPAIGN_TEMPLATE_SUCCESS"

export const REQUEST_SAVE_CAMPAIGN_TEMPLATE_HTML           = "REQUEST_SAVE_CAMPAIGN_TEMPLATE_HTML"
export const REQUEST_SAVE_CAMPAIGN_TEMPLATE_HTML_SUCCESS   = "REQUEST_SAVE_CAMPAIGN_TEMPLATE_HTML_SUCCESS"

export const TEXT                      = 1
export const DIVIDER                   = 2
export const IMAGE                     = 3
export const BUTTON                    = 4
export const SOCIAL_BUTTON             = 5
export const WP_CUSTOM_POST            = 6
export const EMAIL_ONLINE              = 7
export const UNSUBSCRIBE               = 8
export const TITLE                     = 9
export const WP_ARTICLE                = 10
export const WP_ARCHIVE_CUSTOM_POST    = 11
export const WP_ARCHIVE_ARTICLE        = 12
export const SPACER                    = 13
export const WP_WOO_ARCHIVE_PRODUCT    = 14
export const WP_WOO_PRODUCT            = 15

export const SECTION                    = 100
export const SECTION_EMAIL_ONLINE       = 101
export const SECTION_UNSUBSCRIBE        = 102


export const ItemTypes = {
    ADD_ITEM: "add-item",
    MOVE_ITEM: "move-item",
    MOVE_SECTION: "move-section",
    ADD_SECTION: "add-section"
}


export const LIST_TEMPLATE_CONTENT = [
    TITLE,
    TEXT,
    IMAGE,
    BUTTON,
    DIVIDER,
    SOCIAL_BUTTON,
    SPACER,
    WP_ARTICLE,
    WP_ARCHIVE_ARTICLE,
    WP_CUSTOM_POST,
    WP_ARCHIVE_CUSTOM_POST,
    WP_WOO_PRODUCT,
    WP_WOO_ARCHIVE_PRODUCT
]

export const LIST_TEMPLATE_CONTENT_LIKE_SECTION = _.filter(LIST_TEMPLATE_CONTENT, (value) => {
    return [
        WP_ARTICLE,
        WP_ARCHIVE_ARTICLE,
        WP_CUSTOM_POST,
        WP_ARCHIVE_CUSTOM_POST

    ].indexOf(value) >= 0
})

export const WOO_LIST_TEMPLATE_CONTENT_LIKE_SECTION = _.filter(LIST_TEMPLATE_CONTENT, (value) => {
    return [
        WP_WOO_ARCHIVE_PRODUCT,
        WP_WOO_PRODUCT

    ].indexOf(value) >= 0
})

export const SOCIAL_LIST = ['facebook', 'twitter', 'instagram', 'google', 'pinterest', 'linkedin']
