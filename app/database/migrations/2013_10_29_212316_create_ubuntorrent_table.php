<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUbuntorrentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('settings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('key');
			$table->string('value');
			$table->timestamps();
		});

		Schema::create('recent_torrents', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('title');
			$table->date('date_added');
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

		Schema::create('cache', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('type');
			$table->integer('series_id')->nullable();
			$table->integer('movies_id')->nullable();
			$table->timestamps();

			$table->foreign('series_id')->references('id')->on('tvdb_series_cache');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('settings');
		Schema::drop('recent_torrents');
		Schema::drop('cache');
		Schema::drop('tvdb_series_cache');
	}

}
