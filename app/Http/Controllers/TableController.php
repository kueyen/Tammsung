<?php

namespace App\Http\Controllers;

use App\Table;
use App\User;

use Illuminate\Support\Str;
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




    public function kick($id, Request $request)
    {

        $table = $this->mainModel->find($id);

        // ลบ active โต๊ะของ user
        $userRemoveTable = User::where('table_id', $table->id)->update([
            'table_id' => null
        ]);

        $table->update([
            'key' => (string) Str::uuid()
        ]);

        return 'ok';
    }


    private $mainModel;

    public function __construct()
    {
        $this->mainModel = new Table();
    }



    public function index(Request $request)
    {
        $showItem = $request->item ? $request->item : 10;
        $keyword = $request->q ? $request->q : '';
        $sortBy = $request->sortBy ? $request->sortBy : 'desc';


        $items = $this->mainModel::where(function ($q) use ($keyword) {
            $q->orWhere('name', 'LIKE', "%$keyword%");
        })->where(function ($q) {
            $uid = request()->user()->restaurant_id;
            if ($uid) {
                $q->where('restaurant_id', $uid);
            }
        })->orderBy('created_at', $sortBy)->with('latest_bills')->paginate($showItem);

        return [
            "items" =>
            $items
        ];
    }

    public function store(Request $request)
    {
        $uid = request()->user()->restaurant_id;
        $req = $request->all();
        $req['restaurant_id'] = $uid;
        $req['key'] = (string) Str::uuid();

        $item = $this->mainModel::create($req);

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

        $update = $item->update($request->all());


        if (!$update) {
            return response()->json([
                "message" => 'update failed'
            ], 400);
        }

        return 'ok';
    }

    public function destroy($id)
    {
        $item = $this->mainModel::find($id);
        $item->delete();
        return 'ok';
    }
}