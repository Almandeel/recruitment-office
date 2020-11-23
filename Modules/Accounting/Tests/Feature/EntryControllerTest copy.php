<?php

namespace Modules\Accounting\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Accounting\Models\{Year, Account, Entry};
class EntryControllerTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->accountsSeeder();
    }
    /**
    * @test
    * @group accounting
    * @group accounting-entries
    */
    public function can_create_entry_test()
    {
        $this->setPermissions('entries-read|entries-create');
        $this->createYear();
        $this->assertYearIsActive();
        $debts[] = factory(Account::class)->create()->id;
        $debts[] = factory(Account::class)->create()->id;
        $debts[] = factory(Account::class)->create()->id;
        $credits[] = factory(Account::class)->create()->id;
        $credits[] = factory(Account::class)->create()->id;
        $response = $this->actingAs($this->user)->post('/accounting/entries', [
        'entry_date' => $entry_date = date('Y-m-d'),
        'amount' => $amount = 3000,
        'total_debts' => $amount,
        'details' => $details = 'modi tenetur eligendi ducimus voluptatibus minima animi. Sit, illum dicta nesciunt error placeat accusamus!',
        'total_credits' => $amount,
        'debts_accounts' => $debts,
        'debts_amounts' => [1000, 1300, 700],
        'credits_accounts' => $credits,
        'credits_amounts' => [1000, 2000],
        ]);
        $entries = Entry::all();
        $this->assertTrue($entries->count() > 0);
        $entry = $entries->last();
        $this->assertDatabaseHas('entries', $entry->toArray());
        $this->assertTrue($entry->amount == $amount && $entry->entry_date == $entry_date && $entry->details == $details);
    }
    
    
    /**
    * @test
    * @group accounting
    * @group accounting-entries
    */
    public function can_read_entry_test()
    {
        $this->setPermissions('entries-read');
        $this->createYear();
        $this->assertYearIsActive();
        
        $entry = factory(Entry::class)->create();
        $response = $this->actingAs($this->user)->get('/accounting/entries/' . $entry->id);
        
        $response->assertStatus(200);
        // ->assertJsonStructure([
        //     'id', 'status', 'created_at', 'updated_at'
        // ]);
    }
    
    /**
    * @test
    * @group accounting
    * @group accounting-entries
    */
    public function can_read_all_entries_test()
    {
        $this->setPermissions('entries-read');
        $this->createYear();
        $this->assertYearIsActive();
        
        $entries = Entry::all();
        $entries = $entries->merge(factory(Entry::class, range(10, 1000))->create());
        $response = $this->actingAs($this->user)->get('/accounting/entries');
        $response->assertStatus(200);
        // $response->assertSee('Alpha');
        $allEntrys = Entry::all();
        // $this->assertTrue($allEntrys);
        $this->assertCount($entries->count(), $allEntrys);
    }
    
    /**
    * @test
    * @group accounting
    * @group accounting-entries
    */
    public function can_edit_entry_test()
    {
        $this->setPermissions('entries-read|entries-update');
        $this->createYear();
        $this->assertYearIsActive();
        
        $entry = factory(Entry::class)->create();
        $response = $this->actingAs($this->user)->get('/accounting/entries/' . $entry->id . '/edit');
        
        $response->assertStatus(200);
        // ->assertJsonStructure([
        //     'id', 'status', 'created_at', 'updated_at'
        // ]);
    }
    
    /**
    * @test
    * @group accounting
    * @group accounting-entries
    */
    public function can_update_entry_test()
    {
        $this->setPermissions('entries-read|entries-update');
        $entry = factory(Entry::class)->create();
        $old_name = $entry->name;
        $updated_at = (string) $entry->updated_at;
        $response = $this->actingAs($this->user)->put('/accounting/entries/' . $entry->id, [
        'name' => $name = 'updated name',
        ]);
        $entry = $entry->fresh();
        $response->assertStatus(302);
        // dd($old_name, $entry->name, $entry->name == $name, (string) $entry->updated_at, $updated_at, (string) $entry->updated_at != $updated_at);
        $this->assertCount(1, Entry::where('id', $entry->id)->where('name', $name)->get());
        $this->assertTrue($entry->name !== $old_name);
        // $this->assertTrue((string) $entry->updated_at != $updated_at);
        // $response->assertRedirectedTo();
    }
    
    /**
    * @test
    * @group accounting
    * @group accounting-entries
    */
    public function can_delete_entry_test()
    {
        $this->setPermissions('entries-read|entries-delete');
        $entry = factory(Entry::class)->create();
        $response = $this->actingAs($this->user)->delete('/accounting/entries/' . $entry->id);
        
        $response->assertStatus(302);
        $this->assertDatabaseMissing('entries', [
        'id' => $entry->id,
        ]);
    }
}