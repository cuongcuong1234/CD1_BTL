<?php
require "vendor/autoload.php";
$app = require "bootstrap/app.php";
echo "User count: " . \App\Models\User::count() . PHP_EOL;
