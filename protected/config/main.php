<?php

if (isset( $_SERVER['APPLICATION_ENV'] )  && ($_SERVER['APPLICATION_ENV'] == "dev")) { 
// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Clapd',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.views.common.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'claps',
		 	// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		/*
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),*/
		// uncomment the following to use a MySQL database
		
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=claps',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
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
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
		'numContentCols' => 4,
		'numContentRows' => 4,
		'numDefaultClaps' => 20,
		'FB_APP_ID' => '769271589798460',
		'FB_SECRET' => '0eb0340cc385598835221d7c6acf620b',
		
	),
		
);
} else {
    // For production server
	return array(
			'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
			'name'=>'clapd - Social Recommendations',
	
			// preloading 'log' component
			'preload'=>array('log'),
	
			// autoloading model and component classes
			'import'=>array(
					'application.models.*',
					'application.components.*',
		            'application.views.common.*',
			),
	
			'modules'=>array(
			// uncomment the following to enable the Gii tool
	
	
			),
	
			// application components
			'components'=>array(
					'user'=>array(
					// enable cookie-based authentication
							'allowAutoLogin'=>true,
					),
                     'facebook'=>array(
                         'class' => 'ext.yii-facebook-opengraph.SFacebook',
                         'appId'=>'769271589798460', // needed for JS SDK, Social Plugins and PHP SDK
                         'secret'=>'0eb0340cc385598835221d7c6acf620b',
                         'xfbml'=>true,
                         'version'=>'v2.0',
                     ),
					// uncomment the following to enable URLs in path-format
	 'urlManager'=>array(
            'showScriptName' => false,
	 		'urlFormat'=>'path',
	 		'rules'=>array(
                    '<user:\w+>/profile' => 'user/profile',
                    'category/<catid:\d+>/<category>'=>'site/index',
                    'clap/<clap:\d+>/<title>/'=>'site/details',
	 				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
	 				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
                    '<user:\w+>/<catid:\d+>/<category>' => 'site/index',
                    '<user:\w+>' => 'site/index',
	 				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
	 		), 
	 ), 
	/*
	 'db'=>array(
	 		'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
	 ),*/
	// uncomment the following to use a MySQL database

					'db' => array(							
							'connectionString' => 'mysql:host=claps-db.clapd.com;dbname=claps',
							'emulatePrepare' => true,
							'username' => 'claps_db_user',
							'password' => 'matrix',
							'charset' => 'utf8',							
                            'schemaCachingDuration'=>3600,
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
			),
	
			// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
			'params'=>array(
			// this is used in contact page
					'adminEmail'=>'contact@clapd.com',
					'numContentCols' => 4,
					'numContentRows' => 4,
					'numDefaultClaps' => 20,
					'FB_APP_ID' => '769271589798460',
					'FB_SECRET' => '0eb0340cc385598835221d7c6acf620b',
                    'CLAPS_BUDDIES' => '3,4,5',
                    'fromEmail' => 'noreply@clapd.com',
	
			),

            'sourceLanguage'=>'00',
            'language'=>'en',
	
	);	
}
