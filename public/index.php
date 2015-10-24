<?php

error_reporting(E_ALL);

define('APP_PATH', realpath('..'));
require_once '../vendor/autoload.php';
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

    // Получение всех пользователей
    $app->get('/api/users', function () use ($app) {

        // create token
//        new \Firebase\JWT\JWT;
        $key = "example_key";
        $token = array(
            "iss" => "http://example.org",
            "aud" => "http://example.com",
            "iat" => 1356999524,
            "nbf" => 1357000000
        );

        /**
         * IMPORTANT:
         * You must specify supported algorithms for your application. See
         * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
         * for a list of spec-compliant algorithms.
         */
        $jwt = \Firebase\JWT\JWT::encode($token, $key);
        $decoded = \Firebase\JWT\JWT::decode($jwt, $key, array('HS256'));

        print_r($decoded);
        //
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
    // Получение всех пользователей
    $app->get('/data/users', function () use ($app) {

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
    // Получение всех пользователей
    $app->get('/data/info', function () use ($app) {

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
    });    // Поиск пользователей, в названии которых содержится $name
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


    // Получение пользователя по ключу
    $app->get('/api/users/{id:[0-9]+}', function ($id) use ($app) {

        $phql = "SELECT * FROM Users WHERE id = :id:";
        $robot = $app->modelsManager->executeQuery($phql, array(
            'id' => $id
        ))->getFirst();

        // Create a response
        $response = new Phalcon\Http\Response();
        $response->setRawHeader("HTTP/1.1 200 OK");

        if ($robot == false) {
            $data = json_encode(['status'=> 'NOT-FOUND']);
        } else {
            $data = json_encode(
                array(
                    'status' => 'FOUND',
                    'data'   => array(
                        'id'   => $robot->id,
                        'name' => $robot->name
                    )
                )
            );
        }

        echo $data;
    });


    // Получение пользователя по ключу
    $app->get('/info', function () use ($app) {

        echo true;
    });
    echo $app->handle();

} catch (Exception $e) {
    echo "PhalconException: ", $e->getMessage();
}