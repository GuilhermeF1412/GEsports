import subprocess
import sys
import os
from threading import Thread

def run_laravel():
    print("Starting Laravel server...")
    subprocess.run(["php", "artisan", "serve"], cwd=os.getcwd())

def run_fastapi():
    print("Starting FastAPI server...")
    subprocess.run(["uvicorn", "main:app", "--port", "8001"], cwd=os.path.join(os.getcwd(), "GEsportsApi"))

if __name__ == "__main__":
    print("Starting both servers...")
    
    # Create threads for each server
    laravel_thread = Thread(target=run_laravel)
    fastapi_thread = Thread(target=run_fastapi)
    
    # Start both servers
    laravel_thread.start()
    fastapi_thread.start()
    
    try:
        # Keep the main thread alive
        laravel_thread.join()
        fastapi_thread.join()
    except KeyboardInterrupt:
        print("\nStopping servers...")
        sys.exit(0) 