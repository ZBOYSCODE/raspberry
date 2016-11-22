<?php

	use Phalcon\Mvc\View;
	use Phalcon\DI\FactoryDefault;
	use Phalcon\Mvc\Dispatcher;
	use Phalcon\Mvc\Url as UrlProvider;
	use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
	use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
	use Phalcon\Mvc\Model\Metadata\Memory as MetaData;
	use Phalcon\Mvc\Model\Manager as modelsManager;
	use Phalcon\Session\Adapter\Files as SessionAdapter;
	use Phalcon\Flash\Session as FlashSession;
	use Phalcon\Events\Manager as EventsManager;
	use Phalcon\Crypt;



	use App\library\Auth\Auth;
    use App\library\Mifaces\Mifaces;
    use App\library\Mail\Mail;
    use App\library\AccesoAcl\AccesoAcl;
    use App\library\Valida\Valida;
    use App\library\Constants\Constant;
   	use App\library\Errors\Errors;
   	use App\library\PdfCreator\PdfCreator;


	/**
	 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
	 */
	$di = new FactoryDefault();


	$di->set('config', $config);

	/**
	 * We register the events manager
	 */
	

	$di->set('dispatcher', function () {
        $dispatcher = new Dispatcher();
        $dispatcher->setDefaultNamespace('App\Controllers');
        return $dispatcher;
    });

    /**
	 * Crypt service
	 */
	$di->set('crypt', function () use ($config) {
	    $crypt = new Crypt();
	    $crypt->setKey($config->application->cryptSalt);
	    return $crypt;
	});

	/**
	 * The URL component is used to generate all kind of urls in the application
	 */
	$di->set('url', function () use ($config) {
		$url = new UrlProvider();
		$url->setBaseUri($config->application->baseUri);
		return $url;
	});

	$di->set('view', function () use ($config) {
		$view = new View();
		
		//$view->setViewsDir(APP_DIR . $config->application->viewsDir);

		$view->setViewsDir('../src/views/');
		
		$view->registerEngines(array(
			".volt" => 'volt'
		));
		return $view;
	});

	/**
	 * Setting up volt
	 */
	
	$di->set('volt', function ($view, $di) {

		$volt = new VoltEngine($view, $di);

		$volt->setOptions(array(
			"compiledPath" => "../cache/volt/",
			'stat' => true,
            'compileAlways' => true  
		));

		$compiler = $volt->getCompiler();
		$compiler->addFunction('is_a', 'is_a');
		return $volt;
		
	}, true);

	/**
	 * Database connection is created based in the parameters defined in the configuration file
	 */
	/*$di->set('db', function () use ($config) {
		$config = $config->get('database')->toArray();

		echo "<pre>";
		print_r($config);

		echo $dbClass = 'Phalcon\Db\Adapter\Pdo\\' . $config['adapter'];
		unset($config['adapter']);
		return new $dbClass($config);
	});*/

	$di->set('db', function () use ($config) {
	    return new DbAdapter(array(
	        'host' 		=> $config->database->host,
	        'username' 	=> $config->database->username,
	        'password' 	=> $config->database->password,
	        'dbname' 	=> $config->database->dbname,
	        'options' 	=> array(
	            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
	        )        
	    ));
	});

	/**
	 * If the configuration specify the use of metadata adapter use it or use memory otherwise
	 */
	$di->set('modelsMetadata', function () {
		return new MetaData();
	});

	/**
	 * Start the session the first time some component request the session service
	 */
	$di->set('session', function () {
		$session = new SessionAdapter();
		$session->start();
		return $session;
	});

	/**
	 * Loading routes from the routes.php file
	 */
	$di->set('router', function () {
	    return require __DIR__ . '/routes.php';
	});

	/**
	 * Register the flash service with custom CSS classes
	 */
	$di->set('flash', function () {
		return new FlashSession(array(
			'error'   => 'alert alert-danger',
			'success' => 'alert alert-success',
			'notice'  => 'alert alert-info',
			'warning' => 'alert alert-warning'
		));
	});
	
	/**
	 * Register a user component
	 */
	$di->set('elements', function () {
		return new Elements();
	});


	/**
     * Custom authentication component
     */
    $di->set('mifaces', function () {
        return new Mifaces();
    });

     $di->set('auth', function () {
        return new Auth();
    });
    /**
     * Mail service uses AmazonSES
     */
    $di->set('mail', function () {
        return new Mail();
    });

    $di->set('iof', function ()  use ($config) {
    	require_once $config->application->libraryDir.'PHPExcel/IOFactory.php';
        return new IOFactory();
    });

    $di->set('AccesoAcl', function () {
        return new AccesoAcl();
    });

    $di->set('valida', function () {
        return new Valida();
    });

    $di->set('errors', function () {
        return new Errors();
    });

    
    /**
     * Para consultas SQL con ORM
     */
    $di->set('modelsManager', function() {
      return new modelsManager();
    });

   
    $di->set('Constant', function() {
      return new Constant();
    });

    $di->set('pdfcreator', function(){
    	return new PdfCreator();
    });










