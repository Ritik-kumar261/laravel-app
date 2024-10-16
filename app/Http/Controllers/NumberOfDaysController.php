<?php

namespace App\Http\Controllers;

use App\Exports\CustomerDataExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\LevelData;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;



class NumberOfDaysController extends Controller
{
    //here we define the logic reagarding CustomerID reach at certain level in how many days
    public function index(Request $request)
    {


        $power = $request->input('power', 30);
        $core = $request->input('core', 90);
        $casual = $request->input('casual', default: 120);
        $level = $request->input('level', 5);

        $levelID = LevelData::join('customer_data', 'level_data.CustomerID', '=', 'customer_data.CustomerID')
            ->where('level_data.LevelID', '=', $level)
            ->where('level_data.Start', '>', '2024-01-01')
            ->selectRaw('level_data.CustomerID, level_data.LevelID, level_data.Start, customer_data.Start_Date, customer_data.Username, customer_data.FirstName,
                  DATEDIFF( level_data.Start, customer_data.Start_Date) AS Days',
            )
            ->orderBy('customer_data.CustomerID');
        if ($request->ajax()) {
            return DataTables::of($levelID)
            
                ->make(true);


        }




        // here we make new function which find the how many customer take days to reach at certain level

        // this total count store the total value of data 
        $totalCount = LevelData::where('LevelID', $level)
            ->where('Start', '>', '2024-01-01')
            ->count();

        $percentage = LevelData::join('customer_data', 'level_data.CustomerID', '=', 'customer_data.CustomerID')
            ->where('LevelID', '=', $level)
            ->where('Start', '>', '2024-01-01')
            ->selectRaw('
            CASE
                WHEN DATEDIFF(level_data.Start, customer_data.Start_Date) <= ? THEN "< 30 days"
                WHEN DATEDIFF(level_data.Start, customer_data.Start_Date) > ? AND DATEDIFF(level_data.Start, customer_data.Start_Date) <= ? THEN "30-90 days"
                WHEN DATEDIFF(level_data.Start, customer_data.Start_Date) > ? AND DATEDIFF(level_data.Start, customer_data.Start_Date) <= ? THEN "91-120 days"
                ELSE "120+ days" 
            END AS DaysRange,
            COUNT(level_data.CustomerID) AS CustomerCount,
            (COUNT(level_data.CustomerID) / ?) * 100 AS Percentage',
                [$power, $power, $core, $core, $casual, $totalCount]
            )
            ->groupBy('DaysRange')
            ->get();

        // Access data directly from the $percentage collection
        $powerData = $percentage->where('DaysRange', '<=', '< 30 days')->first();
        $casualData = $percentage->where('DaysRange', '=', '30-90 days')->first();
        $coreData = $percentage->where('DaysRange', '=', '91-120 days')->first();
        $coldData = $percentage->where('DaysRange', '=', '120+ days')->first();


        return view('number-days.index', compact('levelID', 'percentage', 'powerData', 'casualData', 'coreData', 'coldData', 'power', 'core', 'casual', 'level'));
    }
    public function exportExcel(Request $request)
    {
        $request->validate([
            'power' => 'nullable|integer',
            'core' => 'nullable|integer',
            'casual' => 'nullable|integer',
            'level' => 'required|integer|min:1|max:8',
        ]);

        // $power = $request->input('power', 30);
        // $core = $request->input('core', 90);
        // $casual = $request->input('casual', 120);
        $level = $request->input('level', 5);

        $levelData = LevelData::join('customer_data', 'level_data.CustomerID', '=', 'customer_data.CustomerID')
            ->where('level_data.LevelID', '=', $level)
            ->where('level_data.Start', '>', '2024-01-01')
            ->selectRaw('level_data.CustomerID, level_data.LevelID, level_data.Start, customer_data.Start_Date, customer_data.Username, customer_data.FirstName,
                  DATEDIFF( level_data.Start, customer_data.Start_Date) AS Days',
            )
            ->orderBy('customer_data.CustomerID')
            ->get();
        $filename = 'customer_data_level_' . $level . '.xlsx';
        return Excel::download(new CustomerDataExport($levelData), $filename);
    }
}
