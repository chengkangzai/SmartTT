<?php

use App\Actions\Profile\UpdateProfileAction;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use function Pest\Laravel\assertModelExists;
use function PHPUnit\Framework\assertNotEmpty;

it('should update user profile', function () {
    $ori = User::factory()->create();
    assertModelExists($ori);

    $new = User::factory()->make([
        'password' => 'new-password',
    ]);
    $action = app(UpdateProfileAction::class)->execute($new->toArray(), $ori);
    expect($action)->toBeInstanceOf(User::class);
    expect($action->name)->toBe($new->name);
    expect($action->email)->toBe($new->email);

    assertModelExists($action);
});

it('should fail to update user profile due to invalid data', function ($name, $data) {
    $user = User::factory()->create();
    foreach ($data as $item) {
        $testArray[$name] = $item;
        if (! isset($data['password_confirmation'])) {
            if (isset($data['password'])) {
                $testArray['password_confirmation'] = $data['password'];
            } else {
                $testArray['password_confirmation'] = '';
            }
        }

        try {
            app(UpdateProfileAction::class)->execute($testArray, $user);
        } catch (ValidationException $e) {
            Log::info($e->validator->errors()->get($name), $testArray);
            assertNotEmpty($e->validator->errors()->get($name));
        }
    }
})->with([
    ['name', [100, -1, null, '', 'a' . str_repeat('a', 255)]],
    ['email', [100, -1, null, '', 'a', 'a' . str_repeat('a', 255)]],
    ['password', [100, -1, 'a',]],
]);
