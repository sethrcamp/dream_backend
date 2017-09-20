<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once __DIR__."/../controllers/categories.php";
require_once __DIR__."/../controllers/groups.php";
require_once __DIR__."/../controllers/pictures.php";
require_once __DIR__."/../controllers/prices.php";
require_once __DIR__."/../controllers/services.php";
require_once __DIR__."/../controllers/messages.php";

//app groups

$app->group('/categories', function () use ($app) {
    $app->post('', 'CategoryController::create');
    $app->get('', 'CategoryController::getAll');
    $app->get('/{id}', 'CategoryController::getById');
    $app->put('/{id}', 'CategoryController::update');
    $app->delete('/{id}', 'CategoryController::delete');
});

$app->group('/groups', function () use ($app) {
    $app->post('', 'GroupController::create');
    $app->get('', 'GroupController::getAll');
    $app->get('/{id}', 'GroupController::getById');
    $app->put('/{id}', 'GroupController::update');
    $app->delete('/{id}', 'GroupController::delete');
});

$app->group('/pictures', function () use ($app) {
    $app->post('', 'PictureController::create');
    $app->get('', 'PictureController::getAll');
    $app->get('/{id}', 'PictureController::getById');
    $app->put('/{id}', 'PictureController::update');
    $app->delete('/{id}', 'PictureController::delete');
    $app->get('/s3/', 'PictureController::getAllFromS3');
    $app->get('/formatted/', 'PictureController::getAllFormatted');
});

$app->group('/prices', function () use ($app) {
    $app->post('', 'PriceController::create');
    $app->get('', 'PriceController::getAll');
    $app->get('/{id}', 'PriceController::getById');
    $app->put('/{id}', 'PriceController::update');
    $app->delete('/{id}', 'PriceController::delete');
});

$app->group('/services', function () use ($app) {
    $app->post('', 'ServiceController::create');
    $app->get('', 'ServiceController::getAll');
    $app->get('/{id}', 'ServiceController::getById');
    $app->put('/{id}', 'ServiceController::update');
    $app->delete('/{id}', 'ServiceController::delete');
    $app->delete('/{id}/prices', 'ServiceController::deleteWithPrices');
    $app->get('/formatted/', 'ServiceController::getAllFormatted');
});

$app->group('/messages', function () use ($app) {
    $app->post('', 'MessageController::create');
    $app->get('', 'MessageController::getAll');
    $app->get('/{id}', 'MessageController::getById');
    $app->put('/{id}', 'MessageController::update');
    $app->delete('/{id}', 'MessageController::delete');
});

$app->group('/admin', function () use ($app) {
    $app->post('/verify', function($request, $response, $args) {
        global $CONFIG;
        $body = $request->getParsedBody();
        $password = $body['password'];
        $verified = [ "verified" => $CONFIG['admin_password'] == $password ];
        return $response->withJson($verified);
    });
});