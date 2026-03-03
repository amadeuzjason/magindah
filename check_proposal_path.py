import sqlite3
import os

DB_PATH = 'd:/12/test-excel-py/data_pipeline.sqlite'

try:
    conn = sqlite3.connect(DB_PATH)
    cursor = conn.cursor()
    cursor.execute("SELECT PROPOSAL FROM records_current ORDER BY id DESC LIMIT 1")
    row = cursor.fetchone()
    
    if row:
        print(f"Proposal Path in DB: {row[0]}")
        
        # Check if file exists
        # In Laravel, 'storage/' usually maps to 'public/storage' in web root
        # The physical location is usually 'storage/app/public' in Laravel root
        
        laravel_root = 'd:/12/test-excel-py/laravel-dashboard'
        
        # If DB path is 'storage/proposals/filename.pdf', 
        # it corresponds to 'storage/app/public/proposals/filename.pdf' relative to laravel root IF correctly stored.
        # But wait, InputController does: $path = $file->storeAs('public/proposals', $filename);
        # Which stores in storage/app/public/proposals/filename.pdf.
        # Then DB stores: str_replace('public/', 'storage/', $path) -> storage/proposals/filename.pdf.
        
        # So we should check:
        # 1. d:/12/test-excel-py/laravel-dashboard/storage/app/public/proposals/filename.pdf
        # 2. d:/12/test-excel-py/laravel-dashboard/public/storage/proposals/filename.pdf (symlinked)
        
        filename = row[0].replace('storage/', '') # remove 'storage/' prefix to get 'proposals/filename.pdf' relative to 'public' folder in storage/app
        
        physical_path = os.path.join(laravel_root, 'storage', 'app', 'public', filename)
        print(f"Checking physical path: {physical_path}")
        print(f"Exists: {os.path.exists(physical_path)}")
        
        symlink_path = os.path.join(laravel_root, 'public', 'storage', filename)
        print(f"Checking symlink path: {symlink_path}")
        print(f"Exists: {os.path.exists(symlink_path)}")
        
    else:
        print("No records found.")
        
    conn.close()

except Exception as e:
    print(f"Error: {e}")
