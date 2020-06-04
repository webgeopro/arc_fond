<?php
// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>"Детский фонд",

	'sourceLanguage' => 'ru_RU',
    'language' => 'ru',

    'preload'=>array('log'),
    
    'theme'=>'basic',
	
	'import'=>array(
        'application.models.*',
        'application.components.*',
        'application.modules.user.models.*',
        'application.modules.user.components.*',
        #'application.extensions.*',
	),

    'modules'=>array(
        'gii'=>array(
            'class'=>'system.gii.GiiModule',
            'password'=>'caNonzmv',
        ),
        'user'=>array(
            'tableUsers'         => 'tbl_users',
            'tableProfiles'      => 'tbl_profiles',
            'tableProfileFields' => 'tbl_profiles_fields',
        ),
        'yiiadmin'=>array(
                'password'=>'caNon',
                'registerModels'=>array(
                    //'application.models.News',
                    //'application.models.BlogPosts',
                    'application.models.*',
                ),
                //'excludeModels'=>array(),
	),
    ),

	'components'=>array(
		'user'=>array(
			'allowAutoLogin'=>true,
            'loginUrl' => array('/user/login'),
		),
		'urlManager'=>array(
			'urlFormat'=>'path',
            'showScriptName'=>false,
			'rules'=>array(
                'id<pID:\d+>/*'   => '/cab',  /* Personal Page (Частные лица) */
                'http://<loginID:\w+>.cesinn.ru/*'   => '/cab',  /* Personal Page (Юр. лица) */
                'http://www.<loginID:\w+>.cesinn.ru/*'   => '/cab',  /* Personal Page (Юр. лица) */
			),
		),
		'db'=>array(
			'connectionString' => 'mysql:host=78.108.80.11;dbname=b124423_fond',
			'emulatePrepare' => true,
			'username' => 'u124423',
			'password' => 'loSkPentaSoft', //'loSk_zmv_931043',
			'charset' => 'utf8',
		),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
		'image'=>array(
			'class'=>'application.extensions.image.CImageComponent',
            // GD or ImageMagick
            'driver'=>'GD',
            // ImageMagick setup path
            //'params'=>array('directory'=>'/opt/local/bin'),
        ),
        'clientScript'=>array(
            'class'=>'application.components.ExtendedClientScript',
            'combineFiles'=>false,
            'compressCss'=>true,
            'compressJs'=>true,
            'excludeFiles'=>array(
                '/assets/79166020/rating/jquery.rating.css', '/assets/79166020/jquery.metadata.js', '/assets/79166020/jquery.rating.js',
                //'/themes/basic/js/jquery.easySlider.js', //Yii::app()->theme->baseUrl.'js/jquery.easySlider.js',
            ),
            'scriptMap'=>array(
                'jquery.js'=>false,
            ),
            //'enableJavaScript'=>false,    // Эта опция отключает любую генерацию javascript'а фреймворком
        ),
	),
	// using Yii::app()->params['paramName']
	'params'=>array(
		
		'adminEmail'         =>'webmaster@fond.loc',
        'dirPeopleAvatars'   =>'/uploads/people/avatars/',
        'dirCompaniesAvatars'=>'/uploads/companies/avatars/',
        'themeUI'=>'redmond',
        'pages'=>array('about','news','jobs','discounts','articles','article_files','vizitka','vizitka_logo', 'tabs', 'comApp', 'opinions',),
        
        /*'pages_c'=>array( # Не используется
            'about'=>array('owner_id=:pageID','txt'),
        )*/
	),
);