-- Quick SQL queries to test video tracking
-- Run these in your database (phpMyAdmin, MySQL Workbench, or php artisan tinker)

-- ============================================
-- 1. CHECK IF MIGRATIONS ARE APPLIED
-- ============================================
SHOW COLUMNS FROM student_lesson_progresses LIKE 'watched_seconds';
-- Should return: watched_seconds, watched_percentage, last_position, max_watched_time, last_watched_at, video_completed

-- ============================================
-- 2. FIND YOUR TEST STUDENT ID
-- ============================================
SELECT user_id, student_id 
FROM users u
JOIN students s ON u.user_id = s.user_id
WHERE u.email = 'student@example.com';  -- Replace with your test student email

-- ============================================
-- 3. FIND YOUR TEST LESSON ID
-- ============================================
SELECT lesson_id, title, content_url, duration_minutes, level_id, lesson_order
FROM lessons
WHERE title LIKE '%test%' OR title LIKE '%video%'
ORDER BY lesson_id DESC
LIMIT 5;

-- ============================================
-- 4. CHECK STUDENT PROGRESS FOR A LESSON
-- ============================================
SELECT 
    slp.*,
    l.title as lesson_title,
    s.student_id,
    u.email as student_email
FROM student_lesson_progresses slp
JOIN lessons l ON slp.lesson_id = l.lesson_id
JOIN students s ON slp.student_id = s.student_id
JOIN users u ON s.user_id = u.user_id
WHERE slp.lesson_id = 1  -- Replace with your lesson_id
AND u.email = 'student@example.com';  -- Replace with your test email

-- ============================================
-- 5. CHECK VIDEO PROGRESS DETAILS
-- ============================================
SELECT 
    watched_seconds,
    watched_percentage,
    last_position,
    max_watched_time,
    video_completed,
    status,
    TIME_FORMAT(SEC_TO_TIME(watched_seconds), '%H:%i:%s') as watched_time_formatted,
    last_watched_at,
    started_at,
    completed_at
FROM student_lesson_progresses
WHERE student_id = 1  -- Replace with your student_id
AND lesson_id = 1;    -- Replace with your lesson_id

-- ============================================
-- 6. CHECK IF GAME IS UNLOCKED
-- ============================================
SELECT 
    g.game_id,
    g.lesson_id,
    g.game_type,
    sgp.status as game_status,
    sgp.score,
    sgp.completed_at
FROM games g
LEFT JOIN student_game_progresses sgp ON g.game_id = sgp.game_id
WHERE g.lesson_id = 1  -- Replace with your lesson_id
AND (sgp.student_id = 1 OR sgp.student_id IS NULL);  -- Replace with your student_id

-- ============================================
-- 7. CHECK IF NEXT LESSON IS UNLOCKED
-- ============================================
SELECT 
    l.lesson_id,
    l.title,
    l.lesson_order,
    clv.is_visible,
    clv.changed_at
FROM lessons l
LEFT JOIN class_lesson_visibilities clv ON l.lesson_id = clv.lesson_id
WHERE l.level_id = 1  -- Replace with your level_id
AND l.lesson_order > 1  -- Replace with current lesson order
ORDER BY l.lesson_order ASC
LIMIT 1;

-- ============================================
-- 8. RESET PROGRESS FOR TESTING (CAREFUL!)
-- ============================================
-- Uncomment and use carefully to reset progress:

-- UPDATE student_lesson_progresses
-- SET 
--     watched_seconds = 0,
--     watched_percentage = 0,
--     last_position = 0,
--     max_watched_time = 0,
--     video_completed = false,
--     status = 'not_started',
--     started_at = NULL,
--     completed_at = NULL
-- WHERE student_id = 1  -- Replace with your student_id
-- AND lesson_id = 1;    -- Replace with your lesson_id

-- ============================================
-- 9. CHECK ALL LESSONS WITH PROGRESS
-- ============================================
SELECT 
    l.lesson_id,
    l.title,
    slp.watched_percentage,
    slp.video_completed,
    slp.status,
    CASE 
        WHEN slp.video_completed = 1 THEN 'Video ✓'
        ELSE 'Video ✗'
    END as video_status,
    CASE 
        WHEN slp.status = 'completed' THEN 'Lesson ✓'
        WHEN slp.status = 'in_progress' THEN 'In Progress'
        ELSE 'Not Started'
    END as lesson_status
FROM lessons l
LEFT JOIN student_lesson_progresses slp ON l.lesson_id = slp.lesson_id
WHERE slp.student_id = 1  -- Replace with your student_id
ORDER BY l.lesson_order;

-- ============================================
-- 10. MANUALLY SET PROGRESS FOR TESTING
-- ============================================
-- Use to test 80% completion threshold:

-- UPDATE student_lesson_progresses
-- SET 
--     watched_percentage = 79.5,
--     video_completed = false
-- WHERE student_id = 1 AND lesson_id = 1;
-- -- Then watch a bit more - should unlock at 80%

-- UPDATE student_lesson_progresses
-- SET 
--     watched_percentage = 80.0,
--     video_completed = true
-- WHERE student_id = 1 AND lesson_id = 1;
-- -- Should unlock game immediately
