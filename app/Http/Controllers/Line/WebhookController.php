<?php

namespace App\Http\Controllers\Line;

use App\Http\Controllers\Controller;
use App\Food;
use App\User;
use App\Table;
use App\Log;
use App\Restaurant;
use Illuminate\Http\Request;
use Krit\LineBot;

class WebhookController extends Controller
{

    private $bot;



    public function __construct()
    {
        $this->bot = new LineBot('1RJVFAn7A09mJIUAj3sfgxTvzic1p51CXhP9Mwx8j1xRdjSWUwXTMmkq7TNgLIrcdMHPbjFcFCpDxeU3JQ40o8Vp9EEisJmZEOiK4m0sMBNczICWYZLOHGBG5F+xfYX+uFVrn1CPqjXfxXg8HzLdSgdB04t89/1O/w1cDnyilFU=');
    }

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


                        $this->bot->setReplyToken($reply_token);
                        $this->bot->setUser($userID);

                        $checkRegister = $this->checkRegister($userID);

                        if ($checkRegister != true) {

                            $this->sendRegisterImage();
                            return false;
                        }


                        // LOGIC FROM REPLY MESSAGE //
                        if ($text == '#สั่งอาหาร') {
                            $this->sendFoodList();
                        }



                        // END LOGIC FROM REPLY MESSAGE //


                    }
                }
            }


            return 'ok';
        } catch (\Exception $e) {
            return $request->all();
        }



        // Failed

    }

    function sendRegisterImage()
    {
        return $this->bot
            ->addImageURI(
                'กรุณาทำการลงทะเบียนก่อนใช้งานฟังชันดังกล่าว',
                url('images/registerBanner.jpg'),
                'https://liff.line.me/1654579616-o707RL0n',
                30,
                26
            )
            ->reply();
    }


    function sendScanTableImage()
    {
        return $this->bot
            ->addImageScanQR(
                'กรุณาทำการสแกน Qr Code ของโต๊ะอาหาร ก่อนใช้งานฟังชันดังกล่าว',
                url('images/qrcode.jpg'),
                30,
                26
            )
            ->reply();
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
    public function sendFoodList()
    {

        $cards = [];

        $user = $this->bot->getUser();
        if (!$user->table_id) {
            // return $this->sendPleaseAddHome($reply_token, 'payment');
            $this->sendScanTableImage();
        }
        $table = Table::find($user->table_id);
        $restaurant = Restaurant::whereId($table->restaurant_id)->withCount('foods')->first();
        try {
            $foods = Food::whereHas('category', function ($q) use ($table) {
                $q->where('restaurant_id', $table->restaurant_id);
            })->where('is_recommend', 1)->limit(4)->get();
        } catch (\Exception $e) {
            // $this->sendText($reply_token, $e->getMessage());
        }




        foreach ($foods as $food) {
            array_push($cards, ['res_name' => $restaurant->name, 'img' => url($food->image_url), 'title' => $food->name, 'description' => $food->description, 'url' => "https://liff.line.me/1654579616-vejGe5jz?show={$food->id}", 'price' => $food->price]);
        }



        $generateCard = $this->generateCard(
            $cards
        );

        array_push($generateCard,  [
            "type" => "bubble",
            "header" => [
                "type" => "box",
                "layout" => "vertical",
                "contents" => [],
                "backgroundColor" => "#fc6011"
            ],
            "body" => [
                "type" => "box",
                "layout" => "vertical",
                "spacing" => "xxl",
                "action" => [
                    "type" => "uri",
                    "uri" => "https://liff.line.me/1654579616-vejGe5jz?id={$table->restaurant_id}"
                ],
                "contents" => [
                    [
                        "type" => "text",
                        "text" => "รายการอาหารอื่นๆ",
                        "size" => "xl",
                        "weight" => "bold",
                        "color" => "#ffffff",
                        "align" => "center"
                    ],
                    [
                        "type" => "box",
                        "layout" => "vertical",
                        "spacing" => "sm",
                        "contents" => [
                            [
                                "type" => "text",
                                "text" => "{$restaurant->foods_count}",
                                "color" => "#ffffff",
                                "size" => "5xl",
                                "align" => "center"
                            ]
                        ]
                    ],
                    [
                        "type" => "box",
                        "layout" => "vertical",
                        "contents" => [
                            [
                                "type" => "text",
                                "text" => "รายการ",
                                "align" => "center",
                                "color" => "#ffffff"
                            ]
                        ]
                    ]
                ],
                "backgroundColor" => "#fc6011"
            ],
            "footer" => [
                "type" => "box",
                "layout" => "vertical",
                "contents" => [
                    [
                        "type" => "spacer",
                        "size" => "xxl"
                    ],
                    [
                        "type" => "button",
                        "style" => "secondary",
                        "color" => "#ffffff",
                        "action" => [
                            "type" => "uri",
                            "label" => "กดเพื่อดูรายการอาหารทั้งหมด",
                            "uri" => "https://liff.line.me/1654579616-vejGe5jz?id={$table->restaurant_id}"
                        ]
                    ]
                ],
                "backgroundColor" => "#fc6011"
            ]
        ]);


        return  $this->bot->addCarousel('กรุณาเลือกรายการเพื่อสั่งอาหาร', $generateCard)->reply();
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
                                "type" => "text",
                                "text" => 'ร้าน : ' . $data['res_name'],
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
                                    "uri" => $data['url']
                                ],
                                "color" => "#fc6011"
                            ],
                            // [
                            //     "type" => "button",
                            //     "action" => [
                            //         "type" => "uri",
                            //         "label" => "เพิ่มลงรายการโปรด",
                            //         "uri" => "https://linecorp.com"
                            //     ]
                            // ]
                        ]
                    ]
                ];






            array_push($cards, $set);
        }


        return $cards;
    }
}