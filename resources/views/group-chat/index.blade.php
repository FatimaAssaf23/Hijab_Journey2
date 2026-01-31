@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-pink-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-8">
        <!-- Header Section -->
        <div class="mb-6 animate-fade-in">
            <div class="relative w-full max-w-7xl mx-auto bg-gradient-to-br from-pink-200/80 via-rose-100/70 to-cyan-200/80 rounded-2xl shadow-xl overflow-hidden transform transition-all duration-500 hover:shadow-2xl border border-pink-200/50 backdrop-blur-sm">
                <!-- Pattern Overlay -->
                <div class="absolute inset-0 opacity-5">
                    <div class="absolute top-0 left-0 w-full h-full" style="background-image: radial-gradient(circle, rgba(255,255,255,0.4) 1px, transparent 1px); background-size: 25px 25px;"></div>
                </div>
                
                <div class="relative flex flex-col lg:flex-row items-center justify-between p-6 lg:p-8">
                    <div class="flex-1 text-center lg:text-left mb-5 lg:mb-0 z-10">
                        <div class="inline-flex items-center gap-2 bg-white/40 backdrop-blur-md px-3 py-1.5 rounded-full mb-3 border border-pink-300/30 shadow-md animate-slide-in-left">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <span class="text-pink-700 font-semibold text-xs tracking-wide">GROUP CHAT</span>
                        </div>
                        <h1 class="text-2xl lg:text-3xl font-black text-gray-800 mb-2 tracking-tight leading-tight animate-slide-in-left" style="animation-delay: 0.1s;">
                            <span class="bg-gradient-to-r from-pink-500 to-cyan-500 bg-clip-text text-transparent">{{ $class->class_name }}</span> 💬
                        </h1>
                        <p class="text-sm lg:text-base text-gray-700 font-medium italic animate-slide-in-left" style="animation-delay: 0.2s;">
                            Teacher: {{ $teacher->first_name }} {{ $teacher->last_name }} • {{ $students->count() }} {{ $students->count() === 1 ? 'student' : 'students' }}
                        </p>
                    </div>
                    
                    <!-- Right: Action Buttons -->
                    <div class="relative flex-shrink-0 mt-5 lg:mt-0 animate-slide-in-right flex gap-3">
                        @if(Auth::user()->role === 'teacher' && $teacherClasses && $teacherClasses->count() > 1)
                            <!-- Class Selector for Teachers -->
                            <select id="class-selector" onchange="switchClass(this.value)" 
                                    class="px-4 py-2.5 bg-white/90 backdrop-blur-md border-2 border-pink-300/50 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-transparent shadow-md hover:shadow-lg transition-all font-medium text-gray-700">
                                @foreach($teacherClasses as $tc)
                                    <option value="{{ $tc->class_id }}" {{ $tc->class_id == $class->class_id ? 'selected' : '' }}>
                                        {{ $tc->class_name }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                        <a href="{{ Auth::user()->role === 'teacher' ? route('teacher.dashboard') : route('student.dashboard') }}" 
                           class="px-5 py-2.5 bg-white/90 backdrop-blur-md border-2 border-pink-300/50 rounded-xl hover:bg-white hover:shadow-lg transition-all font-semibold text-gray-700 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chat Container -->
        <div class="animate-fade-in-up" style="animation-delay: 0.2s;">
            <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-xl border border-pink-200/40 overflow-hidden transform transition-all duration-300 hover:shadow-2xl flex flex-col" style="height: 75vh;">
                <!-- Messages Area -->
                <div id="messages-container" class="flex-1 overflow-y-auto p-6 space-y-4 chat-messages-bg custom-scrollbar">
                    @forelse($messages as $message)
                        <div class="message-item animate-fade-in-up" data-message-id="{{ $message->message_id }}">
                            @include('group-chat.message', ['message' => $message])
                        </div>
                    @empty
                        <div class="text-center text-gray-500 py-16">
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-pink-200/50 to-cyan-200/50 rounded-full mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <p class="text-lg font-semibold text-gray-700 mb-2">No messages yet</p>
                            <p class="text-sm text-gray-500">Start the conversation! 👋</p>
                        </div>
                    @endforelse
                </div>

                <!-- Input Area -->
                <div class="border-t border-pink-200/50 p-4 bg-pink-50/80 backdrop-blur-sm">
                    <form id="message-form" class="flex flex-col gap-3">
                        @csrf
                        <input type="hidden" name="class_id" value="{{ $class->class_id }}">
                        <input type="hidden" name="reply_to_message_id" id="reply-to-message-id" value="">
                        
                        <!-- Reply Preview -->
                        <div id="reply-preview" class="hidden w-full p-3 bg-pink-100/80 border-l-4 border-pink-400 rounded-xl shadow-sm">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-pink-700 font-semibold mb-1">Replying to:</p>
                                    <p class="text-sm text-gray-700 font-medium" id="reply-preview-content"></p>
                                </div>
                                <button type="button" onclick="cancelReply()" class="text-pink-600 hover:text-pink-800 hover:scale-110 transition-transform">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <div class="flex gap-3">
                            <input 
                                type="text" 
                                id="message-input" 
                                name="content" 
                                placeholder="Type your message..." 
                                class="flex-1 px-5 py-3 bg-white/90 backdrop-blur-md border-2 border-pink-300/50 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-transparent shadow-md hover:shadow-lg transition-all font-medium text-gray-700 placeholder-gray-400"
                                maxlength="2000"
                                required
                            >
                            <button 
                                type="submit" 
                                class="px-6 py-3 bg-pink-500 text-white rounded-xl hover:bg-pink-600 transition-all font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center gap-2"
                            >
                                <span>Send</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Emoji Picker Modal -->
<div id="emoji-picker-modal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center">
    <div class="bg-white/95 backdrop-blur-md rounded-2xl p-6 max-w-md w-full mx-4 shadow-2xl border border-pink-200/50 transform animate-fade-in-up">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <span class="text-2xl">😊</span>
                <span>Choose an Emoji</span>
            </h3>
            <button onclick="closeEmojiPicker()" class="text-gray-500 hover:text-gray-700 text-2xl hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="grid grid-cols-6 gap-3" id="emoji-grid">
            <!-- Common emojis -->
            <button onclick="selectEmoji('👍')" class="text-3xl hover:scale-125 transition-transform p-2 rounded-lg hover:bg-pink-100">👍</button>
            <button onclick="selectEmoji('❤️')" class="text-3xl hover:scale-125 transition-transform p-2 rounded-lg hover:bg-pink-100">❤️</button>
            <button onclick="selectEmoji('😂')" class="text-3xl hover:scale-125 transition-transform p-2 rounded-lg hover:bg-pink-100">😂</button>
            <button onclick="selectEmoji('😊')" class="text-3xl hover:scale-125 transition-transform p-2 rounded-lg hover:bg-pink-100">😊</button>
            <button onclick="selectEmoji('🎉')" class="text-3xl hover:scale-125 transition-transform p-2 rounded-lg hover:bg-pink-100">🎉</button>
            <button onclick="selectEmoji('🙏')" class="text-3xl hover:scale-125 transition-transform p-2 rounded-lg hover:bg-pink-100">🙏</button>
            <button onclick="selectEmoji('🔥')" class="text-3xl hover:scale-125 transition-transform p-2 rounded-lg hover:bg-pink-100">🔥</button>
            <button onclick="selectEmoji('✨')" class="text-3xl hover:scale-125 transition-transform p-2 rounded-lg hover:bg-pink-100">✨</button>
            <button onclick="selectEmoji('💯')" class="text-3xl hover:scale-125 transition-transform p-2 rounded-lg hover:bg-pink-100">💯</button>
            <button onclick="selectEmoji('👏')" class="text-3xl hover:scale-125 transition-transform p-2 rounded-lg hover:bg-pink-100">👏</button>
            <button onclick="selectEmoji('😍')" class="text-3xl hover:scale-125 transition-transform p-2 rounded-lg hover:bg-pink-100">😍</button>
            <button onclick="selectEmoji('🤔')" class="text-3xl hover:scale-125 transition-transform p-2 rounded-lg hover:bg-pink-100">🤔</button>
        </div>
    </div>
</div>

<script>
let lastMessageId = {{ $messages->last() ? $messages->last()->message_id : 0 }};
let currentReplyToId = null;
let currentEmojiMessageId = null;
let isSubmitting = false; // Flag to prevent duplicate submissions
let sentMessageIds = new Set(); // Track sent message IDs to prevent duplicates

// Auto-scroll to bottom
function scrollToBottom() {
    const container = document.getElementById('messages-container');
    container.scrollTop = container.scrollHeight;
}

// Load new messages
function loadNewMessages() {
    fetch(`{{ route('group-chat.messages', $class->class_id) }}?last_message_id=${lastMessageId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.messages.length > 0) {
                data.messages.forEach(message => {
                    // Skip messages we just sent (to prevent duplicates)
                    if (!sentMessageIds.has(message.message_id)) {
                        addMessageToDOM(message);
                        lastMessageId = Math.max(lastMessageId, message.message_id);
                    }
                });
                scrollToBottom();
            }
        })
        .catch(error => console.error('Error loading messages:', error));
}

// Add message to DOM
function addMessageToDOM(message) {
    // Check if message already exists in DOM to prevent duplicates
    const existingMessage = document.querySelector(`[data-message-id="${message.message_id}"]`);
    if (existingMessage) {
        return; // Message already exists, don't add again
    }
    
    const container = document.getElementById('messages-container');
    const messageDiv = document.createElement('div');
    messageDiv.className = 'message-item animate-fade-in-up';
    messageDiv.setAttribute('data-message-id', message.message_id);
    
    // Use the message partial template approach
    // For now, we'll create a simple version and let the next poll refresh it properly
    const isCurrentUser = message.sender_id === {{ Auth::id() }};
    const senderName = message.sender ? (message.sender.first_name + ' ' + message.sender.last_name) : 'Unknown';
    const isTeacher = message.sender && message.sender.role === 'teacher';
    
    let replyHtml = '';
    if (message.reply_to) {
        const replySenderName = message.reply_to.sender ? (message.reply_to.sender.first_name + ' ' + message.reply_to.sender.last_name) : 'Unknown';
        replyHtml = `
            <div class="mb-2 p-2 ${isCurrentUser ? 'bg-pink-400/30' : 'bg-gray-100/80'} border-l-4 ${isCurrentUser ? 'border-pink-400' : 'border-gray-400'} rounded-lg text-sm">
                <p class="font-semibold ${isCurrentUser ? 'text-white' : 'text-gray-600'}">${replySenderName}</p>
                <p class="${isCurrentUser ? 'text-white/90' : 'text-gray-700'}">${escapeHtml(message.reply_to.content.substring(0, 50))}${message.reply_to.content.length > 50 ? '...' : ''}</p>
            </div>
        `;
    }
    
    let reactionsHtml = '';
    if (message.reactions && message.reactions.length > 0) {
        const reactionGroups = {};
        message.reactions.forEach(reaction => {
            if (!reactionGroups[reaction.emoji]) {
                reactionGroups[reaction.emoji] = [];
            }
            reactionGroups[reaction.emoji].push(reaction);
        });
        
        reactionsHtml = '<div class="flex gap-2 mt-2 flex-wrap">';
        Object.keys(reactionGroups).forEach(emoji => {
            const count = reactionGroups[emoji].length;
            const hasUserReaction = reactionGroups[emoji].some(r => r.user_id === {{ Auth::id() }});
            reactionsHtml += `
                <button onclick="toggleReaction(${message.message_id}, '${emoji}')" 
                    class="px-2 py-1 rounded-full border ${hasUserReaction ? 'bg-pink-100 border-pink-300' : 'bg-white/80 border-pink-200'} hover:bg-pink-200 transition text-sm shadow-sm">
                    ${emoji} ${count}
                </button>
            `;
        });
        reactionsHtml += '</div>';
    }
    
    messageDiv.innerHTML = `
        <div class="flex ${isCurrentUser ? 'justify-end' : 'justify-start'}">
            <div class="max-w-md ${isCurrentUser ? 'bg-pink-500 text-white shadow-lg' : 'bg-white/90 backdrop-blur-sm border border-pink-200/50 shadow-md'} rounded-2xl p-4 transform hover:scale-[1.02] transition-transform">
                ${!isCurrentUser ? `<div class="flex items-center gap-2 mb-2">
                    <span class="font-semibold ${isTeacher ? 'text-pink-600' : 'text-gray-700'}">${escapeHtml(senderName)}</span>
                    ${isTeacher ? '<span class="text-xs bg-pink-100 text-pink-700 px-2 py-1 rounded-full font-semibold border border-pink-200">Teacher</span>' : ''}
                </div>` : ''}
                ${replyHtml}
                <p class="text-sm ${isCurrentUser ? 'text-white' : 'text-gray-800'} leading-relaxed">${escapeHtml(message.content)}</p>
                <div class="flex items-center justify-between mt-2">
                    <span class="text-xs ${isCurrentUser ? 'text-white/80' : 'text-gray-500'}">Just now</span>
                    <div class="flex gap-2">
                        <button onclick="showEmojiPicker(${message.message_id})" class="text-lg hover:scale-125 transition-transform" title="Add reaction">😊</button>
                        ${!isCurrentUser ? `<button onclick="replyToMessage(${message.message_id}, '${escapeHtml(message.content.substring(0, 50))}')" class="text-sm ${isCurrentUser ? 'text-white/80' : 'text-gray-500'} hover:text-pink-600 hover:underline transition-colors">Reply</button>` : ''}
                        ${isCurrentUser ? `<button onclick="deleteMessage(${message.message_id})" class="text-sm text-white/80 hover:text-white transition-colors">Delete</button>` : ''}
                    </div>
                </div>
                ${reactionsHtml}
            </div>
        </div>
    `;
    
    container.appendChild(messageDiv);
}

// Format time
function formatTime(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diff = now - date;
    const minutes = Math.floor(diff / 60000);
    
    if (minutes < 1) return 'Just now';
    if (minutes < 60) return `${minutes}m ago`;
    if (minutes < 1440) return `${Math.floor(minutes / 60)}h ago`;
    return date.toLocaleDateString();
}

// Escape HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Send message
document.getElementById('message-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Prevent duplicate submissions
    if (isSubmitting) {
        return;
    }
    
    const formData = new FormData(this);
    const content = formData.get('content').trim();
    
    if (!content) return;
    
    // Disable form submission
    isSubmitting = true;
    const submitButton = this.querySelector('button[type="submit"]');
    const originalButtonText = submitButton.innerHTML;
    submitButton.disabled = true;
    submitButton.innerHTML = '<span>Sending...</span>';
    
    fetch('{{ route("group-chat.store") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mark this message as sent to prevent duplicate from polling
            sentMessageIds.add(data.message.message_id);
            addMessageToDOM(data.message);
            lastMessageId = data.message.message_id;
            document.getElementById('message-input').value = '';
            cancelReply();
            scrollToBottom();
        }
    })
    .catch(error => {
        console.error('Error sending message:', error);
        alert('Failed to send message. Please try again.');
    })
    .finally(() => {
        // Re-enable form submission
        isSubmitting = false;
        submitButton.disabled = false;
        submitButton.innerHTML = originalButtonText;
    });
});

// Reply to message
function replyToMessage(messageId, content) {
    currentReplyToId = messageId;
    document.getElementById('reply-to-message-id').value = messageId;
    document.getElementById('reply-preview-content').textContent = content;
    document.getElementById('reply-preview').classList.remove('hidden');
    document.getElementById('message-input').focus();
}

// Cancel reply
function cancelReply() {
    currentReplyToId = null;
    document.getElementById('reply-to-message-id').value = '';
    document.getElementById('reply-preview').classList.add('hidden');
}

// Show emoji picker
function showEmojiPicker(messageId) {
    currentEmojiMessageId = messageId;
    document.getElementById('emoji-picker-modal').classList.remove('hidden');
}

// Close emoji picker
function closeEmojiPicker() {
    document.getElementById('emoji-picker-modal').classList.add('hidden');
    currentEmojiMessageId = null;
}

// Select emoji
function selectEmoji(emoji) {
    if (currentEmojiMessageId) {
        toggleReaction(currentEmojiMessageId, emoji);
        closeEmojiPicker();
    }
}

// Toggle reaction
function toggleReaction(messageId, emoji) {
    fetch(`/group-chat/messages/${messageId}/reaction`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify({ emoji: emoji })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload messages to update reactions
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error toggling reaction:', error);
    });
}

// Delete message
function deleteMessage(messageId) {
    if (!confirm('Are you sure you want to delete this message?')) return;
    
    fetch(`/group-chat/messages/${messageId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelector(`[data-message-id="${messageId}"]`).remove();
        }
    })
    .catch(error => {
        console.error('Error deleting message:', error);
    });
}

// Switch class (for teachers)
function switchClass(classId) {
    if (classId) {
        window.location.href = `{{ route('group-chat.index', '') }}/${classId}`;
    }
}

// Poll for new messages every 3 seconds
setInterval(loadNewMessages, 3000);

// Initial scroll
setTimeout(scrollToBottom, 100);
</script>

@push('styles')
<style>
    /* Chat messages background with light pink */
    .chat-messages-bg {
        background: #fdf2f8;
    }
    
    /* Custom scrollbar matching theme */
    .custom-scrollbar::-webkit-scrollbar {
        width: 8px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(255, 182, 193, 0.1);
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #F472B6, #06B6D4);
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(to bottom, #EC4899, #0891B2);
    }
    
    @keyframes fade-in {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
    
    @keyframes fade-in-up {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes slide-in-left {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes slide-in-right {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .animate-fade-in {
        animation: fade-in 0.6s ease-out;
    }
    
    .animate-fade-in-up {
        animation: fade-in-up 0.6s ease-out forwards;
        opacity: 0;
    }
    
    .animate-slide-in-left {
        animation: slide-in-left 0.6s ease-out forwards;
    }
    
    .animate-slide-in-right {
        animation: slide-in-right 0.6s ease-out forwards;
    }
    
</style>
@endpush
@endsection
