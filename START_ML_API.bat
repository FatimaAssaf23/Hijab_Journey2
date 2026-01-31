@echo off
title ML Prediction API Server
color 0A
echo ========================================
echo   Student Risk Prediction ML API
echo ========================================
echo.

cd /d "%~dp0"
cd ml_api

echo Checking Python...
python --version
if errorlevel 1 (
    echo.
    echo ERROR: Python is not installed or not in PATH!
    echo Please install Python 3.8 or higher.
    pause
    exit /b 1
)

echo.
echo Checking model file...
if exist "student_risk_model.pkl" (
    echo [OK] Model file found
) else (
    echo [WARNING] Model file not found!
    echo The API will start but predictions will fail.
)

echo.
echo Installing/checking dependencies...
pip install -q flask flask-cors joblib pandas scikit-learn numpy

echo.
echo ========================================
echo   Starting API on http://localhost:5000
echo ========================================
echo.
echo IMPORTANT: Keep this window open!
echo Press Ctrl+C to stop the server
echo.
echo ========================================
echo.

python app.py

pause
