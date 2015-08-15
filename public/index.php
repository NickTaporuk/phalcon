<?php

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

    // Регистрация автозагрузчика
    $loader = new \Phalcon\Loader();
    $loader->registerDirs(array(
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

    $app = new Phalcon\Mvc\Micro($di);

    // Получение всех gjльзователей
    $app->get('/api/users', function () use ($app) {

        $phql = "SELECT * FROM Users ORDER BY name";
        $robots = $app->modelsManager->executeQuery($phql);

        $data = array();
        foreach ($robots as $robot) {
            $data[] = array(
                'id'   => $robot->id,
                'name' => $robot->name
            );
        }

        echo json_encode($data);
    });

    // Поиск роботов, в названии которых содержится $name
    $app->get('/api/users/search/{name}', function ($name) use ($app) {

        $phql = "SELECT * FROM Users WHERE name LIKE :name: ORDER BY name";
        $robots = $app->modelsManager->executeQuery(
            $phql,
            array(
                'name' => '%' . $name . '%'
            )
        );

        $data = array();
        foreach ($robots as $robot) {
            $data[] = array(
                'id'   => $robot->id,
                'name' => $robot->name
            );
        }

        echo json_encode($data);
    });


    echo $app->handle();

} catch (Exception $e) {
    echo "PhalconException: ", $e->getMessage();
}