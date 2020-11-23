<?php

namespace Modules\Accounting\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Accounting\Models\Account;
class AccountControllerTest extends TestCase
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
    * @group accounting-accounts
    */
    public function can_create_account_test()
    {
        $this->withoutExceptionHandling();
        $this->setPermissions('accounts-read|accounts-create');
        $response = $this->actingAs($this->user)->post('/accounting/accounts', [
        'name' => $name = 'Test account',
        'main_account' => $main_account = 1212,
        ]);
        $accounts = Account::all();
        $this->assertTrue($accounts->count() > 0);
        $last = $accounts->last();
        $account = $accounts->last();
        $this->assertDatabaseHas('accounts', $account->toArray());
        $this->assertTrue($last->name == $name && $last->main_account == $main_account);
    }
    
    /**
    * @test
    * @group accounting
    * @group accounting-accounts
    */
    public function can_read_account_test()
    {
        $this->setPermissions('accounts-read');
        
        $this->withoutExceptionHandling();
        $account = factory(Account::class)->create();
        $response = $this->actingAs($this->user)->get('/accounting/accounts/' . $account->id);
        
        $response->assertStatus(200);
        // ->assertJsonStructure([
        //     'id', 'status', 'created_at', 'updated_at'
        // ]);
    }
    
    /**
    * @test
    * @group accounting
    * @group accounting-accounts
    */
    public function can_read_account_statement_test()
    {
        $this->setPermissions('accounts-read');
        
        $this->withoutExceptionHandling();
        $account = factory(Account::class)->create();
        $response = $this->actingAs($this->user)->get('/accounting/accounts/' . $account->id, [
            'view' => 'statement'
        ]);
        
        $response->assertStatus(200);
        // ->assertJsonStructure([
        //     'id', 'status', 'created_at', 'updated_at'
        // ]);
    }
    
    /**
    * @test
    * @group accounting
    * @group accounting-accounts
    */
    public function can_read_all_accounts_test()
    {
        $this->setPermissions('accounts-read');
        
        $this->withoutExceptionHandling();
        $accounts = Account::all();
        $accounts = $accounts->merge(factory(Account::class, range(10, 1000))->create());
        $response = $this->actingAs($this->user)->get('/accounting/accounts');
        $response->assertStatus(200);
        // $response->assertSee('Alpha');
        $allAccounts = Account::all();
        // $this->assertTrue($allAccounts);
        $this->assertCount($accounts->count(), $allAccounts);
    }
    
    /**
    * @test
    * @group accounting
    * @group accounting-accounts
    */
    public function can_edit_account_test()
    {
        
        $this->withoutExceptionHandling();
        $this->setPermissions('accounts-read|accounts-update');
        $account = factory(Account::class)->create();
        $response = $this->actingAs($this->user)->get('/accounting/accounts/' . $account->id . '/edit');
        
        $response->assertStatus(200);
        // ->assertJsonStructure([
        //     'id', 'status', 'created_at', 'updated_at'
        // ]);
    }
    
    /**
    * @test
    * @group accounting
    * @group accounting-accounts
    */
    public function can_update_account_test()
    {
        $this->withoutExceptionHandling();
        $this->setPermissions('accounts-read|accounts-update');
        $account = factory(Account::class)->create();
        $old_name = $account->name;
        $updated_at = (string) $account->updated_at;
        $response = $this->actingAs($this->user)->put('/accounting/accounts/' . $account->id, [
        'name' => $name = 'updated name',
        ]);
        $account = $account->fresh();
        $response->assertStatus(302);
        // dd($old_name, $account->name, $account->name == $name, (string) $account->updated_at, $updated_at, (string) $account->updated_at != $updated_at);
        $this->assertCount(1, Account::where('id', $account->id)->where('name', $name)->get());
        $this->assertTrue($account->name !== $old_name);
        // $this->assertTrue((string) $account->updated_at != $updated_at);
        // $response->assertRedirectedTo();
    }
    
    /**
    * @test
    * @group accounting
    * @group accounting-accounts
    */
    public function can_delete_account_test()
    {
        $this->withoutExceptionHandling();
        $this->setPermissions('accounts-read|accounts-delete');
        $account = factory(Account::class)->create();
        $response = $this->actingAs($this->user)->delete('/accounting/accounts/' . $account->id);
        
        $response->assertStatus(302);
        $this->assertDatabaseMissing('accounts', [
        'id' => $account->id,
        ]);
    }
}