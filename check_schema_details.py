import sqlite3

try:
    conn = sqlite3.connect('d:/12/test-excel-py/data_pipeline.sqlite')
    cursor = conn.cursor()
    cursor.execute("PRAGMA table_info(records_current)")
    columns = cursor.fetchall()
    for col in columns:
        print(col)
    conn.close()
except Exception as e:
    print("Error:", e)
