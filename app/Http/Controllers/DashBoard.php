<?php

namespace App\Http\Controllers;

use App\Models\ListData;
use Auth;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DashBoard extends Controller
{
    public function updatePage(){
        return view('login-signup.updatepage');
    }
    public function index(Request $request){
        $listData=ListData::all();

        if ($request->ajax()) {
            $data = ListData::query()->select('id', 'title', 'value', 'status', 'i_con'); // Include ID and other fields
           
           
            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('update.list', $row->id) . '" class="btn btn-primary btn-sm">Edit</a>';
                })
                ->addColumn('icon', function ($row) {
                    return '<img src="' . asset('storage/icons/' . $row->i_con) . '" alt="Icon" style="max-width: 40px;">';
                })
               
                // ->filter(function ($query) use ($request) {
                //     // Apply search filters based on user input
                //     $searchKeyword = $request->input('search.value');
                //     if (strlen($searchKeyword) >= 3) {
                //         // Search only if the keyword has at least 3 characters
                //         $query->where('title', 'like', "%$searchKeyword%");
                //     } 
                    
                // })
               
                ->rawColumns(['action', 'icon','status'])
                // ->setTotalRecords(10)
                ->make(true);
        }

    

        
        return view("login-signup.dashboard",compact('listData'));
    }
    public function statusChange(Request $request, $id) {
    
        $data = ListData::find($id);
        if (!$data) {
            return response()->json(['success' => false, 'message' => 'Data not found'], 404);
        }
    
        // Update the status
        $data->status = $request->input('status'); // Update with the passed status
        $data->save();
    
        return response()->json(['success' => true]);
    }
    

   
  
    
}
