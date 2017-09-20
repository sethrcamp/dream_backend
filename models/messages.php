<?php

class Message {

    public static function create($data) {

        $db = DB::getInstance();

        $statement = $db->prepare("
            INSERT INTO messages
            (name, email, message)
            VALUES (?,?,?)
        ");
        $params = [
            $data['name'],
            $data['email'],
            $data['message'],
        ];
        $statement->execute($params);

        return Message::getById($db->lastInsertId());
    }

    public static function getAll() {

        $db = DB::getInstance();

        $statement = $db->prepare("
            SELECT *
            FROM messages
            ORDER BY id DESC
        ");
        $statement->execute();

        $response = $statement->fetchAll();

        return $response;
    }

    public static function getById($id) {

        $db = DB::getInstance();

        $statement = $db->prepare("
            SELECT *
            FROM messages
            WHERE id = ?
        ");
        $statement->execute([$id]);

        $response = $statement->fetch();

        return $response;
    }

    public static function update($id, $data) {
        $db = DB::getInstance();

        $statement = $db->prepare("
            UPDATE messages
            SET name = ?,
                email = ?,
                message = ?,
            WHERE id = ?
        ");
        $params = [
            $data['name'],
            $data['email'],
            $data['message'],
            $id
        ];
        $statement->execute($params);

        return Message::getById($id);
    }

    public static function delete($id) {

        $message_to_delete = Message::getById($id);
        $db = DB::getInstance();

        $statement = $db->prepare("
            DELETE FROM messages
            WHERE id = ?
        ");
        $statement->execute([$id]);

        return $message_to_delete;
    }

}