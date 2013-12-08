<?php
/**
 * Class View
 *
 * Simple view class
 */
class View
{
	/**
	 * @var array
	 */
	private $tplVars = array();

	/**
	 * Assign template variable
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	public function assign($key, $value)
	{
		$key = trim(strval($key));
		if ($key)
		{
			$this->tplVars[$key] = $value;
		}
	}

	/**
	 * Render template
	 *
	 * @param string $tplPath
	 * @param array $tplVars
	 */
	public function display($tplPath, $tplVars = array())
	{
		if (file_exists($tplPath))
		{
			foreach ((array)$tplVars as $key => $value)
			{
				$this->assign($key, $value);
			}

			include $tplPath;
		}
	}
}