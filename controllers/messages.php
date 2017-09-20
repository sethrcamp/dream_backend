<?php

require_once __DIR__."/../models/messages.php";

class MessageController {

    public static function create($request, $response, $args) {

        $body = $request->getParsedBody();

        $requiredParams = [
            "name",
            "email",
            "message"
        ];
        Helper::checkRequiredParameters($requiredParams, $body);


        $message = Message::create($body);

        return $response->withJson($message);
    }

    public static function getAll($request, $response, $args) {
        $messages = Message::getAll();
        return $response->withJson($messages);
    }

    public static function getById($request, $response, $args) {
        $id = $args['id'];

        $message = Message::getById($id);
        if(!$message) {
            throw new Exception("There are no messages with id: ".$id.".", 400);
        }

        return $response->withJson($message);
    }

    public static function update($request, $response, $args) {
        $id = $args['id'];

        $message = Message::getById($id);
        if(!$message) {
            throw new Exception("There are no messages with id: ".$id.".", 400);
        }

        $body = $request->getParsedBody();

        $data = [
            "name" => isset($body['name']) ? $body['name'] : $message['name'],
            "email" => isset($body['email']) ? $body['email'] : $message['email'],
            "message" => isset($body['message']) ? $body['message'] : $message['message']
        ];

        $updated_message = Message::update($id, $data);
        return $response->withJson($updated_message);
    }

    public static function delete($request, $response, $args) {
        $id = $args['id'];

        $message = Message::getById($id);
        if(!$message) {
            throw new Exception("There are no messages with id: ".$id.".", 400);
        }

        $deleted_message = Message::delete($id);

        return $response->withJson($deleted_message);
    }

}