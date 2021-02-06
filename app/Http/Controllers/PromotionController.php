<?php

namespace App\Http\Controllers;

use App\Promotion;
use Illuminate\Http\Request;
use App\User;

class PromotionController extends Controller
{
    private $mainModel;

    public function __construct()
    {
        $this->mainModel = new Promotion();
    }

    public function index(Request $request)
    {
        $showItem = $request->item ? $request->item : 10;
        $keyword = $request->q ? $request->q : '';
        $sortBy = $request->sortBy ? $request->sortBy : 'desc';


        $items = $this->mainModel::where(function ($q) use ($keyword) {
            $q->orWhere('text', 'LIKE', "%$keyword%");
        })->where(function ($q) {
            $uid = request()->user()->restaurant_id;
            if ($uid) {
                $q->where('restaurant_id', $uid);
            }
        })->orderBy('created_at', $sortBy)->paginate($showItem);

        return [
            "items" =>
            $items
        ];
    }

    public function store(Request $request)
    {

        $req = $request->all();
        $user = $request->user();


        $req['restaurant_id'] = $user->restaurant_id;
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

    public function push($id, Request $request)
    {
        $item = $this->mainModel::find($id);

        $update = $item->update([
            "approve_at" => \Carbon\Carbon::now(),
        ]);


        $user_ids = User::whereNotNull('line_user_id')
            ->whereIn('id', [1])
            ->get()->pluck('line_user_id');

        $data = [
            "to" => $user_ids,
            "messages" => []
        ];

        if ($item->text) {
            array_push($data['messages'], [
                "type" => "text",
                "text" => $item->text
            ]);
        }


        if ($item->image_url) {
            array_push($data['messages'], [
                "type" => "image",
                "originalContentUrl" => url($item->image_url),
                "previewImageUrl" => url($item->image_url),
            ]);
        }


        $post_body = json_encode($data, JSON_UNESCAPED_UNICODE);


        $send_result = $this->send_push_multiMessage($post_body);

        return $send_result;
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

    public function send_push_multiMessage($post_body)
    {

        $url = 'https://api.line.me/v2/bot/message/multicast';
        $accssToken = '1RJVFAn7A09mJIUAj3sfgxTvzic1p51CXhP9Mwx8j1xRdjSWUwXTMmkq7TNgLIrcdMHPbjFcFCpDxeU3JQ40o8Vp9EEisJmZEOiK4m0sMBNczICWYZLOHGBG5F+xfYX+uFVrn1CPqjXfxXg8HzLdSgdB04t89/1O/w1cDnyilFU='; // Access Token ค่าที่เราสร้างขึ้น
        $post_header = array('Content-Type: application/json', 'Authorization: Bearer ' . $accssToken);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $post_header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}