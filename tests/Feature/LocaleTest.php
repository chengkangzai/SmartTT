<?php

namespace Tests\Feature;

use Tests\TestCase;

class LocaleTest extends TestCase
{
    private mixed $msLocale;
    private mixed $enLocale;
    private mixed $zhLocale;

    public function setUp(): void
    {
        parent::setUp();

        $this->enLocale = json_decode(file_get_contents(lang_path() . '/en.json'), true);
        $this->zhLocale = json_decode(file_get_contents(lang_path() . '/zh.json'), true);
        $this->msLocale = json_decode(file_get_contents(lang_path() . '/ms.json'), true);
    }

    public function test_en_translate_is_not_empty()
    {
        foreach ($this->enLocale as $value) {
            $this->assertNotNull($value);
        }
    }

    public function test_if_has_cn_locale()
    {
        foreach ($this->enLocale as $key => $value) {
            $this->assertArrayHasKey($key, $this->zhLocale);
            $this->assertNotNull($this->zhLocale[$key]);
        }
    }

    public function test_if_has_ms_locale()
    {
        foreach ($this->enLocale as $key => $value) {
            $this->assertArrayHasKey($key, $this->msLocale);
            $this->assertNotNull($this->msLocale[$key]);
        }
    }
}
