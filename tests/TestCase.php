<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Modules\Accounting\Models\Account;
use Modules\Accounting\Models\Year;
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    protected $permissions = [];
    protected $user;
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = factory(\App\User::class)->create();
        
        // Here's an example derived from the php-unit docs to skip a test that works on Laravel 6/PHP 7.3
        // $this->markTestSkipped(
        //     'This test will be skipped when you run `php-unit`.'
        // );
    }
    public function setPermissions($permissions){
        foreach (explode('|', str_replace(' ', '', $permissions)) as $permission) {
            if(explode('-', $permission)[1] == '*'){
                foreach (['create', 'read', 'update', 'delete'] as $p) {
                    $permission = \App\Permission::create([
                    'name' => $p,
                    ]);
                    $this->user->permissions()->attach($permission);
                    
                }
            }else{
                $permission = \App\Permission::create([
                'name' => $permission,
                ]);
                $this->user->permissions()->attach($permission);
            }
        }
    }
    public function accountsSeeder(){
        foreach (config('accounting.seeder.accounts') as $account) {
            $this->createAccount($account);
        }
    }
    
    public function createAccount($attributes)
    {
        $account = Account::firstOrCreate(array_except($attributes, 'accounts'));
        if(array_key_exists('accounts', $attributes)){
            foreach ($attributes['accounts'] as $child) {
                $child['main_account'] = $account->id;
                $this->createAccount($child);
            }
        }
    }
    
    public function createYear(){
        $year = factory(Year::class)->create(['status' => Year::STATUS_OPENED]);
        $this->AssertFalse(is_null($year), 'Year was not created');
        return $year;
    }
    
    public function year(){
        return Year::orderBy('updated_at', 'DESC')->limit(1)->get()->last();
    }
    
    public function assertYearExists($year = null){
        $year = is_null($year) ? $this->year() : $year;
        $this->assertFalse(is_null($year), "Year is not exists");
    }
    
    public function assertYearIsOpened($year = null){
        $year = is_null($year) ? $this->year() : $year;
        $this->assertYearExists($year);
        $this->assertTrue($year->status == Year::STATUS_OPENED, "Year is not opened");
    }
    
    public function assertYearIsActive($year = null){
        $year = is_null($year) ? $this->year() : $year;
        $this->assertYearIsOpened($year);
        $this->assertTrue($year->isActive(), "Year is not active");
    }
    
    public function assertResponseStatus($response, $expectedHttpCode = 200, $message = "Response status mismatch")
    {
        $this->assertTrue($response->getStatusCode() === $expectedHttpCode, $message);
    }
    
    // public static function assertTrue($condition, string $message = 'Failed asserting that false is true'): void
    // {
    //     if (!$condition) static::fail($message);
    // }
    
    // public static function assertFalse($condition, string $message = 'Failed asserting that true is false'): void
    // {
    //     if ($condition) static::fail($message);
    // }
    
    // public function test_download_personal_details_file()
    // {
    //     $user = //created a authorized valid user
        
    //     $order = factory(Order::class)
    //     ->create([
    //     'user' => $user->id
    //     ]);
    //     \Storage::fake(config('filesystems.default')); //or any storage you need to fake
    //         \Storage::put($order->path, ''); //Empty file
            
    //         $this->actingAs($user)->get(route('order.personal_info.download', $order))->assertStatus(200);
            
    // }
}