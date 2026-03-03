import os

link_path = 'd:/12/test-excel-py/laravel-dashboard/public/storage'

try:
    target = os.readlink(link_path)
    print(f"Symlink points to: {target}")
except Exception as e:
    print(f"Error reading link: {e}")
