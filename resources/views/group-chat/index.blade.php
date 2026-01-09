@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-pink-50 via-purple-50 to-indigo-50">
    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">💬 Group Chat</h1>
                    <p class="text-gray-600">{{ $class->class_name }}</p>
                    <p class="text-sm text-gray-500 mt-1">
                        Teacher: {{ $teacher->first_name }} {{ $teacher->last_name }} • 
                        {{ $students->count() }} students
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    @if(Auth::user()->role === 'teacher' && $teacherClasses && $teacherClasses->count() > 1)
                        <!-- Class Selector for Teachers -->
                        <select id="class-selector" onchange="switchClass(this.value)" 
                                class="px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent bg-white">
                            @foreach($teacherClasses as $tc)
                                <option value="{{ $tc->class_id }}" {{ $tc->class_id == $class->class_id ? 'selected' : '' }}>
                                    {{ $tc->class_name }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                    <a href="{{ Auth::user()->role === 'teacher' ? route('teacher.dashboard') : route('student.dashboard') }}" 
                       class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg transition">
                        ← Back
                    </a>
                </div>
            </div>
        </div>

        <!-- Chat Container -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden flex flex-col" style="height: 70vh;">
            <!-- Messages Area -->
            <div id="messages-container" class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50">
                @forelse($messages as $message)
                    <div class="message-item" data-message-id="{{ $message->message_id }}">
                        @include('group-chat.message', ['message' => $message])
                    </div>
                @empty
                    <div class="text-center text-gray-500 py-12">
                        <p class="text-lg">No messages yet. Start the conversation! 👋</p>
                    </div>
                @endforelse
            </div>

            <!-- Input Area -->
            <div class="border-t border-gray-200 p-4 bg-white">
                <form id="message-form" class="flex gap-3">
                    @csrf
                    <input type="hidden" name="class_id" value="{{ $class->class_id }}">
                    <input type="hidden" name="reply_to_message_id" id="reply-to-message-id" value="">
                    
                    <!-- Reply Preview -->
                    <div id="reply-preview" class="hidden w-full mb-2 p-2 bg-blue-50 border-l-4 border-blue-500 rounded">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-blue-600 font-semibold">Replying to:</p>
                                <p class="text-sm text-gray-700" id="reply-preview-content"></p>
                            </div>
                            <button type="button" onclick="cancelReply()" class="text-blue-600 hover:text-blue-800">✕</button>
                        </div>
                    </div>
                    
                    <div class="flex-1 flex gap-2">
                        <input 
                            type="text" 
                            id="message-input" 
                            name="content" 
                            placeholder="Type your message..." 
                            class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                            maxlength="2000"
                            required
                        >
                        <button 
                            type="submit" 
                            class="px-6 py-3 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-lg hover:from-pink-600 hover:to-purple-700 transition font-semibold"
                        >
                            Send
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Emoji Picker Modal -->
<div id="emoji-picker-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold">Choose an Emoji</h3>
            <button onclick="closeEmojiPicker()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>
        <div class="grid grid-cols-6 gap-3" id="emoji-grid">
            <!-- Common emojis -->
            <button onclick="selectEmoji('👍')" class="text-3xl hover:scale-125 transition">👍</button>
            <button onclick="selectEmoji('❤️')" class="text-3xl hover:scale-125 transition">❤️</button>
            <button onclick="selectEmoji('😂')" class="text-3xl hover:scale-125 transition">😂</button>
            <button onclick="selectEmoji('😊')" class="text-3xl hover:scale-125 transition">😊</button>
            <button onclick="selectEmoji('🎉')" class="text-3xl hover:scale-125 transition">🎉</button>
            <button onclick="selectEmoji('🙏')" class="text-3xl hover:scale-125 transition">🙏</button>
            <button onclick="selectEmoji('🔥')" class="text-3xl hover:scale-125 transition">🔥</button>
            <button onclick="selectEmoji('✨')" class="text-3xl hover:scale-125 transition">✨</button>
            <button onclick="selectEmoji('💯')" class="text-3xl hover:scale-125 transition">💯</button>
            <button onclick="selectEmoji('👏')" class="text-3xl hover:scale-125 transition">👏</button>
            <button onclick="selectEmoji('😍')" class="text-3xl hover:scale-125 transition">😍</button>
            <button onclick="selectEmoji('🤔')" class="text-3xl hover:scale-125 transition">🤔</button>
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
    messageDiv.className = 'message-item';
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
            <div class="mb-2 p-2 bg-${isCurrentUser ? 'pink-400' : 'gray-100'} border-l-4 border-${isCurrentUser ? 'pink-300' : 'gray-400'} rounded text-sm">
                <p class="font-semibold ${isCurrentUser ? 'text-pink-50' : 'text-gray-600'}">${replySenderName}</p>
                <p class="${isCurrentUser ? 'text-white' : 'text-gray-700'}">${escapeHtml(message.reply_to.content.substring(0, 50))}${message.reply_to.content.length > 50 ? '...' : ''}</p>
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
                    class="px-2 py-1 rounded-full border ${hasUserReaction ? 'bg-blue-100 border-blue-300' : 'bg-gray-100 border-gray-300'} hover:bg-blue-200 transition text-sm">
                    ${emoji} ${count}
                </button>
            `;
        });
        reactionsHtml += '</div>';
    }
    
    messageDiv.innerHTML = `
        <div class="flex ${isCurrentUser ? 'justify-end' : 'justify-start'}">
            <div class="max-w-md ${isCurrentUser ? 'bg-gradient-to-r from-pink-500 to-purple-600 text-white' : 'bg-white border border-gray-200'} rounded-2xl p-4 shadow-sm">
                ${!isCurrentUser ? `<div class="flex items-center gap-2 mb-2">
                    <span class="font-semibold ${isTeacher ? 'text-purple-600' : 'text-gray-700'}">${escapeHtml(senderName)}</span>
                    ${isTeacher ? '<span class="text-xs bg-purple-100 text-purple-700 px-2 py-1 rounded">Teacher</span>' : ''}
                </div>` : ''}
                ${replyHtml}
                <p class="text-sm ${isCurrentUser ? 'text-white' : 'text-gray-800'}">${escapeHtml(message.content)}</p>
                <div class="flex items-center justify-between mt-2">
                    <span class="text-xs ${isCurrentUser ? 'text-pink-100' : 'text-gray-500'}">Just now</span>
                    <div class="flex gap-2">
                        <button onclick="showEmojiPicker(${message.message_id})" class="text-lg hover:scale-125 transition">😊</button>
                        ${!isCurrentUser ? `<button onclick="replyToMessage(${message.message_id}, '${escapeHtml(message.content.substring(0, 50))}')" class="text-sm ${isCurrentUser ? 'text-pink-100' : 'text-gray-500'} hover:underline">Reply</button>` : ''}
                        ${isCurrentUser ? `<button onclick="deleteMessage(${message.message_id})" class="text-sm text-red-300 hover:text-red-100">Delete</button>` : ''}
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
    const originalButtonText = submitButton.textContent;
    submitButton.disabled = true;
    submitButton.textContent = 'Sending...';
    
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
        submitButton.textContent = originalButtonText;
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
@endsection
