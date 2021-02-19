<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Krit\LineBot;

class SyncAccountController extends Controller
{
    private $mainModel;
    private $bot;


    public function __construct()
    {
        $this->bot = new LineBot('1RJVFAn7A09mJIUAj3sfgxTvzic1p51CXhP9Mwx8j1xRdjSWUwXTMmkq7TNgLIrcdMHPbjFcFCpDxeU3JQ40o8Vp9EEisJmZEOiK4m0sMBNczICWYZLOHGBG5F+xfYX+uFVrn1CPqjXfxXg8HzLdSgdB04t89/1O/w1cDnyilFU=');

        $this->mainModel = new User();
    }

    public function index(Request $request)
    {
        $showItem = $request->item ? $request->item : 10;
        $keyword = $request->q ? $request->q : '';
        $sortBy = $request->sortBy ? $request->sortBy : 'desc';


        $items = $this->mainModel::where(function ($q) use ($keyword) {
            $q->orWhere('first_name', 'LIKE', "%$keyword%");
            $q->orWhere('last_name', 'LIKE', "%$keyword%");
            $q->orWhere('email', 'LIKE', "%$keyword%");
            $q->orWhere('tel', 'LIKE', "%$keyword%");
        })->with('restaurant')->orderBy('created_at', $sortBy)->paginate($showItem);

        return [
            "items" =>
            $items
        ];
    }

    public function store(Request $request)
    {
        $item = $this->mainModel::create($request->all());

        if (!$item) {
            return response()->json([
                "message" => 'create failed'
            ], 400);
        }
        return ["message" => 'success'];
    }


    public function show($id)
    {
        $show = $this->mainModel::find($id);
        $show->category;
        return [
            "result" => $show
        ];
    }


    public function update($id, Request $request)
    {
        $item = $this->mainModel::find($id);

        $password =
            rand(1000, 9999);;


        $req = $request->all();

        $req['password'] = bcrypt($password);

        $update = $item->update($req);
        $itemNew = $this->mainModel::find($id);


        $item->restaurant;


        if ($item->line_user_id) {
            if ($item->restaurant_id) {
                $this->bot->setUser($item->line_user_id);
                $gets = $this->bot->addText("ระบบได้ทำการผูกบัญชีของคุณ\nเข้ากับร้าน{$item->restaurant->name}เรียบร้อยแล้ว \nสามารถเข้าใช้งานระบบได้ที่\nhttps://taamsung.com/login \nemail: {$item->email} password: {$password}")->push();
            }
        }


        if (!$update) {
            return response()->json([
                "message" => 'update failed'
            ], 400);
        }

        return $gets;
    }

    public function destroy($id)
    {
        $item = $this->mainModel::find($id);
        $item->delete();
        return 'ok';
    }
}