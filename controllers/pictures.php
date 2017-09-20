<?php

require_once __DIR__."/../models/groups.php";
require_once __DIR__."/../models/pictures.php";


class PictureController {

    public static function create($request, $response, $args) {

        $body = $request->getParsedBody();

        $requiredParams = [
            "name",
            "group_id"
        ];
        Helper::checkRequiredParameters($requiredParams, $body);

        $group = Group::getById($body['group_id']);
        if(!$group) {
            throw new Exception("There is no group with id: ".$body['group_id'].".", 400);
        }

        $picture = Picture::create($body);

        return $response->withJson($picture);
    }

    public static function getAll($request, $response, $args) {
        $pictures = Picture::getAll();
        return $response->withJson($pictures);
    }

    public static function getById($request, $response, $args) {
        $id = $args['id'];

        $picture = Picture::getById($id);
        if(!$picture) {
            throw new Exception("There are no pictures with id: ".$id.".", 400);
        }

        return $response->withJson($picture);
    }

    public static function update($request, $response, $args) {
        $id = $args['id'];

        $picture = Picture::getById($id);
        if(!$picture) {
            throw new Exception("There are no pictures with id: ".$id.".", 400);
        }

        $body = $request->getParsedBody();

        if(isset($body['group_id'])) {
            $group = Group::getById($body['group_id']);
            if(!$group) {
                throw new Exception("There is no group with id: ".$body['group_id'].".", 400);
            }
        }

        $data = [
            "name" => isset($body['name']) ? $body['name'] : $picture['name'],
            "group_id" => isset($body['group_id']) ? $body['group_id'] : $picture['group_id'],
            "description" => isset($body['description']) ? $body['description'] : $picture['description']
        ];

        $updated_picture = Picture::update($id, $data);
        return $response->withJson($updated_picture);
    }

    public static function delete($request, $response, $args) {
        $id = $args['id'];

        $picture = Picture::getById($id);
        if(!$picture) {
            throw new Exception("There are no pictures with id: ".$id.".", 400);
        }

        $deleted_picture = Picture::delete($id);

        return $response->withJson($deleted_picture);
    }

    //END CRUD

    public static function getAllFromS3($request, $response, $args) {
        return $response->withJson(updateDataBase("dreamesquephotography"));
    }

    public static function getAllFormatted($request, $response, $args) {
        $pictures = Picture::getAll();

        $formatted_pictures = [];

        foreach($pictures as $picture) {
            $group = Group::getById($picture['group_id']);
            $category = Category::getByName($group['name']);

            if(!$category) {
                $formatted_pictures[$group['name']][] =$picture;
            } else {
                $formatted_pictures['categories'][$group['name']][] = $picture;
            }
        }

        return $response->withJson($formatted_pictures);
    }

}