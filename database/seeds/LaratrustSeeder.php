<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Modules\Main\Models\Office;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Modules\ExternalOffice\Models\User;
use Modules\ExternalOffice\Models\Country;

class LaratrustSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return  void
     */
    public function run()
    {
        $this->command->info('Truncating User, Role and Permission tables');
        $this->truncateLaratrustTables();

        $config = config('laratrust_seeder.roles_structure');
        $mapPermission = collect(config('laratrust_seeder.permissions_map'));

        foreach ($config as $key => $modules) {

            // Create a new role
            $role = \App\Role::firstOrCreate([
                'name' => $key,
                'display_name' => ucwords(str_replace('_', ' ', $key)),
                'description' => ucwords(str_replace('_', ' ', $key))
            ]);
            $permissions = [];

            $this->command->info('Creating Role ' . strtoupper($key));

            // Reading role permission modules
            foreach ($modules as $module => $value) {

                foreach (explode(',', $value) as $p => $permSec) {
                    $permSection = explode('-', $permSec);
                    $perm = $permSection[0];
                    $display = $permSection[1];
                    $permissionValue = $mapPermission->get($perm);

                    $permissions[] = \App\Permission::firstOrCreate([
                        'name' => $module . '-' . $permissionValue,
                        'display_name' => $display,
                        'description' => $module,
                    ])->id;

                    $this->command->info('Creating Permission to ' . $permissionValue . ' for ' . $module);
                }
            }

            // Attach all permissions to the role
            $role->permissions()->sync($permissions);

            if (Config::get('laratrust_seeder.create_users')) {
                if ($key == 'super') {
                    $this->command->info("Creating '{$key}' user (super)");

                    $user = \App\User::create([
                        'name' => $key,
                        'username' => $key,
                        'password' => bcrypt('123456')
                    ]);

                    $user->attachRole($role);
                }

                if ($key == 'office') {
                    $this->command->info("Creating '{$key}' user (office)");

                    $officeUser = User::create([
                        'name' => 'office',
                        'username' => 'office',
                        'password' => bcrypt('123456'),
                    ]);

                    $office = Office::create([
                        'name' => 'office',
                        'status' => 1,
                        'country_id' => (Country::create(['name' => 'Canada']))->id,
                        'email' => 'test@mail.com',
                        'phone' => '0213456789',
                        'admin_id' => $officeUser->id,
                    ]);

                    $officeUser->update(['office_id' => $office->id]);

                    $officeUser->attachRole($role);
                }
            }
        }
    }

    /**
     * Truncates all the laratrust tables and the users table
     *
     * @return    void
     */
    public function truncateLaratrustTables()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('permission_role')->truncate();
        DB::table('permission_user')->truncate();
        DB::table('role_user')->truncate();
        if (Config::get('laratrust_seeder.truncate_tables')) {
            \App\Role::truncate();
            \App\Permission::truncate();
        }
        if (Config::get('laratrust_seeder.truncate_tables') && Config::get('laratrust_seeder.create_users')) {
            \App\User::truncate();
            User::truncate();
            Office::truncate();
        }
        Schema::enableForeignKeyConstraints();
    }
}
