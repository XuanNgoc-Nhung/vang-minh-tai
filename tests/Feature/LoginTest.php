<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_user_can_login_with_email()
    {
        // Create a test user
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '0123456789',
            'password' => Hash::make('password123'),
            'role' => 0,
            'status' => 1
        ]);

        // Test login with email
        $response = $this->postJson('/login', [
            'login' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Đăng nhập thành công!'
                ])
                ->assertJsonStructure([
                    'success',
                    'message',
                    'redirect',
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'phone',
                        'role'
                    ]
                ]);
    }

    public function test_user_can_login_with_phone()
    {
        // Create a test user
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '0123456789',
            'password' => Hash::make('password123'),
            'role' => 0,
            'status' => 1
        ]);

        // Test login with phone
        $response = $this->postJson('/login', [
            'login' => '0123456789',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Đăng nhập thành công!'
                ]);
    }

    public function test_login_fails_with_invalid_credentials()
    {
        // Test login with invalid email
        $response = $this->postJson('/login', [
            'login' => 'nonexistent@example.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(401)
                ->assertJson([
                    'success' => false,
                    'message' => 'Thông tin đăng nhập không chính xác'
                ]);
    }

    public function test_login_fails_with_inactive_user()
    {
        // Create an inactive user
        $user = User::create([
            'name' => 'Inactive User',
            'email' => 'inactive@example.com',
            'phone' => '0987654321',
            'password' => Hash::make('password123'),
            'role' => 0,
            'status' => 0 // Inactive
        ]);

        // Test login with inactive user
        $response = $this->postJson('/login', [
            'login' => 'inactive@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(403)
                ->assertJson([
                    'success' => false,
                    'message' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.'
                ]);
    }

    public function test_login_validation_errors()
    {
        // Test with missing login field
        $response = $this->postJson('/login', [
            'password' => 'password123'
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['login']);

        // Test with missing password field
        $response = $this->postJson('/login', [
            'login' => 'test@example.com'
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['password']);

        // Test with short password
        $response = $this->postJson('/login', [
            'login' => 'test@example.com',
            'password' => '123'
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['password']);
    }

    public function test_admin_user_redirects_to_admin_panel()
    {
        // Create an admin user
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'phone' => '0111111111',
            'password' => Hash::make('password123'),
            'role' => 1, // Admin
            'status' => 1
        ]);

        // Test admin login
        $response = $this->postJson('/login', [
            'login' => 'admin@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'redirect' => '/admin'
                ]);
    }
}
