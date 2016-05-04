<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\TaskFolder;
use App\User;
use App\Portal;

class TaskFolderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*
        $obj = TaskFolder::where('user_id', 1)->get();
        return response()->json(
            $obj,
            ( count($obj) ? 200 : 404)
        );
        */
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ( $userId = $this->getUserId(Request()['member_id'], Request()['user_id']) ) {
            $result = TaskFolder::firstOrCreate(['user_id' => $userId, 'task_id' => Request()['task_id']]);
            if ( count($result) ) {
                $result->folder = Request()['folder'];
                $result->save();
            } else {
                $result = TaskFolder::create($request->input());
            }

            return response()->json($result, 200);
        } else {
            return response()->json(
                'Missing parameters',
                400
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Request()['member_id']) {
            return response()->json(
                'Missing parameters',
                400
            );

        } else {
            if ( $userId = $this->getUserId(Request()['member_id'], $id) ) {
                // dd($userId);
                if (Request()['taskIds']) {
                    // Возвразщаем только информацию о тех задачах, которая есть в запросе
                    $result = TaskFolder::where('user_id', $userId)->whereIn('task_id', explode('+', Request()['taskIds']))->get();
                } else {
                    // $result = TaskFolder::where('user_id', $user->id)->get();
                    return response()->json(
                        'Missing parameters',
                        400
                    );
                }
            } else {
                return response()->json(
                    'Portal not found',
                    404
                );
            }

            return response()->json(
                $result,
                ( count($result) ? 200 : 404)
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // return
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getUserId($member_id, $id) {
        $portal = Portal::where('member_id', $member_id)->first();
        /* Если портала не существует, то отдадим пустой ответ */
        if ($portal === null) {
            return $false;
        } else {
            // Создает пользователя если его еще нет в нашей БД
            $user = User::firstOrCreate(['user_id' => $id, 'portal_id' => $portal->id]);
            return $user->id;
        }
    }

}
