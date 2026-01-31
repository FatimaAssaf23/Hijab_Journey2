# Automatic ML Prediction Triggers

The ML prediction system automatically generates predictions when certain student activities occur. This ensures predictions are always up-to-date without manual intervention.

## 🎯 Automatic Triggers

### 1. Video Completion (`StudentLessonProgressObserver`)

**Triggered when:**
- A student completes a video lesson (`video_completed` changes to `true`)

**What happens:**
- Automatically calculates student features
- Calls ML API to generate new prediction
- Saves prediction to database

**Example:**
```php
// When student completes a video
$progress->video_completed = true;
$progress->save(); // Observer triggers prediction automatically
```

### 2. Quiz Score Submission (`QuizAttemptObserver`)

**Triggered when:**
- A quiz attempt is submitted with a score
- Quiz score is updated

**What happens:**
- Automatically calculates student features (including new quiz score)
- Calls ML API to generate new prediction
- Saves prediction to database

**Example:**
```php
// When quiz is submitted
$attempt->score = 75.5;
$attempt->submitted_at = now();
$attempt->save(); // Observer triggers prediction automatically
```

### 3. Lesson Score Update (`StudentLessonProgressObserver`)

**Triggered when:**
- Lesson progress score is updated

**What happens:**
- Automatically recalculates prediction with new score data

## 📋 Observer Details

### StudentLessonProgressObserver

**Location:** `app/Observers/StudentLessonProgressObserver.php`

**Events monitored:**
- `updated()` - When lesson progress is updated
  - Triggers on `video_completed` change
  - Triggers on `score` change

**Optional:**
- `created()` - When new lesson progress is created (commented out by default)
  - Can be enabled to track early engagement

### QuizAttemptObserver

**Location:** `app/Observers/QuizAttemptObserver.php`

**Events monitored:**
- `updated()` - When quiz attempt is updated
  - Triggers when `score` is set and `submitted_at` exists
- `created()` - When quiz attempt is created with score

## 🔧 Registration

Observers are registered in `app/Providers/EventServiceProvider.php`:

```php
public function boot(): void
{
    parent::boot();

    // Register observers for ML predictions
    StudentLessonProgress::observe(StudentLessonProgressObserver::class);
    QuizAttempt::observe(QuizAttemptObserver::class);
}
```

## 📊 Prediction Flow

```
Student Activity
    ↓
Model Event (updated/created)
    ↓
Observer Triggered
    ↓
Calculate Student Features
    ↓
Call ML API
    ↓
Save Prediction to Database
    ↓
Available for Teachers/Admins
```

## 🛡️ Error Handling

All observers include error handling:
- Errors are logged to Laravel log file
- Failures don't interrupt the main application flow
- Predictions are attempted but failures are gracefully handled

**Log locations:**
- Success: `storage/logs/laravel.log` (INFO level)
- Errors: `storage/logs/laravel.log` (ERROR level)

## ⚙️ Configuration

### Disable Automatic Predictions

If you want to disable automatic predictions temporarily:

1. **Comment out observer registration:**
   ```php
   // StudentLessonProgress::observe(StudentLessonProgressObserver::class);
   // QuizAttempt::observe(QuizAttemptObserver::class);
   ```

2. **Or disable in observer:**
   ```php
   public function updated(StudentLessonProgress $progress)
   {
       // Temporarily disabled
       return;
       
       // ... rest of code
   }
   ```

### Enable Lesson Start Predictions

To trigger predictions when students start lessons (not just complete them):

1. Open `app/Observers/StudentLessonProgressObserver.php`
2. Uncomment the `created()` method code
3. This will track early engagement patterns

## 📈 Performance Considerations

### Rate Limiting

- Predictions are only triggered on **significant events** (completions, scores)
- Not triggered on every minor update (e.g., watched_percentage changes)
- Uses `isDirty()` to check if relevant fields actually changed

### API Calls

- Each prediction makes one API call to Python ML service
- API calls are wrapped in try-catch to prevent failures
- Timeout set to 10 seconds (configurable in `MLPredictionService`)

### Database

- Predictions are saved to `student_risk_predictions` table
- Uses `updateOrCreate()` to avoid duplicates
- Indexed by `student_id` and `current_level_id` for fast lookups

## 🧪 Testing

### Test Video Completion Trigger

```php
// In tinker or test
$progress = StudentLessonProgress::find(1);
$progress->video_completed = true;
$progress->save(); // Should trigger prediction

// Check logs
tail -f storage/logs/laravel.log
```

### Test Quiz Submission Trigger

```php
// In tinker or test
$attempt = QuizAttempt::find(1);
$attempt->score = 85.5;
$attempt->submitted_at = now();
$attempt->save(); // Should trigger prediction
```

### Verify Prediction Was Created

```php
use App\Models\StudentRiskPrediction;

$latest = StudentRiskPrediction::where('student_id', $studentId)
    ->latest('predicted_at')
    ->first();

dd($latest); // Should show new prediction
```

## 🔍 Monitoring

### Check Observer Activity

```bash
# Watch logs in real-time
tail -f storage/logs/laravel.log | grep "Observer"

# Search for prediction triggers
grep "triggering prediction" storage/logs/laravel.log

# Check for errors
grep "Failed to generate prediction" storage/logs/laravel.log
```

### Database Queries

```sql
-- Check recent predictions
SELECT * FROM student_risk_predictions 
ORDER BY predicted_at DESC 
LIMIT 10;

-- Count predictions per student
SELECT student_id, COUNT(*) as prediction_count 
FROM student_risk_predictions 
GROUP BY student_id;
```

## 🚀 Manual Triggers

You can still trigger predictions manually:

```php
use App\Services\MLPredictionService;

$service = new MLPredictionService();

// Single student
$prediction = $service->predictRisk($studentId);

// Entire class
$predictions = $service->predictForClass($classId);
```

## 📝 Best Practices

1. **Monitor Logs**: Regularly check logs for prediction errors
2. **API Health**: Ensure Python ML API is running and healthy
3. **Database Cleanup**: Consider archiving old predictions periodically
4. **Performance**: Monitor API response times and optimize if needed

## 🔗 Related Documentation

- **ML Integration Guide**: `ML_INTEGRATION_GUIDE.md`
- **Service Documentation**: See `app/Services/MLPredictionService.php`
- **Controller Usage**: `TEACHER_DASHBOARD_ML_CONTROLLER.md`

---

**Status: ✅ Automatic predictions are active and monitoring student activities**
