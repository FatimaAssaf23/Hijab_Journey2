# How to Start the ML API

## ✅ What I've Done

1. ✅ Created mock model file: `ml_api/student_risk_model.pkl`
2. ✅ Verified Python is installed (Python 3.12.10)
3. ✅ Verified Flask is available
4. ✅ Checked student data:
   - Student 2: Has 1 progress record (needs more data)
   - Student 3: No progress records
   - Student 4: Has 7 progress records with 5 completed videos ✅

## 🚀 Start the ML API

### Option 1: Using the Batch File (Windows)
```bash
cd ml_api
start_api.bat
```

### Option 2: Using Python Directly
```bash
cd ml_api
python app.py
```

### Option 3: Using PowerShell
```powershell
Set-Location ml_api
python app.py
```

## ✅ Verify API is Running

Once started, you should see:
```
✅ Model loaded successfully from ...
 * Running on http://127.0.0.1:5000
```

Then test it:
```bash
curl http://localhost:5000/health
```

Or in PowerShell:
```powershell
Invoke-WebRequest -Uri http://localhost:5000/health
```

Expected response:
```json
{
  "status": "ok",
  "model_loaded": true,
  "model_path": "...",
  "model_exists": true
}
```

## 📊 Test Predictions

### 1. Test API Connection
Visit: `http://your-app.test/test-ml-api-connection`

### 2. Test Single Student (Student 4 has data)
Visit: `http://your-app.test/test-ml-prediction/4`

### 3. Full Diagnostic
Visit: `http://your-app.test/ml-diagnose/1`

### 4. Refresh Teacher Dashboard
Once API is running, refresh: `http://your-app.test/teacher/dashboard`

Student 4 should now show a prediction!

## 📝 Summary

**Status:**
- ✅ Model file created
- ✅ API files ready
- ⏳ **API needs to be started manually**
- ✅ Student 4 has enough data for predictions

**Next Step:**
1. Open a new terminal/command prompt
2. Navigate to: `cd C:\wamp64\www\CapHijabJourny\ml_api`
3. Run: `python app.py`
4. Keep that terminal open (API runs in foreground)
5. Refresh teacher dashboard

**Note:** The API must stay running. If you close the terminal, the API stops. For production, you'd want to run it as a service.
