import * as _ from "underscore"

function prepareCss(listStyle, key){

    switch(key){
        case "backgroundColor":
        case "color":
            listStyle = `rgba(${ listStyle.rgb.r }, ${ listStyle.rgb.g }, ${ listStyle.rgb.b }, ${ listStyle.rgb.a })`
            break
    }

    return listStyle
}

export function prepareCssOptin(style){
    return _.mapObject(style, prepareCss)
}