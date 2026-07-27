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
        
        // ✅ Headers
        $sheet->setCellValue('A1', 'Product Name');
        $sheet->setCellValue('B1', 'Variant Name');
        $sheet->setCellValue('C1', 'Color');
        $sheet->setCellValue('D1', 'Total Stock');
        $sheet->setCellValue('E1', 'Status');
        
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);
        $sheet->getStyle('A1:E1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('8B2452');
        $sheet->getStyle('A1:E1')->getFont()->getColor()->setRGB('FFFFFF');
        
        $row = 2;
        foreach ($this->lowStockItems as $item) {
            // ✅ 'remaining_qty' → 'total_stock' with fallback
            $stock = $item['total_stock'] ?? $item['remaining_qty'] ?? 0;
            $status = $item['status'] ?? ($stock <= 0 ? 'Out of Stock' : 'Low Stock');
            
            $sheet->setCellValue('A' . $row, $item['product_name'] ?? 'Unknown');
            $sheet->setCellValue('B' . $row, $item['variant_name'] ?? 'Default');
            $sheet->setCellValue('C' . $row, $item['color_name'] ?? '—');
            $sheet->setCellValue('D' . $row, $stock);
            $sheet->setCellValue('E' . $row, $status);
            
            if ($stock <= 2) {
                $sheet->getStyle('D' . $row)->getFont()->setBold(true)->getColor()->setRGB('DC2626');
            } elseif ($stock <= 5) {
                $sheet->getStyle('D' . $row)->getFont()->setBold(true)->getColor()->setRGB('F97316');
            }
            $row++;
        }
        
        foreach(range('A','E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        $tempPath = storage_path('app/temp/low_stock_' . time() . '.xlsx');
        if (!is_dir(dirname($tempPath))) {
            mkdir(dirname($tempPath), 0777, true);
        }
        
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempPath);
        
        return $tempPath;
        
    } catch (\Exception $e) {
        return null;
    }
}
}