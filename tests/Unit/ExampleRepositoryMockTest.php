<?php

namespace Tests\Unit;

use Tests\TestCase;
use Mockery;
use App\Services\FavoriteService;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ExampleRepositoryMockTest extends TestCase
{
    protected $userRepositoryMock;
    protected $favoriteService;

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

    public function test_get_user_favorites_returns_products_successfully()
    {
        // Arrange
        $userId = 1;
        $mockProducts = new Collection([
            (object) ['id' => 1, 'name' => 'Product 1'],
            (object) ['id' => 2, 'name' => 'Product 2'],
        ]);

        // Set up expectations
        $this->userRepositoryMock
            ->shouldReceive('getUserFavoriteProducts')
            ->once()
            ->with($userId)
            ->andReturn($mockProducts);

        // Act
        $result = $this->favoriteService->getUserFavorites($userId);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals($mockProducts, $result['products']);
    }

    public function test_add_to_favorites_successfully_adds_product()
    {
        // Arrange
        $userId = 1;
        $productId = 2;
        $mockUser = new User(['id' => $userId, 'name' => 'Test User']);

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

    public function test_add_to_favorites_throws_exception_when_user_not_found()
    {
        // Arrange
        $userId = 999;
        $productId = 1;

        // Set up expectations
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
        $productId = 2;
        $mockUser = new User(['id' => $userId, 'name' => 'Test User']);

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
}