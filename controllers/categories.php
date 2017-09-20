<?php

require_once __DIR__."/../models/pictures.php";
require_once __DIR__."/../models/categories.php";

class CategoryController {

    public static function create($request, $response, $args) {

        $body = $request->getParsedBody();

        $requiredParams = [
            "name",
            "picture_id"
        ];
        Helper::checkRequiredParameters($requiredParams, $body);

        $picture = Picture::getById($body['picture_id']);
        if(!$picture) {
            throw new Exception("There is no picture with id: ".$body['picture_id'].".", 400);
        }

        $category = Category::create($body);

        return $response->withJson($category);
    }

    public static function getAll($request, $response, $args) {
        $categories = Category::getAll();
        return $response->withJson($categories);
    }

    public static function getById($request, $response, $args) {
        $id = $args['id'];

        $category = Category::getById($id);
        if(!$category) {
            throw new Exception("There are no categories with id: ".$id.".", 400);
        }

        return $response->withJson($category);
    }

    public static function update($request, $response, $args) {
        $id = $args['id'];

        $category = Category::getById($id);
        if(!$category) {
            throw new Exception("There are no categories with id: ".$id.".", 400);
        }

        $body = $request->getParsedBody();

        if(isset($body['picture_id'])) {
            $picture = Picture::getById($body['picture_id']);
            if(!$picture) {
                throw new Exception("There is no picture with id: ".$body['picture_id'].".", 400);
            }
        }

        $data = [
            "name" => isset($body['name']) ? $body['name'] : $category['name'],
            "picture_id" => isset($body['picture_id']) ? $body['picture_id'] : $category['picture_id']
        ];

        $updated_category = Category::update($id, $data);
        return $response->withJson($updated_category);
    }

    public static function delete($request, $response, $args) {
        $id = $args['id'];

        $category = Category::getById($id);
        if(!$category) {
            throw new Exception("There are no categories with id: ".$id.".", 400);
        }

        $deleted_category = Category::delete($id);

        return $response->withJson($deleted_category);
    }

}