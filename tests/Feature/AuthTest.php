<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    /**
     * Test if the user can create an account
     *
     * @return void
     */
    public function testRegister()
    {
        $response = $this->json('POST', '/api/register', [
            'name' => 'teardown',
            'email' => 'teardown@test.com',
            'password' => 'test12345',
            'password_confirmation' => 'test12345'
        ]);
                
        $response->assertStatus(201)->assertJson([
            'status' => true
        ]);        
    }

    /**
     * Test if the user can create an account
     *
     * @return void
     */
    public function testForgotPassword()
    {
        $response = $this->json('POST', '/api/password/email', [
            'email' => 'teardown@test.com'
        ]);
                
        $response->assertStatus(201)->assertJson([
            'status' => true
        ]);        
    }
}
