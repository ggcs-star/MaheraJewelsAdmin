<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class LowStockAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $lowStockItems;
    public $threshold;
    public $offlineThreshold;

        public function __construct($lowStockItems, $threshold)

    {
        $this->lowStockItems = $lowStockItems;
        $this->threshold = $threshold;

    }

    public function build()
    {
        $excelPath = $this->generateExcel();
        
        $mail = $this->subject('Low Stock Alert - Action Required')
                    ->view('emails.low_stock_alert');
        
        if ($excelPath && file_exists($excelPath)) {
            $mail->attach($excelPath, [
                'as' => 'low_stock_report.xlsx',
                'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ]);
        }
        
        return $mail;
    }
    
   private function generateExcel()
{
    try {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setTitle('Low Stock Report');
        
        // ✅ HEADERS - EMAIL TEMPLATE KE HISAB SE
        $headers = [
            'A1' => 'Product Name',
            'B1' => 'Variant',
            'C1' => 'Color',
            'D1' => 'Color Hex',
            'E1' => 'Available Stock',
            'F1' => 'Status'
        ];
        
        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }
        
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
        $sheet->getStyle('A1:F1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('8B2452');
        $sheet->getStyle('A1:F1')->getFont()->getColor()->setRGB('FFFFFF');
        
        $row = 2;
        foreach ($this->lowStockItems as $item) {
            
            // ✅ EMAIL TEMPLATE KE HISAB SE DATA
            $productName = $item['product_name'] ?? 'Unknown';
            $variantName = $item['variant_name'] ?? 'Default';
            $colorName = $item['color_name'] ?? $item['color'] ?? '—';
            $colorHex = $item['color_hex'] ?? $item['color'] ?? '#FFFFFF';
            
            // ✅ AVAILABLE STOCK - EMAIL TEMPLATE MEIN YAHI USE HO RAHA HAI
            $availableStock = $item['available_stock'] ?? 
                              $item['final_stock'] ?? 
                              $item['remaining_qty'] ?? 
                              $item['quantity'] ?? 
                              $item['stock'] ?? 
                              0;
            
            $status = $item['status'] ?? ($availableStock <= 0 ? 'Out of Stock' : 'Low Stock');
            
            $sheet->setCellValue('A' . $row, $productName);
            $sheet->setCellValue('B' . $row, $variantName);
            $sheet->setCellValue('C' . $row, $colorName);
            $sheet->setCellValue('D' . $row, $colorHex);
            $sheet->setCellValue('E' . $row, $availableStock);
            $sheet->setCellValue('F' . $row, $status);
            
            // ✅ COLOR CODING
            if ($availableStock <= 0) {
                $sheet->getStyle('E' . $row)->getFont()->setBold(true)->getColor()->setRGB('DC2626');
                $sheet->getStyle('F' . $row)->getFont()->setBold(true)->getColor()->setRGB('DC2626');
            } elseif ($availableStock <= 5) {
                $sheet->getStyle('E' . $row)->getFont()->setBold(true)->getColor()->setRGB('F97316');
            }
            $row++;
        }
        
        foreach(range('A','F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // ✅ COLOR HEX COLUMN MEIN COLOR SHOW KARO
        $sheet->getStyle('D2:D' . ($row - 1))->getFill()->setFillType(Fill::FILL_SOLID);
        foreach ($this->lowStockItems as $index => $item) {
            $rowNum = $index + 2;
            $colorHex = $item['color_hex'] ?? $item['color'] ?? '#FFFFFF';
            $sheet->getStyle('D' . $rowNum)->getFill()->getStartColor()->setRGB(str_replace('#', '', $colorHex));
            $sheet->getStyle('D' . $rowNum)->getFont()->getColor()->setRGB('FFFFFF');
        }
        
        $tempPath = storage_path('app/temp/low_stock_' . time() . '.xlsx');
        if (!is_dir(dirname($tempPath))) {
            mkdir(dirname($tempPath), 0777, true);
        }
        
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempPath);
        
        return $tempPath;
        
    } catch (\Exception $e) {
        \Log::error('Excel generation failed: ' . $e->getMessage());
        return null;
    }
}
}