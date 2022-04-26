<?php

use App\Actions\Role\StoreRoleAction;
use Database\Seeders\PermissionSeeder;
use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\seed;
use Spatie\Permission\Models\Permission;

beforeEach(function () {
    seed(PermissionSeeder::class);
});

it('should create a role', function () {
    $permissions= Permission::inRandomOrder()->take(10);
    $role = app(StoreRoleAction::class)->execute([
        'name' => 'test',
        'permissions' => $permissions->pluck('id')->toArray(),
    ]);

    assertModelExists($role);
    assertModelExists($role->permissions);
    assertModelExists($role->permissions->intersect($permissions));

    $permissions->each(function ($permission) use ($role) {
        assert($role->hasPermissionTo($permission));
    });
});
