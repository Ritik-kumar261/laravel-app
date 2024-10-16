<?php

namespace App\Http\Controllers;
use Maatwebsite\Excel\Excel as ExcelFacade;
use App\Exports\ChartDataExport;
use DB;
use Illuminate\Http\Request;
use Log;
use Maatwebsite\Excel\Excel;
use Yajra\DataTables\DataTables;


class ChartController extends Controller
{


    public function index()
    {
        return view("login-signup.chartdashboard");

    }
    public function chartData(Request $request)
    {
        $categoryId = $request->input('category_id', 2);
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date', now()->subMonths(6)->format('Y-m-d'));

        // Log::info('Category ID: ' . $categoryId);
        // Log::info('Start Date: ' . $startDate);
        // Log::info('End Date: ' . $endDate);
        // Ensure startDate is after endDate
        if ($startDate < $endDate) {
            // Swap values if they are in the wrong order
            $temp = $startDate;
            $startDate = $endDate;
            $endDate = $temp;
        }

        switch ($categoryId) {
            case 1: // Meditation
                $query = DB::table('bw_data_session_analysis as bws')
                    ->leftJoin('bw_mind_sessions as ms', 'bws.session_id', '=', 'ms.id')
                    ->leftJoin('bw_mind_categories as mc', 'ms.category_id', '=', 'mc.id')
                    ->select(
                        'mc.title',
                        DB::raw('COUNT(*) as video_count'),
                        DB::raw("'{$startDate}' as start_date"),
                        DB::raw("'{$endDate}' as end_date")
                    )
                    ->where('bws.category_id', 1)
                    ->whereBetween('bws.created_at', [$endDate, $startDate])
                    ->groupBy('mc.title')
                    ->orderBy('video_count', 'DESC');
                  
                   
                break;

            case 2: // Fitness
                $query = DB::table('bw_data_session_analysis as bws')
                    ->leftJoin('bw_workout_sessions as ws', 'bws.session_id', '=', 'ws.id')
                    ->leftJoin('bw_video_categories as vc', 'ws.category_id', '=', 'vc.id')
                    ->select(
                        'vc.title',
                        DB::raw('COUNT(*) as video_count'),
                        DB::raw("'{$startDate}' as start_date"),
                        DB::raw("'{$endDate}' as end_date")
                    )
                    ->where('bws.category_id', 2)
                    ->where('bws.session_id', '>', 0)
                    ->where('bws.session_id', '<', 1000)
                    ->whereBetween('bws.created_at', [$endDate, $startDate])
                    ->groupBy('vc.title')
                    ->orderBy('video_count', 'DESC');
                    
            
                break;

            case 3: // Education
                $query = DB::table('bw_data_session_analysis as bws')
                    ->leftJoin('bw_curriculum_sessions as cs', 'bws.session_id', '=', 'cs.id')
                    ->select(
                        'cs.title',
                        DB::raw('COUNT(*) as video_count'),
                        DB::raw("'{$startDate}' as start_date"),
                        DB::raw("'{$endDate}' as end_date")
                    )
                    ->where('bws.category_id', 3) // Corrected category for education
                    ->where('bws.session_id', '>=', 1000)
                    ->whereBetween('bws.created_at', [$endDate, $startDate])
                    ->groupBy('cs.title')
                    ->orderBy('video_count', 'DESC');
                    
                  
                break;

            default:
            return response()->json([
                'data' => [],
            ]);
        }
        // if ($request->ajax()) {
        //     return DataTables::of($query)
                
        //         ->make(true);
        // }
        

        return response()->json([
            'category_id' => $categoryId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'data' => $query->get(),
        ]);
    }

    //download the excel 
    public function downloadData(Request $request, ExcelFacade $excel)
    {
        $categoryId = $request->input('category_id', 2);
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Ensure startDate is after endDate
        if ($startDate < $endDate) {
            // Swap values if they are in the wrong order
            $temp = $startDate;
            $startDate = $endDate;
            $endDate = $temp;

            // Log::info('Category ID: ' . $categoryId);
            // Log::info('Start Date: ' . $startDate);
            // Log::info('End Date: ' . $endDate);
        }
        // Fetch data based on category ID
        switch ($categoryId) {
            case 1: // Meditation
                $data = DB::table('bw_data_session_analysis as bws')
                    ->leftJoin('bw_mind_sessions as ms', 'bws.session_id', '=', 'ms.id')
                    ->leftJoin('bw_mind_categories as mc', 'ms.category_id', '=', 'mc.id')
                    ->select('mc.title', DB::raw('COUNT(*) as video_count'), DB::raw("'{$startDate}' as start_date"), DB::raw("'{$endDate}' as end_date"))
                    ->where('bws.category_id', 1)
                    ->whereBetween('bws.created_at', [$endDate, $startDate])
                    ->groupBy('mc.title')
                    ->orderBy('video_count', 'DESC')
                    ->limit(10)
                    ->get();
                break;

            case 2: // Fitness
                $data = DB::table('bw_data_session_analysis as bws')
                    ->leftJoin('bw_workout_sessions as ws', 'bws.session_id', '=', 'ws.id')
                    ->leftJoin('bw_video_categories as vc', 'ws.category_id', '=', 'vc.id')
                    ->select('vc.title', DB::raw('COUNT(*) as video_count'), DB::raw("'{$startDate}' as start_date"), DB::raw("'{$endDate}' as end_date"))
                    ->where('bws.category_id', 2)
                    ->where('bws.session_id', '>', 0)
                    ->where('bws.session_id', '<', 1000)
                    ->whereBetween('bws.created_at', [$endDate, $startDate])
                    ->groupBy('vc.title')
                    ->orderBy('video_count', 'DESC')
                    ->limit(10)
                    ->get();
                break;

            case 3: // Education
                $data = DB::table('bw_data_session_analysis as bws')
                    ->leftJoin('bw_curriculum_sessions as cs', 'bws.session_id', '=', 'cs.id')
                    ->select('cs.title', DB::raw('COUNT(*) as video_count'), DB::raw("'{$startDate}' as start_date"), DB::raw("'{$endDate}' as end_date"))
                    ->where('bws.category_id', 3)
                    ->where('bws.session_id', '>=', 1000)
                    ->whereBetween('bws.created_at', [$endDate, $startDate])
                    ->groupBy('cs.title')
                    ->orderBy('video_count', 'DESC')
                    ->limit(10)
                    ->get();
                break;

            default:
                return response()->json(['error' => 'Invalid category'], 400);
        }

        // Return the Excel file download
        return $excel->download(new ChartDataExport($data), 'chart_data.xlsx');
    }


}
