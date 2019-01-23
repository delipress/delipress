<?php defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' ); ?>
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
                <h1><?php esc_html_e('You\'ve been successfully subscribed', "delipress") ?></h1>
                <a href="<?php echo home_url(); ?>">
                    <?php esc_html_e("Back to home", "delipress"); ?>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
