<?php

require_once __DIR__."/../models/groups.php";

class GroupController {

    public static function create($request, $response, $args) {

        $body = $request->getParsedBody();

        $requiredParams = [
            "name"
        ];
        Helper::checkRequiredParameters($requiredParams, $body);

        $group = Group::create($body);

        return $response->withJson($group);
    }

    public static function getAll($request, $response, $args) {
        $groups = Group::getAll();
        return $response->withJson($groups);
    }

    public static function getById($request, $response, $args) {
        $id = $args['id'];

        $group = Group::getById($id);
        if(!$group) {
            throw new Exception("There are no groups with id: ".$id.".", 400);
        }

        return $response->withJson($group);
    }

    public static function update($request, $response, $args) {
        $id = $args['id'];

        $group = Group::getById($id);
        if(!$group) {
            throw new Exception("There are no groups with id: ".$id.".", 400);
        }

        $body = $request->getParsedBody();
        Helper::checkRequiredParameters(["name"], $body);

        $data = [
            "name" => $body['name']
        ];

        $updated_group = Group::update($id, $data);
        return $response->withJson($updated_group);
    }

    public static function delete($request, $response, $args) {
        $id = $args['id'];

        $group = Group::getById($id);
        if(!$group) {
            throw new Exception("There are no groups with id: ".$id.".", 400);
        }

        $deleted_group = Group::delete($id);

        return $response->withJson($deleted_group);
    }
}