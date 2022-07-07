<?php

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\UserRoleSeeder;
use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\seed;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    seed([
        PermissionSeeder::class,
        UserRoleSeeder::class,
    ]);
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

it('should return audit view', function () {
    $this
        ->get(route('roles.audit', Role::first()))
        ->assertViewIs('smartTT.role.audit')
        ->assertViewHas('role', Role::first())
        ->assertViewHas('logs');
});

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
    expect($updatedRole->name)->toBe($role['name'])
        ->and($updatedRole->permissions->count())->toBe(3);
    $updatedRole->permissions()->each(function ($permission) use ($role) {
        expect($role['permissions'])->toContain($permission->id);
    });
});

it('should not store a role because w/o other parameter', function () use ($faker) {
    $this
        ->from(route('roles.create'))
        ->post(route('roles.store'), [])
        ->assertSessionHasErrors([
            'name',
            'permissions',
        ]);
});

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
    expect($updatedRole->name)->toBe($newRole['name'])
        ->and($updatedRole->permissions->count())->toBe(3);
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

    assertModelMissing($role);
});

it('should destroy not role with user', function () use ($faker) {
    $role = Role::create([
        'name' => $faker->name,
    ]);
    User::factory()->count(10)->create()->each(function ($user) use ($role) {
        $user->assignRole($role);
    });
    assertModelExists($role);

    $this
        ->from(route('roles.index'))
        ->delete(route('roles.destroy', $role))
        ->assertRedirect(route('roles.index'))
        ->assertSessionHasErrors();

    assertModelExists($role);
});

it('should attach user to role', function () use ($faker) {
    $users = User::factory()->count(5)->create();
    $role = Role::create([
        'name' => $faker->name,
    ]);

    $this
        ->from(route('roles.show', $role))
        ->put(route('roles.attachUserToRole', $role), [
            'users' => $users->pluck('id')->toArray(),
        ])
        ->assertRedirect(route('roles.show', $role))
        ->assertSessionHasNoErrors()
        ->assertSessionHas('success');

    $role->users()->each(function ($user) use ($users) {
        expect($users->pluck('id'))->toContain($user->id);
    });
});

it('should detach a user from a role', function () use ($faker) {
    $user = User::factory()->create();
    $role = Role::create([
        'name' => $faker->name,
    ]);
    $user->assignRole($role);

    $this
        ->delete(route('roles.detachUserToRole', $role), [
            'user_id' => $user->id,
        ])
        ->assertRedirect(route('roles.show', $role))
        ->assertSessionHasNoErrors()
        ->assertSessionHas('success');

    expect($role->users->count())->toBe(0);
});
