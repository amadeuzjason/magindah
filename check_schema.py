import sqlite3

try:
    conn = sqlite3.connect('d:/12/test-excel-py/data_pipeline.sqlite')
    cursor = conn.cursor()
    cursor.execute("PRAGMA table_info(records_current)")
    columns = cursor.fetchall()
    print(f"{'cid':<5} {'name':<25} {'type':<10} {'notnull':<10} {'dflt_value':<15} {'pk':<5}")
    print("-" * 80)
    for col in columns:
        print(f"{col[0]:<5} {col[1]:<25} {col[2]:<10} {col[3]:<10} {str(col[4]):<15} {col[5]:<5}")
    conn.close()
except Exception as e:
    print("Error:", e)
