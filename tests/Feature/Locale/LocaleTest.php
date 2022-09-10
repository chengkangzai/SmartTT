<?php

use function PHPUnit\Framework\assertArrayHasKey;
use function PHPUnit\Framework\assertNotNull;

it('should not have empty english key', function () {
    $enLocale = json_decode(file_get_contents(lang_path().'/en.json'), true);
    foreach ($enLocale as $value) {
        assertNotNull($value);
    }
});

it('should have cn locale', function () {
    $enLocale = json_decode(file_get_contents(lang_path().'/en.json'), true);
    $zhLocale = json_decode(file_get_contents(lang_path().'/zh.json'), true);

    foreach ($enLocale as $key => $value) {
        assertArrayHasKey($key, $zhLocale);
        assertNotNull($zhLocale[$key]);
    }
});

it('should have ms locale', function () {
    $enLocale = json_decode(file_get_contents(lang_path().'/en.json'), true);
    $msLocale = json_decode(file_get_contents(lang_path().'/ms.json'), true);
    foreach ($enLocale as $key => $value) {
        assertArrayHasKey($key, $msLocale);
        assertNotNull($msLocale[$key]);
    }
});
