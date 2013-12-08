<?php
/**
 * Class Shortener
 *
 * Here we do actual work of shortening urls
 */
class Shortener
{
	private static $map = '123456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';

	/**
	 * Generate short hash for given URL.
	 *
	 * @param string $url
	 *
	 * @return string
	 */
	public static function shortenUrl($url)
	{
		$base = strlen(self::$map);
		$encoded = '';
		$num = time() + abs(crc32($url));

		while ($num >= $base) {
			$div = intval($num / $base);
			$mod = intval($num - ($base * $div));
			$encoded = self::$map[$mod] . $encoded;
			$num = $div;
		}

		if ($num) $encoded = self::$map[$num] . $encoded;

		return $encoded;
	}
}