<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Facades\Storage;

class ExcelController extends Controller
{
    public function showForm()
    {
        return view('form');
    }

    public function saveToExcel(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
        ]);

        // Store data in an array
        $formData = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'request_ip' => $request->ip()
        ];

        $filePath = 'form_data.xlsx';
        $fullFilePath = Storage::path('public/' . $filePath);

        // Check if the file exists and attempt to open it
        if (Storage::exists('public/' . $filePath)) {
            $spreadsheet = IOFactory::load($fullFilePath);
            $sheet = $spreadsheet->getActiveSheet();
        } else {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->mergeCells('A1:G1');
            $sheet->setCellValue('A1', 'User Information');

            $sheet->setCellValue('A2', 'S.No');
            $sheet->setCellValue('B2', 'Name');
            $sheet->setCellValue('C2', 'Email');
            $sheet->setCellValue('D2', 'Phone');
            $sheet->setCellValue('E2', 'Created At');
            $sheet->setCellValue('F2', 'Updated At');
            $sheet->setCellValue('G2', 'Request IP');

            // Apply header formatting
            $headerStyle = [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['argb' => 'FFFFFFFF'], // white text
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF4F81BD'], // blue background
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'], // black border
                    ],
                ],
            ];
            $titleStyle = [
                'font' => [
                    'bold' => true,
                    'size' => 15,

                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,

                ],
            ];
            $borderStyle = [
                'allBorders' => Border::BORDER_THIN,
            ];

            $sheet->getStyle('A2:G2')->applyFromArray($headerStyle);
            $sheet->getStyle('A1:G1')->applyFromArray($titleStyle);
        }
        $lastRow = $sheet->getHighestRow() + 1;
        $serialNumber = $lastRow - 2;
        $sheet->setCellValue('A' . $lastRow, $serialNumber);
        $sheet->setCellValue('B' . $lastRow, $formData['name']);
        $sheet->setCellValue('C' . $lastRow, $formData['email']);
        $sheet->setCellValue('D' . $lastRow, $formData['phone']);
        $sheet->setCellValue('E' . $lastRow, $formData['created_at']);
        $sheet->setCellValue('F' . $lastRow, $formData['updated_at']);
        $sheet->setCellValue('G' . $lastRow, $formData['request_ip']);

        $dataStyle = [
            'font' => [
                'size' => 11,
                'color' => ['argb' => 'FF000000'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $sheet->getStyle('A' . $lastRow . ':G' . $lastRow)->applyFromArray($dataStyle);
        $sheet->getStyle('A1:G' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);

        try {
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save($fullFilePath);
        } catch (\Exception $e) {
            return back()->with('error', 'Error saving the file: ' . $e->getMessage());
        }

        // Provide a download link to the user
        $fileUrl = Storage::url($filePath);
        return back()->with('message', 'Data saved successfully. <a href="' . $fileUrl . '" target="_blank">Click here</a> to download the file.');
    }
}
