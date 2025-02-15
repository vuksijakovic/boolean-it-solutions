<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use League\Csv\Reader;
use App\Models\Product;
use App\Models\Category;
use App\Models\Department;
use App\Models\Manufacturer;

class PopulateDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:populate-database {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ova komanda cita csv fajl i vrsi populaciju baze podataka.';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $this->callSilent('migrate');


        $filePath = $this->argument('file');

        if (!File::exists($filePath)) {
            $this->error("File not found: $filePath");
            return;
        }

        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setHeaderOffset(0); 

        $records = $csv->getRecords();
        foreach ($records as $record) {
            $category = Category::firstOrCreate(['name' => $record['category_name']]);
            $department = Department::firstOrCreate(['name' => $record['deparment_name']]);
            $manufacturer = Manufacturer::firstOrCreate(['name' => $record['manufacturer_name']]);
            Product::updateOrCreate(
                ['product_number' => $record['product_number']], 
                [
                    'category_id' => $category->id,
                    'department_id' => $department->id,
                    'manufacturer_id' => $manufacturer->id,
                    'upc' => $record['upc'],
                    'sku' => $record['sku'],
                    'regular_price' => $record['regular_price'],
                    'sale_price' => $record['sale_price'],
                    'description' => $record['description'],
                ]
            );

            $this->info("Proizvod ubacen u bazu: " . $record['product_number']);
        }

        $this->info('Zavrsen CSV import.');
    }
}
