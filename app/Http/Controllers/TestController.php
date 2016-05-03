<?
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Portal;
use App\TaskFolder;
use App\User;

class TestController extends MainController
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		// $this->data = Portal::firstOrCreate(['name' => 'at2.bitrix24.ru']);
		/*
		$this->data = Portal::where(['name' => 'name', 'member_id' => "dasjdh jkdhjkashdjk l"])->get();
		if ( empty($this->data['items']) ) {
			echo "x";
			$portal = Portal::create(['name' => 'name', 'member_id' => "dasjdh jkdhjkashdjk l"]);
			$portal->save();


		}
		*/

		$this->data = User::get();
		// $this->data = TaskFolder::get();
		dd($this->data);

		// return view('pages.test', $this->data );
	}
}
?>