<?php

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\UserRoleSeeder;
use Filament\Pages\Actions\DeleteAction;
use Phpsa\FilamentAuthentication\Resources\UserResource;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\get;
use function Pest\Laravel\seed;
use function Pest\Livewire\livewire;

beforeEach(function () {
    seed([
        PermissionSeeder::class,
        UserRoleSeeder::class,
    ]);
    actingAs(User::factory()->superAdmin()->create());
});

it('should render user index page', function () {
    get(UserResource::getUrl('index'))
        ->assertSuccessful();
});

it('should render list user component ', function () {
    $users = User::factory()->count(5)->create();

    livewire(UserResource\Pages\ListUsers ::class)
        ->assertCanSeeTableRecords($users);
});

it('should render user create page', function () {
    get(UserResource::getUrl('create'))
        ->assertSuccessful();
});

it('should create user', function () {
    $user = User::factory()->make();
    livewire(UserResource\Pages\CreateUser::class)
        ->fillForm([
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
            'passwordConfirmation' => $user->password,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas('users', [
        'name' => $user->name,
        'email' => $user->email,
    ]);
});

it('can validate input', function () {
    livewire(UserResource\Pages\CreateUser::class)
        ->fillForm([
            'name' => null,
            'email' => null,
            'password' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name',
            'email',
            'password',
        ]);
});

it('should render user view page', function () {
    get(UserResource::getUrl('view', [
        'record' => User::factory()->create()
    ]))->assertSuccessful();
});

it('should render page to view user record ', function () {
    $user = User::factory()->create();

    livewire(UserResource\Pages\ViewUser::class, [
        'record' => $user->getKey(),
    ])
        ->assertFormSet([
            'name' => $user->name,
            'email' => $user->email,
        ]);
});

it('should render user edit page', function () {
    get(UserResource::getUrl('edit', [
        'record' => User::factory()->create()
    ]))->assertSuccessful();
});

it('should render page to show user record in edit view', function () {
    $user = User::factory()->create();

    livewire(UserResource\Pages\EditUser::class, [
        'record' => $user->getKey(),
    ])
        ->assertFormSet([
            'name' => $user->name,
            'email' => $user->email,
        ]);
});

it('should edit user', function () {
    $user = User::factory()->create();
    $newUser = User::factory()->make();

    livewire(UserResource\Pages\EditUser::class, [
        'record' => $user->getKey(),
    ])
        ->fillForm([
            'name' => $newUser->name,
            'email' => $newUser->email,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($newUser->refresh())
        ->name->toBe($newUser->name)
        ->email->toBe($newUser->email);
});

it('should delete user', function () {
    $user = User::factory()->create();

    livewire(UserResource\Pages\EditUser::class, [
        'record' => $user->getKey(),
    ])
        ->callPageAction(DeleteAction::class);

    assertSoftDeleted($user);
});
