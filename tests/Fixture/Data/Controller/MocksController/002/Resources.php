<?php

use Cake\I18n\Time;
use Faker\Generator;

/**
 * @var Generator $faker
 * @return array
 */
return function ($faker) {
    $data = [];

    for ($i = 1; $i <= 10; $i++) {
        $data[] = [
            'id' => $i,
            'url' => sprintf('/v1/resource%d', $i),
            'method' => 'GET',
            'response' => json_encode(['code' => 200, 'body' => 'OK']),
            'created' => Time::now()->i18nFormat('yyyy-MM-dd HH:mm:ss', 'Asia/Tokyo'),
            'modified' => Time::now()->i18nFormat('yyyy-MM-dd HH:mm:ss', 'Asia/Tokyo'),
        ];
    }

    return $data;
};
