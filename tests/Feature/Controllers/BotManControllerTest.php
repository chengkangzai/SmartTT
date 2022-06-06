<?php

it('should successfully finish a post method call', function () {
    $this->post(route('front.botman'), [
        'driver' => 'web',
        'message' => 'hi'
    ])
        ->assertSuccessful();
});
