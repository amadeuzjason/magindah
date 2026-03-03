import sqlite3
import pandas as pd

DB_PATH = 'd:/12/test-excel-py/data_pipeline.sqlite'

try:
    conn = sqlite3.connect(DB_PATH)
    cursor = conn.cursor()
    
    # 1. Check Schema
    print("--- Schema Info ---")
    cursor.execute("PRAGMA table_info(records_current)")
    columns = cursor.fetchall()
    for col in columns:
        print(col)
        
    # 2. Check current status distribution
    print("\n--- Status Distribution ---")
    df = pd.read_sql("SELECT STATUS, COUNT(*) as count FROM records_current GROUP BY STATUS", conn)
    print(df)
    
    # 3. Check Approved By content
    print("\n--- Approved By Sample ---")
    df_ab = pd.read_sql('SELECT "APPROVED BY", COUNT(*) as count FROM records_current GROUP BY "APPROVED BY"', conn)
    print(df_ab)
    
    conn.close()
    
except Exception as e:
    print(f"Error: {e}")
