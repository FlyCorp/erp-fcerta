<?php
/**
 * Created by PhpStorm.
 * User: Railam Ribeiro
 * Date: 22/07/20
 * Time: 17:45
 */

namespace FlyCorp\ErpFCerta\Entities;

use App\Modules\Payment\Constants\PaymentIntegrations;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * Class Authentication
 * @package FlyCorp\ErpFCerta\Entities
 */
class Authentication
{
	/**
	 * @var Client
	 */
	private $httpClient;

	/**
	 * Authentication constructor.
	 */
	public function __construct()
	{
		$this->httpClient = new Client();
	}

	/**
	 * @param $endpoint
	 * @param string $method
	 * @param array $parameters
	 * @param bool $isMultipart
	 * @return Response
	 */
	protected function execute($endpoint, $method = 'GET', $parameters = [], $isMultipart = false)
	{
		try {
			$endpoint = sprintf("%s/%s",
				config(sprintf('payment.integrations.%s.endpoint', PaymentIntegrations::ERP_FC_CERTA)),
				$endpoint
			);
			$content = $this->makeContent($parameters, $isMultipart);

			switch ($method) {
				default:
					$response = $this->httpClient->request('GET', $endpoint, $content);
					break;
				case 'POST':
					$response = $this->httpClient->request('POST', $endpoint, $content);
					break;
				case 'PUT':
					$response = $this->httpClient->request('PUT', $endpoint, $content);
					break;
				case 'PATCH':
					$response = $this->httpClient->request('PATCH', $endpoint, $content);
					break;
			}

			$response = json_decode($response->getBody());

			if (!empty($response->Error)) {
				throw new Exception($response->Error);
			}

			return (new Response)
				->setSuccess(true)
				->setData($response);
		} catch (RequestException $e) {
			return (new Response)
				->setSuccess(false)
				->setMessage($e->getMessage());
		} catch (Exception $e) {
			return (new Response)
				->setSuccess(false)
				->setMessage($e->getMessage());
		}
	}

	/**
	 * @param $parameters
	 * @param $isMultipart
	 * @return array
	 */
	private function makeContent($parameters, $isMultipart)
	{
		if ($isMultipart) {
			return [
				"headers" => [
					'x-token' => config(sprintf('payment.integrations.%s.x_token', PaymentIntegrations::ERP_FC_CERTA)),
					'y-token' => config(sprintf('payment.integrations.%s.y_token', PaymentIntegrations::ERP_FC_CERTA)),
				],
				'multipart' => $this->hasStringKeys($parameters) ? [$parameters] : $parameters
			];
		}

		return [
			"headers" => [
				'Content-Type' => 'application/json',
				'x-token' => config(sprintf('payment.integrations.%s.x_token', PaymentIntegrations::ERP_FC_CERTA)),
				'y-token' => config(sprintf('payment.integrations.%s.y_token', PaymentIntegrations::ERP_FC_CERTA)),
			],
			'body' => !empty($parameters) ? json_encode($parameters) : ''
		];
	}

	/**
	 * @param array $data
	 * @return bool
	 */
	private function hasStringKeys(array $data)
	{
		return count(array_filter(array_keys($data), 'is_string')) > 0;
	}
}