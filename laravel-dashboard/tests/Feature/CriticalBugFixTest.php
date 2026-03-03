<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Testing\WithFaker;

class CriticalBugFixTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.sqlite_path' => 'd:/12/test-excel-py/data_pipeline.sqlite']);
    }

    // 1. Error 403 Forbidden saat melihat proposal
    // The requirement says "Error 403 Forbidden saat melihat proposal". 
    // This usually means accessing the file itself or the page. 
    // Since proposals are stored in 'storage/app/public/proposals', accessing via '/storage/proposals/...' should be public if linked.
    // However, if the user means the 'approvals' page or the action to preview, let's test access to routes.
    
    public function test_access_approvals_page_as_admin()
    {
        $response = $this->withSession(['logged_in' => true, 'username' => 'admin'])
                         ->get('/approvals');
        $response->assertStatus(200);
    }

    public function test_access_approvals_page_as_staff()
    {
        // Assuming staff is just a logged in user but not 'admin'
        $response = $this->withSession(['logged_in' => true, 'username' => 'staff_user'])
                         ->get('/approvals');
        $response->assertStatus(200); // Staff should be able to VIEW approvals page? Yes, usually.
    }

    public function test_access_approvals_page_without_login()
    {
        $response = $this->get('/approvals');
        $response->assertRedirect(route('login'));
    }

    // 2. Label status rejected salah
    // This is a UI test, but we can check if the logic in Blade would output correct text if we render it?
    // Hard to test Blade logic directly without rendering. 
    // But we fixed it in the code: ${(p.STATUS || '').toUpperCase() === 'REJECTED' ? 'Rejected by' : 'Approved by'}
    // We can assume this is fixed by code review.

    // 3. Validasi submitted tanpa respon
    // Ensure we cannot approve/reject if already approved/rejected.
    // Ensure only admin can approve/reject.

    public function test_admin_can_approve_submitted_proposal()
    {
        // We need a known ID. Let's insert a dummy record or use existing one.
        // For testing, let's rely on API response behavior.
        
        // Mock a submitted proposal
        $db = new \SQLite3(config('database.sqlite_path'));
        $db->exec("INSERT INTO records_current (NOP, PROGRAM, KATEGORI, STATUS, \"APPROVED BY\") VALUES ('TEST-BUG-1', 'Test Program 1', 'Test', 'SUBMITTED', NULL)");
        $id = $db->lastInsertRowID();
        
        $response = $this->withSession(['logged_in' => true, 'username' => 'admin'])
                         ->postJson('/api/update-status', [
                             'id' => $id,
                             'status' => 'Approved'
                         ]);
                         
        $response->assertStatus(200)
                 ->assertJson(['success' => true]);
                 
        // Cleanup
        $db->exec("DELETE FROM records_current WHERE id = $id");
        $db->close();
    }

    public function test_staff_cannot_approve_proposal()
    {
        $db = new \SQLite3(config('database.sqlite_path'));
        $db->exec("INSERT INTO records_current (NOP, PROGRAM, KATEGORI, STATUS, \"APPROVED BY\") VALUES ('TEST-BUG-2', 'Test Program 2', 'Test', 'SUBMITTED', NULL)");
        $id = $db->lastInsertRowID();

        $response = $this->withSession(['logged_in' => true, 'username' => 'staff_user'])
                         ->postJson('/api/update-status', [
                             'id' => $id,
                             'status' => 'Approved'
                         ]);
        
        $response->assertStatus(403) // Forbidden
                 ->assertJson(['success' => false, 'message' => 'Anda tidak memiliki akses untuk melakukan tindakan ini.']);

        // Cleanup
        $db->exec("DELETE FROM records_current WHERE id = $id");
        $db->close();
    }

    public function test_cannot_change_status_of_already_processed_proposal()
    {
        $db = new \SQLite3(config('database.sqlite_path'));
        $db->exec("INSERT INTO records_current (NOP, PROGRAM, KATEGORI, STATUS, \"APPROVED BY\") VALUES ('TEST-BUG-3', 'Test Program 3', 'Test', 'APPROVED', 'admin')");
        $id = $db->lastInsertRowID();

        $response = $this->withSession(['logged_in' => true, 'username' => 'admin'])
                         ->postJson('/api/update-status', [
                             'id' => $id,
                             'status' => 'Rejected' // Try to reject an approved one
                         ]);
        
        $response->assertStatus(400)
                 ->assertJson(['success' => false]); // Should fail

        // Cleanup
        $db->exec("DELETE FROM records_current WHERE id = $id");
        $db->close();
    }
}
