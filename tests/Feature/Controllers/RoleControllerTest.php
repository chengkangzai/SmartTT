<?php

use App\Actions\Package\GetTourAndFlightForCreateAndUpdatePackage;
use App\Models\Flight;
use App\Models\Package;
use App\Models\PackagePricing;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\DatabaseSeeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\seed;
use function PHPUnit\Framework\assertInstanceOf;

beforeEach(function () {
    seed(DatabaseSeeder::class);
    $this->actingAs(User::first());
});

it('should return index view', function () {
    $this
        ->get(route('roles.index'))
        ->assertViewIs('smartTT.role.index')
        ->assertViewHas('roles', Role::paginate(10));
});

it('should return create view', function () {
    $this
        ->get(route('roles.create'))
        ->assertViewIs('smartTT.role.create')
        ->assertViewHas([
            'permissions' => Permission::all(),
        ]);
});

it('should return edit view', function () {
    $this
        ->get(route('roles.edit', Role::first()))
        ->assertViewIs('smartTT.role.edit')
        ->assertViewHas([
            'role' => Role::first(),
            'permissions' => Permission::all(),
        ]);
});

it('should return show view', function () {
    $role = Role::first();
    $this
        ->get(route('roles.show', $role))
        ->assertViewIs('smartTT.role.show');
});
//
$faker = Faker\Factory::create();
it('should store a role', function () use ($faker) {
    $role = [
        'name' => $faker->name,
        'permissions' => Permission::inRandomOrder()->take(3)->pluck('id')->toArray(),
    ];
    $this
        ->post(route('roles.store'), $role)
        ->assertRedirect(route('roles.index'))
        ->assertSessionHasNoErrors()
        ->assertSessionHas('success');

    $updatedRole = Role::with('permissions')->orderByDesc('id')->get()->first();
    expect($updatedRole->name)->toBe($role['name']);
    expect($updatedRole->permissions->count())->toBe(3);
    $updatedRole->permissions()->each(function ($permission) use ($role) {
        expect($role['permissions'])->toContain($permission->id);
    });
});

it('should not store a role bc w/o other param', function () use ($faker) {
    $this
        ->from(route('roles.create'))
        ->post(route('roles.store'), [])
        ->assertSessionHasErrors([
            'name',
            'permissions',
        ]);
});
//
it('should update a role', function () use ($faker) {
    $role = Role::create([
        'name' => $faker->name,
    ]);
    assertModelExists($role);

    $permissions = Permission::inRandomOrder()->take(3)->pluck('id')->toArray();
    $role->givePermissionTo($permissions);

    collect($permissions)->each(function ($permission) use ($role) {
        expect($role->permissions->pluck('id'))->toContain($permission);
    });

    $newRole = [
        'name' => $faker->name,
        'permissions' => Permission::inRandomOrder()->take(3)->pluck('id')->toArray(),
    ];

    $this->from(route('roles.edit', $role))
        ->put(route('roles.update', $role), $newRole)
        ->assertRedirect(route('roles.show', $role))
        ->assertSessionHasNoErrors()
        ->assertSessionHas('success');

    $updatedRole = Role::with('permissions')->find($role->id);
    expect($updatedRole->name)->toBe($newRole['name']);
    expect($updatedRole->permissions->count())->toBe(3);
    $updatedRole->permissions()->each(function ($permission) use ($newRole) {
        expect($newRole['permissions'])->toContain($permission->id);
    });
});

it('should destroy a role', function () use ($faker) {
    $role = Role::create([
        'name' => $faker->name,
    ]);
    assertModelExists($role);

    $this
        ->delete(route('roles.destroy', $role))
        ->assertRedirect(route('roles.index'))
        ->assertSessionHas('success');

    assertSoftDeleted($role);
});
