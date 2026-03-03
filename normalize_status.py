import sqlite3

DB_PATH = 'd:/12/test-excel-py/data_pipeline.sqlite'

try:
    conn = sqlite3.connect(DB_PATH)
    cursor = conn.cursor()
    
    print("--- Normalizing Statuses ---")
    
    # Update all statuses that are NOT Approved or Rejected (case insensitive check) to 'Submitted'
    # SQLITE is case sensitive by default for text comparison unless NOCASE collation is used.
    # We will use UPPER() for comparison.
    
    sql = """
    UPDATE records_current
    SET STATUS = 'Submitted'
    WHERE UPPER(STATUS) NOT IN ('APPROVED', 'REJECTED')
    """
    
    cursor.execute(sql)
    rows_affected = cursor.rowcount
    print(f"Updated {rows_affected} records to 'Submitted'.")
    
    # Verify
    cursor.execute("SELECT STATUS, COUNT(*) FROM records_current GROUP BY STATUS")
    print("\nNew Status Distribution:")
    for row in cursor.fetchall():
        print(row)
        
    conn.commit()
    conn.close()
    
except Exception as e:
    print(f"Error: {e}")
