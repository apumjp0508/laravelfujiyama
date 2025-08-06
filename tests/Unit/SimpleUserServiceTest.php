<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\UserService;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\OrderItemRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Mockery;

class SimpleUserServiceTest extends TestCase
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

    public function test_get_current_user_returns_authenticated_user()
    {
        // Arrange
        $mockUser = (object)[
            'id' => 1,
            'name' => 'Test User',
            'email' => 'test@example.com'
        ];

        Auth::shouldReceive('user')
            ->once()
            ->andReturn($mockUser);

        // Act
        $result = $this->userService->getCurrentUser();

        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals($mockUser->id, $result['user']->id);
        $this->assertEquals($mockUser->name, $result['user']->name);
        $this->assertEquals($mockUser->email, $result['user']->email);
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

    public function test_update_user_updates_all_fields()
    {
        // Arrange
        $mockUser = Mockery::mock('App\Models\User')->makePartial();
        $mockUser->name = 'Old Name';
        $mockUser->email = 'old@example.com';
        $mockUser->postal_code = '000-0000';
        $mockUser->address = 'Old Address';
        $mockUser->phone = '000-0000-0000';
        $mockUser->id = 1;

        $updateData = [
            'name' => 'New Name',
            'email' => 'new@example.com',
            'postal_code' => '111-1111',
            'address' => 'New Address',
            'phone' => '111-1111-1111'
        ];

        $expectedUpdateData = [
            'name' => 'New Name',
            'email' => 'new@example.com',
            'postal_code' => '111-1111',
            'address' => 'New Address',
            'phone' => '111-1111-1111'
        ];

        // Mock the repository update method
        $this->userRepository->shouldReceive('update')
            ->once()
            ->with($mockUser, $expectedUpdateData)
            ->andReturn(true);

        // Act
        $result = $this->userService->updateUser($mockUser, $updateData);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals('ユーザー情報を更新しました。', $result['message']);
        $this->assertEquals($mockUser, $result['user']);
    }

    public function test_update_user_preserves_existing_values_for_null_fields()
    {
        // Arrange
        $mockUser = Mockery::mock('App\Models\User')->makePartial();
        $mockUser->name = 'Original Name';
        $mockUser->email = 'original@example.com';
        $mockUser->postal_code = '000-0000';
        $mockUser->address = 'Original Address';
        $mockUser->phone = '000-0000-0000';
        $mockUser->id = 1;

        $updateData = [
            'name' => 'Updated Name',
            'email' => null, // Should preserve original email
            'postal_code' => '111-1111',
            'address' => null, // Should preserve original address
            'phone' => null // Should preserve original phone
        ];

        $expectedUpdateData = [
            'name' => 'Updated Name',
            'email' => 'original@example.com', // Preserved from original
            'postal_code' => '111-1111',
            'address' => 'Original Address', // Preserved from original
            'phone' => '000-0000-0000' // Preserved from original
        ];

        // Mock the repository update method
        $this->userRepository->shouldReceive('update')
            ->once()
            ->with($mockUser, $expectedUpdateData)
            ->andReturn(true);

        // Act
        $result = $this->userService->updateUser($mockUser, $updateData);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals('ユーザー情報を更新しました。', $result['message']);
        $this->assertEquals($mockUser, $result['user']);
    }
}