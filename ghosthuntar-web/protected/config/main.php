<?php

/**
 * Main configuration.
 * All properties can be overridden in mode_<mode>.php files
 */

return array(

	'sourceLanguage' => 'en',
	
	// Set yiiPath (relative to Environment.php)
	'yiiPath'  => dirname(__FILE__) . '/../../../../../yii/framework/yii.php',
	'yiicPath' => dirname(__FILE__) . '/../../../../../yii/framework/yiic.php',
	'yiitPath' => dirname(__FILE__) . '/../../../../../yii/framework/yiit.php',

	// Set YII_DEBUG and YII_TRACE_LEVEL flags
	'yiiDebug' 			=> true,
	'yiiTraceLevel' => 0,

	// Static function Yii::setPathOfAlias()
	'yiiSetPathOfAlias' => array(
		// uncomment the following to define a path alias
		//'local' => 'path/to/local-folder'
	),

	// This is the main Web application configuration. Any writable
	// CWebApplication properties can be configured here.
	'configWeb' => array(

		'basePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
		'name'		 => 'Ghost HuntAR',

		// Preloading 'log' component
		'preload' => array('log'),

		// Autoloading model and component classes
		'import' => array(
			'application.models.*',
			'application.components.*',
			'application.modules.rights.*',
			'application.modules.rights.components.*',
		),
		
		// Application components
		'components' => array(
			'request' => array(
        'enableCookieValidation' => true,
       ),
			'user' => array(
				'loginUrl'			 => array('/login'),
				// enable cookie-based authentication
				'allowAutoLogin' => false,
				'class' 				 => 'RWebUser',
			),
			
			'authManager' => array(
				'class'				 => 'RDbAuthManager',
				'defaultRoles' => array('Guest'),
			),
			
			'urlManager' => array(
				'urlFormat' 		 => 'path',
				'showScriptName' => false,
			),

			// Database
			'db' => array(
				'connectionString' => '', //override in config/mode_<mode>.php
				'emulatePrepare'   => true,
				'username' 				 => '', //override in config/mode_<mode>.php
				'password' 				 => '', //override in config/mode_<mode>.php
				'charset' 				 => 'utf8',
			),

			// Error handler
			'errorHandler' => array(
				// use 'site/error' action to display errors
				'errorAction' => 'site/error',
			),
		),
		
		'modules' => array(
			'rights' => array(
				'superuserName' 		 => 'Admin',	// Name of the role with super user privileges.
				'authenticatedName'  => 'Authenticated',	// Name of the authenticated user role.
				'userClass' 				 => 'User',
				'userIdColumn' 			 => 'id',	// Name of the user id column in the database.
				'userNameColumn' 		 => 'email',	// Name of the user name column in the database.
				'enableBizRule' 		 => true,	// Whether to enable authorization item business rules.
				'enableBizRuleData'  => false,	// Whether to enable data for business rules.
				'displayDescription' => true,	// Whether to use item description instead of name.
				'flashSuccessKey' 	 => 'RightsSuccess',	// Key to use for setting success flash messages.
				'flashErrorKey' 		 => 'RightsError',	// Key to use for setting error flash messages.
				'install' 					 => false,	// Whether to install rights.
				'baseUrl' 					 => '/rights',	// Base URL for Rights. Change if module is nested.
				'layout' 						 => 'rights.views.layouts.main',	// Layout to use for displaying Rights.
				'appLayout' 				 => 'application.views.layouts.main',	// Application layout.
				'cssFile' 					 => '/css/rights/default.css',	// Style sheet file to use for Rights.
				'debug' 						 => false,	// Whether to enable debug mode.
			),
		),

		// application-level parameters that can be accessed
		// using Yii::app()->params['paramName']
		'params' => array(
			// this is used in contact page
			'adminEmail' => 'admin@ghosthuntar.com',
		),

	),

	// This is the Console application configuration. Any writable
	// CConsoleApplication properties can be configured here.
    // Leave array empty if not used.
    // Use value 'inherit' to copy from generated configWeb.
	'configConsole' => array(

		'basePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
		'name' 		 => 'My Console Application',

		// Preloading 'log' component
		'preload' => array('log'),

		// Autoloading model and component classes
		'import' => 'inherit',

		// Application componentshome
		'components' => array(

			// Database
			'db' => 'inherit',

			// Application Log
			'log' => array(
				'class'  => 'CLogRouter',
				'routes' => array(
					// Save log messages on file
					array(
						'class'  => 'CFileLogRoute',
						'levels' => 'error, warning, trace, info',
					),
				),
			),

		),

	),

);