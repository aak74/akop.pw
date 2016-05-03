<?
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Portal;

class PortalController extends MainController
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$result = Portal::get();
		return response()->json(
		    $result,
		    ( count($result) ? 200 : 404)
		);
	}
}
?>