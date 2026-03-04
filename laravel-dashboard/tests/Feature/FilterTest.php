<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Session;

class FilterTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.sqlite_path' => 'd:/12/test-excel-py/data_pipeline.sqlite']);
    }

    public function test_api_data_returns_counts_and_rows()
    {
        $response = $this->withSession(['logged_in' => true, 'username' => 'admin'])
                         ->get('/api/data');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'columns',
                     'rows',
                     'counts' => [
                         'all',
                         'submitted',
                         'approved',
                         'rejected'
                     ]
                 ]);
    }

    public function test_filter_submitted()
    {
        // Insert test data
        $db = new \SQLite3(config('database.sqlite_path'));
        $db->exec("INSERT INTO records_current (NOP, PROGRAM, STATUS) VALUES ('TEST-FILTER-1', 'Filter Test 1', 'SUBMITTED')");
        $id1 = $db->lastInsertRowID();
        
        $db->exec("INSERT INTO records_current (NOP, PROGRAM, STATUS) VALUES ('TEST-FILTER-2', 'Filter Test 2', 'APPROVED')");
        $id2 = $db->lastInsertRowID();

        $response = $this->withSession(['logged_in' => true, 'username' => 'admin'])
                         ->get('/api/data?status=submitted');
        
        $response->assertStatus(200);
        $rows = $response->json('rows');
        
        // Assert that all returned rows are SUBMITTED
        foreach ($rows as $row) {
            $this->assertEquals('SUBMITTED', strtoupper($row['STATUS'] ?? 'SUBMITTED'));
        }
        
        // Cleanup
        $db->exec("DELETE FROM records_current WHERE id IN ($id1, $id2)");
        $db->close();
    }

    public function test_filter_approved()
    {
        // Insert test data
        $db = new \SQLite3(config('database.sqlite_path'));
        $db->exec("INSERT INTO records_current (NOP, PROGRAM, STATUS) VALUES ('TEST-FILTER-3', 'Filter Test 3', 'APPROVED')");
        $id = $db->lastInsertRowID();

        $response = $this->withSession(['logged_in' => true, 'username' => 'admin'])
                         ->get('/api/data?status=approved');
        
        $response->assertStatus(200);
        $rows = $response->json('rows');
        
        // Assert that all returned rows are APPROVED
        foreach ($rows as $row) {
            $this->assertEquals('APPROVED', strtoupper($row['STATUS']));
        }
        
        // Cleanup
        $db->exec("DELETE FROM records_current WHERE id = $id");
        $db->close();
    }
}
