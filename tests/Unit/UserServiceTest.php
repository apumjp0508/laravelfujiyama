<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\UserService;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\OrderItemRepositoryInterface;
use App\Models\User;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\Auth;
use Mockery;

class UserServiceTest extends TestCase
{
    protected $userService;
    protected $userRepository;
    protected $orderItemRepository;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->userRepository = Mockery::mock(UserRepositoryInterface::class);
        $this->orderItemRepository = Mockery::mock(OrderItemRepositoryInterface::class);
        
        $this->userService = new UserService(
            $this->userRepository,
            $this->orderItemRepository
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_user_profile_returns_user_data()
    {
        // Arrange
        $mockUser = new User([
            'id' => 1,
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        $mockUser->id = 1;

        $this->userRepository
            ->shouldReceive('findById')
            ->with(1)
            ->once()
            ->andReturn($mockUser);

        // Act
        $result = $this->userService->getUserProfile(1);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals(1, $result['user']->id);
        $this->assertEquals('Test User', $result['user']->name);
        $this->assertEquals('test@example.com', $result['user']->email);
    }

    public function test_get_user_profile_throws_exception_for_nonexistent_user()
    {
        // Arrange
        $this->userRepository
            ->shouldReceive('findById')
            ->with(999)
            ->once()
            ->andReturn(null);

        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('ユーザープロフィールの取得に失敗しました。');
        $this->expectExceptionCode(500);
        
        $this->userService->getUserProfile(999);
    }

   public function test_get_user_orders_returns_order_items()
    {
        // Arrange
        $mockOrderItems = new EloquentCollection([
            new OrderItem(['id' => 1, 'user_id' => 1, 'product_name' => 'Product 1']),
            new OrderItem(['id' => 2, 'user_id' => 1, 'product_name' => 'Product 2']),
            new OrderItem(['id' => 3, 'user_id' => 1, 'product_name' => 'Product 3']),
        ]);

        $this->orderItemRepository
            ->shouldReceive('findByUserIdWithProduct')
            ->with(1)
            ->once()
            ->andReturn($mockOrderItems);

        // Act
        $result = $this->userService->getUserOrders(1);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertCount(3, $result['orderItems']);
        $this->assertEquals(1, $result['orderItems']->first()->user_id);
    }

   public function test_get_user_orders_returns_empty_for_no_orders()
    {
        // Arrange
        $emptyCollection = new EloquentCollection([]);

        $this->orderItemRepository
            ->shouldReceive('findByUserIdWithProduct')
            ->with(1)
            ->once()
            ->andReturn($emptyCollection);

        // Act
        $result = $this->userService->getUserOrders(1);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertCount(0, $result['orderItems']);
    }

    public function test_update_user_updates_all_fields()
    {
        // Arrange
        $user = new User([
            'id' => 1,
            'name' => 'Old Name',
            'email' => 'old@example.com',
            'postal_code' => '000-0000',
            'address' => 'Old Address',
            'phone' => '000-0000-0000'
        ]);

        $updateData = [
            'name' => 'New Name',
            'email' => 'new@example.com',
            'postal_code' => '111-1111',
            'address' => 'New Address',
            'phone' => '111-1111-1111'
        ];

        $this->userRepository
            ->shouldReceive('update')
            ->with($user, [
                'name' => 'New Name',
                'email' => 'new@example.com',
                'postal_code' => '111-1111',
                'address' => 'New Address',
                'phone' => '111-1111-1111'
            ])
            ->once();

        // Act
        $result = $this->userService->updateUser($user, $updateData);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals('ユーザー情報を更新しました。', $result['message']);
        $this->assertEquals($user, $result['user']);
    }

    public function test_update_user_preserves_existing_values_for_null_fields()
    {
        // Arrange
        $user = new User([
            'id' => 1,
            'name' => 'Original Name',
            'email' => 'original@example.com',
            'postal_code' => '000-0000',
            'address' => 'Original Address',
            'phone' => '000-0000-0000'
        ]);

        $updateData = [
            'name' => 'Updated Name',
            'email' => null, // Should preserve original email
            'postal_code' => '111-1111',
            'address' => null, // Should preserve original address
            'phone' => null // Should preserve original phone
        ];

        $this->userRepository
            ->shouldReceive('update')
            ->with($user, [
                'name' => 'Updated Name',
                'email' => 'original@example.com', // Preserved
                'postal_code' => '111-1111',
                'address' => 'Original Address', // Preserved
                'phone' => '000-0000-0000' // Preserved
            ])
            ->once();

        // Act
        $result = $this->userService->updateUser($user, $updateData);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals($user, $result['user']);
    }

    public function test_get_current_user_returns_authenticated_user()
    {
        // Arrange
        $user = new User([
            'id' => 1,
            'name' => 'Original Name',
            'email' => 'original@example.com',
            'postal_code' => '000-0000',
            'address' => 'Original Address',
            'phone' => '000-0000-0000'
        ]);
        Auth::shouldReceive('user')
            ->once()
            ->andReturn($user);

        // Act
        $result = $this->userService->getCurrentUser();

        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals($user->id, $result['user']->id);
    }

    public function test_get_current_user_throws_exception_when_not_authenticated()
    {
        // Arrange
        Auth::shouldReceive('user')
            ->once()
            ->andReturn(null);

        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('現在のユーザー情報の取得に失敗しました。');
        $this->expectExceptionCode(500);
        
        $this->userService->getCurrentUser();
    }
}