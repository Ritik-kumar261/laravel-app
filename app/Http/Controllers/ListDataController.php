<?php

namespace App\Http\Controllers;

use App\Models\ListData;
use Illuminate\Http\Request;
use Storage;
use Validator;

class ListDataController extends Controller
{
public function index(Request $request , $id){
    // $listData=ListData::all();
     //dd($list);
   $data=ListData::find($id);
   
    return view("list.list",compact('id','data'));
}
public function addPage(Request $request){
    return view('list.addlist');
}
public function addList(Request $request) {
    // Validation rules
    $validator = Validator::make($request->all(), [
        'i_con' => 'required|image|mimes:jpeg,png,jpg,|max:10240',
        'title' => 'required|string|max:255|unique:listdata',
       'value' => 'required|numeric|between:0,99999999.99',

    ]);

    // Check if validation fails
    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }
    $listdata=ListData::create([
        'i_con'=>"",
        'title' => $request->title,
        'value' => $request->value,
    ]);

    // Store the image
    $file = $request->file('i_con');
    $id=$listdata->id;
    $createdAt = now()->format('Y-m-d_H-i-s');
    $filename="{$id}-{$createdAt}.png";
    $iconPath = $file->storeAs( 'icons', $filename,'public');

   // Update the list entry with the full path
$listdata->update([
    'i_con' => $iconPath,
]);

// If you also want to update with just the filename later
$listdata->update([
    'i_con' => $filename,
]);
    

    return redirect()->back()->with('success', 'Successfully added the list.');
}
public function update(Request $request, $id) { 
   
    $validator = Validator::make($request->all(), [
         'i_con' => 'image|mimes:jpeg,png,jpg|max:10240',
        'title' => 'required|string|max:255|unique:listdata',
       'value' => 'required|numeric|between:0,99999999.99'
,
    ]);
    $listItem = ListData::findOrFail($id);
    if ($validator->fails()) {
        return redirect()->back()->withInput()->withErrors($validator);
    }
    if ($request->hasFile('i_con')) {
        // Delete old file if exists
        
        Storage::disk('public')->delete($listItem->i_con);
        
        // Store the new file
        $file = $request->file('i_con');
        $createdAt = now()->format('Y-m-d_H-i-s');
        $filename = "{$id}-{$createdAt}.png";
        $iconPath = $file->storeAs('icons', $filename, 'public');
          
        $listItem->update([
            'i_con' => $iconPath,
        ]);
        
        // If you also want to update with just the filename later
        $listItem->update([
            'i_con' => $filename,
        ]);
    }
    $listItem->title= $request->title;
    $listItem->value= $request->value;
    $listItem->save();
    // dd($id);
    return redirect()->route('account.dashboard')->with('success', 'Item updated successfully.');
}

}
