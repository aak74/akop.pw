<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class AppPortal extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'app_id', 'portal_id', 'settings'
    ];
    protected $table = 'app_portals';

    static public function register ($params)
    {
    	$portalId = Portal::register(
			$params['member_id'],
			$params['domain']
		);

		$appId = Apps::where('code', $params['app_code'])->first()->id;

		// return AppPortal::register($dataApp->id, $data->id);



    	// $memberId, $domain, $appName
    	// $appId, $portalId
    	// dd([$appId, $portalId]);
    	$data = self::firstOrCreate([
    		'app_id' => $appId,
    		'portal_id' => $portalId
    	]);
    	$data->version = self::getVersion($data->settings);
    	return $data;
    }

    static public function getVersion ($settings)
    {
    	if ( empty($settings) ) {
    		$result = 0;
    	} else {
    		$settings = json_decode($settings);
    		$result = $settings->version;
    	}
    	return $result;
    }

    static public function getVersionById ($id)
    {
    	$data = self::find($id);
    	return self::getVersion($data->settings);
    }
}
