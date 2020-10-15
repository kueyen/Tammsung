<?php

namespace App\Http\Controllers\Line;

use App\Http\Controllers\Controller;
use App\Food;
use App\User;
use App\Table;
use App\Log;
use Illuminate\Http\Request;


class WebhookController extends Controller
{



    public function index(Request $request)
    {



        try {

            if ($request['events']) {
                if (sizeof($request['events']) > 0) {
                    foreach ($request['events'] as $event) {
                        $reply_token = $event['replyToken'];
                        $text = $event['message']['text'];
                        $userID = $event['source']['userId'];
                        // checkUser Register
                        $checkRegister = $this->checkRegister($userID);

                        if ($checkRegister != true) {

                            $this->sendRegisterImage($reply_token);
                            return false;
                        }


                        // LOGIC FROM REPLY MESSAGE //
                        if ($text == '#สั่งอาหาร') {
                            $this->sendFoodList($reply_token, $userID);
                        }


                        // END LOGIC FROM REPLY MESSAGE //


                    }
                }
            }


            return 'ok';
        } catch (\Exception $e) {
            $this->sendText($reply_token, $e->getMessage());
        }



        // Failed

    }

    function sendRegisterImage($reply_token)
    {
        $m = [
            [
                "type" => "flex",
                "altText" => "กรุณาทำการลงทะเบียนก่อนใช้งานฟังชันดังกล่าว",
                "contents" => array(
                    'type' => 'carousel',
                    'contents' => [array(
                        'type' => 'bubble',
                        'hero' =>
                        array(
                            'type' => 'image',
                            'url' => url('images/registerBanner.jpg'),
                            'size' => 'full',
                            'aspectRatio' => '30:26',
                            'aspectMode' => 'cover',
                            'action' =>
                            array(
                                'type' => 'uri',
                                'uri' => 'https://liff.line.me/1654579616-o707RL0n',
                            ),
                        ),
                    )]

                )
            ],
        ];
        $data = [
            'replyToken' => $reply_token,
            'messages' =>  $m
        ];

        $post_body = json_encode($data, JSON_UNESCAPED_UNICODE);

        $send_result = $this->send_reply_message($post_body);
    }

    function sendText($reply_token, $text)
    {
        $m = [
            [
                "type" => "text",
                "text" => $text
            ],
        ];
        $data = [
            'replyToken' => $reply_token,
            'messages' =>  $m
        ];

        $post_body = json_encode($data, JSON_UNESCAPED_UNICODE);

        $send_result = $this->send_reply_message($post_body);
    }
    public function sendFoodList($reply_token, $user_id)
    {

        $cards = [];

        $user = User::where('line_user_id', $user_id)->first();
        if (!$user->table_id) {
            // return $this->sendPleaseAddHome($reply_token, 'payment');
            $this->sendText($reply_token, 'กรุณาทำการสแกนโต๊ะก่อน https://line.me/R/nv/QRCodeReader');
        }
        $table = Table::find($user->table_id);
        try {
            $foods = Food::whereHas('category', function ($q) use ($table) {
                $q->where('restaurant_id', $table->restaurant_id);
            })->where('is_recommend', 1)->limit(10)->get();
        } catch (\Exception $e) {
            $this->sendText($reply_token, $e->getMessage());
        }




        foreach ($foods as $food) {
            array_push($cards, ['img' => 'https://www.twinpalmshotelsresorts.com/wp-content/uploads/2019/09/4024-1024x683.jpg', 'title' => $food->name, 'description' => $food->description, 'url' => 'https://google.co.th', 'price' => $food->price]);
        }



        $generateCard = $this->generateCard(
            $cards
        );



        $m = [
            [
                "type" => "flex",
                "altText" => "Foodlist",
                "contents" => array(
                    'type' => 'carousel',
                    'contents' => $generateCard,

                ),
            ],
        ];



        $data = [
            'replyToken' => $reply_token,
            'messages' => $m,
        ];




        $post_body = json_encode($data, JSON_UNESCAPED_UNICODE);





        $send_result = $this->send_reply_message($post_body);

        Log::create([
            'message' => 'Result: ' . $send_result . '\r\n',
            'reply_token' => $reply_token,
            // 'post_body' => $post_body,
        ]);

        return  $send_result;
    }



    function checkRegister($userId)
    {
        $find = User::where('line_user_id', $userId)->first();

        $isRegistered = $find ? true : false;

        return $isRegistered;
    }
    function send_reply_message($post_body)

    {

        $url = 'https://api.line.me/v2/bot/message/reply';
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




    public function generateCard($datas)
    {

        $cards = [];

        foreach ($datas as $data) {

            $set =
                [
                    "type" => "bubble",
                    "hero" => [
                        "type" => "image",
                        "size" => "full",
                        "aspectRatio" => "20:13",
                        "aspectMode" => "cover",
                        "url" => $data['img']
                    ],
                    "body" => [
                        "type" => "box",
                        "layout" => "vertical",
                        "spacing" => "sm",
                        "contents" => [
                            [
                                "type" => "text",
                                "text" => $data['title'],
                                "wrap" => true,
                                "weight" => "bold",
                                "size" => "xl"
                            ],
                            [
                                "type" => "text",
                                "text" => $data['description'],
                                "color" => "#909497"
                            ],
                            [
                                "type" => "box",
                                "layout" => "baseline",
                                "contents" => [
                                    [
                                        "type" => "text",
                                        "text" => $data['price'],
                                        "wrap" => true,
                                        "weight" => "bold",
                                        "size" => "xl",
                                        "flex" => 0,
                                        "color" => "#2ECC71"
                                    ],
                                    [
                                        "type" => "text",
                                        "text" => "฿",
                                        "wrap" => true,
                                        "weight" => "bold",
                                        "size" => "xl",
                                        "flex" => 0,
                                        "color" => "#2ECC71"
                                    ]
                                ]
                            ]
                        ]
                    ],
                    "footer" => [
                        "type" => "box",
                        "layout" => "vertical",
                        "spacing" => "sm",
                        "contents" => [
                            [
                                "type" => "button",
                                "style" => "primary",
                                "action" => [
                                    "type" => "uri",
                                    "label" => "เลือกอาหาร",
                                    "uri" => "https://linecorp.com"
                                ],
                                "color" => "#fc6011"
                            ],
                            [
                                "type" => "button",
                                "action" => [
                                    "type" => "uri",
                                    "label" => "เพิ่มลงรายการโปรด",
                                    "uri" => "https://linecorp.com"
                                ]
                            ]
                        ]
                    ]
                ];






            array_push($cards, $set);
        }

        return $cards;
    }
}