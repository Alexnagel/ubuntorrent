<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEverything extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('added_torrents', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('show_name');
			$table->string('episode_title');
			$table->integer('season');
			$table->integer('episode');
			$table->date('date_added');
			$table->date('pub_date');
			$table->string('magnet');
			$table->boolean('processed');
			$table->timestamps();
		});

		Schema::create('tvdb_series_cache', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('imdbId');
			$table->integer('tvdb_id');
			$table->string('name');
			$table->string('poster');
			$table->string('status');
			$table->string('genres');
			$table->text('overview');
			$table->text('actors');
			$table->datetime('firstAired');
			$table->date('airsDayOfWeek');
			$table->time('airsTime');
			$table->string('rating');
			$table->integer('runtime');
			$table->string('network');
			$table->timestamps();
		});

		Schema::create('tvdb_episodes_cache', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('imdbId');
			$table->string('show_name');
			$table->string('episode_title');
			$table->integer('season');
			$table->integer('episode');
			$table->text('overview');
			$table->text('guest_stars');
			$table->datetime('firstAired');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('added_torrents');
		Schema::drop('tvdb_series_cache');
		Schema::drop('tvdb_episodes_cache');
	}

}
