<?php

class Service {

    public static function create($data) {

        $db = DB::getInstance();

        $statement = $db->prepare("
            INSERT INTO services
            (name)
            VALUES (?)
        ");
        $params = [
            $data['name']
        ];
        $statement->execute($params);

        return Service::getById($db->lastInsertId());
    }

    public static function getAll() {

        $db = DB::getInstance();

        $statement = $db->prepare("
            SELECT *
            FROM services
        ");
        $statement->execute();

        $response = $statement->fetchAll();

        return $response;
    }

    public static function getById($id) {

        $db = DB::getInstance();

        $statement = $db->prepare("
            SELECT *
            FROM services
            WHERE id = ?
        ");
        $statement->execute([$id]);

        $response = $statement->fetch();

        return $response;
    }

    public static function update($id, $data) {
        $db = DB::getInstance();

        $statement = $db->prepare("
            UPDATE services
            SET name = ?
            WHERE id = ?
        ");
        $params = [
            $data['name'],
            $id
        ];
        $statement->execute($params);

        return Service::getById($id);
    }

    public static function delete($id) {

        $service_to_delete = Service::getById($id);
        $db = DB::getInstance();

        $statement = $db->prepare("
            DELETE FROM services
            WHERE id = ?
        ");
        $statement->execute([$id]);

        return $service_to_delete;
    }

}