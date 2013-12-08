<?php
/**
 * Class ShortenController
 *
 * Shorten URL/display UI/handle shortened URL
 */
class ShortenController
{
	/**
	 * Validate input and shorten URL
	 *
	 * @param array $params
	 */
	public function actionShort(array $params)
	{
		$result = false;
		$shortKey = '';
		$shortUrl = '';
		$message = 'Invalid URL';

		if (isset($params['url']) && ($url = trim($params['url'])) && preg_match('|^https?://\S+$|i', $url) && parse_url($url))
		{
			$shortKey = Shortener::shortenUrl($url);

			$storage = new Storage();
			$storage->open(include dirname(__FILE__) . '/../../../config/storage.php');
			for ($sliceLength = 2; $sliceLength <= strlen($shortKey); $sliceLength++)
			{
				$cutKey = substr($shortKey, 0, $sliceLength);
				if ($storage->set($cutKey, $url))
				{
					$result = true;
					$message = '';
					$shortUrl = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $cutKey;
					$shortKey = $cutKey;
					break;
				}
			}

			if (!$result)
			{
				$message = 'Unable to save shortened url';
			}
		}

		$response = json_encode(
			array(
				'result' => intval($result),
				'key' => $shortKey,
				'url' => $shortUrl,
				'message' => $message,
			)
		);

		header('content-type:application/json');
		exit($response);
	}

	/**
	 * Try to find URL by key and redirect client
	 *
	 * @param array $params
	 */
	public function actionRedirect(array $params)
	{
		if (isset($params['key']) && ($shortKey = trim($params['key'])))
		{
			$storage = new Storage();
			$storage->open(include dirname(__FILE__) . '/../../../config/storage.php');
			if ($shortUrl = $storage->get($shortKey))
			{
				header('HTTP/1.0 301 Moved Permanently');
				header('location:' . $shortUrl);
				exit;
			}
		}

		$this->actionDisplay(array('error' => 'Unknown short URL'));
	}

	/**
	 * Display UI
	 */
	public function actionDisplay(array $params = array())
	{
		$view = new View();
		$view->display(dirname(__FILE__) . '/../../view/index.php', $params);
	}
}