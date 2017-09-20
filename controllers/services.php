<?php

require_once __DIR__."/../models/services.php";
require_once __DIR__."/../models/services.php";

class ServiceController {

    public static function create($request, $response, $args) {

        $body = $request->getParsedBody();

        $requiredParams = [
            "name"
        ];
        Helper::checkRequiredParameters($requiredParams, $body);

        $service = Service::create($body);

        return $response->withJson($service);
    }

    public static function getAll($request, $response, $args) {
        $services = Service::getAll();
        return $response->withJson($services);
    }

    public static function getById($request, $response, $args) {
        $id = $args['id'];

        $service = Service::getById($id);
        if(!$service) {
            throw new Exception("There are no services with id: ".$id.".", 400);
        }

        return $response->withJson($service);
    }

    public static function update($request, $response, $args) {
        $id = $args['id'];

        $service = Service::getById($id);
        if(!$service) {
            throw new Exception("There are no services with id: ".$id.".", 400);
        }

        $body = $request->getParsedBody();
        Helper::checkRequiredParameters(["name"], $body);

        $data = [
            "name" => $body['name']
        ];

        $updated_service = Service::update($id, $data);
        return $response->withJson($updated_service);
    }

    public static function delete($request, $response, $args) {
        $id = $args['id'];

        $service = Service::getById($id);
        if(!$service) {
            throw new Exception("There are no services with id: ".$id.".", 400);
        }

        $deleted_service = Service::delete($id);

        return $response->withJson($deleted_service);
    }

    //End CRUD
    public static function getAllFormatted($request, $response, $args) {
        $formatted_services = [];

        $services = Service::getAll();
        foreach($services as $service) {
            $prices = Price::getAllByServiceId($service['id']);
            $service['prices'] = $prices;
            $formatted_services[] = $service;
        }

        return $response->withJson($formatted_services);
    }

    public static function deleteWithPrices($request, $response, $args) {
        $service_id = $args['id'];
        $deleted_service = Service::getById($service_id);
        $prices = Price::getAllByServiceId($service_id);
        foreach($prices as $price) {
            Price::delete($price['id']);
        }
        Service::delete($service_id);
        return $response->withJson($deleted_service);
    }

}