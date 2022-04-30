<?php


use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed(DatabaseSeeder::class);
    $this->actingAs(User::first());
});

it('should return index view', function () {
    $this
        ->get(route('users.index'))
        ->assertViewIs('smartTT.user.index')
        ->assertViewHas('users', User::orderByDesc('id')->paginate(10));
});

it('should return create view', function () {
    $this
        ->get(route('users.create'))
        ->assertViewIs('smartTT.user.create');
});

it('should return edit view', function () {
    $this
        ->get(route('users.edit', User::first()))
        ->assertViewIs('smartTT.user.edit');
});

it('should return show view', function () {
    $this
        ->get(route('users.show', User::first()))
        ->assertViewIs('smartTT.user.show')
        ->assertViewHas('user', User::first());
});

it('should return audit view', function () {
    $this
        ->get(route('users.audit', User::first()))
        ->assertViewIs('smartTT.user.audit')
        ->assertViewHas('user', User::first())
        ->assertViewHas('logs');
});

it('should store a user', function () {
    $user = User::factory()->make();
    $user = $user->toArray();
    $user['password'] = 'password';
    $user['password_confirmation'] = 'password';

    $this
        ->post(route('users.store'), $user)
        ->assertRedirect(route('users.index'))
        ->assertSessionHas('success');

    $latestUser = User::query()->orderByDesc('id')->get()->first();
    expect($latestUser->name)->toBe($user['name']);
    expect($latestUser->email)->toBe($user['email']);
    expect($latestUser->roles()->count())->toBe(1);
    expect($latestUser->roles()->first()->name)->toBe('Customer');
});

it('should update a user', function () {
    $oriUser = User::factory()->create();
    assertModelExists($oriUser);

    $newUser = User::factory()->make();
    $this
        ->put(route('users.update', $oriUser), $newUser->toArray())
        ->assertRedirect(route('users.show', $oriUser))
        ->assertSessionHas('success');

    $latestUser = User::find($oriUser->id);
    expect($latestUser->name)->toBe($newUser->name);
    expect($latestUser->email)->toBe($newUser->email);
});

it('should destroy a user', function () {
    $user = User::factory()->create();
    assertModelExists($user);

    $this
        ->delete(route('users.destroy', $user))
        ->assertRedirect(route('users.index'))
        ->assertSessionHas('success');

    assertSoftDeleted($user);
});
