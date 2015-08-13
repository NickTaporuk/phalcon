<?php

<<<<<<< HEAD
error_reporting(E_ALL);

define('APP_PATH', realpath('..'));

try {

    /**
     * Read the configuration
     */
    $config = include APP_PATH . "/app/config/config.php";

    /**
     * Read auto-loader
     */
    include APP_PATH . "/app/config/loader.php";

    /**
     * Read services
     */
    include APP_PATH . "/app/config/services.php";

    /**
     * Handle the request
     */
=======
try {

    // Регистрация автозагрузчика
    $loader = new \Phalcon\Loader();
    $loader->registerDirs(array(
        '../app/controllers/',
        '../app/models/'
    ))->register();

    // Создание DI
    $di = new Phalcon\DI\FactoryDefault();

    // Настраиваем сервис для работы с БД
    $di->set('db', function () {
        return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
            "host" => "localhost",
            "username" => "root",
            "password" => "root",
            "dbname" => "phalcon"
        ));
    });

    // Настраиваем компонент View
    $di->set('view', function () {
        $view = new \Phalcon\Mvc\View();
        $view->setViewsDir('../app/views/');
        return $view;
    });

    // Обработка запроса
>>>>>>> 02fe9974d50864acea27dfc9c9450c9e72a83eb0
    $application = new \Phalcon\Mvc\Application($di);

    echo $application->handle()->getContent();

<<<<<<< HEAD
} catch (\Exception $e) {
    echo $e->getMessage();
}
=======
} catch (Exception $e) {
    echo "PhalconException: ", $e->getMessage();
}
>>>>>>> 02fe9974d50864acea27dfc9c9450c9e72a83eb0
