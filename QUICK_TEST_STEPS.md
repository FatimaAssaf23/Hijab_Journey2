# Quick Testing Steps

## 🚀 Fast Testing Flow

### 1. Setup (One Time)
```bash
# Run migrations
php artisan migrate

# Make sure you have:
# - A student account
# - A lesson with video file (.mp4, .mov, .avi)
# - Lesson visible to student's class
```

### 2. Quick Test (5 minutes)

1. **Login as Student** → Go to `/lessons/{id}/view`

2. **Open Browser DevTools** (F12)
   - Console tab
   - Network tab (filter: "track")

3. **Play Video**
   - Watch for 10+ seconds
   - Check progress bar fills up
   - Check console logs

4. **Check API Calls**
   - Network tab should show `POST /api/lessons/{id}/video/track` every 10 seconds
   - Response should have `watched_seconds`, `watched_percentage`

5. **Test Forward Seek**
   - Try to jump ahead → Should block and revert

6. **Test Tab Switch**
   - Switch tabs → Video pauses
   - Switch back → Video stays paused

7. **Watch to 80%**
   - Game button should unlock (green)
   - Message shows "Video completed!"

---

## ✅ Success Indicators

✅ **Progress Bar** updates smoothly  
✅ **API calls** every 10 seconds  
✅ **Forward seek** blocked  
✅ **Tab switch** pauses video  
✅ **Game unlocks** at 80%  
✅ **Progress saves** to database  

---

## 🐛 Quick Fixes

**No progress bar?** → Check video file exists and is valid  
**No API calls?** → Check authentication and CSRF token  
**Game not unlocking?** → Check database: `SELECT video_completed FROM student_lesson_progresses`  
**Forward seek works?** → Check browser console for JavaScript errors  

---

## 📊 Verify in Database

```sql
SELECT watched_percentage, video_completed, status 
FROM student_lesson_progresses 
WHERE student_id = YOUR_STUDENT_ID 
AND lesson_id = YOUR_LESSON_ID;
```

Expected:
- `watched_percentage` increases as you watch
- `video_completed = 1` when ≥80%
- `status = 'completed'` when game passed
