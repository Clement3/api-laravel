<?php

namespace Tests\Feature;

use App\Item;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    /** @test */
    public function an_user_has_a_profile()
    {
        $user = factory(User::class)->create();

        $response = $this->json('GET', '/api/user/' . $user->name);
        
        $response->assertStatus(200)->assertJson([
            'status' => true,
            'data' => [
                'name' => $user->name
            ]
        ]);        
    }

    /** @test */
    public function an_user_has_a_profile_with_items()
    {
        $user = factory(User::class)->create();
        $item = factory(Item::class)->create([
            'user_id' => $user->id
        ]);

        $response = $this->json('GET', '/api/user/' . $user->name . '/items');

        $response->assertStatus(200)->assertJson([
            'status' => true,
            'data' => [
                'items' => [
                    [
                        'title' => $item->title
                    ]
                ]
            ]
        ]);        
    }    
}
