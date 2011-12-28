## Yii Twitter Connect with Oauth##

The Yii Twitter Connect with Oauth that connect to the twitter with Oauth.

### Installation ###

Extract the [Yii Twitter Connect with Oauth][1] from archive under protected/extensions
[1]: https://github.com/taty/Twitter-Connect-with-Oauth        "Yii Twitter Connect with Oauth"

## Usage and Configuration ##

For use [Yii Twitter Connect with Oauth][1] need to add some code to configure to the component section:

``` php
<?php
//...
	'preload' => array('log','twitterconnect'),
//...
	'twitterconnect' => array(
            'class' => 'ext.twitterconnect.TwitterConnect',
            'consumerKey' => 'YOUR_APP_CONSUMER_KEY',
            'consumerSecret' => 'YOUR_APP_CONSUMER_SECRET',
            'twitterRequestUrl' => 'https://api.twitter.com/oauth/request_token',
            'twitterAccessUrl' => 'https://api.twitter.com/oauth/access_token',
            'twitterAutorizeUrl' => 'https://api.twitter.com/oauth/authorize' 
        )
```
and you can add it in view section:

``` php
<?php 
//...   
    $this->widget('ext.twitterconnect.TwitterConnectButton', 
            array(  'text' => 'Twitter', 
                    'template' => 'standart',
                    'style' => array(
                        'font-size' => '12px',
                        'font-weight'=>'bold'
                     )
                  )
    );
```
now avalible template:

- box
- standart

