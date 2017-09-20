<?php

require_once __DIR__."/../models/services.php";
require_once __DIR__."/../models/prices.php";

class PriceController {

    public static function create($request, $response, $args) {

        $body = $request->getParsedBody();

        $requiredParams = [
            "name",
            "price",
            "service_id"
        ];
        Helper::checkRequiredParameters($requiredParams, $body);

        $service = Service::getById($body['service_id']);
        if(!$service) {
            throw new Exception("There is no service with id: ".$body['service_id'].".", 400);
        }

        $price = Price::create($body);

        return $response->withJson($price);
    }

    public static function getAll($request, $response, $args) {
        $prices = Price::getAll();
        return $response->withJson($prices);
    }

    public static function getById($request, $response, $args) {
        $id = $args['id'];

        $price = Price::getById($id);
        if(!$price) {
            throw new Exception("There are no prices with id: ".$id.".", 400);
        }

        return $response->withJson($price);
    }

    public static function update($request, $response, $args) {
        $id = $args['id'];

        $price = Price::getById($id);
        if(!$price) {
            throw new Exception("There are no prices with id: ".$id.".", 400);
        }

        $body = $request->getParsedBody();

        if(isset($body['service_id'])) {
            $service = Service::getById($body['service_id']);
            if(!$service) {
                throw new Exception("There is no service with id: ".$body['picture_id'].".", 400);
            }
        }

        $data = [
            "name" => isset($body['name']) ? $body['name'] : $price['name'],
            "price" => isset($body['price']) ? $body['price'] : $price['price'],
            "service_id" => isset($body['service_id']) ? $body['service_id'] : $price['service_id']
        ];

        $updated_price = Price::update($id, $data);
        return $response->withJson($updated_price);
    }

    public static function delete($request, $response, $args) {
        $id = $args['id'];

        $price = Price::getById($id);
        if(!$price) {
            throw new Exception("There are no prices with id: ".$id.".", 400);
        }

        $deleted_price = Price::delete($id);

        return $response->withJson($deleted_price);
    }

}