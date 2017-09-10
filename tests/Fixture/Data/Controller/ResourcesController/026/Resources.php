<?php

use Cake\I18n\Time;
use Faker\Generator;

/**
 * @var Generator $faker
 * @return array
 */
return function ($faker) {
    return [
        [
            'id' => 1,
            'url' => '/v1/users',
            'method' => 'GET',
            'response' => json_encode(['code' => 200, 'body' => 'OK']),
            'created' => Time::now()->i18nFormat('yyyy-MM-dd HH:mm:ss', 'Asia/Tokyo'),
            'modified' => Time::now()->i18nFormat('yyyy-MM-dd HH:mm:ss', 'Asia/Tokyo'),
        ],
    ];
};
