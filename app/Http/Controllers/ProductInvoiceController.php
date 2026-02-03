<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;

class ProductInvoiceController extends Controller
{
    // SHOW INVOICE (SCREEN)
    public function view(Product $product)
    {
        $product->load([
            'variants',
            'supplier',
            'warehouse'
        ]);

        return view('products.invoice', compact('product'));
    }

    // DOWNLOAD INVOICE (PDF)
    public function download(Product $product)
    {
        $product->load([
            'variants',
            'supplier',
            'warehouse'
        ]);

        $pdf = Pdf::loadView('products.invoice-pdf', compact('product'));

        return $pdf->download(
            'invoice-product-'.$product->id.'.pdf'
        );
    }
}
