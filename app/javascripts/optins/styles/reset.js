export default function(){
    return `
        #DELI-Optin, #DELI-Optin div:not([id*="react-tinymce"]){
            background-attachment:scroll;
            background-color:transparent;
            background-image:none;
            background-position:0 0;
            background-repeat:repeat;
            border-color:black;
            border-color:currentColor;
            border-radius:0;
            border-style:none;
            border-width:medium;
            bottom:auto;
            clear:none;
            clip:auto;
            color:inherit;
            counter-increment:none;
            counter-reset:none;
            cursor:auto;
            direction:inherit;
            display:inline;
            float:none;
            font-family: inherit;
            font-size: inherit;
            font-style:inherit;
            font-variant:normal;
            font-weight:inherit;
            height:auto;
            left:auto;
            letter-spacing:normal;
            line-height:inherit;
            list-style-type: inherit;
            list-style-position: outside;
            list-style-image: none;
            margin:0;
            max-height:none;
            max-width:none;
            min-height:0;
            min-width:0;
            opacity:1;
            outline:invert none medium;
            overflow:visible;
            padding:0;
            position:static;
            quotes: "" "";
            right:auto;
            table-layout:auto;
            text-align:inherit;
            text-decoration:inherit;
            text-indent:0;
            text-transform:none;
            top:auto;
            unicode-bidi:normal;
            vertical-align:baseline;
            visibility:inherit;
            white-space:normal;
            width:auto;
            word-spacing:normal;
            z-index:auto;

            background-origin: padding-box;
            background-clip: border-box;
            background-size: auto;
            border-image: none;
            border-radius: 0;
            box-shadow: none;
            box-sizing: border-box;

            -moz-column-count: auto;

                 column-count: auto;
            -moz-column-gap: normal;
                 column-gap: normal;
            -moz-column-rule: medium none black;
                 column-rule: medium none black;
            -moz-column-span: 1;
                 column-span: 1;
            -moz-column-width: auto;
                 column-width: auto;
            font-feature-settings: normal;
            overflow-x: visible;
            overflow-y: visible;
            -webkit-hyphens: manual;
                -ms-hyphens: manual;
                    hyphens: manual;
            perspective: none;
            perspective-origin: 50% 50%;
            -webkit-backface-visibility: visible;
                    backface-visibility: visible;
            text-shadow: none;
            transition: all 0s ease 0s;
            transform: none;
            transform-origin: 50% 50%;
            transform-style: flat;
            word-break: normal;
            -webkit-font-smoothing: antialiased;
        }

        /* == BLOCK-LEVEL == */
        /* Actually, some of these should be inline-block and other values, but block works fine (TODO: rigorously verify this) */
        /* HTML 4.01 */
        #DELI-Optin, #DELI-Optin form, #DELI-Optin div:not([id*="react-tinymce"]){
            display:block;
        }
        #DELI-Optin form{
            margin: 0;
            padding: 0;
        }
        #DELI-Optin a {
            text-decoration: none;
        }
        #DELI-Optin a, #DELI-Optin a *, #DELI-Optin input[type=submit], #DELI-Optin input[type=button], #DELI-Optin input[type=radio], #DELI-Optin input[type=checkbox], #DELI-Optin select, #DELI-Optin button {
            cursor:pointer;
            width: auto;
        }
        #DELI-Optin button, #DELI-Optin input[type=submit] {
            opacity:1;
            text-align: center ;
            padding: 2px 6px 3px ;
            border-radius: 4px ;
            text-decoration: none ;
            font-family: -apple-system, BlinkMacSystemFont, “Segoe UI”, Roboto, Helvetica, Arial, sans-serif;
            font-size: small ;
            background: white ;
            -webkit-appearance: push-button ;
            color: buttontext ;
            border: 1px solid transparent;
            background: #EEE ;
            box-shadow: none ;
            outline: initial ;
            text-transform: none;
            font-weight:normal;
        }
        #DELI-Optin button:active, #DELI-Optin input[type=submit]:active, #DELI-Optin input[type=button]:active, #DELI-Optin button:active {
            background: #CCC /* Old browsers */
            border-color: #CCC;
        }
        #DELI-Optin button {
            padding: 1px 6px 2px 6px;
            margin-right: 5px;
        }
        #DELI-Optin input[type=hidden] {
            display:none !important;
        }

        #DELI-Optin select, #DELI-Optin input {
            border:1px solid #ccc;
        }
        #DELI-Optin select {
            font-size: 11px;
            font-family: helvetica, arial, sans-serif;
            display: inline-block;
        }

        #DELI-Optin input:focus {
            outline: none !important;
        }

        #DELI-Optin input[type=text], #DELI-Optin input[type=email] {
            background: white;
            padding: 1px;
            font-family: initial;
            font-size: small;
            font-family: -apple-system, BlinkMacSystemFont, “Segoe UI”, Roboto, Helvetica, Arial, sans-serif;
            width: auto;
        }

        #DELI-Optin input, #DELI-Optin select {
            vertical-align:baseline;
        }
        /* additional helpers */
        #DELI-Optin [hidden]{
            display: none !important;
        }

        #DELI-Optin img {
            border: 0;
        }

        #DELI-Optin textarea {
            overflow: auto;
            vertical-align: top;
        }

        /* == ROOT CONTAINER ELEMENT == */
        /* This contains default values for child elements to inherit  */
        #DELI-Optin {
            font-size: medium;
            line-height: 1;
            direction:ltr;
            text-align: left; /* for IE, Opera */
            text-align: start; /* recommended W3C Spec */
            font-family: -apple-system, BlinkMacSystemFont, “Segoe UI”, Roboto, Helvetica, Arial, sans-serif;
            color: black;
            font-style:normal;
            font-weight:normal;
            text-decoration:none;
            list-style-type:disc;
        }
    `
}
