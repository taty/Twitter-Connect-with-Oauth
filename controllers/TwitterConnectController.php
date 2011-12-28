<?php
/**
 * TwitterConnectController class.
 *
 * @author Vakulenko Tatiana <vakulenko@zfort.net>
 * @link http://www.zfort.com/
 * @copyright Copyright &copy; 2000-2011 Zfort Group
 * @license http://www.zfort.com/terms-of-use
 * @package packageName
 * @since 1.0
 * 
 */

/**
 * TwitterConnectController class.
 * 
 * TwitterConnectController allows you to authorizate with twitter
 */

class TwitterConnectController extends Controller
{
  
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
    * Function actionIndex.
    * 
    * This method is Index.
    * 
    * @return 
    */
    public function actionIndex() {
        if (is_null(Yii::app()->session->get('twitter')))
        {
            $this->userOAuth();
            Yii::app()->session->add('twitter', $this->getUserInfo(
                Yii::app()->session->get('oauth_token'),
                Yii::app()->session->get('oauth_token_secret'))
            );
        }
    }
    /**
    * Function doOAuthConnection.
    * 
    * This method do oauth connection.
    * 
    * @return 
    */
    public function userOAuth()
    {
        $oauth = Yii::app()->twitterconnect->doOAuthConnection(OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_FORM);
         
        try {
            if (Yii::app()->request->getParam('oauth_token')!='' && !is_null(Yii::app()->session->get('oauth_token_secret')))
            {
                
                $oauth->setToken(Yii::app()->request->getParam('oauth_token'), Yii::app()->session->get('oauth_token_secret'));
                
                $accessToken = $oauth->getAccessToken($this->twitterAccessUrl);
                
                Yii::app()->session->add('oauth_token', $accessToken['oauth_token']);
                Yii::app()->session->add('oauth_token_secret', $accessToken['oauth_token_secret']);

                $response = $oauth->getLastResponse();

                parse_str($response, $responseArr);
                if (false === isset($responseArr['user_id']))
                {
                    throw new Exception(Yii::t('','Authentication failed.'));
                }
                echo CHtml::script('window.close();');
                
            }
            else
            {
                $requestToken = $oauth->getRequestToken($this->twitterRequestUrl);
                
                Yii::app()->session->add('oauth_token_secret', $requestToken['oauth_token_secret']);
                $this->redirect($this->twitterAutorizeUrl . '?oauth_token=' . $requestToken['oauth_token'], true);
            }
        }
        catch (Exception $e)
        {
            throw new Exception(Yii::t("", "Response: {message}", array("{message}"=>$e->getMessage())));
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

        $oauth = Yii::app()->twitterconnect->doOAuthConnection(OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);
        $oauth->setToken($token, $secret);
        $oauth->fetch('http://twitter.com/account/verify_credentials.json');
        $info = $oauth->getLastResponse();
        return CJSON::decode($info, true);
    }
    /**
    * Function actionLogout.
    *
    * Function logout.
    *
    * @return
    */
    public function actionLogout()
    {
        Yii::app()->session->destroy();
        $this->redirect(Yii::app()->request->getUrlReferrer());
    }
} 
