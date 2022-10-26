<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require "../vendor/autoload.php";
$isDevMode = true;

#$config = Setup::creatAnnotationMetadataConfiguration(array("src"), $isDevMode);

$connection = array(
    "dbname" => "dbname",
    "user" => "root",
    "password" => "",
    "host" => "localhost",
    "driver" => "pdo_pgsql"
);

#$entityManager = EntityManager::creat($connection, $config);