<?
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\TaskFolder;

class IndexController extends MainController
{
	public function __construct()
	{
		parent::__construct();
	}
	public function index()
	{
		// dd("text");
		$this->data = TaskFolder::get();
		return view('pages.index', $this->data );
	}
}
?>