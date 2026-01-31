# PowerShell script to start the ML API
Write-Host "Starting Student Risk Prediction ML API..." -ForegroundColor Cyan
Write-Host ""

# Change to ml_api directory
Set-Location -Path "ml_api"

# Check if Python is available
try {
    $pythonVersion = python --version 2>&1
    Write-Host "Python: $pythonVersion" -ForegroundColor Green
} catch {
    Write-Host "ERROR: Python is not installed or not in PATH" -ForegroundColor Red
    exit 1
}

# Check if model file exists
if (Test-Path "student_risk_model.pkl") {
    Write-Host "Model file: Found" -ForegroundColor Green
} else {
    Write-Host "WARNING: student_risk_model.pkl not found!" -ForegroundColor Yellow
    Write-Host "The API will start but predictions will fail." -ForegroundColor Yellow
    Write-Host ""
}

# Check if requirements are installed
Write-Host "Checking dependencies..." -ForegroundColor Cyan
try {
    python -c "import flask; import flask_cors; import joblib; import pandas; import sklearn" 2>&1 | Out-Null
    Write-Host "Dependencies: OK" -ForegroundColor Green
} catch {
    Write-Host "Installing dependencies..." -ForegroundColor Yellow
    pip install -r requirements.txt --quiet
}

Write-Host ""
Write-Host "Starting Flask API on http://localhost:5000" -ForegroundColor Cyan
Write-Host "Press Ctrl+C to stop the server" -ForegroundColor Yellow
Write-Host ""

# Start the API
python app.py
