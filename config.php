<?php

# Принимаем запрос
$data = json_decode(file_get_contents('php://input'), TRUE);
file_put_contents('file.txt', '$data: '.print_r($data, 1)."\n", FILE_APPEND);

$db = mysqli_connect("localhost","33","22","11");

# Важные константы
define('TOKEN', '44');

//https://api.telegram.org/bot55
