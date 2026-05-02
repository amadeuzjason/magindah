<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ApprovalTest extends TestCase
{
    // protected function setUp(): void
    // {
    //     parent::setUp();
    // }

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

        $db = new \SQLite3(config('database.sqlite_path'));
        $stmt = $db->prepare("SELECT * FROM records_current WHERE PROGRAM = :p ORDER BY id DESC LIMIT 1");
        $stmt->bindValue(':p', 'Test Program');
        $result = $stmt->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);
        $db->close();

        $this->assertNotEmpty($row, 'Record not found in DB');
        $this->assertEquals('Submitted', $row['STATUS'] ?? null, 'Status should be Submitted');
        $this->assertTrue(!isset($row['APPROVED BY']) || $row['APPROVED BY'] === null, 'Approved By should be null');
    }
}
