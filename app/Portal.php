<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Portal extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'member_id',
    ];
    protected $table = 'portals';

    /**
     * Регистрирует приложение в БД
     * @param  [string] $memberId [id портала в системе Bitrix24]
     * @param  [string] $domain   [доменное имя портала]
     * @param  [string] $appName  [код приложения]
     * @return [integer]          [id приложения данного портала, используется для получения настроек]
     */
    static public function register ($memberId, $domain)
    {
    	$data = Portal::firstOrCreate([
    		'member_id' => $memberId
    	]);

    	if ($data->name == '') {
    		$data->name = $domain;
    		$data->save();
    	}
        return $data->id;
/*
        $dataApp = Apps::where('code', $appName)->first();
        return AppPortal::register($dataApp->id, $data->id);
*/
    }

}
