<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\FavoriteService;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Mockery;

class FavoriteServiceTest extends TestCase
{
    protected $favoriteService;
    protected $userRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create mock repository
        $this->userRepositoryMock = Mockery::mock(UserRepositoryInterface::class);
        
        // Create service with mocked repository
        $this->favoriteService = new FavoriteService($this->userRepositoryMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_user_favorites_returns_favorite_products()
    {
        // Arrange
        $userId = 1;
        $products = new Collection([
            (object)['id' => 1, 'name' => 'Product 1'],
            (object)['id' => 2, 'name' => 'Product 2'],
            (object)['id' => 3, 'name' => 'Product 3']
        ]);

        // Set up expectations
        $this->userRepositoryMock
            ->shouldReceive('getUserFavoriteProducts')
            ->once()
            ->with($userId)
            ->andReturn($products);

        // Act
        $result = $this->favoriteService->getUserFavorites($userId);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertCount(3, $result['products']);
    }

    public function test_get_user_favorites_throws_exception_for_nonexistent_user()
    {
        // Arrange
        $userId = 999;
        
        $this->userRepositoryMock
            ->shouldReceive('getUserFavoriteProducts')
            ->once()
            ->with($userId)
            ->andThrow(new \Illuminate\Database\Eloquent\ModelNotFoundException());

        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('お気に入り商品の取得に失敗しました。');
        
        $this->favoriteService->getUserFavorites($userId);
    }

    public function test_add_to_favorites_successfully_adds_product()
    {
        // Arrange
        $userId = 1;
        $productId = 1;
        $mockUser = new User(['id' => $userId, 'name' => 'Test User']);
        $mockUser->id = $userId;

        // Set up expectations
        $this->userRepositoryMock
            ->shouldReceive('findById')
            ->once()
            ->with($userId)
            ->andReturn($mockUser);

        $this->userRepositoryMock
            ->shouldReceive('addToFavorites')
            ->once()
            ->with($userId, $productId)
            ->andReturn(true);

        // Act
        $result = $this->favoriteService->addToFavorites($userId, $productId);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals('お気に入りに追加しました。', $result['message']);
    }

    public function test_add_to_favorites_throws_exception_for_nonexistent_user()
    {
        // Arrange
        $userId = 999;
        $productId = 1;

        $this->userRepositoryMock
            ->shouldReceive('findById')
            ->once()
            ->with($userId)
            ->andReturn(null);

        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('お気に入りへの追加に失敗しました。');
        
        $this->favoriteService->addToFavorites($userId, $productId);
    }

    public function test_remove_from_favorites_successfully_removes_product()
    {
        // Arrange
        $userId = 1;
        $productId = 1;
        $mockUser = new User(['id' => $userId, 'name' => 'Test User']);
        $mockUser->id = $userId;

        // Set up expectations
        $this->userRepositoryMock
            ->shouldReceive('findById')
            ->once()
            ->with($userId)
            ->andReturn($mockUser);

        $this->userRepositoryMock
            ->shouldReceive('removeFromFavorites')
            ->once()
            ->with($userId, $productId)
            ->andReturn(true);

        // Act
        $result = $this->favoriteService->removeFromFavorites($userId, $productId);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals('お気に入りから削除しました。', $result['message']);
    }

    public function test_remove_from_favorites_throws_exception_for_nonexistent_user()
    {
        // Arrange
        $userId = 999;
        $productId = 1;

        $this->userRepositoryMock
            ->shouldReceive('findById')
            ->once()
            ->with($userId)
            ->andReturn(null);

        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('お気に入りからの削除に失敗しました。');
        
        $this->favoriteService->removeFromFavorites($userId, $productId);
    }
}