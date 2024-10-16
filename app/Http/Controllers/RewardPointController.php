<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\bw_customer_reward_points;
use Yajra\DataTables\DataTables;


class RewardPointController extends Controller
{
    // here we write the logic about sql query
    public function index(Request $request)
    {
        // dd($request->all());
        $year = $request->input('year', '2020');
        $month = $request->input('month', '1');

        //yha pe data ko fetch karenge 

        // This Query Can be used to deisplay and fetch the as by month and year and after that we can concanicate it 
// CONCAT(YEAR(created_at), "-", LPAD(MONTH(created_at), 2, "0"), "-", LPAD(DAY(created_at), 2, "0")) AS date,
        $customers = bw_customer_reward_points::selectRaw('
DATE_FORMAT(created_at, "%Y-%m-%d") AS date,
        SUM(CASE WHEN reward_points = 0 THEN 1 ELSE 0 END) AS zero,
        SUM(CASE WHEN reward_points BETWEEN 1 AND 2 THEN 1 ELSE 0 END) AS one_to_two,
        SUM(CASE WHEN reward_points BETWEEN 3 AND 5 THEN 1 ELSE 0 END) AS three_to_five,
        SUM(CASE WHEN reward_points > 5 THEN 1 ELSE 0 END) AS five_plus
    ')
    ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            
            ->groupBy('date')
            ->orderBy('date', 'asc');
        // ->simplePaginate(15);

        $results = $customers->get();
        // dd($results);
        if ($request->ajax()) {
            return DataTables::of($results)
                ->make(true);
        }


        //
// pass the data  to the view
        return view('reward_points.index', compact("results"));

    }


}
