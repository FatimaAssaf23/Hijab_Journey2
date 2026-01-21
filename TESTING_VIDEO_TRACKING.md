# Video Tracking System - Testing Guide

## 🚀 Step 1: Run Migrations

First, make sure all database migrations are applied:

```bash
php artisan migrate
```

This will create the following fields in `student_lesson_progresses`:
- `watched_seconds`
- `watched_percentage`
- `last_position`
- `max_watched_time`
- `last_watched_at`
- `video_completed`

---

## 📋 Step 2: Setup Test Data

### 2.1 Create a Test Lesson with Video

1. **Login as Admin** → Go to `/admin/lessons`
2. **Create a new lesson** with:
   - Title: "Test Video Lesson"
   - Content: Upload a test video file (`.mp4`, `.mov`, or `.avi`)
   - Level: Select any level
   - Skills: Any number
   - Make sure `is_visible = true`

### 2.2 Create a Game for the Lesson (Optional)

1. Go to `/teacher/games` or use admin panel
2. Create a game associated with your test lesson
3. This allows testing the game unlock feature

### 2.3 Assign Lesson to Student's Class

1. **Login as Teacher** → Go to `/teacher/lessons/manage`
2. Find your test lesson
3. Click "Show" for the student's class to make it visible

---

## 🧪 Step 3: Test Video Tracking

### 3.1 Access the Lesson

1. **Login as Student**
2. Navigate to `/lessons/{lessonId}/view`
   - Or find the lesson through your lesson list page

### 3.2 Verify UI Elements

Check that you see:
- ✅ **Progress Bar** at the top showing "0%"
- ✅ **Locked Game Button** (if game exists) - gray with 🔒
- ✅ **Video Player** (Video.js with controls)

---

## 📊 Step 4: Test Progress Tracking

### 4.1 Watch the Video

1. **Play the video** and watch for a few seconds
2. **Check Browser Console** (F12 → Console):
   ```
   Expected logs:
   - "Video playing - starting watch time tracking"
   - "Watch progress updated: {data}"
   ```

3. **Check Network Tab** (F12 → Network):
   - Filter by "track"
   - Every 10 seconds, you should see:
     - Request: `POST /api/lessons/{id}/video/track`
     - Status: 200
     - Response: JSON with `watched_seconds`, `watched_percentage`, etc.

### 4.2 Verify Progress Bar Updates

- Progress bar should **fill gradually** as you watch
- Percentage should **increase** in real-time
- Message should update: "Watch X% more to unlock game"

### 4.3 Pause and Resume

1. **Pause the video** → Check console for pause log
2. **Resume** → Progress should continue tracking
3. **Progress should only count when playing AND tab is visible**

---

## 🔒 Step 5: Test Anti-Cheat Features

### 5.1 Test Forward Seeking Prevention

1. **Play video to 30 seconds**
2. **Try to seek forward** to 60 seconds by:
   - Clicking on progress bar ahead
   - Dragging video slider forward
3. **Expected Result:**
   - Video should **revert** to last watched position
   - Warning message: "Forward seeking is disabled"
   - Console log: "Forward seeking detected - reverting"

### 5.2 Test Backward Seeking (Allowed)

1. **Play video to 30 seconds**
2. **Seek backward** to 10 seconds
3. **Expected Result:**
   - Should work normally ✅
   - Video jumps to 10 seconds
   - Tracking continues from 10 seconds

### 5.3 Test Tab Inactivity

1. **Start playing video**
2. **Switch to another tab/window**
3. **Expected Result:**
   - Video **pauses automatically** ✅
   - Progress tracking **stops** ✅
   - Console: "ANTI-CHEAT: Tab hidden - pausing video"

4. **Switch back to the tab**
5. **Expected Result:**
   - Video stays paused (must click play manually)
   - No auto-play ✅

---

## 🎮 Step 6: Test Game Unlock

### 6.1 Reach 80% Watch Time

1. **Watch video until progress bar shows ≥80%**
2. **Check for:**
   - ✅ Progress bar shows green completion message
   - ✅ "✓ Video completed! Game unlocked."
   - ✅ Game button changes from **🔒 Locked** (gray) to **🎮 Unlocked** (green)

### 6.2 Verify Game Button

1. **Click the unlocked game button**
2. **Should redirect** to `/student/games?lesson_id={id}`
3. **Game should be accessible**

---

## ✅ Step 7: Test Lesson Completion

### 7.1 Complete the Game

1. **Play and complete the game**
2. **Submit score** via game completion
3. **Check database or refresh lesson page**

### 7.2 Verify Lesson Completion

1. **Refresh the lesson page**
2. **Check for:**
   - ✅ "✓ Lesson Completed" badge next to title
   - ✅ Lesson status changed to "completed"

### 7.3 Test Next Lesson Unlock

1. **Check if next lesson** (in same level) is now visible/unlocked
2. **Verify in database:**
   ```sql
   SELECT * FROM class_lesson_visibilities 
   WHERE lesson_id = {next_lesson_id} 
   AND is_visible = 1;
   ```

---

## 🔍 Step 8: Database Verification

### Check Progress Data

```sql
SELECT 
    lesson_id,
    watched_seconds,
    watched_percentage,
    last_position,
    max_watched_time,
    video_completed,
    status,
    started_at,
    completed_at
FROM student_lesson_progresses
WHERE student_id = {your_student_id}
AND lesson_id = {your_lesson_id};
```

**Expected values:**
- `watched_seconds` > 0 (increases as you watch)
- `watched_percentage` increases (0-100)
- `last_position` = current video position
- `max_watched_time` = furthest point reached
- `video_completed` = true (when ≥80%)
- `status` = 'completed' (when game passed)

---

## 🐛 Step 9: Debugging Tips

### 9.1 Browser Console Errors

**If Video.js not loading:**
- Check CDN connection
- Verify `video-js.css` and `video.min.js` are loaded

**If API calls failing:**
- Check CSRF token is present
- Verify authentication (must be logged in as student)
- Check route: `/api/lessons/{id}/video/track`

### 9.2 Progress Not Updating

**Check:**
1. Video duration is loaded: `player.duration()`
2. `watchedSeconds` is increasing
3. Network requests are being sent
4. Backend validation is passing

### 9.3 Game Not Unlocking

**Check database:**
```sql
SELECT video_completed FROM student_lesson_progresses 
WHERE student_id = ? AND lesson_id = ?;
```

**Check API response:**
- Network tab → track request → Response
- Verify `video_completed: true` in response

---

## 📝 Step 10: Quick Test Checklist

- [ ] Migrations run successfully
- [ ] Test lesson created with video file
- [ ] Lesson visible to student
- [ ] Video player loads and plays
- [ ] Progress bar appears and updates
- [ ] Progress percentage increases during playback
- [ ] API calls sent every 10 seconds
- [ ] Forward seeking blocked
- [ ] Backward seeking allowed
- [ ] Tab switch pauses video
- [ ] Progress stops when tab inactive
- [ ] Game button locked initially
- [ ] Game unlocks at 80%
- [ ] Progress saves to database
- [ ] Lesson completes after game
- [ ] Next lesson unlocks automatically
- [ ] UI updates in real-time

---

## 🎯 Step 11: Manual API Testing (Optional)

### Test Progress Endpoint

```bash
# Get current progress
curl -X GET "http://localhost/api/lessons/{lessonId}/video/progress" \
  -H "Accept: application/json" \
  -H "Cookie: your_session_cookie"
```

### Test Track Endpoint

```bash
# Update progress
curl -X POST "http://localhost/api/lessons/{lessonId}/video/track" \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: your_token" \
  -H "Cookie: your_session_cookie" \
  -d '{
    "watched_seconds": 120,
    "watched_percentage": 50.0,
    "current_position": 60.5,
    "max_watched_time": 60.5,
    "is_completed": false
  }'
```

---

## 💡 Tips for Testing

1. **Use a short test video** (30-60 seconds) for faster testing
2. **Check browser console** for detailed logs
3. **Use browser DevTools Network tab** to monitor API calls
4. **Test in different browsers** (Chrome, Firefox, Edge)
5. **Test on mobile devices** if applicable
6. **Clear browser cache** if changes don't appear

---

## ⚠️ Common Issues

### Issue: Progress not saving
**Solution:** Check authentication, verify CSRF token, check database connection

### Issue: Video.js not loading
**Solution:** Check internet connection (CDN), verify script tags in page source

### Issue: Game button not unlocking
**Solution:** Verify `watched_percentage >= 80` in database, check `video_completed` flag

### Issue: Forward seeking not blocked
**Solution:** Check browser console for errors, verify `lastAllowedPosition` is updating

---

## 📞 Need Help?

If something doesn't work:
1. Check browser console for errors
2. Check Laravel logs: `storage/logs/laravel.log`
3. Verify all migrations are applied
4. Check routes are registered correctly
5. Verify student is authenticated and has access to lesson
