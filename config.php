<?php

# Принимаем запрос
$data = json_decode(file_get_contents('php://input'), TRUE);
file_put_contents('file.txt', '$data: '.print_r($data, 1)."\n", FILE_APPEND);

$db = mysqli_connect("localhost","serdjio_bot","szBkENC3F6u7DjD","serdjio_bot");

# Важные константы
define('TOKEN', '1238464690:AAERGsopzqudPJXbb7ccfdA9OtG8tJ4MPXI');

//https://api.telegram.org/bot1238464690:AAERGsopzqudPJXbb7ccfdA9OtG8tJ4MPXI/setwebhook?url=https://2smm.ru/index.php