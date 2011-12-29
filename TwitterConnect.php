<?php

/**
 * TwitterConnect class.
 *
 * @author Vakulenko Tatiana <tvakulenko@gmail.com>
 * @link http://www.zfort.com/
 * @copyright Copyright &copy; 2000-2011 Zfort Group
 * @license http://www.zfort.com/terms-of-use
 * @package packageName
 * @since 1.0
 *
 */

/**
 * TwitterConnect class.
 *
 * TwitterConnect allows you to authorizate with twitter
 */

class TwitterConnect extends CApplicationComponent
{

    /**
     * Description of consumerKey variable.
     *
     * @var string consumerKey.
     */
    public $consumerKey;
    /**
     * Description of consumerSecret variable.
     *
     * @var string consumerSecret.
     */
    public $consumerSecret;
    /**
     * Description of twitterRequestUrl variable.
     *
     * @var string twitterRequestUrl.
     */
    public $twitterRequestUrl;
    /**
     * Description of twitterAccessUrl variable.
     *
     * @var string twitterAccessUrl.
     */
    public $twitterAccessUrl;
    /**
     * Description of twitterAutorizeUrl variable.
     *
     * @var string twitterAutorizeUrl.
     */
    public $twitterAutorizeUrl;

    /**
     * Initializes the controller.
     *
     * This method is called by the application before the controller starts to execute.
     * @return
     */
    public function init() {
        parent::init();

        Yii::setPathOfAlias('twitterconnect', dirname(__FILE__));
        Yii::import('twitterconnect.*');
        Yii::import('twitterconnect.controllers.*');

        Yii::app()->configure(array('controllerMap' => CMap::mergeArray(Yii::app()->controllerMap,
                    array('twconnect' => array(
                            'class' => 'TwitterConnectController',
                        )
                    )
                ))
        );
    }

    /**
     * Function getOAuthConnection.
     *
     * This method set oauth connection.
     *
     * @param string $method The method.
     * @param string $type   The type.
     *
     * @return
     */
    public function getOAuthConnection($method, $type) {
        $oauth = new OAuth($this->consumerKey, $this->consumerSecret, $method, $type);
        $oauth->enableDebug();
        return $oauth;
    }

    /**
     * Function userOAuth.
     *
     * This method do oauth connection.
     *
     * @return
     */
    public function userOAuth() {
        $oauth = $this->getOAuthConnection(OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_FORM);

        try {
            if (Yii::app()->request->getParam('oauth_token') !== ''
                    && false === is_null(Yii::app()->session->get('oauth_token_secret')))
            {

                $oauth->setToken(Yii::app()->request->getParam('oauth_token'),
                        Yii::app()->session->get('oauth_token_secret'));

                $accessToken = $oauth->getAccessToken($this->twitterAccessUrl);

                Yii::app()->session->add('oauth_token', $accessToken['oauth_token']);
                Yii::app()->session->add('oauth_token_secret', $accessToken['oauth_token_secret']);

                $response = $oauth->getLastResponse();

                parse_str($response, $responseArr);
                if (false === isset($responseArr['user_id']))
                {
                    throw new Exception(Yii::t('', 'Authentication failed.'));
                }
                echo CHtml::script('window.close();');
            } 
            else
            {
                $requestToken = $oauth->getRequestToken($this->twitterRequestUrl);

                Yii::app()->session->add('oauth_token_secret', $requestToken['oauth_token_secret']);
                Yii::app()->request->redirect($this->twitterAutorizeUrl . '?oauth_token='
                        . $requestToken['oauth_token'], true);
            }
        } 
        catch (Exception $e)
        {
            echo Yii::t("", "Response: {message}", array("{message}" => $e->getMessage()));
        }
    }

    /**
     * Function getUserInfo.
     *
     * Function return user information.
     *
     * @param string $token  Token.
     * @param string $secret Token secret.
     *
     * @return
     */
    public function getUserInfo($token, $secret) {

        $oauth = $this->getOAuthConnection(OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);
        $oauth->setToken($token, $secret);
        $oauth->fetch('http://twitter.com/account/verify_credentials.json');
        $info = $oauth->getLastResponse();
        return CJSON::decode($info, true);
    }

}
