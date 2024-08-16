<?php

namespace App\Console\Commands;

use App\Mail\ProductAlertMail;
use App\Models\Product;
use App\Models\RepoHasProducts;
use App\Models\Setting;
use Illuminate\Console\Command;

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
        // Get all products
        $products = Product::all();

        //alert settings
        $alert = Setting::getGroup('alert');

        if ( $alert['email_enabled'] == 0 ) {
            return;
        }

        // Loop through each product
        foreach ($products as $product) {

            $repoHasProduct = RepoHasProducts::where(['product_id' => $product->id])->first();

            if ( $repoHasProduct ) {

                if ( $repoHasProduct->quantity < $product->min_alert ) {

                    $message = \Str::of($alert['email_message'])
                        ->replace('{repo_name}', $repoHasProduct->repo->name ?? 'Unknown')
                        ->replace('{product_name}', $product->name)
                        ->replace('{product_count}', $repoHasProduct->quantity);

                    // Send email
                    $details = [
                        'title' => $alert['email_subject'],
                        'body' => $message
                    ];

                    \Mail::to($alert['email_address'])->send(new ProductAlertMail($details));
                }
            }
        }
    }
}
