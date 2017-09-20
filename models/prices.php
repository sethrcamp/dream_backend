<?php

class Price {

    public static function create($data) {

        $db = DB::getInstance();

        $statement = $db->prepare("
            INSERT INTO prices
            (name, price, service_id)
            VALUES (?,?,?)
        ");
        $params = [
            $data['name'],
            $data['price'],
            $data['service_id']
        ];
        $statement->execute($params);

        return Price::getById($db->lastInsertId());
    }

    public static function getAll() {

        $db = DB::getInstance();

        $statement = $db->prepare("
            SELECT *
            FROM prices
        ");
        $statement->execute();

        $response = $statement->fetchAll();

        return $response;
    }

    public static function getById($id) {

        $db = DB::getInstance();

        $statement = $db->prepare("
            SELECT *
            FROM prices
            WHERE id = ?
        ");
        $statement->execute([$id]);

        $response = $statement->fetch();

        return $response;
    }

    public static function update($id, $data) {
        $db = DB::getInstance();

        $statement = $db->prepare("
            UPDATE prices
            SET name = ?,
                price = ?,
                service_id = ?
            WHERE id = ?
        ");
        $params = [
            $data['name'],
            $data['price'],
            $data['service_id'],
            $id
        ];
        $statement->execute($params);

        return Price::getById($id);
    }

    public static function delete($id) {

        $price_to_delete = Price::getById($id);
        $db = DB::getInstance();

        $statement = $db->prepare("
            DELETE FROM prices
            WHERE id = ?
        ");
        $statement->execute([$id]);

        return $price_to_delete;
    }

    public static function getAllByServiceId($id) {
        $db = DB::getInstance();

        $statement = $db->prepare("
            SELECT *
            FROM prices
            WHERE service_id = ?
        ");
        $statement->execute([$id]);

        $response = $statement->fetchAll();

        return $response;
    }
}