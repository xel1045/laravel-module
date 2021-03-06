<?php namespace Exolnet\Core;

class EnvironmentDetector
{
	private static $environments = [
		'development.exolnet.com' => 'local',
		'staging.exolnet.com'     => 'staging',
		'testing.exolnet.com'     => 'staging',
		'qa.exolnet.com'          => 'staging',
	];

	/**
	 * @param $environments
	 * @return callable
	 */
	public static function detect($environments)
	{
		// TODO: Do not allow $is_behat = true in production environment
		$is_behat = self::is_remote_test();
		if ($is_behat) {
			return function () {
				return 'test';
			};
		}

		if (isset($_SERVER['HTTP_HOST'])) {
			$host = $_SERVER['HTTP_HOST'];

			if (array_key_exists($host, self::$environments)) {
				return function () use ($host) {
					return self::$environments[$host];
				};
			}
		}

		return $environments;
	}

	/**
	 * Add environements to host detector.
	 *
	 * @param array $environments
	 */
	public static function addEnvironments(array $environments)
	{
		static::$environments = $environments + static::$environments;
	}

	/**
	 * @return bool
	 */
	public static function is_remote_test()
	{
		return array_get($_COOKIE, 'test-env') === 'true';
	}
}
