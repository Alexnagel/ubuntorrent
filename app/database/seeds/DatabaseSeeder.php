<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		Setting::create(array('key' => 'first_run', 'value' => 'true'));
		Setting::create(array('key' => 'torrents_added', 'value' => '0'));
	}

}