@extends('layouts.app')

@push('styles')
<!-- Video.js CSS -->
<link href="https://vjs.zencdn.net/8.6.1/video-js.css" rel="stylesheet" />
<style>
    /* Custom styling to match existing design */
    .video-js {
        width: 100%;
        height: 100%;
        border-radius: 0.75rem;
    }
    .vjs-poster {
        border-radius: 0.75rem;
    }
    /* Warning message styling */
    .video-seek-warning {
        animation: slideDown 0.3s ease-out;
    }
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translate(-50%, -20px);
        }
        to {
            opacity: 1;
            transform: translate(-50%, 0);
        }
    }
    /* Progress bar styling */
    .video-progress-bar {
        height: 8px;
        background-color: #fce7f3;
        border-radius: 10px;
        overflow: hidden;
        position: relative;
    }
    .video-progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #f472b6, #ec4899);
        border-radius: 10px;
        transition: width 0.3s ease;
        position: relative;
    }
    .video-progress-fill::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        animation: shimmer 2s infinite;
    }
    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    /* Completed badge */
    .completed-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
    }
</style>
@endpush

@section('content')
<div class="py-12 px-2" style="background-color: #FFF4FA; min-height: 100vh;">
    <div class="max-w-2xl mx-auto">
        <div class="relative bg-white/70 backdrop-blur-xl rounded-3xl shadow-2xl p-10 border border-pink-100">
            <!-- Decorative SVG background -->
            <svg class="absolute right-0 top-0 w-32 h-32 opacity-10 pointer-events-none z-0" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="100" cy="100" r="100" fill="#f472b6"/>
            </svg>
            <div class="relative z-10">
                <div class="flex items-center gap-4 mb-4">
                    <span class="text-5xl drop-shadow">{{ $lesson->icon ?? '📘' }}</span>
                    <div class="flex-1">
                        <h1 class="text-3xl font-extrabold text-pink-600 tracking-tight">{{ $lesson->title }}</h1>
                        {{-- Completed Lesson Indicator --}}
                        @if(isset($progress) && $progress && $progress->status === 'completed')
                            <div class="mt-2">
                                <span class="completed-badge">
                                    <span>✓</span>
                                    <span>Lesson Completed</span>
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Video Progress Bar --}}
                @if(isset($progress) && $progress && $lesson->content_url && Str::endsWith($lesson->content_url, ['.mp4', '.mov', '.avi']))
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold text-pink-600">Video Progress</span>
                            <span class="text-sm text-gray-600" id="video-progress-percentage">
                                {{ round($progress->watched_percentage ?? 0, 1) }}%
                            </span>
                        </div>
                        <div class="video-progress-bar">
                            <div class="video-progress-fill" id="video-progress-bar" style="width: {{ $progress->watched_percentage ?? 0 }}%"></div>
                        </div>
                        @if(($progress->watched_percentage ?? 0) >= 80)
                            <p class="text-xs text-green-600 mt-1 font-medium">✓ Video completed! Game unlocked.</p>
                        @else
                            <p class="text-xs text-gray-500 mt-1">Watch {{ round(80 - ($progress->watched_percentage ?? 0), 1) }}% more to unlock game</p>
                        @endif
                    </div>
                @endif

                <div class="mb-5 text-lg text-gray-700 leading-relaxed">{{ $lesson->description }}</div>
                <div class="mb-2 text-sm text-gray-500 flex gap-4">
                    <span><span class="font-semibold text-pink-500">Skills:</span> {{ $lesson->skills }}</span>
                    <span><span class="font-semibold text-pink-500">Duration:</span> {{ $lesson->duration_minutes ?? '-' }} min</span>
                </div>
                
                {{-- Game Button - Locked/Unlocked States --}}
                @if(isset($hasGame) && $hasGame)
                    <div class="mb-6 mt-6">
                        @if(isset($isVideoCompleted) && $isVideoCompleted)
                            {{-- Unlocked Game Button --}}
                            <a href="{{ route('student.games', ['lesson_id' => $lesson->lesson_id]) }}" 
                               class="inline-flex items-center gap-2 bg-gradient-to-r from-green-400 via-green-300 to-green-200 hover:from-green-500 hover:to-green-300 text-white font-bold py-3 px-8 rounded-full shadow-lg transition-all text-base transform hover:scale-105">
                                <span class="text-xl">🎮</span>
                                <span>Play Game</span>
                                @if(isset($isGameCompleted) && $isGameCompleted)
                                    <span class="ml-2 bg-green-600 px-2 py-1 rounded-full text-xs">✓ Completed</span>
                                @else
                                    <span class="ml-2 bg-green-600 px-2 py-1 rounded-full text-xs">Unlocked</span>
                                @endif
                            </a>
                        @else
                            {{-- Locked Game Button --}}
                            <div class="inline-flex items-center gap-2 bg-gray-200 text-gray-500 font-semibold py-3 px-8 rounded-full shadow cursor-not-allowed relative">
                                <span class="text-xl opacity-50">🔒</span>
                                <span>Play Game</span>
                                <span class="ml-2 bg-gray-400 px-2 py-1 rounded-full text-xs text-white">Locked</span>
                            </div>
                            <p class="text-sm text-gray-600 mt-2 ml-2">
                                ⏳ Watch 80% of the video to unlock this game
                            </p>
                        @endif
                    </div>
                @endif
                @if($lesson->content_url)
                    <div class="mt-8">
                        @php
                            $fileExtension = strtolower(pathinfo($lesson->content_url, PATHINFO_EXTENSION));
                            $isVideo = in_array($fileExtension, ['mp4', 'mov', 'avi', 'mkv', 'wmv', 'flv', 'webm']);
                            $isPdf = $fileExtension === 'pdf';
                            // Remove leading slash if present to avoid double slashes in URL
                            $contentUrl = ltrim($lesson->content_url, '/');
                            $storageUrl = asset('storage/' . $contentUrl);
                        @endphp
                        
                        @if($isPdf)
                            <iframe src="{{ $storageUrl }}" width="100%" height="600px" class="rounded-xl border border-pink-100 shadow"></iframe>
                            <div class="mt-2">
                                <a href="{{ $storageUrl }}" target="_blank" class="inline-block bg-pink-100 text-pink-700 px-4 py-2 rounded-lg shadow hover:bg-pink-200 transition text-base font-semibold mt-2">Open PDF in New Tab</a>
                            </div>
                        @elseif($isVideo)
                            <video controls width="100%" class="rounded-xl border border-pink-100 shadow">
                                <source src="{{ $storageUrl }}" type="video/{{ $fileExtension }}">
                                Your browser does not support the video tag.
                            </video>
                            @if($lesson->video_duration_seconds)
                                <p class="text-sm text-gray-500 mt-2">Duration: {{ gmdate('i:s', $lesson->video_duration_seconds) }}</p>
                            @endif
                        @else
                            <a href="{{ $storageUrl }}" class="inline-block bg-pink-100 text-pink-700 px-4 py-2 rounded-lg shadow hover:bg-pink-200 transition text-base font-semibold mt-2" target="_blank">Download Content</a>
                            <p class="text-sm text-gray-500 mt-2">File: {{ basename($lesson->content_url) }}</p>
                        @endif
                    </div>
                @else
                    <div class="text-gray-400 italic mt-8">No content uploaded for this lesson.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Video.js JavaScript -->
<script src="https://vjs.zencdn.net/8.6.1/video.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize if video element exists
    const videoElement = document.getElementById('lesson-video-player');
    if (!videoElement) return;

    // Initialize Video.js player
    const player = videojs('lesson-video-player', {
        fluid: true,
        responsive: true,
        aspectRatio: '16:9',
        playbackRates: [0.5, 1, 1.25, 1.5, 2]
    });

    // Track variables
    let lastPosition = 0;           // Last video position we tracked
    let maxWatchedTime = 0;         // Furthest point reached in video (for preventing forward seeking)
    let lastAllowedPosition = 0;    // Last position student is allowed to seek to (enforces no forward seeking)
    let watchedSeconds = 0;         // Total seconds actually watched (excluding skipped/paused time)
    let isPlaying = false;          // Whether video is currently playing
    let isTabVisible = true;        // Whether browser tab is active
    let sessionStartPosition = 0;   // Video position when current session started
    let sessionStartTimestamp = null; // Real-time timestamp when current session started
    let isSeekingForward = false;   // Flag to track if forward seek is being attempted
    
    // Track watched segments to prevent double-counting rewatched sections
    // Format: [{start: 10, end: 25}, {start: 30, end: 45}]
    let watchedSegments = [];

    // Get lesson ID from Laravel
    const lessonId = {{ $lesson->lesson_id }};

    /**
     * Calculate total watched seconds from segments
     * Ensures we don't double-count rewatched sections
     */
    function calculateWatchedSeconds() {
        if (watchedSegments.length === 0) return 0;
        
        // Sort segments by start time
        const sorted = [...watchedSegments].sort((a, b) => a.start - b.start);
        
        // Merge overlapping segments
        const merged = [];
        for (const segment of sorted) {
            if (merged.length === 0) {
                merged.push({...segment});
            } else {
                const last = merged[merged.length - 1];
                if (segment.start <= last.end) {
                    // Overlapping or adjacent - merge
                    last.end = Math.max(last.end, segment.end);
                } else {
                    // Non-overlapping - add as new segment
                    merged.push({...segment});
                }
            }
        }
        
        // Calculate total duration
        return merged.reduce((total, seg) => total + (seg.end - seg.start), 0);
    }

    /**
     * Add watched segment (only if it's new territory or extends existing)
     */
    function addWatchedSegment(start, end) {
        // Ensure start < end and values are valid
        if (start >= end || start < 0 || isNaN(start) || isNaN(end)) return;
        
        // Add or extend segment
        watchedSegments.push({ start: start, end: end });
        
        // Recalculate total
        watchedSeconds = calculateWatchedSeconds();
    }

    /**
     * Disable Video.js progress bar forward seeking
     * Intercepts clicks on progress bar and prevents forward seeking
     */
    player.ready(function() {
        // Disable forward seeking on progress bar
        const progressControl = player.getChild('progressControl');
        if (progressControl) {
            const seekBar = progressControl.getChild('seekBar');
            if (seekBar) {
                // Intercept mouse/touch events on seek bar
                seekBar.on('mousedown', function(e) {
                    handleSeekAttempt(e);
                });
                seekBar.on('touchstart', function(e) {
                    handleSeekAttempt(e);
                });
            }
        }
        
        // Also disable keyboard seeking forward (arrow keys, etc.)
        player.on('keydown', function(e) {
            // Arrow right, Page Down, End - seek forward
            if (e.keyCode === 39 || e.keyCode === 34 || e.keyCode === 35) {
                e.preventDefault();
                e.stopPropagation();
                showSeekWarning('Forward seeking is not allowed');
                return false;
            }
            // Arrow left, Page Up, Home - seek backward (allowed)
            // Other keys are allowed
        });
    });

    /**
     * Handle seek attempt on progress bar
     * Checks if user is trying to seek forward and blocks it
     */
    function handleSeekAttempt(event) {
        const seekBar = player.getChild('progressControl').getChild('seekBar');
        if (!seekBar) return;
        
        const mousePosX = event.offsetX || (event.touches && event.touches[0] ? event.touches[0].clientX : 0);
        const rect = seekBar.el().getBoundingClientRect();
        const percent = mousePosX / rect.width;
        const duration = player.duration();
        
        if (duration) {
            const targetTime = percent * duration;
            
            // Check if seeking forward beyond allowed position
            if (targetTime > lastAllowedPosition) {
                event.preventDefault();
                event.stopPropagation();
                
                // Revert to last allowed position
                player.currentTime(lastAllowedPosition);
                
                showSeekWarning('You cannot seek forward. You can only rewind.');
                return false;
            }
        }
    }

    /**
     * Show warning message when forward seeking is attempted
     */
    function showSeekWarning(message) {
        // Remove existing warning if any
        const existingWarning = document.querySelector('.video-seek-warning');
        if (existingWarning) {
            existingWarning.remove();
        }
        
        // Create warning element
        const warning = document.createElement('div');
        warning.className = 'video-seek-warning fixed top-4 left-1/2 transform -translate-x-1/2 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 text-center font-semibold';
        warning.textContent = message;
        document.body.appendChild(warning);
        
        // Auto-remove after 3 seconds
        setTimeout(() => {
            if (warning.parentNode) {
                warning.remove();
            }
        }, 3000);
    }

    /**
     * EVENT: seeking
     * Purpose: Intercept seek attempts BEFORE they complete
     * Action: Block forward seeking, allow backward seeking only
     * 
     * LOGIC:
     * 1. Detect if user is trying to seek forward (targetTime > lastAllowedPosition)
     * 2. If forward seek detected: prevent it immediately and revert to lastAllowedPosition
     * 3. If backward seek: allow it and update lastAllowedPosition
     * 4. lastAllowedPosition is updated continuously as video plays forward
     */
    player.on('seeking', function() {
        const targetTime = player.currentTime();
        const seekThreshold = 0.5; // Allow 0.5 seconds tolerance for normal playback variance
        
        // Check if attempting to seek forward beyond allowed position
        if (targetTime > lastAllowedPosition + seekThreshold) {
            console.log('ANTI-CHEAT: Forward seeking blocked at', targetTime, '- reverting to', lastAllowedPosition);
            isSeekingForward = true;
            
            // Immediately revert to last allowed position
            player.currentTime(lastAllowedPosition);
            
            // Show warning message
            showSeekWarning('Forward seeking is disabled. You can only rewind to review content.');
            
            // Prevent the seek from completing
            return false;
        } else {
            // Backward seek or small forward movement (allowed)
            isSeekingForward = false;
            
            // If seeking backward, update lastAllowedPosition to allow rewinding
            if (targetTime < lastAllowedPosition) {
                // Allow backward seeking - update allowed position
                lastAllowedPosition = targetTime;
                console.log('Backward seek allowed - new allowed position:', lastAllowedPosition);
            }
        }
    });

    /**
     * EVENT: play
     * Purpose: Start tracking watch time when video begins playing
     * Action: Record start position and timestamp of current watching session
     */
    player.on('play', function() {
        // Only start tracking if tab is visible
        if (!isTabVisible) {
            console.log('Video playing but tab inactive - pausing video');
            player.pause();
            return;
        }
        
        console.log('Video playing - starting watch time tracking');
        isPlaying = true;
        sessionStartPosition = player.currentTime();
        sessionStartTimestamp = Date.now();
        lastPosition = sessionStartPosition;
        
        // Update max watched time and last allowed position
        if (sessionStartPosition > maxWatchedTime) {
            maxWatchedTime = sessionStartPosition;
        }
        if (sessionStartPosition > lastAllowedPosition) {
            lastAllowedPosition = sessionStartPosition;
        }
    });

    /**
     * EVENT: pause
     * Purpose: Stop tracking watch time when video is paused
     * Action: Calculate watched segment and accumulate to total, send update to backend
     */
    player.on('pause', function() {
        console.log('Video paused - stopping watch time tracking');
        
        // Finalize current session
        if (isPlaying && sessionStartTimestamp !== null) {
            const currentPosition = player.currentTime();
            
            // Only count forward progress (don't count if user seeked backward)
            if (currentPosition > sessionStartPosition) {
                addWatchedSegment(sessionStartPosition, currentPosition);
            }
            
            // Update max watched time
            if (currentPosition > maxWatchedTime) {
                maxWatchedTime = currentPosition;
            }
            
            lastPosition = currentPosition;
        }
        
        isPlaying = false;
        sessionStartTimestamp = null;
        sessionStartPosition = 0;
        
        // Send update to backend
        updateWatchProgress();
    });

    /**
     * Update progress bar in real-time during playback
     */
    function updateProgressBarRealTime() {
        const duration = player.duration();
        if (duration && watchedSeconds > 0) {
            const currentPercentage = (watchedSeconds / duration) * 100;
            const progressBar = document.getElementById('video-progress-bar');
            const progressPercentage = document.getElementById('video-progress-percentage');
            
            if (progressBar && progressPercentage) {
                progressBar.style.width = currentPercentage + '%';
                progressPercentage.textContent = parseFloat(currentPercentage).toFixed(1) + '%';
            }
        }
    }

    /**
     * EVENT: timeupdate
     * Purpose: Continuously track current video position (fires every ~250ms)
     * Action: Update position and accumulate watched time ONLY if playing AND tab visible
     * 
     * ANTI-CHEAT LOGIC:
     * - Continuously update lastAllowedPosition as video plays forward naturally
     * - This ensures students can only seek up to where they've actually watched
     * - If video is somehow ahead of lastAllowedPosition (shouldn't happen), block it
     */
    player.on('timeupdate', function() {
        // Only track if actively playing AND tab is visible
        if (!isPlaying || !isTabVisible || sessionStartTimestamp === null) {
            return;
        }
        
        const currentTime = player.currentTime();
        
        // ANTI-CHEAT: Ensure video cannot play beyond allowed position
        // This catches any forward seeking that somehow got past the seeking event
        if (currentTime > lastAllowedPosition + 0.5) {
            console.log('ANTI-CHEAT: Video ahead of allowed position - reverting');
            player.currentTime(lastAllowedPosition);
            return;
        }
        
        // Only count forward progress (real watching, not backward seeking)
        if (currentTime > lastPosition) {
            // We're making forward progress - this is real watch time
            
            // Update max watched time and last allowed position
            if (currentTime > maxWatchedTime) {
                maxWatchedTime = currentTime;
            }
            if (currentTime > lastAllowedPosition) {
                lastAllowedPosition = currentTime;
            }
            
            lastPosition = currentTime;
            
            // Update progress bar in real-time
            updateProgressBarRealTime();
        } else if (currentTime < lastPosition) {
            // User seeked backward - update lastPosition but don't add to watched time
            // Also update lastAllowedPosition to allow this backward position
            lastAllowedPosition = currentTime;
            lastPosition = currentTime;
            
            // Update progress bar even when seeking backward
            updateProgressBarRealTime();
        }
    });

    /**
     * EVENT: seeked
     * Purpose: Detect when seek operation completes
     * Action: Finalize previous segment and restart tracking from new position
     * 
     * ANTI-CHEAT LOGIC:
     * - Double-check that seek didn't go beyond lastAllowedPosition
     * - If forward seek somehow completed, revert it immediately
     * - Only allow backward seeks to finalize
     */
    player.on('seeked', function() {
        const currentTime = player.currentTime();
        
        // ANTI-CHEAT: Final safety check - ensure we didn't seek forward
        if (currentTime > lastAllowedPosition + 0.5) {
            console.log('ANTI-CHEAT: Seek completed beyond allowed - reverting');
            player.currentTime(lastAllowedPosition);
            return;
        }
        
        // Finalize previous watching session if we were tracking
        if (isPlaying && sessionStartTimestamp !== null && lastPosition > sessionStartPosition) {
            addWatchedSegment(sessionStartPosition, lastPosition);
        }
        
        // Update positions
        lastPosition = currentTime;
        
        // If backward seek, update lastAllowedPosition to allow it
        if (currentTime < lastAllowedPosition) {
            lastAllowedPosition = currentTime;
        }
        
        // If still playing, start new session from this position
        if (isPlaying && isTabVisible) {
            sessionStartPosition = currentTime;
            sessionStartTimestamp = Date.now();
        } else {
            sessionStartPosition = currentTime;
            sessionStartTimestamp = null;
        }
    });

    /**
     * EVENT: ended
     * Purpose: Triggered when video reaches the end
     * Action: Finalize current watch time segment and mark as completed
     */
    player.on('ended', function() {
        console.log('Video ended');
        
        // Finalize current session
        if (sessionStartTimestamp !== null) {
            const endPosition = player.currentTime();
            if (endPosition > sessionStartPosition) {
                addWatchedSegment(sessionStartPosition, endPosition);
            }
        }
        
        // Update max watched time to video duration
        const duration = player.duration();
        if (duration && duration > maxWatchedTime) {
            maxWatchedTime = duration;
        }
        
        lastPosition = duration || 0;
        isPlaying = false;
        sessionStartTimestamp = null;
        
        // Final update with completion status
        updateWatchProgress(true);
    });

    /**
     * Tab Visibility API - Pause video when tab is inactive (ANTI-CHEAT)
     * Purpose: 
     * 1. Prevent students from opening video in background tab and letting it play
     * 2. Only count time when student is actively watching (tab visible)
     * 
     * LOGIC:
     * - When tab becomes hidden: PAUSE THE VIDEO (not just stop tracking)
     * - This prevents background playback cheating
     * - When tab becomes visible: Video remains paused (student must manually play)
     */
    document.addEventListener('visibilitychange', function() {
        const wasVisible = isTabVisible;
        isTabVisible = !document.hidden;
        
        if (!wasVisible && isTabVisible) {
            // Tab became visible - allow resuming (but don't auto-play)
            console.log('Tab visible - video can be resumed manually');
            // Don't auto-play - student must click play button
            // This ensures intentional watching
            
            // If video was playing before, student needs to resume manually
            if (isPlaying) {
                // Finalize any previous session that was interrupted
                if (sessionStartTimestamp !== null) {
                    const currentPosition = player.currentTime();
                    if (currentPosition > sessionStartPosition) {
                        addWatchedSegment(sessionStartPosition, currentPosition);
                    }
                }
                // Reset session - will restart when student clicks play
                isPlaying = false;
                sessionStartTimestamp = null;
            }
        } else if (wasVisible && !isTabVisible) {
            // Tab became hidden - PAUSE VIDEO IMMEDIATELY (ANTI-CHEAT)
            console.log('ANTI-CHEAT: Tab hidden - pausing video');
            
            // Pause the video player
            if (!player.paused()) {
                player.pause();
            }
            
            // Finalize current session before pausing tracking
            if (isPlaying && sessionStartTimestamp !== null) {
                const currentPosition = player.currentTime();
                
                // Finalize current session
                if (currentPosition > sessionStartPosition) {
                    addWatchedSegment(sessionStartPosition, currentPosition);
                }
                
                lastPosition = currentPosition;
                sessionStartTimestamp = null;
            }
            
            isPlaying = false;
        }
    });

    /**
     * Window Blur Event - Additional anti-cheat measure
     * Pause video if window loses focus (user switches to another window/app)
     */
    window.addEventListener('blur', function() {
        if (!player.paused() && isTabVisible) {
            console.log('ANTI-CHEAT: Window blurred - pausing video');
            player.pause();
        }
    });

    /**
     * Update watch progress on backend
     * Throttled to prevent too many API calls
     */
    let updateTimer = null;
    function updateWatchProgress(isCompleted = false) {
        clearTimeout(updateTimer);
        
        updateTimer = setTimeout(function() {
            // Finalize any ongoing session before sending update
            if (isPlaying && sessionStartTimestamp !== null && isTabVisible) {
                const currentPosition = player.currentTime();
                if (currentPosition > sessionStartPosition) {
                    addWatchedSegment(sessionStartPosition, currentPosition);
                    // Restart session from current position to continue tracking
                    sessionStartPosition = currentPosition;
                    sessionStartTimestamp = Date.now();
                }
                lastPosition = currentPosition;
            }
            
            const duration = player.duration() || 0;
            const watchedPercentage = duration > 0 ? (watchedSeconds / duration) * 100 : 0;
            const isEightyPercentWatched = watchedPercentage >= 80;

            const data = {
                lesson_id: lessonId,
                watched_seconds: Math.floor(watchedSeconds),
                watched_percentage: parseFloat(watchedPercentage.toFixed(2)),
                current_position: parseFloat(lastPosition.toFixed(2)),
                max_watched_time: parseFloat(maxWatchedTime.toFixed(2)),
                is_completed: isCompleted || isEightyPercentWatched
            };

            // Send progress update to backend
            fetch('/api/lessons/' + lessonId + '/video/track', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(responseData => {
                if (responseData.success) {
                    console.log('Watch progress updated:', responseData.data);
                    
                    // Update UI: Progress bar and percentage
                    updateProgressBarUI(responseData.data.watched_percentage, responseData.data.video_completed);
                } else {
                    console.error('Error updating progress:', responseData.message);
                }
            })
            .catch(error => {
                console.error('Error updating watch progress:', error);
            });
        }, 10000); // Update every 10 seconds
    }

    /**
     * Update progress bar UI dynamically
     */
    function updateProgressBarUI(watchedPercentage, videoCompleted) {
        const progressBar = document.getElementById('video-progress-bar');
        const progressPercentage = document.getElementById('video-progress-percentage');
        
        if (progressBar && progressPercentage) {
            // Update progress bar width
            progressBar.style.width = watchedPercentage + '%';
            
            // Update percentage text
            progressPercentage.textContent = parseFloat(watchedPercentage).toFixed(1) + '%';
            
            // Update progress message
            const progressMessage = progressBar.parentElement.nextElementSibling;
            if (progressMessage) {
                if (videoCompleted || watchedPercentage >= 80) {
                    progressMessage.innerHTML = '<p class="text-xs text-green-600 mt-1 font-medium">✓ Video completed! Game unlocked.</p>';
                } else {
                    const remaining = 80 - watchedPercentage;
                    progressMessage.innerHTML = '<p class="text-xs text-gray-500 mt-1">Watch ' + remaining.toFixed(1) + '% more to unlock game</p>';
                }
            }
        }
        
        // Update game button state if video just completed
        if (videoCompleted) {
            const gameButton = document.querySelector('[href*="games"]');
            if (gameButton && gameButton.classList.contains('cursor-not-allowed')) {
                // Reload page to show unlocked button (or update via JS)
                location.reload();
            }
        }
    }

    // Load existing progress on page load
    player.ready(function() {
        fetch('/api/lessons/' + lessonId + '/video/progress', {
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data) {
                // Restore max watched time (prevents forward seeking)
                if (data.max_watched_time !== undefined) {
                    maxWatchedTime = parseFloat(data.max_watched_time) || 0;
                }
                
                // Restore last allowed position (key anti-cheat variable)
                if (data.max_watched_time !== undefined) {
                    lastAllowedPosition = parseFloat(data.max_watched_time) || 0;
                } else if (data.last_position !== undefined) {
                    // Fallback to last_position if max_watched_time not available
                    lastAllowedPosition = parseFloat(data.last_position) || 0;
                }
                
                // Restore watched seconds
                if (data.watched_seconds !== undefined) {
                    watchedSeconds = parseFloat(data.watched_seconds) || 0;
                }
                
                // Restore last position and resume from there
                // Ensure we don't resume beyond lastAllowedPosition
                if (data.last_position !== undefined && data.last_position > 0) {
                    const resumePosition = Math.min(
                        parseFloat(data.last_position) || 0,
                        lastAllowedPosition
                    );
                    player.currentTime(resumePosition);
                    lastPosition = resumePosition;
                }
                
                console.log('Progress loaded:', {
                    maxWatchedTime: maxWatchedTime,
                    lastAllowedPosition: lastAllowedPosition,
                    watchedSeconds: watchedSeconds,
                    lastPosition: lastPosition
                });
            }
        })
        .catch(error => {
            console.error('Error loading video progress:', error);
        });
    });

    // Cleanup on page unload - finalize and save progress
    window.addEventListener('beforeunload', function() {
        // Finalize current session
        if (isPlaying && sessionStartTimestamp !== null && isTabVisible) {
            const currentPosition = player.currentTime();
            if (currentPosition > sessionStartPosition) {
                addWatchedSegment(sessionStartPosition, currentPosition);
            }
        }
        
        // Send final update (synchronous if possible, or use sendBeacon)
        updateWatchProgress();
        
        // Use sendBeacon as fallback for more reliable final save
        if (navigator.sendBeacon) {
            const duration = player.duration() || 0;
            const completionPercentage = duration > 0 ? (watchedSeconds / duration) * 100 : 0;
            const isEightyPercentWatched = completionPercentage >= 80;
            
            const blob = new Blob([JSON.stringify({
                lesson_id: lessonId,
                current_position: lastPosition,
                max_watched_time: maxWatchedTime,
                watched_seconds: Math.floor(watchedSeconds),
                is_completed: isEightyPercentWatched
            })], { type: 'application/json' });
            
            navigator.sendBeacon('/api/lessons/' + lessonId + '/video/track?token={{ csrf_token() }}', blob);
        }
    });
});
</script>
@endpush
