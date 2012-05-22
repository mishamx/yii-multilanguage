<?php
/**
 * Created by JetBrains PhpStorm.
 * User: misha
 * Date: 22.05.12
 * Time: 19:43
 * To change this template use File | Settings | File Templates.
 */
class MLUrlManager extends CUrlManager
{
    /**
     * @var languages list
     */
    public $languages = array();

    private $_currentUrl = '';

    /**
     * Initializes the application component.
     */
    public function init() {
        if (!$this->languages)
            $this->languages = array(Yii::app()->language);
        $langReg = implode('|',$this->languages);
        $newRules = array();
        foreach ($this->rules as $reg => $rule) {
            $newRules['<language:'.$langReg.'>/'.$reg] = $rule;
        }
        $newRules['<language:'.$langReg.'>'] = Yii::app()->defaultController;
        $this->rules=$newRules;
        parent::init();
    }

    /**
     * Constructs a URL.
     * @param string $route the controller and the action (e.g. article/read)
     * @param array $params list of GET parameters (name=>value). Both the name and value will be URL-encoded.
     * If the name is '#', the corresponding value will be treated as an anchor
     * and will be appended at the end of the URL.
     * @param string $ampersand the token separating name-value pairs in the URL. Defaults to '&'.
     * @return string the constructed URL
     */
    public function createUrl($route,$params=array(),$ampersand='&') {
        if (!isset($params['language'])) {
            $params['language'] = Yii::app()->language;
        }
        return parent::createUrl($route,$params,$ampersand);
    }

    /**
     * Parses the user request.
     * @param CHttpRequest $request the request application component
     * @return string the route (controllerID/actionID) and perhaps GET parameters in path format.
     */
    public function parseUrl($request) {
        $this->_currentUrl = parent::parseUrl($request);
        if (isset($_GET['language'])&&in_array($_GET['language'],$this->languages)) {
            Yii::app()->language = $_GET['language'];
            Yii::app()->user->setState('language',$_GET['language']);
        } else {
            if (Yii::app()->user->hasState('language'))
                Yii::app()->language = Yii::app()->user->getState('language');
            else if (isset(Yii::app()->request->cookies['language']))
                Yii::app()->language = Yii::app()->request->cookies['language']->value;
        }
        return $this->_currentUrl;
    }

    /*
     * returns the current link with the set language for CHtml::link()
     * @param $language language string
     * @return array
     */
    public function changeLanguage($language) {
        if ($this->_currentUrl)
            $this->_currentUrl = '/'.$this->_currentUrl;
        $newUrl = array($this->_currentUrl);
        $newUrl = CMap::mergeArray($newUrl,$_GET);
        if(in_array($language,$this->languages)) {
            $newUrl['language'] = $language;
        }
        return $newUrl;
    }

    /*
     * returns the current url with the set language
     * @param $language language string
     * @return string
     */
    public function createLanguageUrl($language) {
        if ($this->_currentUrl)
            $this->_currentUrl = '/'.$this->_currentUrl;
        $get = $_GET;
        if(in_array($language,$this->languages)) {
            $get['language'] = $language;
        }
        return Yii::app()->createUrl($this->_currentUrl,$get);
    }

    /*
     * returns the current absolute url with the set language
     * @param $language language string
     * @return string
     */
    public function createAbsoluteLanguageUrl($language) {
        if ($this->_currentUrl)
            $this->_currentUrl = '/'.$this->_currentUrl;
        $get = $_GET;
        if(in_array($language,$this->languages)) {
            $get['language'] = $language;
        }
        return Yii::app()->createAbsoluteUrl($this->_currentUrl,$get);
    }

    /*
     * returns languages list array
     * @return array
     */
    public function listLanguage() {
        $list = array();
        foreach ($this->languages as $language) {
            $list[$language] = $this->changeLanguage($language);
        }
        return $list;
    }
}
