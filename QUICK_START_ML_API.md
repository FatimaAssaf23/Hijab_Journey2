# 🚀 Quick Start: ML API Server

## ✅ Everything is Ready!

Your ML API is fully configured and ready to start.

## 🎯 Easiest Way to Start:

### Option 1: Double-Click (Recommended)
1. Navigate to: `C:\wamp64\www\CapHijabJourny`
2. **Double-click:** `START_ML_API.bat`
3. A window will open - **keep it open**
4. Wait for: `* Running on http://127.0.0.1:5000`
5. **Refresh your teacher dashboard**

### Option 2: From ml_api Folder
1. Navigate to: `C:\wamp64\www\CapHijabJourny\ml_api`
2. **Double-click:** `start.bat`
3. Keep the window open
4. Refresh your dashboard

### Option 3: Command Line
```bash
cd C:\wamp64\www\CapHijabJourny\ml_api
python app.py
```

## ✅ What You'll See:

When the API starts successfully:
```
✅ Model loaded successfully from ...
 * Running on http://127.0.0.1:5000
 * Debug mode: on
```

## 🔍 Verify It's Working:

1. **Check the terminal** - Should show "Running on http://127.0.0.1:5000"
2. **Visit:** `http://your-app.test/test-ml-api-connection`
   - Should return: `{"success": true, ...}`
3. **Refresh teacher dashboard** - Predictions should appear

## ⚠️ Important:

- **Keep the terminal window open** - Closing it stops the API
- **Don't close the window** - Minimize it if needed
- The API must run continuously for predictions to work

## 🐛 Troubleshooting:

### "Python is not installed"
- Install Python 3.8+ from python.org
- Make sure to check "Add Python to PATH" during installation

### "Port 5000 already in use"
- Another application is using port 5000
- Close other applications or change the port in `ml_api/app.py`

### "Module not found"
- Dependencies will auto-install when you start the API
- If issues persist: `pip install -r ml_api/requirements.txt`

## 📝 Summary:

✅ **All files are ready**
✅ **Python is installed** (3.12.10)
✅ **Model file exists**
✅ **Just need to start it!**

**Double-click `START_ML_API.bat` and you're done!** 🎉
