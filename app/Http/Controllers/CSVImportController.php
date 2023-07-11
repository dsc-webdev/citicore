<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class CSVImportController extends Controller
{
    public function import(Request $request)
    {
        if ($request->isMethod('post')) {
            // Get the uploaded files
            $files = $request->file('csv_files');

            foreach ($files as $file) {
                // Generate a unique file name or use the original file name
                $fileName = uniqid() . '.' . $file->getClientOriginalExtension();

                // Move the file to a desired location
                $file->storeAs('csv_files', $fileName);

                // Open the CSV file and read its contents
                $csv = Reader::createFromPath(storage_path('app/csv_files/' . $fileName), 'r');

                // Skip the header row
                $headerSkipped = false;
                
                // Process each row of the CSV file
                foreach ($csv as $row) {
                    // Skip the header row
                    if (!$headerSkipped) {
                        $headerSkipped = true;
                        continue;
                    }

                    // Extract the relevant data from each row
                    $data = [
                        'name' => $row[0],
                        'email' => $row[1],
                        'password' => $row[2],
                        // Add more columns as needed
                    ];

                    // Store the data into your MSSQL database
                    DB::connection('sqlsrv')->table('csv_data')->insert($data);
                }
            }

            // Redirect or display a success message
            return redirect()->back()->with('success', 'CSV files imported successfully.');
        }

        // Handle GET request for displaying the form
        return view('import-csv');
    }

    public function generateCSV()
    {
        for ($i = 1; $i <= 744; $i++) {
            $csvData = [
                ['name', 'email', 'password'],
                ['John Doe'.$i, 'john'.$i.'@example.com', 'secret'.$i.'123'],
                ['Jane Smith'.$i, 'jane'.$i.'@example.com', 'password'.$i.'456'],
                // Add more rows as needed with unique data
            ];

            $csvFileName = 'test' . $i . '.csv';

            $csvFile = fopen(storage_path('app/csv_files/' . $csvFileName), 'w');

            foreach ($csvData as $data) {
                fputcsv($csvFile, $data);
            }

            fclose($csvFile);
        }

        return 'CSV files generated successfully.';
    }

}