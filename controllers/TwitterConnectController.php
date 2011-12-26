<?php
/**
 * TwitterConnectController class.
 *
 * @author Vakulenko Tatiana <vakulenko@zfort.net>
 * @link http://www.zfort.com/
 * @copyright Copyright &copy; 2000-2011 Zfort Group
 * @license http://www.zfort.com/terms-of-use
 * @version $Id$
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
    protected $twitterRequestUrl = 'https://api.twitter.com/oauth/request_token';
    /**
     * Description of twitterAccessUrl variable.
     *  
     * @var string twitterAccessUrl.
     */
    protected $twitterAccessUrl = 'https://api.twitter.com/oauth/access_token';
    /**
     * Description of twitterAutorizeUrl variable.
     *  
     * @var string twitterAutorizeUrl.
     */
    protected $twitterAutorizeUrl = 'https://api.twitter.com/oauth/authorize';

    /**
    * Function actionIndex.
    * 
    * This method is Index.
    * 
    * @return 
    */
    public function actionIndex() {
        $session=new CHttpSession;
        $session->open();

        if (false === isset($_SESSION['twitter']))
        {
            $this->setOAuth();
            $_SESSION['twitter'] = $this->getUserInfo($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
        }
    }
    /**
    * Function setOAuth.
    * 
    * This method set Oauth.
    * 
    * @return 
    */
    public function setOAuth()
    {

        $oauth = new OAuth($this->consumerKey, $this->consumerSecret, OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_FORM);
        $oauth->enableDebug();
        
        try {


            if (true === isset($_GET['oauth_token']) && true === isset($_SESSION['oauth_token_secret']))
            {

                
                $oauth->setToken($_GET['oauth_token'], $_SESSION['oauth_token_secret']);
                
                $accessToken = $oauth->getAccessToken($this->twitterAccessUrl);
                
                $_SESSION['oauth_token'] = $accessToken['oauth_token'];
                $_SESSION['oauth_token_secret'] = $accessToken['oauth_token_secret'];

                $response = $oauth->getLastResponse();

                parse_str($response, $responseArr);
                if (false === isset($responseArr['user_id']))
                {
                    throw new Exception('Authentication failed.');
                }
                echo '<script type="text/javascript">window.close();</script>';
            }
            else
            {
                $requestToken = $oauth->getRequestToken($this->twitterRequestUrl);
                


                $_SESSION['oauth_token_secret'] = $requestToken['oauth_token_secret'];
                $this->redirect($this->twitterAutorizeUrl . '?oauth_token=' . $requestToken['oauth_token']);
                die();
            }
        }
        catch (Exception $e)
        {
            echo Yii::t("", "Response: {message}", array("{message}"=>$e->getMessage()));
            die($e->getMessage());
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

        $oauth = new OAuth($this->consumerKey, $this->consumerSecret, OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);
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
        $session=new CHttpSession;
        $session->open();
        $session->destroy();
        $this->redirect($_SERVER['HTTP_REFERER']);
    }
} 
