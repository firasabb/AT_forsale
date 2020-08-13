<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'create_user',
            'delete_user',
            'edit_user',
            'view_users',
            'create_post',
            'delete_post',
            'edit_post',
            'create_comment',
            'delete_comment',
            'edit_comment'
        ];

        $admin = Role::where('name', 'admin')->first();
        $moderator = Role::where('name', 'moderator')->first();
        $user = Role::where('name', 'user')->first();

        foreach($permissions as $permission){
            $exists = Permission::where('name', $permission)->first();
            if(!$exists){
                $newPermission = Permission::create(['name' => $permission]);
                switch($permission){
                    case 'create_user':
                        $newPermission->assignRole($admin);
                        break;
                    case 'delete_user':
                        $newPermission->assignRole($admin);
                        break;
                    case 'edit_user':
                        $newPermission->assignRole($admin);
                        break;
                    case 'view_user':
                        $newPermission->assignRole($admin);
                        break;
                    case 'create_post':
                        $newPermission->assignRole($admin);
                        $newPermission->assignRole($moderator);
                        $newPermission->assignRole($user);
                        break;
                    case 'delete_post':
                        $newPermission->assignRole($admin);
                        $newPermission->assignRole($moderator);
                        $newPermission->assignRole($user);
                        break;
                    case 'edit_post':
                        $newPermission->assignRole($admin);
                        $newPermission->assignRole($moderator);
                        $newPermission->assignRole($user);
                        break;
                    case 'create_comment':
                        $newPermission->assignRole($admin);
                        $newPermission->assignRole($moderator);
                        $newPermission->assignRole($user);
                        break;
                    case 'delete_comment':
                        $newPermission->assignRole($admin);
                        $newPermission->assignRole($moderator);
                        $newPermission->assignRole($user);
                        break;
                    case 'edit_comment':
                        $newPermission->assignRole($admin);
                        $newPermission->assignRole($moderator);
                        $newPermission->assignRole($user);
                        break;
                    case 'approve_comment':
                        $newPermission->assignRole($admin);
                        $newPermission->assignRole($moderator);
                        break;
                }
            }
        }
    }
}
