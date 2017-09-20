<?php

class Picture {

    public static function create($data) {

        $db = DB::getInstance();

        $statement = $db->prepare("
            INSERT INTO pictures
            (name, group_id, description)
            VALUES (?,?,?)
        ");
        $params = [
            $data['name'],
            $data['group_id'],
            $data['description']
        ];
        $statement->execute($params);

        return Picture::getById($db->lastInsertId());
    }

    public static function getAll() {

        $db = DB::getInstance();

        $statement = $db->prepare("
            SELECT *
            FROM pictures
        ");
        $statement->execute();

        $response = $statement->fetchAll();

        return $response;
    }

    public static function getById($id) {

        $db = DB::getInstance();

        $statement = $db->prepare("
            SELECT *
            FROM pictures
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
            FROM pictures
            WHERE name = ?
        ");
        $statement->execute([$name]);

        $response = $statement->fetch();

        return $response;
    }

    public static function update($id, $data) {
        $db = DB::getInstance();

        $statement = $db->prepare("
            UPDATE pictures
            SET name = ?,
                group_id = ?,
                description = ?
            WHERE id = ?
        ");
        $params = [
            $data['name'],
            $data['group_id'],
            $data['description'],
            $id
        ];
        $statement->execute($params);

        return Picture::getById($id);
    }

    public static function delete($id) {

        $picture_to_delete = Picture::getById($id);
        $db = DB::getInstance();

        $statement = $db->prepare("
            DELETE FROM pictures
            WHERE id = ?
        ");
        $statement->execute([$id]);

        return $picture_to_delete;
    }

}