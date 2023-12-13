<?php

require __DIR__.'/vendor/autoload.php';
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Storage;

$factory = (new Factory)
->withServiceAccount('kidcare-b6e4f-firebase-adminsdk-t4pnz-96f5eb94be.json')
->withDatabaseUri('https://kidcare-b6e4f-default-rtdb.asia-southeast1.firebasedatabase.app/');

$database = $factory->createDatabase();
$auth = $factory->createAuth();
$storage = $factory->createStorage();
?>