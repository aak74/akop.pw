<?
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Apps;

class AppsController extends Controller
{
	/*
	public function __construct()
	{
		parent::__construct();
	}
*/
	public function index()
	{
		return view('pages.apps', [
			'menuActive' => 'apps',
			'items' => Apps::all()
		] );
	}

	public function show($code)
	{
		// dd(Apps::where('code', $code)->firstOrFail());
		return view('pages.app', [
			'menuActive' => 'apps',
			'item' => Apps::where('code', $code)->firstOrFail(),
		] );
	}
}
?>