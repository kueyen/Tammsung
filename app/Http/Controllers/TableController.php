<?php

namespace App\Http\Controllers;

use App\Table;
use App\User;

use Illuminate\Http\Request;

class TableController extends Controller
{
    public function add(Request $request)
    {
        $table = Table::where('key', $request->key)->first();
        $user = User::where('line_user_id', $request->user_id)->first();

        $user->update([
            'table_id' =>
            $table->id
        ]);

        return [
            'status' => '200',
        ];
    }
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\table  $table
     * @return \Illuminate\Http\Response
     */
    public function show(table $table)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\table  $table
     * @return \Illuminate\Http\Response
     */
    public function edit(table $table)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\table  $table
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, table $table)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\table  $table
     * @return \Illuminate\Http\Response
     */
    public function destroy(table $table)
    {
        //
    }
}