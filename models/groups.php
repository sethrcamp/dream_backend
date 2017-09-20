<?php

class Group {

    public static function create($data) {

        $db = DB::getInstance();

        $statement = $db->prepare("
            INSERT INTO groups
            (name)
            VALUES (?)
        ");
        $params = [
            $data['name']
        ];
        $statement->execute($params);

        return Group::getById($db->lastInsertId());
    }

    public static function getAll() {

        $db = DB::getInstance();

        $statement = $db->prepare("
            SELECT *
            FROM groups
        ");
        $statement->execute();

        $response = $statement->fetchAll();

        return $response;
    }

    public static function getById($id) {

        $db = DB::getInstance();

        $statement = $db->prepare("
            SELECT *
            FROM groups
            WHERE id = ?
        ");
        $statement->execute([$id]);

        $response = $statement->fetch();

        return $response;
    }

    public static function getByName($name) {

        $db = DB::getInstance();

        $statement = $db->prepare("
            SELECT *
            FROM groups
            WHERE name = ?
        ");
        $statement->execute([$name]);

        $response = $statement->fetch();

        return $response;
    }

    public static function update($id, $data) {
        $db = DB::getInstance();

        $statement = $db->prepare("
            UPDATE groups
            SET name = ?
            WHERE id = ?
        ");
        $params = [
            $data['name'],
            $id
        ];
        $statement->execute($params);

        return Group::getById($id);
    }

    public static function delete($id) {

        $group_to_delete = Group::getById($id);
        $db = DB::getInstance();

        $statement = $db->prepare("
            DELETE FROM groups
            WHERE id = ?
        ");
        $statement->execute([$id]);

        return $group_to_delete;
    }

}