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
    * Initializes the controller.
    *
    * This method is called by the application before the controller starts to execute.
    * @return
    */
    public function init()
    {
        parent::init();

        if(!empty($this->consumerKey) && !empty($this->consumerSecret))
        {

            Yii::app()->attachEventHandler('onBeginRequest', array($this, 'onBeginRequest'));
            Yii::app()->attachEventHandler('onEndRequest', array($this, 'onEndRequest'));
            Yii::setPathOfAlias('twitterconnect', dirname(__FILE__));
            Yii::import('twitterconnect.*');
            Yii::app()->configure(array('controllerMap' => array(
                'twconnect' => array(
                    'class' => 'twitterconnect.controllers.TwitterConnectController',
                    'consumerKey' => $this->consumerKey,
                    'consumerSecret' => $this->consumerSecret
                )
            )));
        }
        else
        {
            throw new Exception('You need to add consumerKey and consumerSecret to config file');
        }
    }
    /**
    * Function onBeginRequest.
    *
    * Description of onBeginRequest.
    *
    * @param object $event Event object.
    *
    * @return
    */
    protected function onBeginRequest(CEvent $event)
    {
        
    }
    /**
    * Function onEndRequest.
    *
    * Description of onEndRequest.
    *
    * @param object $event Event object.
    *
    * @return
    */
    protected function onEndRequest(CEvent $event)
    {

    }
    
    
}
