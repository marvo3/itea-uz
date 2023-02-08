<?php

if (!empty($_POST) || $_SERVER['REQUEST_METHOD'] === 'POST') {
    $to = array();
    $to[] = 'nykolay@gns-it.com';
    $to[] = 'administration@itea.ua';

    $subject = 'Уведомление об успешной оплате через platon.ua';
    $message  = '<html><head><title>Новое сообщение</title><meta charset="utf-8"></head><body>';

    foreach ($_POST as $key => $value)
    {
        if ($key === 'ext1' && $value === 'Lviv') {
            $to = ['nykolay@gns-it.com', 'ulyana.volynec@itea.ua'];
        }

        $message .= "<p>{$key} : {$value}</p>";
    }

    $message .= '</body></html>';

    $headers = array();
    $headers[] = 'From: IT Education Academy <platon@itea.ua>';
    $headers[] = 'Content-Type: text/html';

    mail(implode(',', $to), $subject, $message, implode("\r\n", $headers));
    echo json_encode(['status' => 'ok']);
    exit;
}
