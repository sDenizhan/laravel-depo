<?php

namespace App\Console\Commands;

use App\Mail\ProductAlertMail;
use App\Models\Product;
use App\Models\Repo;
use App\Models\RepoHasProducts;
use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CheckProductQuantities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check product quantities and send alerts if necessary';

    /**
     * Execute the console command.
     */

    public function handle()
    {
        $alert = Setting::getGroup('alert');

        if ( $alert['email_enabled'] ) {
            $this->sendEmail($this->findLowProducts());
        }

        if ( $alert['sms_enabled']) {
            $this->sendSms($this->findLowProducts());
        }
    }

    public function findLowProducts()
    {
        // Get all products
        $products = Product::all();

        $data = [];

        // Loop through each product
        foreach ($products as $product) {
            $repoHasProduct = RepoHasProducts::where(['product_id' => $product->id])->first();

            if ( $repoHasProduct ) {

                if ( is_null($repoHasProduct->repo->name)) {
                    continue;
                }

                if ( $repoHasProduct->quantity < $product->min_alert ) {
                    $data[] = [
                        'repo_name' => $repoHasProduct->repo->name,
                        'product_name' => $product->name,
                        'product_count' => $repoHasProduct->quantity
                    ];
                }
            }
        }

        return $data;
    }

    private function sendEmail(?array $products = [])
    {
        if (empty($products)) {
            return;
        }

        $alert = Setting::getGroup('alert');

        $table = '<table>';
        $table .= '<tr><th>Repo Name</th><th>Product Name</th><th>Product Count</th></tr>';
        foreach ($products as $product) {
            $table .= '<tr>';
            $table .= '<td>' . $product['repo_name'] . '</td>';
            $table .= '<td>' . $product['product_name'] . '</td>';
            $table .= '<td>' . $product['product_count'] . '</td>';
            $table .= '</tr>';
        }
        $table .= '</table>';

        $details = [
            'title' => $alert['email_subject'],
            'body' => Str::of($alert['email_message'])->replace('{table}', $table)
        ];

        \Mail::to($alert['email_address'])->send(new ProductAlertMail($details));
    }

    private function sendSms(?array $products = [])
    {
        return true;
    }
}
