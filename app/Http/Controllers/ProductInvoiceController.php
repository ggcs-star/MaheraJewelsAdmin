<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;

class ProductInvoiceController extends Controller
{
    public function view(Product $product)
    {
        $product->load([
            'variants',
            'supplier',
            'warehouse'
        ]);

        return view('products.invoice', compact('product'));
    }
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
