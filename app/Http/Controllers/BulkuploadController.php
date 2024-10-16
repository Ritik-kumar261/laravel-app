<?php

namespace App\Http\Controllers;

use App\Models\VideoCategory;

use Illuminate\Http\File;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Validator;

class BulkuploadController extends Controller
{
    //
    public function index(Request $request)
    {

        $request->validate([
            'csvFile' => 'required|mimes:csv,txt|max:2048', // Validate file type and size
        ]);
        $file = $request->file('csvFile');
        $fileName = time() . '_' . $file->getClientOriginalName(); // Generate unique filename
        $filePath = $file->storeAs('uploads', $fileName, 'public');

        $file = $request->file('csvFile');
        $path = $file->getRealPath();
        $data = array_map('str_getcsv', file($path));
        var_dump($data);
        // dd($data);
        // Read CSV file into an array
        $header = array_shift($data); // Get the header
        $successCount = 0;
        $errorCount = 0;
        $errorRows = [];

        foreach ($data as $row) {

            $dataRow = array_combine($header, $row); // Combine header with row data

            $validator = Validator::make($dataRow, [
                'title' => 'required|unique:bw_video_categories,title',
                'image_url' => 'required|url',
                'order' => 'required|integer|min:0|max:9',
            ]);

            if ($validator->fails()) {
                $errorCount++;
                $errors = $validator->errors()->all();
                $dataRow['Error Message'] = implode(', ', $errors); // Add the error message to the row
                $errorRows[] = $dataRow; // Store rows with errors
                continue; // Skip to next row

            }
            VideoCategory::create([
                'title' => $dataRow['title'],
                'image_url' => $dataRow['image_url'],
                'order' => $dataRow['order'],
            ]);
            $successCount++;
            $successRows[] = $dataRow;
        }
    
        // Step 4: Write success data to CSV file
        if ($successCount > 0) {
            $successFileName = 'success_file_' . time() . '.csv';
    
            // Create and write the CSV using Laravel's Storage facade
            $csvData = implode(',', $header) . "\n"; // Add the header to CSV
            foreach ($successRows as $row) {
                $csvData .= implode(',', $row) . "\n"; // Add each row
            }
    
            // Store the CSV file in the 'public/successfile' directory
            Storage::disk('public')->put('successfile/' . $successFileName, $csvData);
        }
    
      

        // Create an error CSV file for download
        if ($errorCount > 0) {
            $errorFileName = 'error_rows_' . time() . '.csv';
            $errorFilePath = storage_path('app/public/faultFile/' . $errorFileName);
            $fileHandle = fopen($errorFilePath, 'w');
            fputcsv($fileHandle, $header); // Write the header

            foreach ($errorRows as $errorRow) {
                fputcsv($fileHandle, $errorRow); // Write each error row
            }

            fclose($fileHandle);
            $errorFileUrl = url(Storage::url('faultFile/' . $errorFileName));   
            session(['error_file' => $errorFileUrl]);
         

        }


        return redirect()->route('account.dashboard')->with([
            'success' => "$successCount rows successfully stored.",
            'error' => "$errorCount rows failed to store.",
            //'error_file' => isset($errorFileName) ? $errorFileName : null,
        ]);


    }


}
