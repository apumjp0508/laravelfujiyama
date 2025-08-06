<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\ProductSet;
use App\Models\Product;

class ProductSetMigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_sets_table_exists_with_product_id_column()
    {
        // This test verifies the table structure after adding product_id
        $this->assertTrue(Schema::hasTable('product_sets'));
        
        // Check existing columns
        $this->assertTrue(Schema::hasColumn('product_sets', 'id'));
        $this->assertTrue(Schema::hasColumn('product_sets', 'name'));
        $this->assertTrue(Schema::hasColumn('product_sets', 'description'));
        $this->assertTrue(Schema::hasColumn('product_sets', 'stock'));
        $this->assertTrue(Schema::hasColumn('product_sets', 'img'));
        $this->assertTrue(Schema::hasColumn('product_sets', 'widthSize'));
        $this->assertTrue(Schema::hasColumn('product_sets', 'heightSize'));
        $this->assertTrue(Schema::hasColumn('product_sets', 'created_at'));
        $this->assertTrue(Schema::hasColumn('product_sets', 'updated_at'));
        
        // Verify product_id column now exists after migration
        $this->assertTrue(Schema::hasColumn('product_sets', 'product_id'));
    }

    public function test_product_sets_can_be_created_without_product_id()
    {
        // This test ensures current functionality works before migration
        $productSet = ProductSet::create([
            'name' => 'Test Set',
            'description' => 'Test Description', 
            'stock' => 10,
            'img' => 'storage/images/test.jpg',
            'widthSize' => 100,
            'heightSize' => 150
        ]);

        $this->assertNotNull($productSet);
        $this->assertDatabaseHas('product_sets', [
            'name' => 'Test Set',
            'description' => 'Test Description',
            'stock' => 10
        ]);
    }

    public function test_existing_data_integrity_before_migration()
    {
        // Create test data to ensure migration doesn't break existing records
        $productSet1 = ProductSet::factory()->create([
            'name' => 'Existing Set 1',
            'stock' => 5
        ]);
        
        $productSet2 = ProductSet::factory()->create([
            'name' => 'Existing Set 2', 
            'stock' => 10
        ]);

        // Verify data exists
        $this->assertDatabaseHas('product_sets', ['name' => 'Existing Set 1']);
        $this->assertDatabaseHas('product_sets', ['name' => 'Existing Set 2']);
        
        // Verify we can query the data
        $sets = ProductSet::all();
        $this->assertCount(2, $sets);
        
        $setNames = $sets->pluck('name')->toArray();
        $this->assertContains('Existing Set 1', $setNames);
        $this->assertContains('Existing Set 2', $setNames);
    }
}