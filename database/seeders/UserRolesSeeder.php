<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\RolesEnums;
use App\Enums\PermissionsEnums;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = PermissionsEnums::toValues();

        foreach ($permissions as $key => $value) {
            Permission::firstOrCreate(['name' => $value]);
        }

        $bureauPermission = PermissionsEnums::SeeSelf()->value;

        $dcmRole = Role::firstOrCreate(['name' => RolesEnums::Dcm()->value]);

        $bureauPosteRole = Role::firstOrCreate(['name' => RolesEnums::Bureau()->value]);

        $dcmRole->syncPermissions($permissions);

        $bureauPosteRole->syncPermissions($bureauPermission);

        $adminRole = Role::firstOrCreate(['name' => RolesEnums::Admin()->value]);

        $bPostales = User::where("name", "B_POSTALES")->first();

        $bPostales->syncRoles( RolesEnums::Admin()->value);
    }
}
