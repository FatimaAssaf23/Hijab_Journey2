@extends('layouts.app')

@push('styles')
<!-- Video.js CSS -->
<link href="https://vjs.zencdn.net/8.6.1/video-js.css" rel="stylesheet" />
<style>
    /* Custom styling to match existing design */
    .video-js {
        width: 100%;
        height: auto;
        min-height: 240px;
        max-height: 360px;
        border-radius: 0.75rem;
        background-color: #000;
    }
    .video-js .vjs-tech {
        border-radius: 0.75rem;
    }
    .vjs-poster {
        border-radius: 0.75rem;
    }
    /* Ensure video container has proper aspect ratio */
    #lesson-video-player {
        width: 100%;
        max-width: 100%;
    }
    /* Minimize video container */
    .video-container {
        max-width: 850px;
        margin: 0 auto;
    }
    @media (max-width: 768px) {
        .video-container {
            max-width: 100%;
        }
        .video-js {
            min-height: 220px;
            max-height: 300px;
        }
    }
    /* Warning message styling for forward seeking */
    .video-seek-warning {
        position: fixed !important;
        background-color: #ef4444 !important;
        color: white !important;
        padding: 0.75rem 1rem !important;
        border-radius: 0.5rem !important;
        font-size: 0.875rem !important;
        font-weight: 600 !important;
        white-space: nowrap !important;
        z-index: 99999 !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5) !important;
        animation: slideUpVolume 0.3s ease-out !important;
        pointer-events: none !important;
        min-width: 220px !important;
        text-align: center !important;
        opacity: 1 !important;
        display: block !important;
        visibility: visible !important;
    }
    /* Progress bar styling */
    .video-progress-bar {
        height: 10px;
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
    /* Rewind 10 seconds button styling inside video player */
    .vjs-rewind-10-button {
        font-family: VideoJS;
        font-weight: normal;
        font-style: normal;
    }
    .vjs-rewind-10-button:before {
        content: "\f11a"; /* Video.js rewind icon */
        font-size: 1.8em;
        line-height: 1.67;
    }
    .vjs-rewind-10-button:hover:before {
        text-shadow: 0 0 1em #fff;
    }
    .vjs-rewind-10-button .vjs-icon-placeholder {
        cursor: pointer;
        width: 2em;
        height: 100%;
    }
    /* Forward 10 seconds button styling inside video player */
    .vjs-forward-10-button {
        font-family: VideoJS;
        font-weight: normal;
        font-style: normal;
        cursor: pointer;
    }
    .vjs-forward-10-button:before {
        content: "\f11a"; /* Video.js rewind icon (same as first rewind button) */
        font-size: 1.8em;
        line-height: 1.67;
        display: block;
    }
    .vjs-forward-10-button:hover:before {
        text-shadow: 0 0 1em #fff;
    }
    .vjs-forward-10-button .vjs-icon-placeholder {
        cursor: pointer;
        width: 2em;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .vjs-forward-10-button.vjs-disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    /* Ensure buttons are visible */
    .vjs-rewind-10-button,
    .vjs-forward-10-button {
        display: inline-block;
        visibility: visible !important;
        opacity: 1 !important;
    }
    /* Volume warning message styling */
    .volume-warning-message {
        position: fixed !important;
        background-color: #ef4444 !important;
        color: white !important;
        padding: 0.75rem 1rem !important;
        border-radius: 0.5rem !important;
        font-size: 0.875rem !important;
        font-weight: 600 !important;
        white-space: nowrap !important;
        z-index: 99999 !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5) !important;
        animation: slideUpVolume 0.3s ease-out !important;
        pointer-events: none !important;
        min-width: 220px !important;
        text-align: center !important;
        opacity: 1 !important;
        display: block !important;
        visibility: visible !important;
    }
    .volume-warning-message::after {
        content: '';
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        border: 8px solid transparent;
        border-top-color: #ef4444;
    }
    @keyframes slideUpVolume {
        from {
            opacity: 0;
            transform: translate(-50%, 15px);
        }
        to {
            opacity: 1;
            transform: translate(-50%, 0);
        }
    }
    /* Full screen lesson view styles */
    body.lesson-fullscreen main {
        padding: 0 !important;
        margin: 0 !important;
        overflow: visible !important;
    }
    body.lesson-fullscreen {
        overflow: visible !important;
    }
    .lesson-fullscreen-container {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 1.5rem !important;
        min-height: 100vh !important;
        background-color: #FFF4FA !important;
        overflow: visible !important;
        box-sizing: border-box !important;
    }
    .lesson-content-wrapper {
        width: 100% !important;
        padding: 2rem 1.5rem !important;
        background-color: #FFF4FA !important;
        overflow: visible !important;
        box-sizing: border-box !important;
    }
    /* Prevent text cropping */
    .relative.z-10 {
        overflow: visible !important;
        padding-top: 0.5rem !important;
    }
</style>
@endpush

@section('content')
<div class="lesson-fullscreen-container">
    <div class="lesson-content-wrapper">
        <div class="relative bg-white/70 backdrop-blur-xl rounded-3xl shadow-2xl p-7 border border-pink-100 w-full overflow-visible" style="overflow: visible !important; box-sizing: border-box;">
            <!-- Decorative SVG background -->
            <svg class="absolute right-0 top-0 w-24 h-24 opacity-10 pointer-events-none z-0" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="100" cy="100" r="100" fill="#f472b6"/>
            </svg>
            <div class="relative z-10" style="overflow: visible !important; padding-top: 0;">
                <!-- Go Back Button -->
                <div class="mb-5" style="margin-top: 0 !important;">
                    <a href="{{ route('levels') }}" class="inline-flex items-center gap-2 bg-white hover:bg-pink-50 text-pink-600 px-4 py-2.5 rounded-lg text-sm font-semibold shadow-md hover:shadow-lg transition-all duration-150 border-2 border-pink-200 hover:border-pink-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Go Back
                    </a>
                </div>
                <div class="flex items-start gap-3 mb-4 overflow-visible">
                    <span class="text-3xl drop-shadow flex-shrink-0 mt-1">{{ $lesson->icon ?? '📘' }}</span>
                    <div class="flex-1 min-w-0 overflow-visible" style="overflow: visible !important; word-wrap: break-word;">
                        <h1 class="text-xl font-extrabold text-pink-600 tracking-tight break-words" style="overflow: visible !important; word-wrap: break-word; hyphens: auto; line-height: 1.4;">{{ $lesson->title }}</h1>
                        {{-- Completed Lesson Indicator --}}
                        @if(isset($progress) && $progress && $progress->status === 'completed')
                            <div class="mt-1.5">
                                <span class="completed-badge" style="padding: 0.375rem 0.75rem; font-size: 0.75rem;">
                                    <span>✓</span>
                                    <span>Lesson Completed</span>
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Video Progress Bar - Always show for video lessons --}}
                @php
                    $isVideo = $lesson->content_url && Str::endsWith($lesson->content_url, ['.mp4', '.mov', '.avi', '.mkv', '.wmv', '.flv', '.webm']);
                    // Use accurate percentage passed from route handler (most reliable)
                    // This ensures we always show the correct percentage when returning from game page
                    $initialProgress = 0;
                    if (isset($accuratePercentageForView) && $accuratePercentageForView > 0) {
                        $initialProgress = round($accuratePercentageForView, 1);
                    } elseif (isset($progress) && $progress) {
                        // Fallback: Calculate from max_watched_time if accuratePercentageForView not available
                        $videoDuration = $lesson->video_duration_seconds ?? 0;
                        $maxWatchedTime = $progress->max_watched_time ?? 0;
                        if ($videoDuration > 0 && $maxWatchedTime > 0) {
                            $initialProgress = round(($maxWatchedTime / $videoDuration) * 100, 1);
                        } else {
                            $initialProgress = round($progress->watched_percentage ?? 0, 1);
                        }
                    }
                @endphp
                @if($isVideo)
                    <div class="mb-3" id="video-progress-container">
                        <div class="flex items-center justify-between mb-1.5 gap-2">
                            <span class="text-xs font-semibold text-pink-600 whitespace-nowrap">Video Progress</span>
                            <span class="text-xs text-gray-600 whitespace-nowrap" id="video-progress-percentage">
                                {{ $initialProgress }}%
                            </span>
                        </div>
                        <div class="video-progress-bar">
                            <div class="video-progress-fill" id="video-progress-bar" style="width: {{ $initialProgress }}%"></div>
                        </div>
                        <div id="video-progress-message" class="break-words">
                            @if($initialProgress >= 80)
                                <p class="text-xs text-green-600 mt-1 font-medium break-words">✓ Lesson completed! Game unlocked.</p>
                            @else
                                <p class="text-xs text-gray-500 mt-1 break-words">Watch {{ round(80 - $initialProgress, 1) }}% more to complete lesson and unlock game</p>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="mb-3 text-sm text-gray-700 leading-relaxed break-words">{{ $lesson->description }}</div>
                <div class="mb-2 text-xs text-gray-500 flex gap-4 flex-wrap">
                    <span><span class="font-semibold text-pink-500">Skills:</span> {{ $lesson->skills }}</span>
                    <span><span class="font-semibold text-pink-500">Duration:</span> {{ $lesson->duration_minutes ?? '-' }} min</span>
                </div>
                
                {{-- Game Button - Locked/Unlocked States --}}
                @if(isset($hasGame) && $hasGame)
                    <div class="mb-4 mt-4" id="game-button-container">
                        @if(isset($isVideoCompleted) && $isVideoCompleted)
                            {{-- Unlocked Game Button --}}
                            <a href="{{ route('student.games', ['lesson_id' => $lesson->lesson_id]) }}" 
                               class="inline-flex items-center gap-2 bg-gradient-to-r from-green-400 via-green-300 to-green-200 hover:from-green-500 hover:to-green-300 text-white font-bold py-2 px-6 rounded-full shadow-lg transition-all text-sm transform hover:scale-105">
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
                            <div id="locked-game-button" class="inline-flex items-center gap-2 bg-gray-200 text-gray-500 font-semibold py-2 px-6 rounded-full shadow cursor-not-allowed relative text-sm">
                                <span class="text-xl opacity-50">🔒</span>
                                <span>Play Game</span>
                                <span class="ml-2 bg-gray-400 px-2 py-1 rounded-full text-xs text-white">Locked</span>
                            </div>
                            <p id="game-unlock-message" class="text-xs text-gray-600 mt-1.5 ml-2 break-words">
                                ⏳ Watch 80% of the video to unlock this game
                            </p>
                        @endif
                    </div>
                @endif
                @if($lesson->content_url)
                    <div class="mt-3">
                        @php
                            $fileExtension = strtolower(pathinfo($lesson->content_url, PATHINFO_EXTENSION));
                            $isVideo = in_array($fileExtension, ['mp4', 'mov', 'avi', 'mkv', 'wmv', 'flv', 'webm']);
                            $isPdf = $fileExtension === 'pdf';
                            // Remove leading slash if present to avoid double slashes in URL
                            $contentUrl = ltrim($lesson->content_url, '/');
                            $storageUrl = asset('storage/' . $contentUrl);
                        @endphp
                        
                        @if($isPdf)
                            <iframe src="{{ $storageUrl }}" width="100%" height="400px" class="rounded-xl border border-pink-100 shadow"></iframe>
                            <div class="mt-2">
                                <a href="{{ $storageUrl }}" target="_blank" class="inline-block bg-pink-100 text-pink-700 px-3 py-1.5 rounded-lg shadow hover:bg-pink-200 transition text-sm font-semibold mt-2">Open PDF in New Tab</a>
                            </div>
                        @elseif($isVideo)
                            <div class="video-container rounded-xl border border-pink-100 shadow overflow-hidden mx-auto" style="background-color: #000; max-width: 850px;">
                                <video
                                    id="lesson-video-player"
                                    class="video-js vjs-default-skin"
                                    controls
                                    preload="auto"
                                    width="100%">
                                    <source src="{{ $storageUrl }}" type="video/{{ $fileExtension }}">
                                    <p class="vjs-no-js">
                                        To view this video please enable JavaScript, and consider upgrading to a web browser that
                                        <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>.
                                    </p>
                                </video>
                            </div>
                            @if($lesson->video_duration_seconds)
                                <p class="text-xs text-gray-500 mt-1.5 text-center">Duration: {{ gmdate('i:s', $lesson->video_duration_seconds) }}</p>
                            @endif
                        @else
                            <a href="{{ $storageUrl }}" class="inline-block bg-pink-100 text-pink-700 px-3 py-1.5 rounded-lg shadow hover:bg-pink-200 transition text-sm font-semibold mt-2" target="_blank">Download Content</a>
                            <p class="text-xs text-gray-500 mt-1.5">File: {{ basename($lesson->content_url) }}</p>
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
    // Add full screen class to body
    document.body.classList.add('lesson-fullscreen');
    
    // Remove class when leaving page
    window.addEventListener('beforeunload', function() {
        document.body.classList.remove('lesson-fullscreen');
    });
    // Only initialize if video element exists
    const videoElement = document.getElementById('lesson-video-player');
    if (!videoElement) return;

    // Initialize Video.js player
    const player = videojs('lesson-video-player', {
        fluid: true,
        responsive: true,
        aspectRatio: '16:9',
        playbackRates: [0.25, 0.5, 0.75, 1],
        controls: true,
        preload: 'auto',
        width: '100%',
        height: 'auto',
        controlBar: {
            rewindButton: false,  // Disable default rewind button
            muteToggle: false     // Disable mute button
        }
    }, function() {
        // Player is ready
        console.log('Video.js player initialized');
        
        // Ensure video is visible and respects max-height
        const videoElement = this.el();
        if (videoElement) {
            videoElement.style.width = '100%';
            videoElement.style.height = 'auto';
            videoElement.style.maxHeight = '360px';
        }
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
    let lastUpdateTime = 0;         // Timestamp of last progress update (for throttling)
    let timeBeforeSeek = 0;         // Time before a seek operation starts (for detecting forward seeks)
    
    // Track watched segments to prevent double-counting rewatched sections
    // Format: [{start: 10, end: 25}, {start: 30, end: 45}]
    let watchedSegments = [];

    // Get lesson ID from Laravel
    const lessonId = {{ $lesson->lesson_id }};

    /**
     * Custom Video.js Control: Rewind 10 seconds button
     */
    const RewindButton = videojs.getComponent('Button');
    
    class Rewind10Button extends RewindButton {
        constructor() {
            super(...arguments);
            this.controlText('Rewind 10 seconds');
        }
        
        handleClick() {
            const currentTime = player.currentTime();
            const newTime = Math.max(0, currentTime - 10); // Don't go below 0
            
            // Explicitly set flag BEFORE seeking to indicate this is a backward seek
            isSeekingForward = false;
            
            // Store current position before seeking
            const previousPosition = lastPosition;
            lastPosition = currentTime;
            
            // Seek backward - this should always be allowed
            console.log('Rewind button clicked: Rewinding from', currentTime, 'to', newTime, 'maxWatchedTime:', maxWatchedTime);
            
            // Directly seek - the seeking event handler will see isSeekingForward=false and allow it
            player.currentTime(newTime);
            
            // Update positions after a brief delay to ensure seek completes
            setTimeout(() => {
                const actualTime = player.currentTime();
                lastPosition = actualTime;
                console.log('Rewind completed: new position', actualTime);
                updateProgressBarRealTime();
            }, 150);
        }
        
        buildCSSClass() {
            return 'vjs-rewind-10-button vjs-control vjs-button';
        }
    }
    
    /**
     * Custom Video.js Control: Rewind 10 seconds button (second button)
     * This is the button beside the sound button - also rewinds 10 seconds
     */
    class Forward10Button extends RewindButton {
        constructor() {
            super(...arguments);
            this.controlText('Rewind 10 seconds');
        }
        
        handleClick() {
            const currentTime = player.currentTime();
            const duration = player.duration();
            
            // Calculate new time (10 seconds back, but not below 0)
            let newTime = Math.max(0, currentTime - 10);
            
            // Make sure we have valid values
            if (isNaN(currentTime) || currentTime <= 0) {
                console.log('Invalid current time, using 0');
                newTime = 0;
            }
            
            // Explicitly set flag BEFORE seeking to indicate this is a backward seek
            isSeekingForward = false;
            
            // Store current position before seeking
            lastPosition = currentTime;
            
            // Seek backward - this should always be allowed
            console.log('Rewind button (second) clicked: Rewinding from', currentTime, 'to', newTime, 'maxWatchedTime:', maxWatchedTime);
            
            // Use requestAnimationFrame to ensure the seek happens after flag is set
            requestAnimationFrame(() => {
                // Directly set the time
                if (player && !player.isDisposed()) {
                    player.currentTime(newTime);
                    
                    // Update positions immediately
                    lastPosition = newTime;
                    
                    // Verify the seek worked
                    setTimeout(() => {
                        const actualTime = player.currentTime();
                        console.log('Rewind completed: new position', actualTime, 'expected:', newTime);
                        
                        // If seek didn't work, try again
                        if (Math.abs(actualTime - newTime) > 0.5) {
                            console.log('Seek verification failed, retrying...');
                            player.currentTime(newTime);
                            lastPosition = newTime;
                        }
                        
                        updateProgressBarRealTime();
                    }, 100);
                }
            });
        }
        
        buildCSSClass() {
            return 'vjs-forward-10-button vjs-control vjs-button';
        }
    }
    
    // Register the custom button components
    videojs.registerComponent('Rewind10Button', Rewind10Button);
    videojs.registerComponent('Forward10Button', Forward10Button);
    
    // Prevent playback speed from exceeding 1x
    player.ready(function() {
        // Set initial playback rate to 1.0
        player.playbackRate(1.0);
        
        // Listen for rate changes and enforce cap (prevents programmatic changes above 1x)
        let isResetting = false;
        player.on('ratechange', function() {
            if (isResetting) return; // Prevent infinite loop
            
            const currentRate = player.playbackRate();
            if (currentRate > 1.0) {
                console.log('Playback speed exceeded 1.0x, resetting to 1.0x');
                isResetting = true;
                player.playbackRate(1.0);
                setTimeout(() => {
                    isResetting = false;
                }, 100);
            }
        });
        
        // Also monitor the native video element if accessible
        const tech = player.tech();
        if (tech && tech.el_) {
            const videoEl = tech.el_;
            if (videoEl && videoEl.addEventListener) {
                videoEl.addEventListener('ratechange', function() {
                    if (videoEl.playbackRate > 1.0) {
                        console.log('Native video playback rate exceeded 1.0x, resetting');
                        videoEl.playbackRate = 1.0;
                        player.playbackRate(1.0);
                    }
                });
            }
        }
    });
    
    // Add the buttons to the player when ready
    player.ready(function() {
        // Wait for control bar to be fully ready
        setTimeout(() => {
            const controlBar = player.getChild('controlBar');
            if (!controlBar) {
                console.error('Control bar not found');
                return;
            }
            
            console.log('Control bar found, adding buttons...');
            const children = controlBar.children();
            console.log('Control bar children:', children.map(c => c.name()));
            
            // Find components
            const playToggle = controlBar.getChild('playToggle');
            const volumeControl = controlBar.getChild('volumeControl');
            const currentTimeDisplay = controlBar.getChild('currentTimeDisplay');
            
            // Remove default rewind button if it exists
            const rewindControl = controlBar.getChild('rewindControl');
            if (rewindControl) {
                controlBar.removeChild(rewindControl);
                console.log('Default rewind button removed');
            }
            
            // Don't add custom rewind button - user only wants forward button
            
            // Add forward button after volume control (beside sound button)
            if (volumeControl) {
                const volumeIndex = children.indexOf(volumeControl);
                // Add after volume control
                controlBar.addChild('Forward10Button', {}, volumeIndex + 1);
                console.log('Forward button added after volume control at index:', volumeIndex + 1);
            } else if (currentTimeDisplay) {
                // If no volume control, add after current time display
                const timeIndex = children.indexOf(currentTimeDisplay);
                controlBar.addChild('Forward10Button', {}, timeIndex + 1);
                console.log('Forward button added after time display at index:', timeIndex + 1);
            } else {
                // Add at position 2 (after rewind and play)
                controlBar.addChild('Forward10Button', {}, 2);
                console.log('Forward button added at index 2');
            }
            
            // Verify buttons were added and make them visible
            setTimeout(() => {
                const rewindBtn = controlBar.getChild('Rewind10Button');
                const forwardBtn = controlBar.getChild('Forward10Button');
                console.log('Rewind button added:', !!rewindBtn);
                console.log('Forward button added:', !!forwardBtn);
                
                if (rewindBtn && rewindBtn.el()) {
                    rewindBtn.el().style.display = 'inline-block';
                    rewindBtn.el().style.visibility = 'visible';
                    console.log('Rewind button element:', rewindBtn.el());
                }
                
                if (forwardBtn && forwardBtn.el()) {
                    forwardBtn.el().style.display = 'inline-block';
                    forwardBtn.el().style.visibility = 'visible';
                    console.log('Forward button element:', forwardBtn.el());
                }
            }, 200);
        }, 500);
    });

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
        
        // Cap segment end at video duration to prevent >100%
        const duration = player.duration();
        if (duration && duration > 0) {
            start = Math.min(start, duration);
            end = Math.min(end, duration);
            // If start >= end after capping, skip this segment
            if (start >= end) return;
        }
        
        // Add or extend segment
        watchedSegments.push({ start: start, end: end });
        
        // Recalculate total
        watchedSeconds = calculateWatchedSeconds();
        
        // Final cap: ensure watchedSeconds never exceeds video duration
        if (duration && duration > 0) {
            watchedSeconds = Math.min(watchedSeconds, duration);
        }
    }

    /**
     * Disable Video.js progress bar forward seeking
     * Intercepts clicks on progress bar and prevents forward seeking
     */
    player.ready(function() {
        const MIN_VOLUME = 0.25; // 25% minimum volume
        
        // Set initial volume and ensure not muted
        player.volume(MIN_VOLUME);
        player.muted(false);
        
        // Remove mute button from control bar completely
        const controlBar = player.getChild('controlBar');
        const volumeControl = controlBar.getChild('volumeControl');
        if (volumeControl) {
            const muteToggle = volumeControl.getChild('muteToggle');
            if (muteToggle) {
                // Completely remove mute button
                muteToggle.dispose();
                // Or hide it and disable
                muteToggle.hide();
                muteToggle.disable();
                // Prevent all click events
                muteToggle.off();
                muteToggle.on('click', function(e) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    e.stopPropagation();
                    player.muted(false);
                    player.volume(MIN_VOLUME);
                    showVolumeWarning('You cannot mute the video');
                    return false;
                });
            }
        }
        
        // Override muted() method to always return false
        const originalMuted = player.muted.bind(player);
        let muteWarningShown = false;
        player.muted = function(mute) {
            if (arguments.length > 0 && mute === true) {
                // Prevent muting - always keep unmuted
                if (!muteWarningShown) {
                    muteWarningShown = true;
                    showVolumeWarning('You cannot mute the video');
                    setTimeout(() => {
                        muteWarningShown = false;
                    }, 3000);
                }
                return originalMuted(false);
            }
            return originalMuted();
        };
        
        // Override volume() method to enforce minimum
        const originalVolume = player.volume.bind(player);
        let volumeWarningShown = false;
        player.volume = function(vol) {
            if (arguments.length > 0) {
                const currentVol = originalVolume();
                // If setting volume, enforce minimum
                if (vol < MIN_VOLUME) {
                    vol = MIN_VOLUME;
                    // Show warning if user was trying to decrease (not initial set)
                    if (currentVol >= MIN_VOLUME && !volumeWarningShown) {
                        volumeWarningShown = true;
                        showVolumeWarning('Volume cannot be decreased below 25%');
                        setTimeout(() => {
                            volumeWarningShown = false;
                        }, 3000);
                    }
                }
                // Also ensure not muted
                originalMuted(false);
                return originalVolume(vol);
            }
            return originalVolume();
        };
        
        // Listen to volume changes and enforce minimum and no muting
        let lastVolume = MIN_VOLUME;
        let isInitializing = true;
        
        // Set flag after initial setup
        setTimeout(() => {
            isInitializing = false;
        }, 1000);
        
        player.on('volumechange', function() {
            const currentVolume = player.volume();
            const wasMuted = player.muted();
            
            // Always prevent muting
            if (player.muted()) {
                player.muted(false);
                if (!isInitializing) {
                    showVolumeWarning('You cannot mute the video');
                }
            }
            
            // Always enforce minimum volume
            if (currentVolume < MIN_VOLUME) {
                const attemptedVolume = currentVolume;
                player.volume(MIN_VOLUME);
                // Only show warning if user was trying to decrease (not initial set)
                if (!isInitializing && lastVolume >= MIN_VOLUME && attemptedVolume < MIN_VOLUME) {
                    showVolumeWarning('Volume cannot be decreased below 25%');
                }
            }
            
            lastVolume = player.volume();
        });
        
        // Intercept volume slider interactions directly
        if (volumeControl) {
            const volumeBar = volumeControl.getChild('volumeBar');
            if (volumeBar) {
                const volumeLevel = volumeBar.getChild('volumeLevel');
                const volumeHandle = volumeBar.getChild('volumeHandle');
                
                // Track previous volume to detect attempts
                let previousVolume = player.volume();
                
                // Intercept all mouse events on volume bar
                const handleVolumeInteraction = function(e) {
                    const rect = volumeBar.el().getBoundingClientRect();
                    const percent = Math.max(0, Math.min(1, (e.clientX - rect.left) / rect.width));
                    const requestedVolume = percent;
                    const newVolume = Math.max(MIN_VOLUME, percent); // Enforce minimum
                    
                    // Show warning if user tried to go below minimum
                    if (requestedVolume < MIN_VOLUME && previousVolume >= MIN_VOLUME) {
                        showVolumeWarning('Volume cannot be decreased below 25%');
                    }
                    
                    // Set volume directly, bypassing normal flow
                    originalVolume(newVolume);
                    originalMuted(false);
                    
                    // Update the visual slider
                    if (volumeLevel) {
                        volumeLevel.el().style.width = (newVolume * 100) + '%';
                    }
                    if (volumeHandle) {
                        volumeHandle.el().style.left = (newVolume * 100) + '%';
                    }
                    
                    previousVolume = newVolume;
                };
                
                volumeBar.on('mousedown', handleVolumeInteraction);
                volumeBar.on('touchstart', handleVolumeInteraction);
                
                if (volumeLevel) {
                    volumeLevel.on('mousedown', handleVolumeInteraction);
                    volumeLevel.on('touchstart', handleVolumeInteraction);
                }
                
                if (volumeHandle) {
                    volumeHandle.on('mousedown', function(e) {
                        e.preventDefault();
                        const handleMove = function(moveEvent) {
                            handleVolumeInteraction(moveEvent);
                        };
                        const handleUp = function() {
                            document.removeEventListener('mousemove', handleMove);
                            document.removeEventListener('mouseup', handleUp);
                        };
                        document.addEventListener('mousemove', handleMove);
                        document.addEventListener('mouseup', handleUp);
                    });
                }
            }
        }
        
        // Set initial volume to ensure it's at least 25%
        player.volume(MIN_VOLUME);
        player.muted(false);
        
        // Disable forward seeking on progress bar
        const progressControl = player.getChild('progressControl');
        if (progressControl) {
            const seekBar = progressControl.getChild('seekBar');
            if (seekBar && seekBar.el()) {
                const seekBarElement = seekBar.el();
                
                // Capture time before any seek interaction
                let mouseDownTime = 0;
                
                // Intercept mousedown FIRST (before Video.js processes it)
                seekBarElement.addEventListener('mousedown', function(e) {
                    console.log('Direct mousedown on seek bar element - capturing time');
                    // Capture current time IMMEDIATELY before any seek
                    const currentTime = player.currentTime();
                    mouseDownTime = currentTime;
                    timeBeforeSeek = Math.max(currentTime, maxWatchedTime, lastPosition);
                    console.log('Time captured - currentTime:', currentTime, 'timeBeforeSeek:', timeBeforeSeek, 'maxWatchedTime:', maxWatchedTime);
                }, true); // Use capture phase to intercept before Video.js
                
                // Intercept click to block forward seeks
                seekBarElement.addEventListener('click', function(e) {
                    console.log('Click on seek bar element');
                    
                    // Use the time we captured on mousedown, or current time as fallback
                    const referenceTime = timeBeforeSeek > 0 ? timeBeforeSeek : Math.max(player.currentTime(), maxWatchedTime, lastPosition);
                    
                    // Calculate target time from mouse position
                    const rect = seekBarElement.getBoundingClientRect();
                    const mousePosX = e.clientX - rect.left;
                    const percent = Math.max(0, Math.min(1, mousePosX / rect.width));
                    const duration = player.duration();
                    
                    if (duration > 0) {
                        const targetTime = percent * duration;
                        
                        console.log('Click seek - referenceTime:', referenceTime, 'target:', targetTime, 'maxWatchedTime:', maxWatchedTime);
                        
                        // Block forward seeking
                        if (targetTime > referenceTime + 0.1) {
                            console.log('BLOCKING FORWARD SEEK - showing warning');
                            e.preventDefault();
                            e.stopPropagation();
                            e.stopImmediatePropagation();
                            
                            // Prevent Video.js from processing this seek
                            player.currentTime(referenceTime);
                            
                            // Show warning
                            showSeekWarning('Forward seeking is completely disabled. You can only rewind.');
                            
                            // Reset mouseDownTime
                            mouseDownTime = 0;
                            return false;
                        } else {
                            // Backward seek allowed - update timeBeforeSeek for seeking event
                            timeBeforeSeek = referenceTime;
                        }
                    }
                }, true); // Use capture phase
                
                // Also handle touch events
                seekBarElement.addEventListener('touchstart', function(e) {
                    const currentTime = player.currentTime();
                    mouseDownTime = currentTime;
                    timeBeforeSeek = Math.max(currentTime, maxWatchedTime, lastPosition);
                }, true);
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
     * Allows backward seeking only - NO forward seeking allowed
     */
    function handleSeekAttempt(event) {
        console.log('Seek attempt detected');
        
        // Capture current time before seek (if not already captured)
        const currentTime = player.currentTime();
        if (timeBeforeSeek === 0) {
            timeBeforeSeek = Math.max(currentTime, maxWatchedTime, lastPosition);
        }
        const referenceTime = timeBeforeSeek > 0 ? timeBeforeSeek : Math.max(currentTime, maxWatchedTime, lastPosition);
        
        const progressControl = player.getChild('progressControl');
        if (!progressControl) {
            console.log('Progress control not found');
            return;
        }
        
        const seekBar = progressControl.getChild('seekBar');
        if (!seekBar || !seekBar.el()) {
            console.log('Seek bar not found');
            return;
        }
        
        const rect = seekBar.el().getBoundingClientRect();
        const mousePosX = event.offsetX !== undefined ? event.offsetX : 
                         (event.touches && event.touches[0] ? event.touches[0].clientX - rect.left : 0);
        const percent = Math.max(0, Math.min(1, mousePosX / rect.width));
        const duration = player.duration();
        
        if (duration && duration > 0) {
            const targetTime = percent * duration;
            
            console.log('handleSeekAttempt - referenceTime:', referenceTime, 'target:', targetTime, 'timeBeforeSeek:', timeBeforeSeek);
            
            // BLOCK ALL FORWARD SEEKING - even within watched content
            // Only allow backward seeking (target time less than reference time)
            if (targetTime > referenceTime + 0.1) {
                console.log('Forward seek blocked in handleSeekAttempt - showing warning');
                event.preventDefault();
                event.stopPropagation();
                event.stopImmediatePropagation();
                
                // Revert to reference position (block forward seeking)
                player.currentTime(referenceTime);
                
                // Show warning immediately
                showSeekWarning('Forward seeking is completely disabled. You can only rewind.');
                
                timeBeforeSeek = 0; // Reset
                return false;
            }
            // Backward seeking is allowed - let it proceed naturally
        }
    }

    /**
     * Show warning message when forward seeking is attempted
     */
    function showSeekWarning(message) {
        console.log('showSeekWarning called with message:', message);
        
        // Remove existing warning if any
        const existingWarning = document.querySelector('.video-seek-warning');
        if (existingWarning) {
            existingWarning.remove();
        }
        
        // Create warning element
        const warning = document.createElement('div');
        warning.className = 'video-seek-warning';
        warning.textContent = message;
        document.body.appendChild(warning);
        
        // Force initial styles
        warning.style.display = 'block';
        warning.style.visibility = 'visible';
        warning.style.opacity = '1';
        
        console.log('Warning element created and added to DOM');
        
        let centerX, topY;
        
        // Try to find the video player to position at top edge (same as volume warning)
        function findVideoPlayerPosition() {
            try {
                const videoPlayer = document.getElementById('lesson-video-player');
                if (videoPlayer) {
                    const playerRect = videoPlayer.getBoundingClientRect();
                    if (playerRect.width > 0 && playerRect.height > 0) {
                        centerX = playerRect.left + (playerRect.width / 2);
                        topY = playerRect.top + 20; // Position at top edge of video
                        return true;
                    }
                }
            } catch(e) {
                console.log('Error finding video player:', e);
            }
            return false;
        }
        
        // Try to find video player position
        if (!findVideoPlayerPosition()) {
            // Retry after a short delay
            setTimeout(() => {
                if (!findVideoPlayerPosition()) {
                    // Fallback: center of screen
                    centerX = window.innerWidth / 2;
                    topY = 100;
                }
                positionWarning();
            }, 50);
        } else {
            positionWarning();
        }
        
        function positionWarning() {
            if (!centerX) centerX = window.innerWidth / 2;
            if (!topY) topY = 100;
            
            // Position the warning (same as volume warning)
            warning.style.position = 'fixed';
            warning.style.left = centerX + 'px';
            warning.style.top = topY + 'px';
            warning.style.transform = 'translateX(-50%)';
            warning.style.zIndex = '99999';
            warning.style.backgroundColor = '#ef4444';
            warning.style.color = 'white';
            warning.style.padding = '0.75rem 1rem';
            warning.style.borderRadius = '0.5rem';
            warning.style.fontSize = '0.875rem';
            warning.style.fontWeight = '600';
            warning.style.whiteSpace = 'nowrap';
            warning.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.5)';
            warning.style.animation = 'slideUpVolume 0.3s ease-out';
            warning.style.pointerEvents = 'none';
            warning.style.minWidth = '220px';
            warning.style.textAlign = 'center';
            
            console.log('Warning positioned at:', centerX, topY);
            console.log('Warning element:', warning);
            console.log('Warning computed style:', window.getComputedStyle(warning));
        }
        
        // Auto-remove after 2.5 seconds
        setTimeout(() => {
            if (warning.parentNode) {
                warning.style.opacity = '0';
                warning.style.transition = 'opacity 0.3s ease-out';
                setTimeout(() => {
                    if (warning.parentNode) {
                        warning.remove();
                    }
                }, 300);
            }
        }, 2500);
    }

    /**
     * Show volume warning message above the sound button
     */
    function showVolumeWarning(message) {
        console.log('showVolumeWarning called with message:', message);
        
        // Remove existing volume warning if any
        const existingWarning = document.querySelector('.volume-warning-message');
        if (existingWarning) {
            existingWarning.remove();
        }
        
        // Create warning element
        const warning = document.createElement('div');
        warning.className = 'volume-warning-message';
        warning.textContent = message;
        document.body.appendChild(warning);
        
        // Force initial styles
        warning.style.display = 'block';
        warning.style.visibility = 'visible';
        warning.style.opacity = '1';
        
        let centerX, topY;
        
        // Try to find the video player to position at top edge (same as seek warning)
        function findVideoPlayerPosition() {
            try {
                const videoPlayer = document.getElementById('lesson-video-player');
                if (videoPlayer) {
                    const playerRect = videoPlayer.getBoundingClientRect();
                    if (playerRect.width > 0 && playerRect.height > 0) {
                        centerX = playerRect.left + (playerRect.width / 2);
                        topY = playerRect.top + 20; // Position at top edge of video
                        return true;
                    }
                }
            } catch(e) {
                console.log('Error finding video player:', e);
            }
            return false;
        }
        
        // Function to position warning with all styles
        function positionWarning() {
            if (!centerX) centerX = window.innerWidth / 2;
            if (!topY) topY = 100;
            
            // Use requestAnimationFrame to ensure DOM is ready
            requestAnimationFrame(() => {
                // Position the warning with all necessary styles
                warning.style.position = 'fixed';
                warning.style.left = centerX + 'px';
                warning.style.top = topY + 'px';
                warning.style.transform = 'translate(-50%, 0)';
                warning.style.zIndex = '99999';
                warning.style.backgroundColor = '#ef4444';
                warning.style.color = 'white';
                warning.style.padding = '0.75rem 1rem';
                warning.style.borderRadius = '0.5rem';
                warning.style.fontSize = '0.875rem';
                warning.style.fontWeight = '600';
                warning.style.whiteSpace = 'nowrap';
                warning.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.5)';
                warning.style.animation = 'slideUpVolume 0.3s ease-out';
                warning.style.pointerEvents = 'none';
                warning.style.minWidth = '220px';
                warning.style.textAlign = 'center';
                warning.style.opacity = '1';
                warning.style.display = 'block';
                warning.style.visibility = 'visible';
                warning.style.width = 'auto';
                warning.style.height = 'auto';
                warning.style.lineHeight = '1.5';
                warning.style.maxWidth = '90vw';
                warning.style.overflow = 'visible';
                warning.style.textOverflow = 'clip';
                
                // Force a reflow to ensure styles are applied
                void warning.offsetHeight;
                
                console.log('Volume warning positioned at:', centerX, topY);
                console.log('Volume warning element:', warning);
                console.log('Volume warning computed style:', window.getComputedStyle(warning));
                console.log('Volume warning dimensions:', {
                    width: warning.offsetWidth,
                    height: warning.offsetHeight,
                    clientWidth: warning.clientWidth,
                    clientHeight: warning.clientHeight
                });
            });
        }
        
        // Try to find video player position
        if (!findVideoPlayerPosition()) {
            // Retry after a short delay
            setTimeout(() => {
                if (!findVideoPlayerPosition()) {
                    // Fallback: center of screen
                    centerX = window.innerWidth / 2;
                    topY = 100;
                }
                positionWarning();
            }, 50);
        } else {
            positionWarning();
        }
        
        // Auto-remove after 2.5 seconds
        setTimeout(() => {
            if (warning.parentNode) {
                warning.style.opacity = '0';
                warning.style.transition = 'opacity 0.3s ease-out';
                setTimeout(() => {
                    if (warning.parentNode) {
                        warning.remove();
                    }
                }, 300);
            }
        }, 2500);
    }

    /**
     * EVENT: seeking
     * Purpose: Intercept seek attempts BEFORE they complete
     * Action: Block forward seeking, allow backward seeking only
     * 
     * LOGIC:
     * 1. Detect if user is trying to seek forward (targetTime > maxWatchedTime)
     * 2. If forward seek detected: prevent it immediately and revert to maxWatchedTime
     * 3. If backward seek: allow it completely (don't interfere)
     * 4. maxWatchedTime is the furthest point reached through normal playback
     */
    player.on('seeking', function() {
        const targetTime = player.currentTime();
        const seekThreshold = 0.3; // Allow 0.3 seconds tolerance for normal playback variance
        
        // Get the actual current time before this seek
        // Use timeBeforeSeek if it was set, otherwise use max of lastPosition and maxWatchedTime
        const currentTimeBeforeSeek = timeBeforeSeek > 0 ? timeBeforeSeek : Math.max(lastPosition, maxWatchedTime);
        
        console.log('Seeking event - target:', targetTime, 'currentTimeBeforeSeek:', currentTimeBeforeSeek, 'timeBeforeSeek:', timeBeforeSeek, 'lastPosition:', lastPosition, 'maxWatchedTime:', maxWatchedTime);
        
        // If isSeekingForward is explicitly false, it's a backward seek - allow it
        if (isSeekingForward === false) {
            console.log('Backward seek detected (flag) - allowing:', targetTime);
            // Allow backward seeking - don't interfere
            timeBeforeSeek = 0; // Reset
            return;
        }
        
        // Check if this is a backward seek (target time is less than current position)
        if (targetTime < currentTimeBeforeSeek - seekThreshold) {
            console.log('Backward seek detected (time < currentTimeBeforeSeek) - allowing:', targetTime);
            isSeekingForward = false;
            timeBeforeSeek = 0; // Reset
            // Allow backward seeking - don't interfere
            return;
        }
        
        // BLOCK ALL FORWARD SEEKING - even within watched content
        // Only allow backward seeking or normal forward playback
        if (targetTime > currentTimeBeforeSeek + seekThreshold) {
            console.log('ANTI-CHEAT: Forward seeking blocked at', targetTime, '- reverting to', currentTimeBeforeSeek);
            isSeekingForward = true;
            
            // Immediately revert to the position before seek
            player.currentTime(currentTimeBeforeSeek);
            
            // Show warning message immediately
            console.log('Showing forward seek warning');
            showSeekWarning('Forward seeking is completely disabled. You can only rewind to review content.');
            
            // Reset timeBeforeSeek
            timeBeforeSeek = 0;
            
            // Prevent the seek from completing
            return false;
        }
        
        // Small movement within threshold (likely normal playback) - allow it
        isSeekingForward = false;
        timeBeforeSeek = 0; // Reset after processing
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
        
        // Start initial progress update immediately
        updateWatchProgress();
    });

    /**
     * EVENT: pause
     * Purpose: Stop tracking watch time when video is paused
     * Action: Calculate watched segment and accumulate to total, send update to backend
     */
    player.on('pause', function() {
        console.log('Video paused - finalizing current session');
        
        // Finalize current session
        if (isPlaying && sessionStartTimestamp !== null) {
            const currentPosition = player.currentTime();
            
            // Only count forward progress (don't count if user seeked backward)
            if (currentPosition > sessionStartPosition) {
                addWatchedSegment(sessionStartPosition, currentPosition);
            }
            
            // Recalculate watched seconds
            watchedSeconds = calculateWatchedSeconds();
            
            // Update max watched time
            if (currentPosition > maxWatchedTime) {
                maxWatchedTime = currentPosition;
            }
            
            lastPosition = currentPosition;
            
            // Update UI immediately
            const duration = player.duration() || 0;
            if (duration > 0) {
                const pausedPercentage = Math.min(100, (watchedSeconds / duration) * 100);
                updateProgressBarUI(pausedPercentage, pausedPercentage >= 80);
            }
        }
        
        isPlaying = false;
        sessionStartTimestamp = null;
        sessionStartPosition = 0;
        
        // Send update to backend
        updateWatchProgress();
    });

    /**
     * Unlock game button immediately when 80% is reached
     */
    function unlockGameButton() {
        const gameButtonContainer = document.getElementById('game-button-container');
        const lockedGameButton = document.getElementById('locked-game-button');
        const gameUnlockMessage = document.getElementById('game-unlock-message');
        
        if (!gameButtonContainer) return;
        
        // Check if game is already unlocked
        const existingUnlockedButton = gameButtonContainer.querySelector('a[href*="games"]');
        if (existingUnlockedButton) {
            // Update completion badge if game is completed (check from server-side variable)
            @if(isset($isGameCompleted) && $isGameCompleted)
                const badge = existingUnlockedButton.querySelector('.bg-green-600');
                if (badge && badge.textContent.trim() !== '✓ Completed') {
                    badge.textContent = '✓ Completed';
                }
            @endif
            return; // Already unlocked
        }
        
        // Replace locked button with unlocked button
        if (lockedGameButton) {
            const lessonId = {{ $lesson->lesson_id }};
            const gameUrl = '{{ route("student.games", ["lesson_id" => $lesson->lesson_id]) }}';
            
            // Create unlocked button
            const unlockedButton = document.createElement('a');
            unlockedButton.href = gameUrl;
            unlockedButton.className = 'inline-flex items-center gap-2 bg-gradient-to-r from-green-400 via-green-300 to-green-200 hover:from-green-500 hover:to-green-300 text-white font-bold py-3 px-8 rounded-full shadow-lg transition-all text-base transform hover:scale-105';
            // Check if game is completed from server-side variable
            const isGameCompleted = @json(isset($isGameCompleted) && $isGameCompleted);
            unlockedButton.innerHTML = `
                <span class="text-xl">🎮</span>
                <span>Play Game</span>
                <span class="ml-2 bg-green-600 px-2 py-1 rounded-full text-xs">${isGameCompleted ? '✓ Completed' : 'Unlocked'}</span>
            `;
            
            // Add animation
            unlockedButton.style.opacity = '0';
            unlockedButton.style.transform = 'scale(0.9)';
            
            // Replace locked button with unlocked button
            lockedGameButton.replaceWith(unlockedButton);
            
            // Animate in
            setTimeout(() => {
                unlockedButton.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';
                unlockedButton.style.opacity = '1';
                unlockedButton.style.transform = 'scale(1)';
            }, 10);
            
            // Remove unlock message
            if (gameUnlockMessage) {
                gameUnlockMessage.style.transition = 'opacity 0.3s ease-out';
                gameUnlockMessage.style.opacity = '0';
                setTimeout(() => {
                    gameUnlockMessage.remove();
                }, 300);
            }
            
            console.log('Game button unlocked immediately at 80%');
        }
    }

    /**
     * Update progress bar in real-time during playback
     */
    function updateProgressBarRealTime() {
        const duration = player.duration();
        if (duration && watchedSeconds > 0) {
            // Cap percentage at 100%
            const cappedWatchedSeconds = Math.min(watchedSeconds, duration);
            const currentPercentage = Math.min(100, (cappedWatchedSeconds / duration) * 100);
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
        const currentTime = player.currentTime();
        
        // Reset timeBeforeSeek during normal playback (not seeking)
        // This ensures it's only set when user actually interacts with progress bar
        if (isPlaying && !isSeekingForward && timeBeforeSeek > 0) {
            // If we're playing forward normally, reset timeBeforeSeek
            // Only keep it if we're very close to when it was set (within 1 second)
            if (Math.abs(currentTime - timeBeforeSeek) > 1) {
                timeBeforeSeek = 0;
            }
        }
        
        // ANTI-CHEAT: Ensure video cannot play beyond max watched time
        // This catches any forward seeking that somehow got past the seeking event
        // Only check this if video is playing forward (not during backward seeking)
        if (isPlaying && !isSeekingForward && currentTime > maxWatchedTime + 0.5) {
            console.log('ANTI-CHEAT: Video ahead of max watched time - reverting');
            player.currentTime(maxWatchedTime);
            return;
        }
        
        // Only track watch time if actively playing AND tab is visible
        if (isPlaying && isTabVisible && sessionStartTimestamp !== null) {
            // Only count forward progress (real watching, not backward seeking)
            if (currentTime > lastPosition) {
                // We're making forward progress - this is real watch time
                
                // Update max watched time (this is the furthest point reached)
                if (currentTime > maxWatchedTime) {
                    maxWatchedTime = currentTime;
                }
                // Update last allowed position to current position
                lastAllowedPosition = currentTime;
                
                lastPosition = currentTime;
                
                // Calculate watched time based on max watched time for real-time display
                // This gives immediate feedback without waiting for segment finalization
                // maxWatchedTime represents the furthest point reached (most accurate)
                const duration = player.duration() || 0;
                if (duration > 0) {
                    // Use maxWatchedTime for real-time progress calculation (not watchedSeconds)
                    const realTimeWatched = Math.min(maxWatchedTime, duration);
                    const realTimePercentage = (realTimeWatched / duration) * 100;
                    
                    // Update progress bar in real-time with current position
                    const progressBar = document.getElementById('video-progress-bar');
                    const progressPercentage = document.getElementById('video-progress-percentage');
                    if (progressBar && progressPercentage) {
                        progressBar.style.width = Math.min(100, realTimePercentage) + '%';
                        progressPercentage.textContent = parseFloat(Math.min(100, realTimePercentage)).toFixed(1) + '%';
                    }
                }
                
                // Trigger progress update more frequently when approaching 80%
                if (duration > 0) {
                    const realTimePercentage = (maxWatchedTime / duration) * 100;
                    
                    // Unlock game immediately when 80% is reached in real-time
                    if (realTimePercentage >= 80) {
                        unlockGameButton();
                        updateProgressBarUI(realTimePercentage, true);
                    }
                    
                    // Update more frequently when close to 80%
                    if (realTimePercentage >= 70 && realTimePercentage < 85 && isPlaying) {
                        // Trigger update every 2 seconds when close to completion
                        const now = Date.now();
                        if (now - lastUpdateTime > 2000) {
                            updateWatchProgress();
                            lastUpdateTime = now;
                        }
                    }
                }
            } else if (currentTime < lastPosition) {
                // User seeked backward - just update lastPosition, don't interfere
                // Allow backward seeking to any point before maxWatchedTime (including 10+ seconds back)
                lastPosition = currentTime;
                
                // Update progress bar even when seeking backward
                updateProgressBarRealTime();
            }
        } else if (!isPlaying) {
            // When paused, allow backward seeking - just update position
            if (currentTime < lastPosition) {
                lastPosition = currentTime;
            }
        }
    });

    /**
     * EVENT: seeked
     * Purpose: Detect when seek operation completes
     * Action: Finalize previous segment and restart tracking from new position
     * 
     * ANTI-CHEAT LOGIC:
     * - Double-check that seek didn't go beyond maxWatchedTime
     * - If forward seek somehow completed, revert it immediately
     * - Allow backward seeks completely - don't interfere
     */
    player.on('seeked', function() {
        const currentTime = player.currentTime();
        const referenceTime = Math.max(timeBeforeSeek, maxWatchedTime, lastPosition);
        
        console.log('Seeked event - currentTime:', currentTime, 'referenceTime:', referenceTime, 'lastPosition:', lastPosition, 'maxWatchedTime:', maxWatchedTime);
        
        // ANTI-CHEAT: Final safety check - block ALL forward seeking
        // Check if we seeked forward (beyond reference position)
        if (currentTime > referenceTime + 0.3) {
            console.log('ANTI-CHEAT: Forward seek detected in seeked event - reverting to', referenceTime);
            player.currentTime(referenceTime);
            isSeekingForward = false;
            showSeekWarning('Forward seeking is completely disabled.');
            return;
        }
        
        // Reset seeking flag
        isSeekingForward = false;
        
        // Finalize previous watching session if we were tracking
        if (isPlaying && sessionStartTimestamp !== null && lastPosition > sessionStartPosition) {
            addWatchedSegment(sessionStartPosition, lastPosition);
        }
        
        // Update positions
        lastPosition = currentTime;
        
        // If backward seek, don't update maxWatchedTime - allow free backward seeking
        // Only update maxWatchedTime if we're making forward progress through normal playback
        // (not through seeking)
        if (currentTime > maxWatchedTime && isPlaying) {
            maxWatchedTime = currentTime;
            lastAllowedPosition = currentTime;
        }
        
        console.log('Seek completed to:', currentTime, 'lastPosition:', lastPosition, 'maxWatchedTime:', maxWatchedTime);
        
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
        console.log('Video ended - finalizing progress immediately');
        
        const duration = player.duration() || 0;
        
        // Finalize current session
        if (sessionStartTimestamp !== null && isPlaying) {
            const endPosition = duration; // Use full duration, not currentTime (which might be slightly less)
            if (endPosition > sessionStartPosition) {
                addWatchedSegment(sessionStartPosition, endPosition);
            }
        }
        
        // Ensure we have a segment covering the full video if video was watched
        // Add a segment from 0 to duration to ensure 100% completion
        if (duration > 0) {
            // Check if we already have a segment covering the full duration
            let hasFullSegment = false;
            for (const segment of watchedSegments) {
                if (segment.start <= 0 && segment.end >= duration * 0.8) {
                    hasFullSegment = true;
                    break;
                }
            }
            
            // If we don't have a full segment, add one from 0 to duration
            // This ensures 100% completion when video ends
            if (!hasFullSegment && maxWatchedTime >= duration * 0.8) {
                // Video was watched to at least 80%, mark as fully watched
                watchedSegments = [{ start: 0, end: duration }];
                watchedSeconds = duration;
            }
        }
        
        // Update max watched time to video duration
        if (duration && duration > maxWatchedTime) {
            maxWatchedTime = duration;
        }
        
        lastPosition = duration;
        isPlaying = false;
        sessionStartTimestamp = null;
        
        // Recalculate watched seconds to ensure accuracy
        watchedSeconds = calculateWatchedSeconds();
        // Cap at duration
        if (duration > 0) {
            watchedSeconds = Math.min(watchedSeconds, duration);
        }
        
        // Calculate final percentage
        const finalPercentage = duration > 0 ? Math.min(100, (watchedSeconds / duration) * 100) : 0;
        
        // Update UI immediately
        updateProgressBarUI(finalPercentage, true);
        
        // Force immediate update to backend (bypass throttle)
        clearTimeout(updateTimer);
        
        const data = {
            lesson_id: lessonId,
            watched_seconds: Math.floor(watchedSeconds),
            watched_percentage: parseFloat(finalPercentage.toFixed(2)),
            current_position: parseFloat(duration.toFixed(2)),
            max_watched_time: parseFloat(duration.toFixed(2)),
            is_completed: true
        };

        // Send immediate update to backend
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
                console.log('Video ended - progress finalized:', responseData.data);
                
                // Update UI with server response
                updateProgressBarUI(responseData.data.watched_percentage, true);
                
                // Reload page to show completion status
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                console.error('Error finalizing progress:', responseData.message);
            }
        })
        .catch(error => {
            console.error('Error finalizing watch progress:', error);
        });
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
     * Uses adaptive throttling - more frequent updates when approaching 80%
     */
    let updateTimer = null;
    let lastUpdatePercentage = 0;
    function updateWatchProgress(isCompleted = false) {
        clearTimeout(updateTimer);
        
        // Calculate current percentage to determine update frequency
        const duration = player.duration() || 0;
        const currentWatchedSeconds = Math.min(Math.floor(watchedSeconds), Math.floor(duration));
        const currentPercentage = duration > 0 ? Math.min(100, (currentWatchedSeconds / duration) * 100) : 0;
        
        // Adaptive update interval:
        // - Every 2 seconds when between 75-85% (very close to completion)
        // - Every 5 seconds when between 70-90% (approaching completion)
        // - Every 10 seconds otherwise
        let updateInterval = 10000; // Default 10 seconds
        if (currentPercentage >= 75 && currentPercentage < 85) {
            updateInterval = 2000; // Every 2 seconds when very close
        } else if (currentPercentage >= 70 && currentPercentage < 90) {
            updateInterval = 5000; // Every 5 seconds when approaching
        }
        
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
            
            // Recalculate after finalizing session
            // Use maxWatchedTime for percentage calculation (most accurate - represents furthest point reached)
            const finalMaxWatchedTime = Math.min(maxWatchedTime, duration);
            const watchedPercentage = duration > 0 ? Math.min(100, (finalMaxWatchedTime / duration) * 100) : 0;
            const isEightyPercentWatched = watchedPercentage >= 80;
            
            // Also calculate watched_seconds from segments for reference
            const finalWatchedSeconds = Math.min(Math.floor(watchedSeconds), Math.floor(duration));
            
            // Update UI immediately with current progress (before API call)
            updateProgressBarUI(watchedPercentage, isEightyPercentWatched);
            
            const data = {
                lesson_id: lessonId,
                watched_seconds: finalWatchedSeconds,
                watched_percentage: parseFloat(watchedPercentage.toFixed(2)),
                current_position: parseFloat(lastPosition.toFixed(2)),
                max_watched_time: parseFloat(finalMaxWatchedTime.toFixed(2)),
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
                    
                    // Update UI with server response (more accurate)
                    updateProgressBarUI(responseData.data.watched_percentage, responseData.data.video_completed);
                    
                    // Check if we just crossed 80% threshold
                    const justReached80 = watchedPercentage >= 80 && lastUpdatePercentage < 80;
                    lastUpdatePercentage = watchedPercentage;
                    
                    // If lesson is completed (80% reached)
                    const isLessonCompleted = responseData.data.is_completed || responseData.data.status === 'completed';
                    const isEightyPercent = responseData.data.watched_percentage >= 80;
                    
                    // Unlock game immediately when 80% is reached
                    if (justReached80 || isEightyPercent) {
                        console.log('Just reached 80% - unlocking game immediately');
                        unlockGameButton();
                        updateProgressBarUI(responseData.data.watched_percentage, true);
                    }
                    
                    // Reload page after a delay to show completion badge (but game is already unlocked)
                    if (isLessonCompleted && isEightyPercent) {
                        console.log('Lesson completed at 80% - will reload to show completion badge');
                        // Small delay to ensure UI updates are visible before reload
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    }
                } else {
                    console.error('Error updating progress:', responseData.message);
                }
            })
            .catch(error => {
                console.error('Error updating watch progress:', error);
            });
        }, updateInterval);
    }

    /**
     * Update progress bar UI dynamically
     */
    function updateProgressBarUI(watchedPercentage, videoCompleted) {
        const progressBar = document.getElementById('video-progress-bar');
        const progressPercentage = document.getElementById('video-progress-percentage');
        const progressMessage = document.getElementById('video-progress-message');
        const progressContainer = document.getElementById('video-progress-container');
        
        // Ensure progress container is visible
        if (progressContainer) {
            progressContainer.style.display = 'block';
        }
        
        if (progressBar && progressPercentage) {
            // Update progress bar width
            progressBar.style.width = watchedPercentage + '%';
            
            // Update percentage text
            progressPercentage.textContent = parseFloat(watchedPercentage).toFixed(1) + '%';
        }
        
        // Update progress message
        if (progressMessage) {
            if (videoCompleted || watchedPercentage >= 80) {
                progressMessage.innerHTML = '<p class="text-xs text-green-600 mt-1 font-medium">✓ Lesson completed! Game unlocked.</p>';
            } else {
                const remaining = 80 - watchedPercentage;
                progressMessage.innerHTML = '<p class="text-xs text-gray-500 mt-1">Watch ' + remaining.toFixed(1) + '% more to complete lesson and unlock game</p>';
            }
        }
        
        // Unlock game button immediately when 80% is reached
        if (videoCompleted || watchedPercentage >= 80) {
            unlockGameButton();
        }
    }

    /**
     * Load existing progress immediately on page load
     * This ensures progress bar is visible and tracking starts right away
     */
    function loadVideoProgress() {
        fetch('/api/lessons/' + lessonId + '/video/progress', {
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data && data.success && data.data) {
                const progressData = data.data;
                
                // Update UI immediately with saved progress (before video duration is available)
                // Use the accurate percentage from the API response
                if (progressData.watched_percentage !== undefined) {
                    const savedPercentage = parseFloat(progressData.watched_percentage) || 0;
                    // Use video_completed from API, or calculate from percentage
                    const videoCompleted = progressData.video_completed || 
                                         progressData.status === 'completed' || 
                                         savedPercentage >= 80;
                    
                    // Only update if API data is more recent/accurate than server-side initial values
                    // This prevents resetting progress that was already displayed
                    @if(isset($progress) && $progress && isset($accuratePercentageForView))
                        const serverPercentage = {{ $accuratePercentageForView }};
                        // Use the maximum percentage to ensure progress never decreases
                        const finalPercentage = Math.max(savedPercentage, serverPercentage);
                        updateProgressBarUI(finalPercentage, videoCompleted);
                    @else
                        updateProgressBarUI(savedPercentage, videoCompleted);
                    @endif
                    
                    // Unlock game button immediately if video is completed
                    if (videoCompleted || savedPercentage >= 80) {
                        unlockGameButton();
                    }
                    
                    console.log('Progress loaded from API:', {
                        percentage: savedPercentage,
                        videoCompleted: videoCompleted,
                        status: progressData.status,
                        maxWatchedTime: progressData.max_watched_time
                    });
                }
                
                // Wait for video duration to be available for accurate tracking
                const checkDuration = setInterval(() => {
                    const duration = player.duration();
                    if (duration && duration > 0) {
                        clearInterval(checkDuration);
                        
                        // Restore max watched time (prevents forward seeking)
                        if (progressData.max_watched_time !== undefined) {
                            maxWatchedTime = Math.min(parseFloat(progressData.max_watched_time) || 0, duration);
                        }
                        
                        // Restore last allowed position (key anti-cheat variable)
                        if (progressData.max_watched_time !== undefined) {
                            lastAllowedPosition = Math.min(parseFloat(progressData.max_watched_time) || 0, duration);
                        } else if (progressData.last_position !== undefined) {
                            // Fallback to last_position if max_watched_time not available
                            lastAllowedPosition = Math.min(parseFloat(progressData.last_position) || 0, duration);
                        }
                        
                        // Restore watched seconds - CAP at video duration to prevent >100%
                        const savedWatchedSeconds = parseFloat(progressData.watched_seconds) || 0;
                        watchedSeconds = Math.min(savedWatchedSeconds, duration);
                        
                        // Reconstruct watched segments from saved progress
                        // If max_watched_time exists, create segment from 0 to max_watched_time
                        // This represents all the content that has been watched
                        if (maxWatchedTime > 0) {
                            watchedSegments = [{ start: 0, end: Math.min(maxWatchedTime, duration) }];
                            // Recalculate to ensure consistency
                            watchedSeconds = calculateWatchedSeconds();
                        }
                        
                        // Restore last position and resume from there
                        // Ensure we don't resume beyond lastAllowedPosition
                        if (progressData.last_position !== undefined && progressData.last_position > 0) {
                            const resumePosition = Math.min(
                                parseFloat(progressData.last_position) || 0,
                                lastAllowedPosition,
                                duration
                            );
                            player.currentTime(resumePosition);
                            lastPosition = resumePosition;
                        }
                        
                        // Update UI with accurate loaded progress
                        // Use max_watched_time for percentage (most accurate), not watchedSeconds
                        const loadedMaxWatchedTime = progressData.max_watched_time !== undefined 
                            ? Math.min(parseFloat(progressData.max_watched_time) || 0, duration) 
                            : maxWatchedTime;
                        const loadedPercentage = duration > 0 
                            ? Math.min(100, (loadedMaxWatchedTime / duration) * 100) 
                            : (progressData.watched_percentage || 0);
                        updateProgressBarUI(loadedPercentage, progressData.video_completed || false);
                        
                        // Also unlock game button if video is completed
                        if (progressData.video_completed || loadedPercentage >= 80) {
                            unlockGameButton();
                        }
                        
                        console.log('Progress loaded and tracking initialized:', {
                            maxWatchedTime: maxWatchedTime,
                            lastAllowedPosition: lastAllowedPosition,
                            watchedSeconds: watchedSeconds,
                            watchedPercentage: loadedPercentage,
                            lastPosition: lastPosition,
                            videoCompleted: progressData.video_completed
                        });
                    }
                }, 100);
                
                // Timeout after 5 seconds if duration never loads
                setTimeout(() => {
                    clearInterval(checkDuration);
                }, 5000);
            } else {
                // No progress found - initialize with 0%
                updateProgressBarUI(0, false);
                console.log('No existing progress found - starting fresh');
            }
        })
        .catch(error => {
            console.error('Error loading video progress:', error);
            // Initialize with 0% on error
            updateProgressBarUI(0, false);
        });
    }
    
    // CRITICAL: Initialize with server-side values first to prevent reset
    // This ensures the UI shows correct state immediately, even before API call
    @if(isset($progress) && $progress && isset($accuratePercentageForView))
        const serverSideProgress = {
            watched_percentage: {{ $accuratePercentageForView }},
            video_completed: {{ ($progress->video_completed ?? false) || ($progress->status === 'completed') || ($accuratePercentageForView >= 80) ? 'true' : 'false' }},
            status: '{{ $progress->status ?? 'not_started' }}',
            max_watched_time: {{ $progress->max_watched_time ?? 0 }},
            watched_seconds: {{ $progress->watched_seconds ?? 0 }},
            last_position: {{ $progress->last_position ?? 0 }}
        };
        
        // Update UI immediately with server-side values
        updateProgressBarUI(serverSideProgress.watched_percentage, serverSideProgress.video_completed);
        
        // Unlock game button if video is completed according to server
        if (serverSideProgress.video_completed || serverSideProgress.watched_percentage >= 80) {
            unlockGameButton();
        }
        
        console.log('Initialized with server-side progress:', serverSideProgress);
    @endif
    
    // Load progress immediately when player is ready
    player.ready(function() {
        // Load progress right away (will update if different from server-side)
        loadVideoProgress();
        
        // Also start tracking immediately
        console.log('Video player ready - tracking initialized');
    });
    
    // Also try to load progress immediately on DOM ready (before player is ready)
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(loadVideoProgress, 500);
        });
    } else {
        setTimeout(loadVideoProgress, 500);
    }

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
