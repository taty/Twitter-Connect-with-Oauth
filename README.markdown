## Yii Twitter Connect with Oauth##

The Yii Twitter Connect with Oauth that connect to the twitter with Oauth.

### Installation ###

Extract the [Yii Twitter Connect with Oauth][1] from archive under protected/extensions
[1]: https://github.com/taty/Twitter-Connect-with-Oauth        "Yii Twitter Connect with Oauth"

## Usage and Configuration ##

For use [Yii Twitter Connect with Oauth][1] need to add some code to configure to the component section:

<code>
<?php
//...
	'twitterconnect' => array(
            'class' => 'ext.twitterconnect.TwitterConnect',
            'consumerKey' => 'YOUR_APP_CONSUMER_KEY',
            'consumerSecret' => 'YOUR_APP_CONSUMER_SECRET',
        )
</code>

