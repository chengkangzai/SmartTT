<?php

use App\Actions\User\UpdateUserAction;
use App\Models\User;
use function Pest\Laravel\assertModelExists;

it('should update a user', function () {
    $user = User::factory()->create();
    assertModelExists($user);

    $mock = User::factory()->make();
    $updatedUser = app(UpdateUserAction::class)->execute($mock->toArray(), $user);
    expect($updatedUser)->toBeInstanceOf(User::class);
    assertModelExists($updatedUser);
    expect($updatedUser->id)->toBe($user->id)
        ->and($updatedUser->name)->toBe($mock->name)
        ->and($updatedUser->email)->toBe($mock->email);
});
