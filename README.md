Yii-Multilanguage
=================

Yii PHP Framework extension for multilingual sites

Download
--------

Download or git clone to protected/extension

Configure
---------

Update your config:

    'components'=>array(
        #...
        # More setting http://www.yiiframework.com/doc/guide/1.1/en/topics.url
		'urlManager'=>array(
            'class'=>'ext.yii-multilanguage.MLUrlManager',
			'urlFormat'=>'path',
            'languages'=>array(
                #...
                'de',
                'el',
                'en',
                'es',
                'fr',
                'hu',
                'ja',
                'nl',
                'pl',
                'pt',
                'ro',
                'ru',
                'uk',
                #...
            ),
			'rules'=>array(
			    # ... more user rules

				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',

                '<module:\w+>/<controller:\w+>/<id:\d+>/<action:\w+>'=>'<module>/<controller>/<action>',
                '<module:\w+>/<controller:\w+>/<id:\d+>'=>'<module>/<controller>/view',
                '<module:\w+>/<controller:\w+>/<action:\w+>'=>'<module>/<controller>/<action>',
                '<module:\w+>/<controller:\w+>'=>'<module>/<controller>',
                '<module:\w+>'=>'<module>',

                # ...
			),
		),
		#...
    ),

Example use inside app
---

    # return url
    Yii::app()->UrlManager->createLanguageUrl('en')

    # return absolute url
    Yii::app()->UrlManager->createAbsoluteLanguageUrl('en')

    # returns the current link with the set language
    CHtml::link('fr',Yii::app()->UrlManager->changeLanguage('fr'))

    # languages list
    foreach (Yii::app()->UrlManager->listLanguage() as $language => $languageUrl) {
        echo '<ul>';
        if (Yii::app()->language==$language) {
            echo '<li>'.$language.'</li>;
        } else {
            echo '<li>.CHtml::link($language,$languageUrl).'</li>;
        }
        echo '</ul>';
    }

