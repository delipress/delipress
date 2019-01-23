import * as _ from "underscore"

let frame = null

export function getFrame(){
    if(_.isNull(frame)){
        frame = wp.media({
            title: translationDelipressReact.wp_media_title,
            library: {
                type: 'image',
                uploadedTo: null
            },
            button: {
                text: translationDelipressReact.wp_media_button_text
            },
            multiple: false
        });
    }

    return frame
}