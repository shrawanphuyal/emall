<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		// $this->call(UsersTableSeeder::class);
		DB::table('users')->insert([
			'name'       => 'Ashish Dhamala',
			'email'      => 'ashish@gmail.com',
			'password'   => bcrypt('admin1'),
			'address'    => 'Anamnagar, Kathmandu',
			'phone'      => '9876547653',
			'dob'        => \Carbon\Carbon::now(),
			'verified'   => 1,
			'auth_token' => str_random(250),
			'created_at' => \Carbon\Carbon::now(),
			'updated_at' => \Carbon\Carbon::now(),
		]);

		$roles = ['admin', 'normal', 'vendor'];
		foreach ($roles as $role) {
			DB::table('roles')->insert([
				'name'       => $role,
				'created_at' => \Carbon\Carbon::now(),
				'updated_at' => \Carbon\Carbon::now(),
			]);
		}

		// role user
		DB::table('role_user')->insert([
			'user_id'    => 1,
			'role_id'    => 1,
			'created_at' => \Carbon\Carbon::now(),
			'updated_at' => \Carbon\Carbon::now(),
		]);

		DB::table('companies')->insert([
			'name'             => config('app.name'),
			'email'            => 'ashish@gmail.com',
			'established_date' => date('Y-m-d h:i:s'),
			'address'          => 'Anamnagar, Kathmandu',
			'phone'            => '9843639987',
			'created_at'       => \Carbon\Carbon::now(),
			'updated_at'       => \Carbon\Carbon::now(),
		]);
	}
}
