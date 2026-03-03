<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ApprovalTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Override config to use the real database file instead of :memory:
        // This ensures the service connects to the DB where records_current table exists.
        config(['database.sqlite_path' => 'd:/12/test-excel-py/data_pipeline.sqlite']);
    }

    // Note: We are using SQLite directly via service, not Eloquent models for records_current.
    // So RefreshDatabase trait might not work as expected for that table if it's not in migrations.
    // But we can check side effects.

    public function test_new_submission_has_submitted_status_and_null_approver()
    {
        Storage::fake('public');
        
        $file = UploadedFile::fake()->create('proposal.pdf', 100);

        $response = $this->withSession(['username' => 'testuser', 'logged_in' => true])
            ->postJson(route('input.store'), [
                'nop' => 'TEST-NOP-' . rand(1000, 9999),
                'program' => 'Test Program',
                'kategori' => 'New Product',
                'proposal' => $file,
                'budget' => 50000000,
                // Intentionally sending status and approved_by to test they are ignored/overwritten
                'status' => 'Approved', 
                'approved_by' => 'Hacker'
            ]);

        if ($response->status() !== 200) {
            dump($response->json());
        }

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        // Verify in Database directly since we don't have a Model for records_current easily accessible in tests usually
        // But we can use DB facade if configured.
        // Assuming the app uses the sqlite service which connects to a specific file.
        
        $dbPath = config('database.sqlite_path', base_path('database/database.sqlite')); 
        // Wait, the app uses a custom path in SQLiteService: d:\12\test-excel-py\data_pipeline.sqlite
        // We need to check that file.
        
        $db = new \SQLite3('d:/12/test-excel-py/data_pipeline.sqlite');
        $result = $db->query("SELECT * FROM records_current WHERE PROGRAM = 'Test Program' ORDER BY id DESC LIMIT 1");
        $row = $result->fetchArray(SQLITE3_ASSOC);
        
        $this->assertNotNull($row, 'Record not found in DB');
        $this->assertEquals('Submitted', $row['STATUS'], 'Status should be Submitted');
        $this->assertNull($row['APPROVED BY'], 'Approved By should be null');
        $this->assertNotEquals('Hacker', $row['APPROVED BY']);
    }
}
