<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class TaskFolderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $obj = \App\TaskFolder::where('USER_ID', 2)->get(); 
        return response()->json(
            $obj, 
            ( count($obj) ? 200 : 404)
        );
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
        $result = \App\TaskFolder::where('USER_ID', Request()['USER_ID'])->where('TASK_ID', Request()['TASK_ID'])->first(); 
        if ( count($result) ) {
            $result->FOLDER = Request()['FOLDER'];
            $result->save();
        } else {
            $result = \App\TaskFolder::create($request->input());
        }
        return response()->json($result, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (Request()['TASK_ID']) {
            $result = \App\TaskFolder::where('USER_ID', $id)->where('TASK_ID', Request()['TASK_ID'])->get(); 
        } else {
            $result = \App\TaskFolder::where('USER_ID', $id)->get(); 
        }

        return response()->json(
            $result, 
            ( count($result) ? 200 : 404)
        );
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
}
