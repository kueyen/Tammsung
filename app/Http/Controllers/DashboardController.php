<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Bill;
use App\BillDetail;
use App\Food;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $restaurant_id = $user->restaurant_id;
        $totalBill = Bill::whereHas('table', function ($q) use ($restaurant_id) {
            $q->where('restaurant_id', $restaurant_id);
        })->count();

        $totalBillPriceToday = BillDetail::whereHas('bill', function ($q) use ($restaurant_id) {
            $q->whereHas('table', function ($q) use ($restaurant_id) {
                $q->where('restaurant_id', $restaurant_id);
            });
        })->whereDate('created_at', Carbon::now())->sum('price_sum');

        $totalBillPriceMonth = BillDetail::whereHas('bill', function ($q) use ($restaurant_id) {
            $q->whereHas('table', function ($q) use ($restaurant_id) {
                $q->where('restaurant_id', $restaurant_id);
            });
        })->whereMonth('created_at', Carbon::now())->sum('price_sum');

        $totalBillPriceAllTime = BillDetail::whereHas('bill', function ($q) use ($restaurant_id) {
            $q->whereHas('table', function ($q) use ($restaurant_id) {
                $q->where('restaurant_id', $restaurant_id);
            });
        })->sum('price_sum');

        $foodMax = Food::withCount('bill_details')->whereHas('category', function ($q) use ($restaurant_id) {
            $q->where('restaurant_id', $restaurant_id);
        })->get();


        return [
            "restaurant_id" => $restaurant_id,
            "totalBill" => $totalBill,
            "totalBillPriceToday" =>  $totalBillPriceToday,
            "totalBillPriceMonth" => $totalBillPriceMonth,
            "totalBillPriceAllTime" => $totalBillPriceAllTime,
            "foodMax" => $foodMax,
            "carbon" => Carbon::now()
        ];
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
        //
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