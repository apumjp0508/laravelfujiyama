<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Repositories\ProductSetRepository;
use App\Models\ProductSet;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductSetRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $productSetRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->productSetRepository = new ProductSetRepository();
    }

    public function test_find_by_id_returns_product_set()
    {
        // Arrange
        $productSet = ProductSet::factory()->create(['name' => 'Test Set']);

        // Act
        $result = $this->productSetRepository->findById($productSet->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals('Test Set', $result->name);
    }

    public function test_find_by_id_returns_null_for_nonexistent_id()
    {
        // Act
        $result = $this->productSetRepository->findById(999);

        // Assert
        $this->assertNull($result);
    }

    public function test_find_by_attributes_returns_matching_product_set()
    {
        // Arrange
        ProductSet::factory()->create(['name' => 'Test Set A', 'stock' => 10]);
        ProductSet::factory()->create(['name' => 'Test Set B', 'stock' => 20]);

        // Act
        $result = $this->productSetRepository->findByAttributes(['name' => 'Test Set A']);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals('Test Set A', $result->name);
        $this->assertEquals(10, $result->stock);
    }

    public function test_create_creates_new_product_set()
    {
        // Arrange
        $data = [
            'name' => 'New Set',
            'description' => 'New Description',
            'stock' => 15,
            'img' => 'storage/images/new.jpg',
            'widthSize' => 100,
            'heightSize' => 150
        ];

        // Act
        $result = $this->productSetRepository->create($data);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals('New Set', $result->name);
        $this->assertDatabaseHas('product_sets', ['name' => 'New Set']);
    }

    public function test_update_updates_existing_product_set()
    {
        // Arrange
        $productSet = ProductSet::factory()->create(['name' => 'Original']);

        // Act
        $success = $this->productSetRepository->update($productSet, ['name' => 'Updated']);

        // Assert
        $this->assertTrue($success);
        $this->assertEquals('Updated', $productSet->fresh()->name);
    }

    public function test_delete_removes_product_set()
    {
        // Arrange
        $productSet = ProductSet::factory()->create();

        // Act
        $success = $this->productSetRepository->delete($productSet);

        // Assert
        $this->assertTrue($success);
        $this->assertDatabaseMissing('product_sets', ['id' => $productSet->id]);
    }

    public function test_all_returns_all_product_sets()
    {
        // Arrange
        ProductSet::factory()->count(3)->create();

        // Act
        $result = $this->productSetRepository->all();

        // Assert
        $this->assertCount(3, $result);
    }

    public function test_find_by_name_returns_matching_product_set()
    {
        // Arrange
        ProductSet::factory()->create(['name' => 'Unique Name']);

        // Act
        $result = $this->productSetRepository->findByName('Unique Name');

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals('Unique Name', $result->name);
    }

    // Tests for new product_id functionality (these will work after migration)
    public function test_find_by_product_id_returns_product_sets_for_specific_product()
    {
        // Arrange
        $product = Product::factory()->create(['productType' => 'set']);
        $otherProduct = Product::factory()->create(['productType' => 'set']);
        ProductSet::factory()->create(['product_id' => $product->id, 'name' => 'Set for Product 1']);
        ProductSet::factory()->create(['product_id' => $product->id, 'name' => 'Set for Product 2']);
        ProductSet::factory()->create(['product_id' => $otherProduct->id, 'name' => 'Set for Other Product']);

        // Act
        $result = $this->productSetRepository->findByProductId($product->id);

        // Assert
        $this->assertCount(2, $result);
        $setNames = $result->pluck('name')->toArray();
        $this->assertContains('Set for Product 1', $setNames);
        $this->assertContains('Set for Product 2', $setNames);
        $this->assertNotContains('Set for Other Product', $setNames);
    }

    public function test_find_by_product_ids_returns_product_sets_for_multiple_products()
    {
        // Arrange
        $product1 = Product::factory()->create(['productType' => 'set']);
        $product2 = Product::factory()->create(['productType' => 'set']);
        $otherProduct = Product::factory()->create(['productType' => 'set']);
        
        ProductSet::factory()->create(['product_id' => $product1->id, 'name' => 'Set for Product 1']);
        ProductSet::factory()->create(['product_id' => $product2->id, 'name' => 'Set for Product 2']);
        ProductSet::factory()->create(['product_id' => $otherProduct->id, 'name' => 'Set for Other Product']);

        // Act
        $result = $this->productSetRepository->findByProductIds([$product1->id, $product2->id]);

        // Assert
        $this->assertCount(2, $result);
        $setNames = $result->pluck('name')->toArray();
        $this->assertContains('Set for Product 1', $setNames);
        $this->assertContains('Set for Product 2', $setNames);
        $this->assertNotContains('Set for Other Product', $setNames);
    }

    public function test_get_all_for_product_returns_both_specific_and_general_sets()
    {
        // Arrange
        $product = Product::factory()->create(['productType' => 'set']);
        $otherProduct = Product::factory()->create(['productType' => 'set']);
        
        ProductSet::factory()->create(['product_id' => $product->id, 'name' => 'Specific Set']);
        ProductSet::factory()->create(['product_id' => null, 'name' => 'General Set']);
        ProductSet::factory()->create(['product_id' => $otherProduct->id, 'name' => 'Other Product Set']);

        // Act
        $result = $this->productSetRepository->getAllForProduct($product->id);

        // Assert
        $this->assertCount(2, $result);
        $setNames = $result->pluck('name')->toArray();
        $this->assertContains('Specific Set', $setNames);
        $this->assertContains('General Set', $setNames);
        $this->assertNotContains('Other Product Set', $setNames);
    }

    public function test_find_by_product_id_returns_empty_collection_for_nonexistent_product()
    {
        // Act
        $result = $this->productSetRepository->findByProductId(999);

        // Assert
        $this->assertCount(0, $result);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $result);
    }
}