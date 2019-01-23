<?php 

$provider   = $this->optionServices->getProvider();
$stylesEmailOnline = array(
    "item" => array (
        'font-size' => 12,
        'color' => 
        array (
            'hex' => '#000000',
            'rgb' => 
            array (
                'r' => 0,
                'g' => 0,
                'b' => 0,
                'a' => 1,
            ),
        ),
        'font-family'    => 'Arial',
        'align'          => 'center',
        'padding-top'    => 15,
        'padding-bottom' => 15,
        'padding-left'   => 0,
        'padding-right'  => 0,
        'line-height'    => 1.3,
    )
);

$stylesUnsubscribe = array(
    "item" => array (
        'font-size' => 11,
        'color' => array (
            'hex' => '#CCC',
            'rgb' => array (
                'r' => 204,
                'g' => 204,
                'b' => 204,
                'a' => 1,
            ),
        ),
        'font-family' => 'Arial',
        'align' => 'center',
        'padding-top' => 15,
        'padding-bottom' => 15,
        'padding-left' => 0,
        'padding-right' => 0,
        'line-height' => 1.3,
    )
);

$args = array (
  'theme' => 
  array (
    'mj-attributes' => 
    array (
      'mj-all' => 
      array (
      ),
      'mj-text' => 
      array (
        'color' => 
        array (
          'hsl' => 
          array (
            'h' => 210.00000000000023,
            's' => 0,
            'l' => 0.33333333333333326,
            'a' => 1,
          ),
          'hex' => '#555555',
          'rgb' => 
          array (
            'r' => 85,
            'g' => 85,
            'b' => 85,
            'a' => 1,
          ),
          'hsv' => 
          array (
            'h' => 210.00000000000023,
            's' => 0,
            'v' => 0.33333333333333326,
            'a' => 1,
          ),
          'oldHue' => 210.00000000000023,
          'source' => 'rgb',
        ),
      ),
      'mj-container' => 
      array (
        'background-color' => 
        array (
          'hsl' => 
          array (
            'h' => 300,
            's' => 0,
            'l' => 0.97254901960784312,
            'a' => 1,
          ),
          'hex' => '#f8f8f8',
          'rgb' => 
          array (
            'r' => 248,
            'g' => 248,
            'b' => 248,
            'a' => 1,
          ),
          'hsv' => 
          array (
            'h' => 300,
            's' => 0,
            'v' => 0.97254901960784312,
            'a' => 1,
          ),
          'oldHue' => 300,
          'source' => 'hex',
        ),
      ),
    ),
    'mj-styles' => 
    array (
      'link-color' => 
      array (
        'hsl' => 
        array (
          'h' => 210.90909090909091,
          's' => 0.14042553191489365,
          'l' => 0.46078431372549022,
          'a' => 1,
        ),
        'hex' => '#657586',
        'rgb' => 
        array (
          'r' => 101,
          'g' => 117,
          'b' => 134,
          'a' => 1,
        ),
        'hsv' => 
        array (
          'h' => 210.90909090909091,
          's' => 0.24626865671641798,
          'v' => 0.52549019607843139,
          'a' => 1,
        ),
        'oldHue' => 198.77300613496934,
        'source' => 'hex',
      ),
    ),
  ),
  'items' => 
  array (
    0 => 
    array (
      'before' => false,
      'after' => false,
      'abItmId' => 0,
      'number' => 1,
      'type' => 100,
      'columns' => 
      array (
        0 => 
        array (
          'items' => 
          array (
            0 => 
            array (
              'keyRow' => 0,
              'keyColumn' => 0,
              '_id' => 0,
              'type' => 3,
              'styles' => 
              array (
                'background' => 
                array (
                  'rgb' => 
                  array (
                    'r' => 255,
                    'g' => 255,
                    'b' => 255,
                    'a' => 0,
                  ),
                  'hex' => 'transparent',
                ),
                'padding-top' => 20,
                'padding-bottom' => 20,
                'padding-left' => 20,
                'padding-right' => 20,
                'src' => DELIPRESS_PATH_PUBLIC_IMG . '/templates/logo-placeholder.png',
                'width' => 140,
                'height' => 'auto',
                'href' => '',
                'align' => 'center',
                'sizeSelect' => 'full',
                'border-radius' => 0,
                'valuePercent' => 35,
                'srcWidth' => 400,
                'srcHeight' => 242,
                'sizes' => 
                array (
                  'thumbnail' => 
                  array (
                    'height' => 150,
                    'width' => 150,
                    'url' => DELIPRESS_PATH_PUBLIC_IMG . '/templates/logo-placeholder-150x150.png',
                    'orientation' => 'landscape',
                  ),
                  'full' => 
                  array (
                    'url' => DELIPRESS_PATH_PUBLIC_IMG . '/templates/logo-placeholder.png',
                    'height' => 242,
                    'width' => 400,
                    'orientation' => 'landscape',
                  ),
                  'post-thumbnail' => 
                  array (
                    'height' => 230,
                    'width' => 380,
                    'url' => DELIPRESS_PATH_PUBLIC_IMG . '/templates/logo-placeholder-380x230.png',
                    'orientation' => 'landscape',
                  ),
                ),
              ),
            ),
            1 => 
            array (
              'keyRow' => 0,
              'keyColumn' => 0,
              '_id' => 1,
              'before' => false,
              'after' => true,
              'type' => 9,
              'abItmId' => 0,
              'value' => 'Share your story.',
              'styles' => 
              array (
                'background' => 
                array (
                  'rgb' => 
                  array (
                    'r' => 255,
                    'g' => 255,
                    'b' => 255,
                    'a' => 0,
                  ),
                  'hex' => 'transparent',
                ),
                'padding-top' => 10,
                'padding-bottom' => 10,
                'padding-left' => 10,
                'padding-right' => 10,
                'presetChoice' => 'H1',
                'presets' => 
                array (
                  0 => 
                  array (
                    'type' => 'H1',
                    'font-size' => 32,
                    'font-weight' => 'bold',
                    'font-family' => 'Arial',
                    'color' => 
                    array (
                      'hex' => '#000000',
                      'rgb' => 
                      array (
                        'r' => 0,
                        'g' => 0,
                        'b' => 0,
                        'a' => 1,
                      ),
                    ),
                    'line-height' => 1.1000000000000001,
                    'align' => 'left',
                  ),
                  1 => 
                  array (
                    'type' => 'H2',
                    'font-size' => 30,
                    'font-weight' => 'bold',
                    'font-family' => 'Arial',
                    'color' => 
                    array (
                      'hex' => '#000000',
                      'rgb' => 
                      array (
                        'r' => 0,
                        'g' => 0,
                        'b' => 0,
                        'a' => 1,
                      ),
                    ),
                    'line-height' => 1.1000000000000001,
                    'align' => 'left',
                  ),
                  2 => 
                  array (
                    'type' => 'H3',
                    'font-size' => 28,
                    'font-weight' => 'bold',
                    'font-family' => 'Arial',
                    'color' => 
                    array (
                      'hex' => '#000000',
                      'rgb' => 
                      array (
                        'r' => 0,
                        'g' => 0,
                        'b' => 0,
                        'a' => 1,
                      ),
                    ),
                    'line-height' => 1.1000000000000001,
                    'align' => 'left',
                  ),
                ),
              ),
            ),
            2 => 
            array (
              'keyRow' => 0,
              'keyColumn' => 0,
              '_id' => 2,
              'before' => false,
              'after' => true,
              'type' => 1,
              'abItmId' => 0,
              'value' => "<p style='text-align: left;'>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed diam tellus, iaculis non lectus quis, malesuada eleifend ante. Proin non ipsum lacus.</p>",
              'styles' => 
              array (
                'background' => 
                array (
                  'rgb' => 
                  array (
                    'r' => 255,
                    'g' => 255,
                    'b' => 255,
                    'a' => 0,
                  ),
                  'hex' => 'transparent',
                ),
                'padding-top' => 10,
                'padding-bottom' => 10,
                'padding-left' => 10,
                'padding-right' => 10,
                'line-height' => 1.5,
                'font-size' => 15,
                'font-family' => 'Arial',
                'css-class' => 'mjtext',
              ),
            ),
          ),
          'styles' => 
          array (
            'width' => 100,
            'vertical-align' => 'top',
            'border-radius' => 0,
          ),
          'type' => 100,
        ),
      ),
      'styles' => 
      array (
        'background' => 
        array (
          'hex' => '#ffffff',
          'rgb' => 
          array (
            'r' => 255,
            'g' => 255,
            'b' => 255,
            'a' => 1,
          ),
        ),
        'background-url' => '',
        'padding-top' => 0,
        'padding-bottom' => 0,
        'padding-left' => 0,
        'padding-right' => 0,
      ),
      'keyRow' => 0,
    ),
    1 => 
    array (
      'before' => false,
      'after' => true,
      'abItmId' => 0,
      'number' => 2,
      'type' => 100,
      'columns' => 
      array (
        0 => 
        array (
          'items' => 
          array (
            0 => 
            array (
              'keyRow' => 1,
              'keyColumn' => 0,
              '_id' => 0,
              'before' => false,
              'after' => true,
              'type' => 3,
              'abItmId' => 2,
              'styles' => 
              array (
                'background' => 
                array (
                  'rgb' => 
                  array (
                    'r' => 255,
                    'g' => 255,
                    'b' => 255,
                    'a' => 0,
                  ),
                  'hex' => 'transparent',
                ),
                'padding-top' => 10,
                'padding-bottom' => 10,
                'padding-left' => 10,
                'padding-right' => 10,
                'src' => DELIPRESS_PATH_PUBLIC_IMG . '/templates/image-placeholder-square.png',
                'width' => 300,
                'height' => 'auto',
                'href' => '',
                'align' => 'center',
                'sizeSelect' => 'full',
                'border-radius' => 0,
                'valuePercent' => 100,
                'srcWidth' => 300,
                'srcHeight' => 293,
                'sizes' => 
                array (
                  'thumbnail' => 
                  array (
                    'height' => 150,
                    'width' => 150,
                    'url' => DELIPRESS_PATH_PUBLIC_IMG . '/templates/image-placeholder-square-150x150.png',
                    'orientation' => 'landscape',
                  ),
                  'full' => 
                  array (
                    'url' => DELIPRESS_PATH_PUBLIC_IMG . '/templates/image-placeholder-square.png',
                    'height' => 438,
                    'width' => 448,
                    'orientation' => 'landscape',
                  ),
                  'post-thumbnail' => 
                  array (
                    'height' => 230,
                    'width' => 380,
                    'url' => DELIPRESS_PATH_PUBLIC_IMG . '/templates/image-placeholder-square-380x230.png',
                    'orientation' => 'landscape',
                  ),
                ),
              ),
            ),
            1 => 
            array (
              'keyRow' => 1,
              'keyColumn' => 0,
              '_id' => 1,
              'before' => false,
              'after' => true,
              'type' => 1,
              'abItmId' => 0,
              'value' => "<p style='text-align: left;'>You can create unique layouts by placing a variety of content blocks in different sections of your template.Use the &ldquo;design&rdquo; tab to set styles like background colors and borders.</p>",
              'styles' => 
              array (
                'background' => 
                array (
                  'rgb' => 
                  array (
                    'r' => 255,
                    'g' => 255,
                    'b' => 255,
                    'a' => 0,
                  ),
                  'hex' => 'transparent',
                ),
                'padding-top' => 10,
                'padding-bottom' => 10,
                'padding-left' => 10,
                'padding-right' => 10,
                'line-height' => 1.5,
                'font-size' => 15,
                'font-family' => 'Arial',
                'css-class' => 'mjtext',
              ),
            ),
          ),
          'styles' => 
          array (
            'width' => 50,
            'vertical-align' => 'top',
            'border-radius' => 0,
            'alignSelf' => 'flex-start',
          ),
          'type' => 100,
        ),
        1 => 
        array (
          'items' => 
          array (
            0 => 
            array (
              'keyRow' => 1,
              'keyColumn' => 1,
              '_id' => 0,
              'before' => false,
              'after' => true,
              'type' => 3,
              'abItmId' => 2,
              'styles' => 
              array (
                'background' => 
                array (
                  'rgb' => 
                  array (
                    'r' => 255,
                    'g' => 255,
                    'b' => 255,
                    'a' => 0,
                  ),
                  'hex' => 'transparent',
                ),
                'padding-top' => 10,
                'padding-bottom' => 10,
                'padding-left' => 10,
                'padding-right' => 10,
                'src' => DELIPRESS_PATH_PUBLIC_IMG . '/templates/image-placeholder-square.png',
                'width' => 300,
                'height' => 'auto',
                'href' => '',
                'align' => 'center',
                'sizeSelect' => 'full',
                'border-radius' => 0,
                'valuePercent' => 100,
                'srcWidth' => 300,
                'srcHeight' => 293,
                'sizes' => 
                array (
                  'thumbnail' => 
                  array (
                    'height' => 150,
                    'width' => 150,
                    'url' => DELIPRESS_PATH_PUBLIC_IMG . '/templates/image-placeholder-square-150x150.png',
                    'orientation' => 'landscape',
                  ),
                  'full' => 
                  array (
                    'url' => DELIPRESS_PATH_PUBLIC_IMG . '/templates/image-placeholder-square.png',
                    'height' => 438,
                    'width' => 448,
                    'orientation' => 'landscape',
                  ),
                  'post-thumbnail' => 
                  array (
                    'height' => 230,
                    'width' => 380,
                    'url' => DELIPRESS_PATH_PUBLIC_IMG . '/templates/image-placeholder-square-380x230.png',
                    'orientation' => 'landscape',
                  ),
                ),
              ),
            ),
            1 => 
            array (
              'keyRow' => 1,
              'keyColumn' => 1,
              '_id' => 1,
              'before' => false,
              'after' => true,
              'type' => 1,
              'abItmId' => 0,
              'value' => "<p style='text-align: left;'>You can create unique layouts by placing a variety of content blocks in different sections of your template.Use the &ldquo;design&rdquo; tab to set styles like background colors and borders.</p>",
              'styles' => 
              array (
                'background' => 
                array (
                  'rgb' => 
                  array (
                    'r' => 255,
                    'g' => 255,
                    'b' => 255,
                    'a' => 0,
                  ),
                  'hex' => 'transparent',
                ),
                'padding-top' => 10,
                'padding-bottom' => 10,
                'padding-left' => 10,
                'padding-right' => 10,
                'line-height' => 1.5,
                'font-size' => 15,
                'font-family' => 'Arial',
                'css-class' => 'mjtext',
              ),
            ),
          ),
          'styles' => 
          array (
            'width' => 50,
            'vertical-align' => 'top',
            'border-radius' => 0,
            'alignSelf' => 'flex-start',
          ),
          'type' => 100,
        ),
      ),
      'styles' => 
      array (
        'background' => 
        array (
          'hex' => '#ffffff',
          'rgb' => 
          array (
            'r' => 255,
            'g' => 255,
            'b' => 255,
            'a' => 1,
          ),
        ),
        'background-url' => '',
        'padding-top' => 0,
        'padding-bottom' => 0,
        'padding-left' => 0,
        'padding-right' => 0,
        'display' => 'flex',
        'vertical-align' => 'top',
      ),
      'keyRow' => 1,
    ),
    2 => 
    array (
      'before' => false,
      'after' => true,
      'abItmId' => 1,
      'number' => 1,
      'type' => 100,
      'columns' => 
      array (
        0 => 
        array (
          'items' => 
          array (
            0 => 
            array (
              'keyRow' => 2,
              'keyColumn' => 0,
              '_id' => 0,
              'before' => false,
              'after' => true,
              'type' => 5,
              'abItmId' => 4,
              'background' => 
              array (
                'rgb' => 
                array (
                  'r' => 255,
                  'g' => 255,
                  'b' => 255,
                  'a' => 0,
                ),
                'hex' => 'transparent',
              ),
              'padding-top' => 10,
              'padding-bottom' => 10,
              'padding-left' => 10,
              'padding-right' => 10,
              'styles' => 
              array (
                'background' => 
                array (
                  'rgb' => 
                  array (
                    'r' => 255,
                    'g' => 255,
                    'b' => 255,
                    'a' => 0,
                  ),
                  'hex' => 'transparent',
                ),
                'padding-top' => 10,
                'padding-bottom' => 10,
                'padding-left' => 10,
                'padding-right' => 10,
                'toggle_facebook' => true,
                'toggle_twitter' => true,
                'font-size' => 13,
                'icon-size' => 20,
                'textColor' => 
                array (
                  'hex' => '#000000',
                  'rgb' => 
                  array (
                    'r' => 0,
                    'g' => 0,
                    'b' => 0,
                    'a' => 1,
                  ),
                ),
                'font-family' => 'Arial',
                'content_facebook' => 'Share',
                'content_twitter' => 'Tweet',
                'content_google' => '+1',
                'content_youtube' => 'Subscribe',
                'align' => 'center',
                'css-class' => 'mjsocial',
                'monochromeActive' => false,
                'monochromeColor' => 
                array (
                  'hex' => '#C1C1C1',
                  'rgb' => 
                  array (
                    'r' => 193,
                    'g' => 193,
                    'b' => 193,
                    'a' => 1,
                  ),
                ),
              ),
            ),
          ),
          'styles' => 
          array (
            'width' => 100,
            'vertical-align' => 'top',
            'border-radius' => 0,
          ),
          'type' => 100,
        ),
      ),
      'styles' => 
      array (
        'background' => 
        array (
          'hsl' => 
          array (
            'h' => 0,
            's' => 0,
            'l' => 0,
            'a' => 0,
          ),
          'hex' => 'transparent',
          'rgb' => 
          array (
            'r' => 0,
            'g' => 0,
            'b' => 0,
            'a' => 0,
          ),
          'hsv' => 
          array (
            'h' => 0,
            's' => 0,
            'v' => 0,
            'a' => 0,
          ),
          'oldHue' => 0,
          'source' => 'hex',
        ),
        'background-url' => '',
        'padding-top' => 20,
        'padding-bottom' => 20,
        'padding-left' => 0,
        'padding-right' => 0,
      ),
      'keyRow' => 2,
    ),
  ),
  'email_online' => $this->createEmailOnline($stylesEmailOnline),
  'unsubscribe' => $this->createUnsubscribe($provider, $stylesUnsubscribe),
  'email_online_active' => true,
  'loaded' => true,
);