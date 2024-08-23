<?php

namespace Database\Seeders;

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

        $bureauPosteRole->syncPermissions($permissions);



    }
}
