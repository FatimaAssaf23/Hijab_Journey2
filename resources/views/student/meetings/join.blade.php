<!DOCTYPE html>
<html>
<head>
    <title>{{ $enrollment->meeting->title }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { margin: 0; padding: 0; overflow: hidden; }
        #meet-frame { width: 100%; height: 100vh; border: none; }
        
        .attendance-modal {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.8); display: none; justify-content: center;
            align-items: center; z-index: 10000;
        }
        .attendance-modal.show { display: flex; }
        .modal-content {
            background: white; padding: 40px; border-radius: 12px;
            text-align: center; box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            max-width: 400px; width: 90%;
        }
        .modal-content h2 {
            margin-top: 0; color: #333; font-size: 24px;
        }
        .modal-content p {
            color: #666; font-size: 16px; margin-bottom: 20px;
        }
        .btn-confirm {
            background: #4CAF50; color: white; border: none;
            padding: 15px 40px; font-size: 16px; border-radius: 6px;
            cursor: pointer; font-weight: bold;
            transition: background 0.3s;
        }
        .btn-confirm:hover { background: #45a049; }
    </style>
</head>
<body>
    <iframe id="meet-frame" src="{{ $enrollment->meeting->google_meet_link }}" allow="camera; microphone"></iframe>
    
    <div id="attendance-modal" class="attendance-modal">
        <div class="modal-content">
            <h2>Attendance Confirmation</h2>
            <p>Are you still here?</p>
            <button onclick="confirmAttendance()" class="btn-confirm">Yes, I'm Here</button>
        </div>
    </div>

    <script>
        const enrollmentId = {{ $enrollment->id }};
        let currentConfirmationId = null;
        let checkInterval;

        // Record join time when page loads
        window.addEventListener('load', function() {
            recordJoinTime();
            startAttendanceChecking();
        });

        function recordJoinTime() {
            fetch(`/student/meetings/{{ $enrollment->meeting->id }}/record-join`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ enrollment_id: enrollmentId })
            }).then(r => r.json()).then(d => console.log('Join recorded')).catch(err => console.error('Error recording join:', err));
        }

        function startAttendanceChecking() {
            checkInterval = setInterval(checkForPrompt, 30000); // Check every 30 seconds
            checkForPrompt(); // Check immediately
        }

        function checkForPrompt() {
            fetch(`/api/enrollments/${enrollmentId}/check-prompt`)
                .then(r => r.json())
                .then(data => {
                    if (data.prompt_needed && data.confirmation_id) {
                        currentConfirmationId = data.confirmation_id;
                        showModal();
                        setTimeout(autoMissConfirmation, 60000); // Auto-mark missed after 60s
                    }
                })
                .catch(err => console.error('Error checking prompt:', err));
        }

        function showModal() {
            document.getElementById('attendance-modal').classList.add('show');
        }

        function hideModal() {
            document.getElementById('attendance-modal').classList.remove('show');
        }

        function confirmAttendance() {
            if (!currentConfirmationId) return;
            
            fetch(`/student/confirmations/${currentConfirmationId}/confirm`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ is_confirmed: true })
            }).then(() => {
                hideModal();
                currentConfirmationId = null;
            }).catch(err => {
                console.error('Error confirming attendance:', err);
                hideModal();
            });
        }

        function autoMissConfirmation() {
            if (document.getElementById('attendance-modal').classList.contains('show')) {
                fetch(`/student/confirmations/${currentConfirmationId}/confirm`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ is_confirmed: false })
                }).then(() => {
                    hideModal();
                    currentConfirmationId = null;
                }).catch(err => console.error('Error marking missed:', err));
            }
        }

        window.addEventListener('beforeunload', function() {
            clearInterval(checkInterval);
        });
    </script>
</body>
</html>
