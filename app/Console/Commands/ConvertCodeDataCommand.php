<?php

namespace App\Console\Commands;

use App\Models\Attribute;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\User;
use Illuminate\Console\Command;

class ConvertCodeDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'convert:code';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert code data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $products = Product::all();
        foreach ($products as $product){
            \DB::table('products')->where('id', $product->id)
                ->update(['pro_code' => render_code_id('pro')]);
        }

        $users = User::all();
        foreach ($users as $item){
            \DB::table('users')->where('id', $item->id)
                ->update(['code' => render_code_id('us')]);
        }

        $categories = Category::all();
        foreach ($categories as $item){
            \DB::table('categories')->where('id', $item->id)
                ->update(['c_code' => render_code_id('cg')]);
        }

        $suppliers = Supplier::all();
        foreach ($suppliers as $item){
            \DB::table('supplieres')->where('id', $item->id)
                ->update(['s_code' => render_code_id('sl')]);
        }

        $attributes = Attribute::all();
        foreach ($attributes as $item){
            \DB::table('attributes')->where('id', $item->id)
                ->update(['atb_code' => render_code_id('atb')]);
        }

    }
}
