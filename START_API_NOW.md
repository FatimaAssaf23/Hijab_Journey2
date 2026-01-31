# 🚀 Start ML API - Step by Step

## Current Status
✅ All files are ready:
- ✅ `ml_api/app.py` exists
- ✅ `ml_api/student_risk_model.pkl` exists  
- ✅ Python 3.12.10 installed
- ✅ Flask and dependencies installed

## ⚡ Quick Start (Choose One Method)

### Method 1: PowerShell (Recommended)
1. Open **PowerShell** (not Command Prompt)
2. Navigate to your project:
   ```powershell
   cd C:\wamp64\www\CapHijabJourny\ml_api
   ```
3. Start the API:
   ```powershell
   python app.py
   ```

### Method 2: Command Prompt
1. Open **Command Prompt** (cmd)
2. Navigate to your project:
   ```cmd
   cd C:\wamp64\www\CapHijabJourny\ml_api
   ```
3. Start the API:
   ```cmd
   python app.py
   ```

### Method 3: Double-Click Batch File
1. Navigate to `C:\wamp64\www\CapHijabJourny\ml_api` in File Explorer
2. Double-click `start_api.bat`

## ✅ What You Should See

When the API starts successfully, you'll see:
```
✅ Model loaded successfully from C:\wamp64\www\CapHijabJourny\ml_api\student_risk_model.pkl
 * Running on http://127.0.0.1:5000
 * Debug mode: on
```

**Keep this terminal window open!** Closing it will stop the API.

## 🧪 Test the API

Once running, open a **NEW** terminal and test:
```powershell
Invoke-WebRequest -Uri http://localhost:5000/health
```

Or visit in browser: `http://localhost:5000/health`

You should see:
```json
{
  "status": "ok",
  "model_loaded": true,
  "model_path": "...",
  "model_exists": true
}
```

## 🔄 Next Steps

1. **Keep the API terminal open** (minimize it, don't close it)
2. **Refresh your teacher dashboard** in the browser
3. **You should now see predictions** for Student 4 (who has data)

## ❌ Troubleshooting

### "Port 5000 already in use"
- Something else is using port 5000
- Close other applications or change the port in `ml_api/app.py` (line 190)

### "Module not found"
- Install dependencies: `pip install -r requirements.txt`
- Make sure you're in the `ml_api` folder

### "Model file not found"
- The model should be at: `ml_api/student_risk_model.pkl`
- If missing, run: `python ml_api/create_mock_model.py`

## 💡 Tip

For convenience, you can create a desktop shortcut:
1. Right-click on `start_api.bat`
2. Create shortcut
3. Move shortcut to desktop
4. Double-click to start API anytime
