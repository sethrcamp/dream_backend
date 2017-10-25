<?php


require_once '../vendor/autoload.php';
require_once __DIR__.'/aws_config.php';


global $AWS_CONFIG;

$s3Client = new \Aws\S3\S3Client([
    "version" => "latest",
    "region" => $AWS_CONFIG['region'],
    "credentials" => [
        "key" => $AWS_CONFIG['key'],
        "secret" => $AWS_CONFIG['secret']
    ]
]);

function updateDataBase($bucket){
    global $s3Client;
    $objects = $s3Client->listObjects(["Bucket"=>$bucket])["Contents"];
    $all_s3_pictures = [];
    $all_s3_groups = [];
    foreach($objects as $object){
        $found_pictures = [];
        preg_match('~(?:[^/]+)(?:\.)(?:png|jpg|jpeg|svg)$~i', $object['Key'], $found_pictures);

        if(sizeof($found_pictures) > 0) {
            $isCategory = false;
            $found_groups = [];
            preg_match('~(?<=/)\S+(?=/)~i', $object['Key'], $found_groups);

            if(sizeof($found_groups) == 0) {
                preg_match('~^[^/]+(?=/)~i', $object['Key'], $found_groups);
            } else {
                $isCategory = true;
            }


            $group = Group::getByName($found_groups[0]);

            if(!$group) {
                $group = Group::create(["name" => $found_groups[0]]);
                $picture = Picture::create(["name" => $found_pictures[0], "group_id" => $group['id'], "description" => null]);
            } else {
                $picture = Picture::getByNameAndGroup($found_pictures[0], $group['id']);
                if(!$picture) {
                    $picture = Picture::create(["name" => $found_pictures[0], "group_id" => $group['id'], "description" => null]);
                }
            }


            if(!in_array($group, $all_s3_groups))
                $all_s3_groups[] = $group;
            $all_s3_pictures[] = $picture;

            if($isCategory) {
                $category = Category::getByName($found_groups[0]);
                $main_picture = [];
                preg_match("~^MAIN_~", $picture['name'], $main_picture);
                if(!$category) {
                    if(sizeof($main_picture) != 0)
                        Category::create(["name" => $found_groups[0], "picture_id" => $picture['id']]);
                    else
                        Category::create(["name" => $found_groups[0], "picture_id" => null]);
                } else {
                    if(sizeof($main_picture) != 0)
                        Category::update($category['id'], ["name" => $found_groups[0], "picture_id" => $picture['id']]);
                }
            }
        }
    }

    $all_db_pictures = Picture::getAll();
    $all_db_groups = Group::getAll();

    $toDump = "";
    foreach($all_db_pictures as $pic) {
        $toDump .= $pic['name']." ";
    }
    var_dump("dbPics: ".$toDump);

    $toDump = "";
    foreach($all_s3_pictures as $pic) {
        $toDump .= $pic['name']." ";
    }
    var_dump("s3Pics: ".$toDump);


    $pictures_to_delete = array_udiff($all_db_pictures, $all_s3_pictures, function($a, $b) {
        return strcmp($a['name'], $b['name']);
    });
    $groups_to_delete = array_udiff($all_db_groups, $all_s3_groups, function($a, $b) {
        return strcmp($a['name'], $b['name']);
    });


//    var_dump($pictures_to_delete, $groups_to_delete);die();
    foreach($groups_to_delete as $group_to_delete) {
        $category_to_delete = Category::getByName($group_to_delete['name']);
        Category::delete($category_to_delete['id']);
    }
    foreach($pictures_to_delete as $picture_to_delete) {
        Picture::delete($picture_to_delete['id']);
    }
    foreach($groups_to_delete as $group_to_delete) {
        Group::delete($group_to_delete['id']);
    }


    return ["success" => "true"];
}

