<?php

namespace Modules\Accounting\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Accounting\Models\{Year, Account, Entry};

class EntryTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->accountsSeeder();
    }
    /**
     * A basic unit test create entry.
     *
     * @return void
     */
    public function testCreateEntry()
    {
        // $this->createYear();
        $this->assertYearIsActive();
        $entry = factory(Entry::class)->create();
        $entries = Entry::all();
        $last_entry = $entries->last();
        $this->assertTrue(true);
        $this->assertTrue($entries->count() > 0, "No entries");
        $this->assertDatabaseHas('entries', $entry->getAttributes());
        $this->assertTrue($entry->id == $last_entry->id, "Entry not inserted");

    }
}
