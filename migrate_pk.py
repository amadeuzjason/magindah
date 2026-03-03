import sqlite3
import pandas as pd
import logging

# Configure logging
logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(levelname)s - %(message)s')

db_path = 'd:/12/test-excel-py/data_pipeline.sqlite'

def migrate():
    try:
        conn = sqlite3.connect(db_path)
        logging.info("Connected to database.")

        # 1. Check if records_current has 'id' column
        cursor = conn.execute("PRAGMA table_info(records_current)")
        columns = [row[1] for row in cursor.fetchall()]
        
        if 'id' in columns and 'NOP' not in [row[1] for row in cursor.execute("PRAGMA index_info(records_current)").fetchall()]: 
             # Rough check if schema is already correct-ish. 
             # Actually PRAGMA table_info gives 'pk' flag.
             cursor = conn.execute("PRAGMA table_info(records_current)")
             for col in cursor.fetchall():
                 if col[1] == 'id' and col[5] == 1:
                     logging.info("Schema already seems to have 'id' as PK. Skipping migration.")
                     conn.close()
                     return

        logging.info("Starting migration...")

        # 2. Read existing data
        # We handle case where table might not exist
        try:
            df = pd.read_sql("SELECT * FROM records_current", conn)
            logging.info(f"Loaded {len(df)} records from existing table.")
        except Exception as e:
            logging.warning(f"Could not read records_current: {e}. Assuming empty or non-existent.")
            df = pd.DataFrame()

        # 3. Rename old table
        try:
            conn.execute("ALTER TABLE records_current RENAME TO records_current_old_pk")
            logging.info("Renamed old table to records_current_old_pk")
        except Exception as e:
            logging.error(f"Error renaming table: {e}")
            # If rename fails, maybe it doesn't exist?
            pass

        # 4. Create new table with ID as PK
        # We need to dynamically construct the CREATE statement based on what we know about columns
        # Or we can rely on process_export.ensure_schema logic if we import it, but better to be explicit here.
        
        # If df is not empty, use its columns. If empty, use a hardcoded list of expected columns.
        if not df.empty:
            cols = [c for c in df.columns if c not in ['id', 'ID']]
        else:
            # Fallback to standard columns
            cols = [
                "NOP", "PROGRAM", "KATEGORI", "JUSTIFIKASI", "PROPOSAL", "BUDGET", 
                "REVENUE", "COST", "PROFIT", "INCREMENTAL 1", "INCREMENTAL 2", 
                "INCREMENTAL 3", "STATUS", "PILOT", "DRIVEN PROGRAM", "ASSIGN BY", 
                "APPROVED BY", "row_hash", "ingest_timestamp", "source_file"
            ]

        col_defs = ", ".join([f'"{c}" TEXT' for c in cols])
        
        create_sql = f"""
            CREATE TABLE records_current (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                {col_defs}
            )
        """
        conn.execute(create_sql)
        logging.info("Created new records_current table with id PK.")

        # 5. Insert data back
        if not df.empty:
            # Ensure we only insert columns that exist in the new table definition (which matches df columns excluding potential old id)
            # df might have 'id' if we are re-running? No, we filtered it out in cols definition logic if present.
            # But we need to make sure df doesn't have 'id' column when we call to_sql if we want auto-increment.
            if 'id' in df.columns:
                df = df.drop(columns=['id'])
                
            df.to_sql('records_current', conn, if_exists='append', index=False)
            logging.info(f"Inserted {len(df)} records into new table.")

        # 6. Drop old table
        try:
            conn.execute("DROP TABLE records_current_old_pk")
            logging.info("Dropped old table.")
        except:
            pass

        conn.commit()
        logging.info("Migration completed successfully.")
        
    except Exception as e:
        logging.error(f"Migration failed: {e}")
        if conn:
            conn.rollback()
    finally:
        if conn:
            conn.close()

if __name__ == "__main__":
    migrate()
