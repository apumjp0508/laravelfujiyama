<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\SelectProductService;
use App\Models\Product;
use App\Models\ProductSet;
use App\Models\BeforeBuySelectedProductSet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Mockery;

class SelectProductServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $selectProductService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->selectProductService = new SelectProductService();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_product_sets_and_user_returns_filtered_product_sets()
    {
        // Arrange
        $user = User::factory()->create();
        $product = Product::factory()->create(['productType' => 'set']);
        $otherProduct = Product::factory()->create(['productType' => 'set']);
        
        // Create product sets: 2 for our product, 1 general, 1 for other product
        ProductSet::factory()->create(['product_id' => $product->id, 'name' => 'Specific Set 1']);
        ProductSet::factory()->create(['product_id' => $product->id, 'name' => 'Specific Set 2']);
        ProductSet::factory()->create(['product_id' => null, 'name' => 'General Set']);
        ProductSet::factory()->create(['product_id' => $otherProduct->id, 'name' => 'Other Product Set']);

        Auth::shouldReceive('user')->andReturn($user);

        // Act
        $result = $this->selectProductService->getProductSetsAndUser($product);

        // Assert
        $this->assertArrayHasKey('productSets', $result);
        $this->assertArrayHasKey('user', $result);
        $this->assertArrayHasKey('product', $result);
        $this->assertCount(3, $result['productSets']); // 2 specific + 1 general
        $this->assertEquals($user->id, $result['user']->id);
        $this->assertEquals($product->id, $result['product']->id);
        
        // Verify correct product sets are returned
        $setNames = $result['productSets']->pluck('name')->toArray();
        $this->assertContains('Specific Set 1', $setNames);
        $this->assertContains('Specific Set 2', $setNames);
        $this->assertContains('General Set', $setNames);
        $this->assertNotContains('Other Product Set', $setNames);
    }

    public function test_get_product_sets_and_user_with_unauthenticated_user()
    {
        // Arrange
        $product = Product::factory()->create(['productType' => 'set']);
        ProductSet::factory()->create(['product_id' => $product->id]);
        ProductSet::factory()->create(['product_id' => null]); // General set

        Auth::shouldReceive('user')->andReturn(null);

        // Act
        $result = $this->selectProductService->getProductSetsAndUser($product);

        // Assert
        $this->assertArrayHasKey('productSets', $result);
        $this->assertArrayHasKey('user', $result);
        $this->assertArrayHasKey('product', $result);
        $this->assertCount(2, $result['productSets']); // 1 specific + 1 general
        $this->assertNull($result['user']);
        $this->assertEquals($product->id, $result['product']->id);
    }

    public function test_create_selected_product_sets_creates_records_successfully()
    {
        // Arrange
        $user = User::factory()->create();
        $product = Product::factory()->create(['productType' => 'set']);
        $productSet1 = ProductSet::factory()->create(['widthSize' => 100, 'heightSize' => 150]);
        $productSet2 = ProductSet::factory()->create(['widthSize' => 200, 'heightSize' => 250]);
        
        $selectedProductSetIds = [$productSet1->id, $productSet2->id];

        // Act
        $result = $this->selectProductService->createSelectedProductSets(
            $selectedProductSetIds, 
            $product->id, 
            $user->id, 
            'test-set-id'
        );

        // Assert
        $this->assertCount(2, $result);
        $this->assertDatabaseHas('before_buy_selected_product_sets', [
            'product_id' => $product->id,
            'product_set_id' => $productSet1->id,
            'user_id' => $user->id,
            'widthSize' => 100,
            'heightSize' => 150,
            'set_id' => 'test-set-id'
        ]);
        $this->assertDatabaseHas('before_buy_selected_product_sets', [
            'product_id' => $product->id,
            'product_set_id' => $productSet2->id,
            'user_id' => $user->id,
            'widthSize' => 200,
            'heightSize' => 250,
            'set_id' => 'test-set-id'
        ]);
    }

    public function test_create_selected_product_sets_removes_existing_selections()
    {
        // Arrange
        $user = User::factory()->create();
        $product = Product::factory()->create(['productType' => 'set']);
        $productSet1 = ProductSet::factory()->create();
        $productSet2 = ProductSet::factory()->create();

        // Create existing selection
        BeforeBuySelectedProductSet::create([
            'product_id' => $product->id,
            'product_set_id' => $productSet1->id,
            'user_id' => $user->id,
            'widthSize' => 100,
            'heightSize' => 150,
            'set_id' => 'old-set-id'
        ]);

        $selectedProductSetIds = [$productSet2->id];

        // Act
        $result = $this->selectProductService->createSelectedProductSets(
            $selectedProductSetIds, 
            $product->id, 
            $user->id, 
            'new-set-id'
        );

        // Assert
        $this->assertCount(1, $result);
        $this->assertDatabaseMissing('before_buy_selected_product_sets', [
            'product_id' => $product->id,
            'product_set_id' => $productSet1->id,
            'set_id' => 'old-set-id'
        ]);
        $this->assertDatabaseHas('before_buy_selected_product_sets', [
            'product_id' => $product->id,
            'product_set_id' => $productSet2->id,
            'user_id' => $user->id,
            'set_id' => 'new-set-id'
        ]);
    }

    public function test_create_selected_product_sets_handles_invalid_product_set_ids()
    {
        // Arrange
        $user = User::factory()->create();
        $product = Product::factory()->create(['productType' => 'set']);
        $validProductSet = ProductSet::factory()->create();
        
        $selectedProductSetIds = [$validProductSet->id, 999]; // 999 doesn't exist

        // Act
        $result = $this->selectProductService->createSelectedProductSets(
            $selectedProductSetIds, 
            $product->id, 
            $user->id
        );

        // Assert - Only valid product set should be created
        $this->assertCount(1, $result);
        $this->assertDatabaseHas('before_buy_selected_product_sets', [
            'product_id' => $product->id,
            'product_set_id' => $validProductSet->id,
            'user_id' => $user->id
        ]);
        $this->assertDatabaseMissing('before_buy_selected_product_sets', [
            'product_set_id' => 999
        ]);
    }

    public function test_create_selected_product_sets_with_empty_array()
    {
        // Arrange
        $user = User::factory()->create();
        $product = Product::factory()->create(['productType' => 'set']);

        // Act
        $result = $this->selectProductService->createSelectedProductSets(
            [], 
            $product->id, 
            $user->id
        );

        // Assert
        $this->assertCount(0, $result);
        $this->assertDatabaseCount('before_buy_selected_product_sets', 0);
    }

    public function test_get_product_sets_and_user_throws_exception_for_non_set_product()
    {
        // Arrange
        $product = Product::factory()->create(['productType' => 'single']); // Not a set product
        
        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('This product is not a set product type.');
        
        $this->selectProductService->getProductSetsAndUser($product);
    }

    public function test_get_product_sets_and_user_throws_exception_when_no_product_sets_available()
    {
        // Arrange
        $product = Product::factory()->create(['productType' => 'set']);
        // No product sets created for this test
        
        Auth::shouldReceive('user')->andReturn(User::factory()->create());
        
        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('No product sets are available for this product. Please contact administrator to add product sets first.');
        
        $this->selectProductService->getProductSetsAndUser($product);
    }

    public function test_get_product_sets_and_user_works_with_only_general_product_sets()
    {
        // Arrange
        $product = Product::factory()->create(['productType' => 'set']);
        $user = User::factory()->create();
        
        // Create only general product sets (product_id = null)
        ProductSet::factory()->create(['product_id' => null, 'name' => 'General Set 1']);
        ProductSet::factory()->create(['product_id' => null, 'name' => 'General Set 2']);
        
        Auth::shouldReceive('user')->andReturn($user);
        
        // Act
        $result = $this->selectProductService->getProductSetsAndUser($product);
        
        // Assert
        $this->assertArrayHasKey('productSets', $result);
        $this->assertCount(2, $result['productSets']);
        $setNames = $result['productSets']->pluck('name')->toArray();
        $this->assertContains('General Set 1', $setNames);
        $this->assertContains('General Set 2', $setNames);
    }

    public function test_get_product_sets_and_user_works_with_only_specific_product_sets()
    {
        // Arrange
        $product = Product::factory()->create(['productType' => 'set']);
        $user = User::factory()->create();
        
        // Create only specific product sets for this product
        ProductSet::factory()->create(['product_id' => $product->id, 'name' => 'Specific Set 1']);
        ProductSet::factory()->create(['product_id' => $product->id, 'name' => 'Specific Set 2']);
        
        Auth::shouldReceive('user')->andReturn($user);
        
        // Act
        $result = $this->selectProductService->getProductSetsAndUser($product);
        
        // Assert
        $this->assertArrayHasKey('productSets', $result);
        $this->assertCount(2, $result['productSets']);
        $setNames = $result['productSets']->pluck('name')->toArray();
        $this->assertContains('Specific Set 1', $setNames);
        $this->assertContains('Specific Set 2', $setNames);
    }
}