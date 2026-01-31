@echo off
echo Starting Student Risk Prediction ML API...
echo.

REM Check if virtual environment exists
if exist "..\venv\Scripts\activate.bat" (
    echo Activating virtual environment...
    call ..\venv\Scripts\activate.bat
) else (
    echo No virtual environment found. Using system Python.
)

REM Check if model file exists
if not exist "student_risk_model.pkl" (
    echo.
    echo WARNING: student_risk_model.pkl not found!
    echo Please ensure the model file exists in the ml_api directory.
    echo You can generate it by running model/student_risk_model.ipynb
    echo.
    pause
)

REM Install/upgrade dependencies
echo Installing dependencies...
pip install -r requirements.txt --quiet

echo.
echo Starting Flask API on http://localhost:5000
echo Press Ctrl+C to stop the server
echo.

python app.py
