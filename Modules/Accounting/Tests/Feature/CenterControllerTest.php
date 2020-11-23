<?php

namespace Modules\Accounting\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Accounting\Models\{Account, Center};
class CenterControllerTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();
        $this->accountsSeeder();
    }
    /**
    * @test
    * @group accounting
    * @group accounting-centers
    */
    public function can_create_center_test()
    {
        $this->withoutExceptionHandling();
        $this->setPermissions('centers-read|centers-create');
        $response = $this->actingAs($this->user)->post('/accounting/centers', [
            'name' => $name = 'Test center',
            'type' => $type = Center::TYPE_COST,
        ]);
        $centers = Center::all();
        $this->assertTrue($centers->count() > 0);
        $last = $centers->last();
        $center = $centers->last();
        $this->assertDatabaseHas('centers', $center->toArray());
        $this->assertTrue($last->name == $name && $last->type == $type);
    }
    /**
    * @test
    * @group accounting
    * @group accounting-centers
    */
    public function can_add_account_to_center_test()
    {
        $this->withoutExceptionHandling();
        $this->setPermissions('centers-read|centers-update');
        $center = factory(Center::class)->create();
        $account = factory(Account::class)->create();
        $response = $this->actingAs($this->user)->put('/accounting/centers/' . $center->id, [
            'operation' => 'add',
            'account_id' => $account->id,
        ]);
        $this->assertTrue($center->accounts->contains($account->id));
        // $response->assertRedirectedTo('/accounting/centers/' . $center->id);
    }

    /**
    * @test
    * @group accounting
    * @group accounting-centers
    */
    public function can_remove_account_from_center_test()
    {
        $this->withoutExceptionHandling();
        $this->setPermissions('centers-read|centers-update');
        $center = factory(Center::class)->create();
        $account = factory(Account::class)->create();
        $response = $this->actingAs($this->user)->put('/accounting/centers/' . $center->id, [
            'operation' => 'remove',
            'account_id' => $account->id,
        ]);
        $this->assertFalse($center->accounts->contains($account->id));
        // $response->assertRedirectedTo('/accounting/centers/' . $center->id);
    }
    
    /**
    * @test
    * @group accounting
    * @group accounting-centers
    */
    public function can_read_center_test()
    {
        $this->setPermissions('centers-read');
        
        $this->withoutExceptionHandling();
        $center = factory(Center::class)->create();
        $response = $this->actingAs($this->user)->get('/accounting/centers/' . $center->id);
        
        $response->assertStatus(200);
        // ->assertJsonStructure([
        //     'id', 'status', 'created_at', 'updated_at'
        // ]);
    }
    
    /**
    * @test
    * @group accounting
    * @group accounting-centers
    */
    public function can_read_all_centers_test()
    {
        $this->setPermissions('centers-read');
        
        $this->withoutExceptionHandling();
        $centers = Center::all();
        $centers = $centers->merge(factory(Center::class, range(10, 1000))->create());
        $response = $this->actingAs($this->user)->get('/accounting/centers');
        $response->assertStatus(200);
        // $response->assertSee('Alpha');
        $allCenters = Center::all();
        // $this->assertTrue($allCenters);
        $this->assertCount($centers->count(), $allCenters);
    }
    
    /**
    * @test
    * @group accounting
    * @group accounting-centers
    */
    public function can_edit_center_test()
    {
        
        $this->withoutExceptionHandling();
        $this->setPermissions('centers-read|centers-update');
        $center = factory(Center::class)->create();
        $response = $this->actingAs($this->user)->get('/accounting/centers/' . $center->id . '/edit');
        
        $response->assertStatus(200);
        // ->assertJsonStructure([
        //     'id', 'status', 'created_at', 'updated_at'
        // ]);
    }
    
    /**
    * @test
    * @group accounting
    * @group accounting-centers
    */
    public function can_update_center_test()
    {
        $this->withoutExceptionHandling();
        $this->setPermissions('centers-read|centers-update');
        $center = factory(Center::class)->create();
        $old_name = $center->name;
        $updated_at = (string) $center->updated_at;
        $response = $this->actingAs($this->user)->put('/accounting/centers/' . $center->id, [
        'name' => $name = 'updated name',
        ]);
        $center = $center->fresh();
        $response->assertStatus(302);
        // dd($old_name, $center->name, $center->name == $name, (string) $center->updated_at, $updated_at, (string) $center->updated_at != $updated_at);
        $this->assertCount(1, Center::where('id', $center->id)->where('name', $name)->get());
        $this->assertTrue($center->name !== $old_name);
        // $this->assertTrue((string) $center->updated_at != $updated_at);
        // $response->assertRedirectedTo();
    }
    
    /**
    * @test
    * @group accounting
    * @group accounting-centers
    */
    public function can_delete_center_test()
    {
        $this->withoutExceptionHandling();
        $this->setPermissions('centers-read|centers-delete');
        $center = factory(Center::class)->create();
        $response = $this->actingAs($this->user)->delete('/accounting/centers/' . $center->id);
        
        $response->assertStatus(302);
        $this->assertDatabaseMissing('centers', [
        'id' => $center->id,
        ]);
    }
}