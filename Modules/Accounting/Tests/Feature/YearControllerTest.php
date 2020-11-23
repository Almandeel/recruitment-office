<?php

namespace Modules\Accounting\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Modules\Accounting\Models\Year;
class YearControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
    * @test
    * @group accounting
    * @group accounting-years
    */
    public function can_create_year_test()
    {
        $this->withoutExceptionHandling();
        
        $response = $this->actingAs(factory(User::class)->create())->post('/accounting/years', [
        'opened_at' => $opened_at = '2010-09-20',
        'id' => $id = date('Ymd', strtotime($opened_at)),
        'status' => $status = array_rand([0, 1]),
        ]);
        $year = Year::first();
        $this->assertCount(1,Year::all());
        $this->assertDatabaseHas('years', $year->toArray());
    }
    
    /**
    * @test
    * @group accounting
    * @group accounting-years
    */
    public function can_read_year_test()
    {
        $this->withoutExceptionHandling();
        $this->accountsSeeder();
        $year = factory(Year::class)->create();
        $response = $this->actingAs(factory(User::class)->create())->get('/accounting/years/' . $year->id);
        
        $response->assertStatus(200);
        // ->assertJsonStructure([
        //     'id', 'status', 'created_at', 'updated_at'
        // ]);
    }
    
    /**
    * @test
    * @group accounting
    * @group accounting-years
    */
    public function can_read_all_years_test()
    {
        $this->withoutExceptionHandling();
        $this->accountsSeeder();
        $years = factory(Year::class, range(10, 1000))->create();
        $response = $this->actingAs(factory(User::class)->create())->get('/accounting/years');
        $response->assertStatus(200);
        // $response->assertSee('Alpha');
        $allYears = Year::all();
        // $this->assertTrue($allYears);
        $this->assertCount($years->count(), $allYears);
    }
    
    /**
    * @test
    * @group accounting
    * @group accounting-years
    */
    public function can_edit_year_test()
    {
        $this->withoutExceptionHandling();
        $this->accountsSeeder();
        $year = factory(Year::class)->create();
        $response = $this->actingAs(factory(User::class)->create())->get('/accounting/years/' . $year->id . '/edit');
        
        $response->assertStatus(200);
        // ->assertJsonStructure([
        //     'id', 'status', 'created_at', 'updated_at'
        // ]);
    }
    
    /**
    * @test
    * @group accounting
    * @group accounting-years
    */
    public function can_update_year_test()
    {
        $this->withoutExceptionHandling();
        $year = factory(Year::class)->create();
        do {
            $status = array_rand(Year::STATUSES);
        } while ($status == $year->status);

        $response = $this->actingAs(factory(User::class)->create())->put('/accounting/years/' . $year->id, [
            // 'opened_at' => $opened_at = '2020-09-20',
            // 'id' => $id = date('Ymd', strtotime($opened_at)),
            'status' => $status,
        ]);
        
        // $response->assertStatus(200);
        $this->assertCount(1, Year::where([
            'id' => $year->id,
            'opened_at' => $year->opened_at,
            'status' => ($status),
        ])->get());
    }
    
    /**
    * @test
    * @group accounting
    * @group accounting-years
    */
    public function can_delete_year_test()
    {
        $this->withoutExceptionHandling();
        $year = factory(Year::class)->create();
        $response = $this->actingAs(factory(User::class)->create())->delete('/accounting/years/' . $year->id);
        
        // $response->assertStatus(200);
        $this->assertDatabaseMissing('years', [
        'id' => $year->id,
        ]);
    }
}