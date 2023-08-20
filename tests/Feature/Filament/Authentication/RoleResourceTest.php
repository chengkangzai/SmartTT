<?php

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\UserRoleSeeder;
use Filament\Pages\Actions\DeleteAction;
use Phpsa\FilamentAuthentication\Resources\RoleResource;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertModelMissing;
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

it('should render role index page', function () {
    get(RoleResource::getUrl('index'))
        ->assertSuccessful();
});

it('should render list role component ', function () {
    $roles = Role::all();

    livewire(RoleResource\Pages\ListRoles::class)
        ->assertCanSeeTableRecords($roles);
});

it('should render role create page', function () {
    get(RoleResource::getUrl('create'))
        ->assertSuccessful();
});

it('should create role', function () {
    $role = new Role();
    $role->name = 'test';
    $role->guard_name = 'web';

    livewire(RoleResource\Pages\CreateRole::class)
        ->fillForm([
            'name' => $role->name,
            'guard_name' => $role->guard_name,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas('roles', [
        'name' => $role->name,
        'guard_name' => $role->guard_name,
    ]);
});
//
//it('can validate input', function () {
//    livewire(RoleResource\Pages\CreateRole::class)
//        ->fillForm([
//            'name' => null,
//            'guard' => null,
//        ])
//        ->call('create')
//        ->assertHasFormErrors([
//            'name',
//            'email',
//            'password',
//        ]);
//});

it('should render role view page', function () {
    get(RoleResource::getUrl('view', [
        'record' => Role::get()->first()->id,
    ]))->assertSuccessful();
});

it('should render page to view role record ', function () {
    $role = Role::get()->first();

    livewire(RoleResource\Pages\ViewRole::class, [
        'record' => $role->getKey(),
    ])
        ->assertFormSet([
            'name' => $role->name,
            'guard_name' => $role->guard_name,
        ]);
});

it('should render role edit page', function () {
    get(RoleResource::getUrl('edit', [
        'record' => Role::get()->get(2)->id,
    ]))->assertSuccessful();
});

it('should render page to show role record in edit view', function () {
    $role = Role::get()->get(2);

    livewire(RoleResource\Pages\EditRole::class, [
        'record' => $role->getKey(),
    ])
        ->assertSuccessful()
        ->assertFormSet([
            'name' => $role->name,
            'guard_name' => $role->guard_name,
        ]);
});

it('should edit role', function () {
    $role = Role::get()->get(2);
    $newRole = new Role();
    $newRole->name = 'test';
    $newRole->guard_name = 'web';

    livewire(RoleResource\Pages\EditRole::class, [
        'record' => $role->getKey(),
    ])
        ->fillForm([
            'name' => $newRole->name,
            'guard_name' => $newRole->guard_name,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($newRole->refresh())
        ->name->toBe($newRole->name)
        ->guard_name->toBe($newRole->guard_name);
});

it('should delete role', function () {
    $role = Role::get()->get(2);

    livewire(RoleResource\Pages\EditRole::class, [
        'record' => $role->getKey(),
    ])
        ->callPageAction(DeleteAction::class);

    assertModelMissing($role);
});
