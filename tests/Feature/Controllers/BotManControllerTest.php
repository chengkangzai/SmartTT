<?php

it('should successfully finish a post method call', function () {
    $this->post(route('front.botman'))
        ->assertSuccessful();
});

it('should successfully finish a get method call', function () {
    $this->get(route('front.botman'))
        ->assertSuccessful();
});
