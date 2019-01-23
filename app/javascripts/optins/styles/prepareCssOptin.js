function prepareCss(listStyle, key){
     switch(key){
        case "backgroundColor":
        case "color":
            if(typeof listStyle === "object"){
                listStyle = `rgba(${ listStyle.rgb.r }, ${ listStyle.rgb.g }, ${ listStyle.rgb.b }, ${ listStyle.rgb.a })`
            }
            break
    }

    return listStyle
}

export function prepareCssOptin(style){

    Object.keys(style).map(function(objectKey, index) {
        var value = style[objectKey];
        style[objectKey] = prepareCss(value, objectKey)
    });
 
    return style
}