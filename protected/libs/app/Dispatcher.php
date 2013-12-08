<?php
/**
 * Class Dispatcher
 *
 * Simple dispatcher
 */
class Dispatcher
{
	public static function dispatch()
	{
		$controller = new ShortenController();
		if ('POST' == $_SERVER['REQUEST_METHOD'])
		{
			$controller->actionShort($_POST);
		}
		else
		{
			if (isset($_GET['key']))
			{
				$controller->actionRedirect($_GET);
			}
			else
			{
				$controller->actionDisplay();
			}
		}
	}
}