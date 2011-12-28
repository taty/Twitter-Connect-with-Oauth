<?php

/**
 * TwitterConnect class.
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
        
        if (!empty($this->consumerKey) && !empty($this->consumerSecret)) {

            Yii::setPathOfAlias('twitterconnect', dirname(__FILE__));
            Yii::import('twitterconnect.*');
            Yii::import('twitterconnect.controllers.*');

            Yii::app()->configure(array('controllerMap' => CMap::mergeArray(Yii::app()->controllerMap,
                                    array('twconnect' => array(
                                            'class' => 'TwitterConnectController',
                                            'twitterRequestUrl' => $this->twitterRequestUrl,
                                            'twitterAccessUrl' => $this->twitterAccessUrl,
                                            'twitterAutorizeUrl' => $this->twitterAutorizeUrl
                                        )
                                    )
                                ))
            );
            
        } else {
            throw new Exception('You need to add consumerKey and consumerSecret to config file');
        }
    }
    /**
    * Function doOAuthConnection.
    *
    * This method set oauth connection.
    *
    * @param string $method The method.
    * @param string $type   The type.
    *
    * @return
    */
    public function doOAuthConnection($method, $type)
    {
         $oauth = new OAuth($this->consumerKey, $this->consumerSecret, $method, $type);
         $oauth->enableDebug();
         return $oauth;
    }
}
