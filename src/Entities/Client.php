<?php
/**
 * Created by PhpStorm.
 * User: Railam Ribeiro
 * Date: 22/07/20
 * Time: 17:45
 */

namespace FlyCorp\ErpFCerta\Entities;

use Illuminate\Http\Request;

/**
 * Class Client
 * @package FlyCorp\ErpFCerta\Entities
 */
class Client extends Authentication
{
	/**
	 * @param Request $request
	 * @return Response
	 */
	public function order(Request $request)
	{
		return $this->execute(sprintf('unity/%s/order/%s',
			$request->get('branch_id'),
			$request->get('order')
		), 'GET');
	}
}