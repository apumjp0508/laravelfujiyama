<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ProfileService;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Mockery;

class ProfileServiceTest extends TestCase
{

    protected $profileService;
    protected $mockUserRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockUserRepository = Mockery::mock(UserRepositoryInterface::class);
        $this->profileService = new ProfileService($this->mockUserRepository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_update_profile_successfully_updates_user_data()
    {
        // Arrange
        $user = Mockery::mock(User::class)->makePartial();
        $user->shouldReceive('fill')->once()->with([
            'name' => 'New Name',
            'postal_code' => '111-1111',
            'address' => 'New Address',
            'phone' => '111-1111-1111'
        ]);
        $user->shouldReceive('isDirty')->with('email')->once()->andReturn(false);
        $user->shouldReceive('save')->once();
        $user->id = 1;
        $user->name = 'New Name';
        $user->postal_code = '111-1111';
        $user->address = 'New Address';
        $user->phone = '111-1111-1111';
        $user->email = 'old@example.com';

        $updateData = [
            'name' => 'New Name',
            'postal_code' => '111-1111',
            'address' => 'New Address',
            'phone' => '111-1111-1111'
        ];

        // Act
        $result = $this->profileService->updateProfile($user, $updateData);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals('プロフィールを更新しました。', $result['message']);
        $this->assertEquals($user, $result['user']);
    }

    public function test_update_profile_resets_email_verification_when_email_changed()
    {
        // Arrange
        $user = Mockery::mock(User::class)->makePartial();
        $user->shouldReceive('fill')->once()->with([
            'name' => 'Test User',
            'email' => 'new@example.com',
        ]);
        $user->shouldReceive('isDirty')->with('email')->once()->andReturn(true);
        $user->shouldReceive('save')->once();
        $user->id = 1;
        $user->name = 'Test User';
        $user->email = 'new@example.com';
        $user->email_verified_at = null;

        $updateData = [
            'name' => 'Test User',
            'email' => 'new@example.com',
        ];

        // Act
        $result = $this->profileService->updateProfile($user, $updateData);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals($user, $result['user']);
    }

    public function test_delete_account_successfully_deletes_user_with_correct_password()
    {
        // Arrange
        $password = 'correct-password';
        $user = Mockery::mock(User::class)->makePartial();
        $user->id = 1;
        
        $user->shouldReceive('delete')->once();

        // パスワード確認はコントローラーで実行済みなので、ここではモックしない

        Auth::shouldReceive('logout')
            ->once();

        // Act
        $result = $this->profileService->deleteAccount($user, $password);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals('アカウントを削除しました。', $result['message']);
    }

    // This test is no longer relevant since password validation is now handled at the controller level
    // The service assumes the password has already been validated by the controller

    public function test_get_user_profile_returns_user_data()
    {
        // Arrange
        $userId = 1;
        $user = new User([
            'id' => $userId,
            'name' => 'Test User',
            'email' => 'test@example.com'
        ]);
        $user->id = $userId;

        $this->mockUserRepository->shouldReceive('findByIdOrFail')
            ->with($userId)
            ->once()
            ->andReturn($user);

        // Act
        $result = $this->profileService->getUserProfile($userId);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals($user, $result['user']);
    }

    public function test_get_user_profile_throws_exception_for_nonexistent_user()
    {
        // Arrange
        $userId = 999;

        $this->mockUserRepository->shouldReceive('findByIdOrFail')
            ->with($userId)
            ->once()
            ->andThrow(new \Illuminate\Database\Eloquent\ModelNotFoundException());

        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('プロフィール情報の取得に失敗しました。');
        $this->expectExceptionCode(500);
        
        $this->profileService->getUserProfile($userId);
    }

    public function test_update_profile_handles_database_error()
    {
        // Arrange
        $user = Mockery::mock(User::class)->makePartial();
        $user->shouldReceive('fill')->once();
        $user->shouldReceive('isDirty')->with('email')->once()->andReturn(false);
        $user->shouldReceive('save')->once()->andThrow(new \Illuminate\Database\QueryException('test', [], new \Exception('Database error')));
        $user->id = 1;

        $updateData = ['name' => 'New Name'];

        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('プロフィールの更新に失敗しました。データベースエラーが発生しました。');
        $this->expectExceptionCode(500);
        
        $this->profileService->updateProfile($user, $updateData);
    }

    public function test_update_profile_handles_general_exception()
    {
        // Arrange
        $user = Mockery::mock(User::class)->makePartial();
        $user->shouldReceive('fill')->once();
        $user->shouldReceive('isDirty')->with('email')->once()->andReturn(false);
        $user->shouldReceive('save')->once()->andThrow(new \Exception('General error'));
        $user->id = 1;

        $updateData = ['name' => 'New Name'];

        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('プロフィールの更新に失敗しました。');
        $this->expectExceptionCode(500);
        
        $this->profileService->updateProfile($user, $updateData);
    }

    public function test_delete_account_handles_database_error()
    {
        // Arrange
        $password = 'correct-password';
        $user = Mockery::mock(User::class)->makePartial();
        $user->id = 1;
        
        $user->shouldReceive('delete')->once()->andThrow(new \Illuminate\Database\QueryException('test', [], new \Exception('Database error')));

        // パスワード確認はコントローラーで実行済みなので、ここではモックしない

        Auth::shouldReceive('logout')
            ->once();

        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('アカウントの削除に失敗しました。データベースエラーが発生しました。');
        $this->expectExceptionCode(500);
        
        $this->profileService->deleteAccount($user, $password);
    }

    public function test_get_user_profile_handles_database_error()
    {
        // Arrange
        $userId = 1;

        $this->mockUserRepository->shouldReceive('findByIdOrFail')
            ->with($userId)
            ->once()
            ->andThrow(new \Illuminate\Database\QueryException('test', [], new \Exception('Database error')));

        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('プロフィール情報の取得に失敗しました。データベースエラーが発生しました。');
        $this->expectExceptionCode(500);
        
        $this->profileService->getUserProfile($userId);
    }
}