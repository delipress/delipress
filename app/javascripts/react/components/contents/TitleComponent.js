import * as _ from "underscore"
import React, { Component } from 'react'
import { connect } from 'react-redux'
import classNames from 'classnames'
import { compose, bindActionCreators } from 'redux'
import { mjml2html } from 'mjml';
import { transformItemTextAlone } from 'javascripts/react/helpers/transformToMjml'

import BaseTextContentComponent from './BaseTextContentComponent'
import EditorActions from '../../services/actions/EditorActions'
import { shallowEqual } from 'javascripts/react/helpers/shallowEqual'
import TinyMCE from 'javascripts/backend/react-tinymce/index';


class TitleComponent extends BaseTextContentComponent {

    wirteCssTitle(){
        const {
            item
        } = this.props

        let itemStyles = {}
        if(!_.isUndefined(item.styles.presetChoice)){
            itemStyles = _.clone(
                _.find(item.styles.presets, {"type" : item.styles.presetChoice})
            )
        }
        else{
            itemStyles = _.clone(item.styles)
        }

        const idStyle  = `delipress-component-${item.keyRow}${item.keyColumn}${item["_id"]}`
        let css = `\n#delipress-react-selector #${idStyle} .mce-content-body {
            line-height:${itemStyles["line-height"]};
            font-size:${itemStyles["font-size"]}px;
            font-family:${itemStyles["font-family"]} , Helvetica, Arial, sans-serif;
            font-weight: ${itemStyles["font-weight"]};
            color:rgba(${itemStyles.color.rgb.r}, ${itemStyles.color.rgb.g}, ${itemStyles.color.rgb.b}, ${itemStyles.color.rgb.a});
        }`

        return css
    }

    render(){
        const {
            item,
            activeItem,
            textTheme,
        } = this.props

        const element   = transformItemTextAlone(item)
        const result    = mjml2html(element)

        let tempDom   = jQuery('<output>').append(jQuery.parseHTML(result.html));
        let content   = jQuery('table', tempDom).wrap( "<div></div>").parent();

        let _html = ""
        if(content.length > 0){
            _html = content.html()
        }

        const styleItem = this.getStyles()
        const index     = `${item.keyRow}_${item.keyColumn}_${item._id}`

        return (
            <div
                id={"delipress-component-" + item.keyRow + item.keyColumn + item["_id"]}
                className={this.getClasses()}
                onClick={this._activeComponent}
                style={styleItem.component}
            >
                <style>{this.writeCSS()}{this.wirteCssTitle()}</style>

                <TinyMCE
                    content={item.value}
                    config={{
                        inline: true,
                        menubar:false,
                        paste_as_text: true,
                        forced_root_block: false,
                        language_url: configDelipressReact.tinymce_lang_url,
                        plugins: 'paste colorpicker textcolor',
                        fixed_toolbar_container: "#react-toolbar-tinymce",
                        relative_urls: false,
                        convert_urls: false,
                        protect: [
                            /\<\/?(if|endif)\>/g,  // Protect <if> & </endif>
                            /\<xsl\:[^>]+\>/g,  // Protect <xsl:...>
                            /<\?php.*?\?>/g  // Protect php code
                        ],
                        toolbar: [],
                        setup: (ed) => {
                            ed.on("change",() => {
                                this._handleOnKeyUpContent( ed.getContent() );
                            })
                            ed.on("keyUp",() => {
                                this._handleOnKeyUpContent( ed.getContent() );
                            })
                        }
                    }}
                />

            </div>
        )
    }
}

function mapStateToProps(state){
    return {
        "activeItem" : state.EditorReducer.activeItem,
    }
}


function mapDispatchToProps(dispatch, context){
    const actionsEditor   = new EditorActions()

    return {
        "actionsEditor"  : bindActionCreators(actionsEditor, dispatch)
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(TitleComponent)
