<?php

namespace Tests\Feature;

use App\Item;
use App\User;
use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookmarkTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function an_user_have_bookmarks()
    {
        Passport::actingAs(factory(User::class)->create());

        $response = $this->json('GET', '/api/bookmarks');

        $response->assertStatus(200)->assertJson([
            'status' => true
        ]);         
    }

    /** @test */
    public function an_user_have_bookmarks_with_items()
    {
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $item = factory(Item::class)->create([
            'user_id' => $user2->id
        ]);

        DB::table('bookmarks')->insert([
            'user_id' => $user->id,
            'item_id' => $item->id
        ]);

        Passport::actingAs($user);

        $response = $this->json('GET', '/api/bookmarks');

        $response->assertStatus(200)->assertJson([
            'status' => true,
            'data' => [
                [
                    'item' => [
                        'title' => $item->title
                    ]
                ]
            ]
        ]);         
    }
    
    /** @test */
    public function an_user_can_create_a_bookmark()
    {
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $item = factory(Item::class)->create([
            'user_id' => $user2->id
        ]);

        Passport::actingAs($user);
            
        $response = $this->json('GET', '/api/bookmark/' . $item->slug . '/create');
        $response->assertStatus(201)->assertJson([
            'status' => true,
        ]);         
    }

    /** @test */
    public function an_user_cant_create_a_bookmark_if_its_own_item()
    {
        $user = factory(User::class)->create();

        $item = factory(Item::class)->create([
            'user_id' => $user->id
        ]);      
        
        Passport::actingAs($user);

        $response = $this->json('GET', '/api/bookmark/' . $item->slug . '/create');

        $response->assertStatus(403);        
    }
    
    /** @test */
    public function an_user_cant_create_a_bookmark_if_already_exist()
    {
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $item = factory(Item::class)->create([
            'user_id' => $user2->id
        ]);

        DB::table('bookmarks')->insert([
            'user_id' => $user->id,
            'item_id' => $item->id
        ]);

        Passport::actingAs($user);

        $response = $this->json('GET', '/api/bookmark/' . $item->slug . '/create');

        $response->assertStatus(403);  
    }
    
    /** @test */
    public function an_user_can_delete_a_bookmark()
    {
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $item = factory(Item::class)->create([
            'user_id' => $user2->id
        ]);

        DB::table('bookmarks')->insert([
            'user_id' => $user->id,
            'item_id' => $item->id
        ]);

        Passport::actingAs($user);

        $response = $this->json('DELETE', '/api/bookmark/' . $item->slug . '/delete');

        $response->assertStatus(200)->assertJson([
            'status' => true,
        ]);  
    } 

    /** @test */
    public function an_user_can_delete_a_bookmark_if_the_item_is_softdelete()
    {
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $item = factory(Item::class)->create([
            'user_id' => $user2->id
        ]);

        DB::table('bookmarks')->insert([
            'user_id' => $user->id,
            'item_id' => $item->id
        ]);
        
        $item->delete();

        Passport::actingAs($user);

        $response = $this->json('DELETE', '/api/bookmark/' . $item->slug . '/delete');

        $response->assertStatus(200)->assertJson([
            'status' => true,
        ]);        
    }

    /** @test */
    public function an_user_cant_delete_a_bookmark_if_not_its_own_bookmark()
    {
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $user3 = factory(User::class)->create();

        $item = factory(Item::class)->create([
            'user_id' => $user2->id
        ]);

        DB::table('bookmarks')->insert([
            'user_id' => $user->id,
            'item_id' => $item->id
        ]);

        Passport::actingAs($user3);

        $response = $this->json('DELETE', '/api/bookmark/' . $item->slug . '/delete');

        $response->assertStatus(404)->assertJson([
            'status' => false,
        ]);
    }        
}
