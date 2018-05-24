<?php

namespace Tests\Feature;

use App\User;
use App\Mail\ResetPassword;
use App\Mail\ConfirmEmail;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_user_cant_login_if_not_confirmed()
    {
        $user = factory(User::class)->create();

        Artisan::call('passport:install');

        $client = DB::table('oauth_clients')->where('name', 'Laravel Password Grant Client')->first();

        $response = $this->json('POST', '/oauth/token', [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => $user->email,
            'password' => 'secret'
        ]);

        $response->assertStatus(401)->assertJson([
            'error' => 'user_unconfirmed'
        ]);        
    }

    /** @test */
    public function an_user_cant_login_if_not_activated()
    {
        $user = factory(User::class)->create([
            'is_confirmed' => 1,
            'is_activated' => 0
        ]);

        Artisan::call('passport:install');

        $client = DB::table('oauth_clients')->where('name', 'Laravel Password Grant Client')->first();
                
        $response = $this->json('POST', '/oauth/token', [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => $user->email,
            'password' => 'secret'
        ]);

        $response->assertStatus(401)->assertJson([
            'error' => 'user_inactive'
        ]);        
    }

    public function an_user_can_login()
    {
        $user = factory(User::class)->create([
            'is_confirmed' => 1
        ]);

        Artisan::call('passport:install');

        $client = DB::table('oauth_clients')->where('name', 'Laravel Password Grant Client')->first();
        
        $response = $this->json('POST', '/oauth/token', [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => $user->email,
            'password' => 'secret'
        ]);

        $response->assertStatus(200);    
    }

    /** @test */
    public function an_user_can_create_an_account()
    {
        Mail::fake();

        $response = $this->json('POST', '/api/register', [
            'name' => 'teardown',
            'email' => 'teardown@test.com',
            'password' => 'test12345',
            'password_confirmation' => 'test12345'
        ]);

        Mail::assertQueued(ConfirmEmail::class, function ($mail) {
            return $mail->hasTo('teardown@test.com');
        });

        $response->assertStatus(201)->assertJson([
            'status' => true
        ]);        
    }

    /** @test */
    public function an_user_can_confirm_his_email()
    {
        $register = $this->json('POST', '/api/register', [
            'name' => 'teardown',
            'email' => 'teardown@test.com',
            'password' => 'test12345',
            'password_confirmation' => 'test12345'
        ]);

        $register->assertStatus(201)->assertJson([
            'status' => true
        ]);

        $user = User::whereName('teardown')->first(); 
        
        $this->assertFalse($user->is_confirmed);
        $this->assertNotNull($user->confirmation_token);

        $confirm = $this->json('GET', '/api/register/confirm', [
            'token' => $user->confirmation_token
        ]);

        $confirm->assertStatus(200)->assertJson([
            'status' => true
        ]);

        tap($user->fresh(), function ($user) {
            $this->assertTrue($user->is_confirmed);
            $this->assertNull($user->confirmation_token);
        });        
    }

    /** @test */
    public function confirming_an_invalid_token()
    {
        $response = $this->json('GET', '/api/register/confirm', [
            'token' => 'invalid'
        ]);

        $response->assertStatus(404)->assertJson([
            'status' => false
        ]);    
    }

    /** @test */
    public function an_user_can_forget_his_password()
    {
        Mail::fake();
        
        $user = factory(User::class)->create();

        $response = $this->json('POST', '/api/password/email', [
            'email' => $user->email
        ]);

        Mail::assertQueued(ResetPassword::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        }); 

        $response->assertStatus(201)->assertJson([
            'status' => true
        ]);        
    }

    /** @test */
    public function an_user_can_reset_his_password()
    {   
        Mail::fake();

        $user = factory(User::class)->create();
        
        $password_email = $this->json('POST', '/api/password/email', [
            'email' => $user->email
        ]);

        $password_email->assertStatus(201)->assertJson([
            'status' => true
        ]);
        
        $token = hash_hmac('sha256', str_random(40), $user);
        
        DB::table('password_resets')->where('email', $user->email)->update([
            'token' => password_hash($token, PASSWORD_BCRYPT, ['cost' => '10'])
        ]);

        Mail::assertQueued(ResetPassword::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });

        $response = $this->json('POST', '/api/password/reset', [
            'email' => $user->email,
            'password' => 'test12345',
            'password_confirmation' => 'test12345',
            'token' => $token
        ]);
    
        $response->assertStatus(200)->assertJson([
            'status' => true
        ]);    
    }
}
