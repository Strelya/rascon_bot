<?php

include("config.php");

# Статус команды
$q_command = mysqli_query($db, "SELECT `value` FROM `rascon` WHERE `key` = 'command'");
list($command) = mysqli_fetch_row($q_command);

//Номер базы
$q_base = mysqli_query($db, "SELECT `value` FROM `rascon` WHERE `key` = 'n_base'");
list($n_base) = mysqli_fetch_row($q_base);

# Обрабатываем ручной ввод или нажатие на кнопку
$data = $data['callback_query'] ? $data['callback_query'] : $data['message'];

# Записываем сообщение пользователя
$message = mb_strtolower(($data['text'] ? $data['text'] : $data['data']),'utf-8');

switch ($command) {
    case 'start':
        mysqli_query($db, "UPDATE `rascon` SET `value` = 'n_base' WHERE `key` = 'command'");
        # Обрабатываем сообщение
        switch ($message)
        {
            case '/start':
            case 'старт':
            $method = 'sendMessage';
            $send_data = [
                'text'   => 'Введите номер базы:',
                'reply_markup' => [
                    'resize_keyboard' => true,
                    'keyboard' => [
                        [
                            ['text' => $n_base],
                        ],
                        [
                            ['text' => $n_base + 1],
                            ['text' => $n_base + 2],
                        ]
                    ]
                ]
            ];
            break;

            default:
                $method = 'sendMessage';
                $send_data = [
                    'text' => 'Старт'
                ];
        }
        break;

    case 'n_base':
        mysqli_query($db, "UPDATE `rascon` SET `value` = '$message' WHERE `key` = 'n_base'");
        mysqli_query($db, "UPDATE `rascon` SET `value` = 'date_base' WHERE `key` = 'command'");
            $method = 'sendMessage';
            $send_data = [
                    'text'   => 'Введите дату:',
                    'reply_markup' => [
                        'resize_keyboard' => true,
                        'keyboard' => [
                            [
                                ['text' => date ( 'd.m.Y' )],
                            ],
                            [
                                ['text' => date ( 'd.m.Y', time() - 86400 )],
                                ['text' => date ( 'd.m.Y', time() + 86400 )],
                            ]
                        ]
                    ]
            ];
    break;

    case 'date_base':
        mysqli_query($db, "UPDATE `rascon` SET `value` = '$message' WHERE `key` = 'date_base'");
        mysqli_query($db, "UPDATE `rascon` SET `value` = 'url_zp' WHERE `key` = 'command'");
                $method = 'sendMessage';
                $send_data = [
                    'text'   => 'Введите ccылку для ZP:',
                ];
    break;

    case 'url_zp':
        mysqli_query($db, "UPDATE `rascon` SET `value` = '$message' WHERE `key` = 'url_zp'");
        mysqli_query($db, "UPDATE `rascon` SET `value` = 'url_exe' WHERE `key` = 'command'");
                $method = 'sendMessage';
                $send_data = [
                    'text'   => 'Введите ccылку для EXE:',
                ];
        break;

    case 'url_exe':
        mysqli_query($db, "UPDATE `rascon` SET `value` = '$message' WHERE `key` = 'url_exe'");
        mysqli_query($db, "UPDATE `rascon` SET `value` = 'start' WHERE `key` = 'command'");
            $method = 'sendMessage';
            $send_data = [
                    'text'   => 'Конец. Вывести данные',
                    'reply_markup' => [
                        'resize_keyboard' => true,
                        'keyboard' => [
                            [
                                ['text' => 'Старт'],
                            ]
                        ]
                    ]
            ];
    break;
    
    default:
        mysqli_query($db, "UPDATE `rascon` SET `value` = 'start' WHERE `key` = 'command'");
        $method = 'sendMessage';
        $send_data = [
                'text' => 'Старт',
                'reply_markup' => [
                        'resize_keyboard' => true,
                        'keyboard' => [
                            ['text' => 'Старт'],
                        ]
                ]
            ];
        break;
}

# Добавляем данные пользователя
$send_data['chat_id'] = $data['chat']['id'];

$res = sendTelegram($method, $send_data);

function sendTelegram($method, $data, $headers = [])
{
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'https://api.telegram.org/bot' . TOKEN . '/' . $method,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array_merge(array("Content-Type: application/json"), $headers)
    ]);   
    
    $result = curl_exec($curl);
    curl_close($curl);
    return (json_decode($result, 1) ? json_decode($result, 1) : $result);
}