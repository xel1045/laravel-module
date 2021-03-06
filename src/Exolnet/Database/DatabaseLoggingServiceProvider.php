<?php namespace Exolnet\Database;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class DatabaseLoggingServiceProvider extends ServiceProvider
{
	public function register()
	{
		$log = new Logger('db');
		$log->pushHandler(new StreamHandler(storage_path() . '/logs/laravel-db.log'));

		DB::listen(function ($sql, $bindings, $time) use ($log) {
			$sql = str_replace(['%', '?'], ['%%', '%s'], $sql);
			$full_sql = vsprintf($sql, $bindings);
			//echo PHP_EOL.'- BEGIN QUERY -'.PHP_EOL.$full_sql.PHP_EOL.'- END QUERY -'.PHP_EOL;
			$log->addInfo($full_sql);
		});
	}
}
