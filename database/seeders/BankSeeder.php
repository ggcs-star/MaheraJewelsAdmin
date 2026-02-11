<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bank;

class BankSeeder extends Seeder
{
    public function run(): void
    {
        $banks = [
            [
                'name'   => 'HDFC Bank',
                'code'   => 'HDFC',
                'status' => 1,
            ],
            [
                'name'   => 'ICICI Bank',
                'code'   => 'ICICI',
                'status' => 1,
            ],
            [
                'name'   => 'State Bank of India',
                'code'   => 'SBI',
                'status' => 1,
            ],
            [
                'name'   => 'Axis Bank',
                'code'   => 'AXIS',
                'status' => 1,
            ],
            [
                'name'   => 'Kotak Mahindra Bank',
                'code'   => 'KOTAK',
                'status' => 1,
            ],
            [
                'name'   => 'Punjab National Bank',
                'code'   => 'PNB',
                'status' => 1,
            ],
            [
                'name'   => 'Yes Bank',
                'code'   => 'YES',
                'status' => 1,
            ],
            [
                'name'   => 'IndusInd Bank',
                'code'   => 'INDUS',
                'status' => 1,
            ],
            [
                'name'   => 'IDFC First Bank',
                'code'   => 'IDFC',
                'status' => 1,
            ],
            [
                'name'   => 'Bank of Baroda',
                'code'   => 'BOB',
                'status' => 1,
            ],
            [
                'name'   => 'Canara Bank',
                'code'   => 'CANARA',
                'status' => 1,
            ],
            [
                'name'   => 'Union Bank of India',
                'code'   => 'UNION',
                'status' => 0, // inactive for testing
            ],
        ];

        foreach ($banks as $bank) {
            Bank::updateOrCreate(
                ['code' => $bank['code']], // unique key
                $bank
            );
        }
    }
}
