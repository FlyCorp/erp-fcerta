<?php
/**
 * Created by PhpStorm.
 * User: Railam Ribeiro
 * Date: 24/07/20
 * Time: 16:10
 */

namespace FlyCorp\ErpFCerta;

use Illuminate\Http\Request;
use FlyCorp\ErpFCerta\Entities\Client;
use FlyCorp\ErpFCerta\Entities\Response;

/**
 * Class Payments
 * @package FlyCorp\ErpFCerta
 */
class Payments
{
	/**
	 * @var Client
	 */
	private $erp;

	/**
	 * Payments constructor.
	 */
	function __construct()
	{
		$this->erp = new Client();
	}

	/**
	 * @param Request $request
	 * @return Response
	 */
	public function order(Request $request)
	{
		try {
			$request->validate([
				'branch_id' => 'required',
				'order' => 'required'
			]);

			return $this->erp->order($request);
		} catch (\Exception $e) {
			return (new Response())
				->setSuccess(false)
				->setMessage($e->getMessage());
		}
	}
}