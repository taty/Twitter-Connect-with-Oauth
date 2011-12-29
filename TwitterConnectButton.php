<?php

/**
 * TwitterConnectButton class file.
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
 * TwitterConnectButton represents an ...
 *
 * Description of TwitterConnectButton
 */

class TwitterConnectButton extends CWidget
{

    /**
     * Description of template variable.
     *
     * @var string Template.
     */
    private $_template = 'standart';
    /**
     * Description of availableTemplates variable.
     *
     * @var array AvailableTemplates.
     */
    private $_availableTemplates = array(
        'standart',
        'box'
    );
    /**
     * Description of options variable.
     *
     * @var array Options.
     */
    private $_options = array(
        'type' => 'button',
        'class' => 'twconnect',
        'value' => 'Twitter Connect',
        'width' => '',
        'style' => '',
    );

    /**
     * Initializes the controller.
     *
     * This method is called by the application before the controller starts to execute.
     * @return
     */
    public function init() {
        $this->registerConfigurationScripts();
    }

    /**
     * Function Constructor.
     *
     * This method set the сonstructor.
     *
     * @param string $owner The default owner variable.
     *
     * @return
     */
    public function __construct($owner = null) {
        parent::__construct($owner);
    }

    /**
     * Function run.
     *
     * This method run the controller.
     *
     * @return
     */
    public function run() {
        if (false === is_null(Yii::app()->session->get('twitter')))
        {
            $twitterData = Yii::app()->session->get('twitter');
            echo CHtml::image($twitterData['profile_image_url'], 'Profile') . ' '
                    . $twitterData['screen_name'] . '<br>';
            echo CHtml::link('Sign out', '/twconnect/logout');
        } 
        else
        {
            $render = 'render' . ucfirst($this->_template);
            if (true === method_exists($this, $render))
            {
                $this->$render();
            } 
            else
            {
                throw new Exception('This method doesn\'t exist');
            }
        }
    }

    /**
     * Function setTemplate.
     *
     * This method set the template.
     *
     * @param string $value The default set Template.
     *
     * @return
     */
    public function setTemplate($value) {
        if (true === is_string($value))
        {
            if (true === in_array($this->_template, $this->_availableTemplates))
            {
                $this->_template = $value;
            }
        } 
        else
        {
            throw new Exception('This parametr must be a string');
        }
    }

    /**
     * Function getOptions.
     *
     * This method get the options.
     *
     * @return
     */
    public function getOptions() {
        if (is_null($this->_options) !== true)
        {
            $this->_options = new CMap($this->_options, false);
        }
        return $this->_options;
    }

    /**
     * Function getTagOptions.
     *
     * This method get tag options.
     *
     * @return
     */
    private function getTagOptions() {
        return $this->getOptions()->toArray();
    }

    /**
     * Function renderStandart.
     *
     * This method render standart template.
     *
     * @return
     */
    public function renderStandart() {
        echo CHtml::openTag('div', array('class' => 'twitter_standart'));
        $this->renderWidget();
        echo CHtml::closeTag('div');
    }

    /**
     * Function renderBox.
     *
     * This method render the box template.
     *
     * @return
     */
    public function renderBox() {
        echo CHtml::openTag('div', array('class' => 'twitter_box'));
        $this->renderWidget();
        echo CHtml::closeTag('div');
    }

    /**
     * Function renderWidget.
     *
     * This method render the widget.
     *
     * @return
     */
    protected function renderWidget() {
        echo Chtml::button('button', $this->getTagOptions());
    }

    /**
     * Function setWidth.
     *
     * This method set the width.
     *
     * @param integer $value The width of the Twitter button.
     *
     * @return
     */
    public function setWidth($value) {
        $this->getOptions()->add('width', $value);
    }

    /**
     * Function setText.
     *
     * This method set the text.
     *
     * @param string $value The default tweet button text.
     *
     * @return
     */
    public function setText($value) {
        $this->getOptions()->add('value', $value);
    }

    /**
     * Function setStyle.
     *
     * This method set the style.
     *
     * @param array $params The default styles.
     *
     * @return
     */
    public function setStyle(array $params = array()) {
        $str = '';
        foreach ($params as $param => $val)
        {
            $str .= $param . ':' . $val . ';';
        }
        $this->getOptions()->add('style', $str);
    }

    /**
     * Function registerConfigurationScripts.
     *
     * This method register configuration scripts.
     *
     * @return
     */
    protected function registerConfigurationScripts() {
        Yii::setPathOfAlias('twitterconnect', dirname(__FILE__));
        $url = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('twitterconnect.assets'),
                        false, -1, YII_DEBUG);

        $cs = Yii::app()->clientScript
                        // Config script.
                        ->registerScriptFile($url . '/configure.js')
                        ->registerCssFile($url . '/twitter.css')
                        // Required depencies.
                        ->registerCoreScript('jquery')
                        ->registerCoreScript('jquery.ui');
    }

}

