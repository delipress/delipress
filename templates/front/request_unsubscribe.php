<html>

<head>
    <style>
        body {
            background-color: #f2f2f2;
            font-family: -apple-system, BlinkMacSystemFont, “Segoe UI”, Roboto, Helvetica, Arial, sans-serif;
        }

        * {
            box-sizing: border-box;
        }

        .delipress__unsubscribe {
            display: flex;
            flex-direction: column;
            width: 94%;
            max-width: 550px;
            margin: 10vh auto;
        }

        .delipress__unsubscribe__bloc {
            background-color: #fff;
            padding: 30px;
            border-radius: 4px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .delipress__unsubscribe h1{
            margin: 0 0 .8em;
            font-size: 28px;
            font-weight: 700
        }

        .delipress__unsubscribe h1 + p {
            font-size: 14px;
            opacity: .8;
            line-height: 1.6
        }

        .delipress__unsubscribe label{
            margin-bottom: .5rem;
            font-size: 14px;
            display: inline-block;
            font-weight: 600
        }

        .delipress__unsubscribe input[type="email"]{
            width: 100%;
            border-radius: 2px;
            font-size: 16px;
            padding: .5rem 1rem;
            border: 1px solid #DDD;
        }

        .delipress__unsubscribe button{
            border: 1px solid rgba(0, 0, 0, .1);
            padding: .5rem 1rem;
            font-size: 16px;
            background-color: #E57373;
            font-weight: 600;
            border-radius: 2px;
            color: #FFF;
        }

        .response{ display: none; }

        .delipress__unsubscribe.success .response{ display: block;}

        .delipress__unsubscribe.success .request{ display: none; }

    </style>
</head>
<body>
    <div class="delipress__unsubscribe">

        <div class="delipress__unsubscribe__bloc">
            <div class="request">
                <h1><?php _esc_html_e('Unsubscribe', 'delipress') ?></h1>
                <p><?php _esc_html_e('Enter your email address to unsubscribe', 'delipress') ?></p>
                <form action="<?php echo $this->unsubscribeServices->getRequestUnsubscribeFormUrl($_GET["list_id"])?>" method="post">
                    <p>
                        <label for="email_unsubscribe"><?php _esc_html_e('Email Adress') ?></label>
                        <br>
                        <input autofocus required type="email" name="email" />
                    </p>

                    <input type="hidden" name="list_id" value="<?php echo $_GET["list_id"]; ?>"/>
                    <input type="hidden" name="action" value="delipress_request_unsubscribe" />
                    <p>
                        <button type="submit"><?php _esc_html_e('Unsubscribe', 'delipress') ?></button>
                    </p>
                </form>
            </div>
            <div class="response">
                <h1><?php _esc_html_e('Unsubscribe successfull', "delipress") ?></h1>
                <p><?php _esc_html_e('We sent you a message to confirm the unsubscription.',"delipress") ?></p>
            </div>
        </div>
    </div>
    <script>
        const form = document.querySelector('form');

        form.addEventListener('submit', function(e){
            e.preventDefault();
            let kvpairs = [];
            for ( var i = 0; i < form.elements.length; i++ ) {
               var e = form.elements[i];
               kvpairs.push(encodeURIComponent(e.name) + "=" + encodeURIComponent(e.value));
            }
            const queryString = kvpairs.join("&");

            var request = new XMLHttpRequest();
            request.open('POST', form.getAttribute('action'), true);
            request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
            request.send(queryString);

            request.onreadystatechange = function () {
                var DONE = 4;
                var OK = 200;
                if (request.readyState === DONE && request.status === OK) {
                    document.querySelector('.delipress__unsubscribe').classList.add('success');
                }
            }

        })
    </script>
</body>
</html>
