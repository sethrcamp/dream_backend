<?php

class Category {

    public static function create($data) {

        $db = DB::getInstance();

        $statement = $db->prepare("
            INSERT INTO categories
            (name, picture_id)
            VALUES (?,?)
        ");
        $params = [
            $data['name'],
            $data['picture_id']
        ];
        $statement->execute($params);

        return Category::getById($db->lastInsertId());
    }

    public static function getAll() {

        $db = DB::getInstance();

        $statement = $db->prepare("
            SELECT *
            FROM categories
        ");
        $statement->execute();

        $response = $statement->fetchAll();

        return $response;
    }

    public static function getById($id) {

        $db = DB::getInstance();

        $statement = $db->prepare("
            SELECT *
            FROM categories
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
            FROM categories
            WHERE name = ?
        ");
        $statement->execute([$name]);

        $response = $statement->fetch();

        return $response;
    }

    public static function update($id, $data) {
        $db = DB::getInstance();

        $statement = $db->prepare("
            UPDATE categories
            SET name = ?,
                picture_id =?
            WHERE id = ?
        ");
        $params = [
            $data['name'],
            $data['picture_id'],
            $id
        ];
        $statement->execute($params);

        return Category::getById($id);
    }

    public static function delete($id) {

        $category_to_delete = Category::getById($id);
        $db = DB::getInstance();

        $statement = $db->prepare("
            DELETE FROM categories
            WHERE id = ?
        ");
        $statement->execute([$id]);

        return $category_to_delete;
    }

}