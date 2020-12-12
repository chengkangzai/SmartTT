<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
{
    public function only_logged_in_user_can_see_trip_index()
    {
        $response= $this->get('/trip')->assertRedirect('/login');

    }
}
