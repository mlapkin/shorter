<?php
/**
 * Class Storage
 *
 * Reduced and simplified implementation of 'unified storage interface' idea.
 */
class Storage
{
	/**
	 * @var Redis
	 */
	private $driver = null;

	/**
	 * Open connection to storage
	 *
	 * @param array $params
	 *
	 * @return bool
	 */
	public function open(array $params)
	{
		if ($this->isOpen())
		{
			return true;
		}

		$this->driver = new Redis();
		try
		{
			$this->driver->connect($params['host'], $params['port'], $params['timeout']);
		}
		catch (RedisException $e)
		{
			$this->driver = null;
			return false;
		}

		return true;
	}

	/**
	 * Check if storage is open
	 *
	 * @return bool
	 */
	public function isOpen()
	{
		return $this->driver instanceof Redis;
	}

	/**
	 * Close storage
	 */
	public function close()
	{
		if ($this->isOpen())
		{
			$this->driver->close();
			$this->driver = null;
		}
	}

	/**
	 * Save data to storage
	 * Try to set-if-not-exist and if failed compare stored value with one to store.
	 * If they're equal then everything alright. Otherwise it seems we've collision issue.
	 *
	 * @param $key
	 * @param $value
	 *
	 * @return bool
	 */
	public function set($key, $value)
	{
		if (!$this->driver->setnx($key, $value))
		{
			return ($this->driver->get($key) == $value);
		}
		return true;
	}

	/**
	 * Get data from storage
	 *
	 * @param $key
	 *
	 * @return bool|null|string
	 */
	public function get($key)
	{
		return $this->isOpen()
			? $this->driver->get($key)
			: null;
	}
}