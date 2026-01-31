# Quick Start: ML API

## ✅ Status Check
- ✅ Python 3.12.10 installed
- ✅ Flask installed
- ✅ Joblib installed  
- ✅ Model file exists: `ml_api/student_risk_model.pkl`
- ✅ API file exists: `ml_api/app.py`

## 🚀 Start the API

### Option 1: Using PowerShell Script (Easiest)
```powershell
.\start_ml_api.ps1
```

### Option 2: Using Batch File
```cmd
cd ml_api
start_api.bat
```

### Option 3: Manual Start
```powershell
Set-Location ml_api
python app.py
```

Or in Command Prompt:
```cmd
cd ml_api
python app.py
```

## ✅ Verify API is Running

After starting, you should see:
```
✅ Model loaded successfully from ...
 * Running on http://127.0.0.1:5000
```

Then test it:
```powershell
Invoke-WebRequest -Uri http://localhost:5000/health
```

Or visit in browser: `http://localhost:5000/health`

## 📝 Important Notes

1. **Keep the terminal open** - The API runs in the foreground. Closing the terminal stops the API.

2. **For production** - You'd want to run this as a Windows service or use a process manager.

3. **Port 5000** - Make sure nothing else is using port 5000. If it's in use, you can change it in `ml_api/app.py` (last line).

## 🔄 After Starting

1. Refresh the teacher dashboard
2. You should see predictions for students with data (Student 4)
3. The error message will disappear
