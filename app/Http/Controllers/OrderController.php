<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BillDetail;

class OrderController extends Controller
{

    private $mainModel;

    public function __construct()
    {
        $this->mainModel = new BillDetail();
    }

    public function index(Request $request)
    {
        $showItem = $request->item ? $request->item : 10;
        $keyword = $request->q ? $request->q : '';
        $sortBy = $request->sortBy ? $request->sortBy : 'asc';


        $items = $this->mainModel::where(function ($q) use ($keyword) {
            // $q->orWhere('name', 'LIKE', "%$keyword%");
        })->where(function ($q) {
            $uid = request()->user()->restaurant_id;
            if ($uid) {
                $q->whereHas('food.category', function ($q) use ($uid) {
                    $q->where('restaurant_id', $uid);
                });
            }
        })->with(['food.category', 'bill.table'])->whereHas('food.category')->whereHas('bill.table')->whereHas('bill', function ($q) {
            $q->where('status', '!=', 0);
        })->whereStatus(1)->paginate($showItem);

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