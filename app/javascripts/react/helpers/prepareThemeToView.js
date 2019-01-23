import reactCSS from 'reactcss'

export function prepareTextView(theme){
    return reactCSS({
        'default': {
            text: {
                color: theme.mjAll.color,
            },
            link: {
                color: theme.linkColor
            }
        }
    })
}