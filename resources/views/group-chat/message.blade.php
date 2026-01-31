@php
    $isCurrentUser = $message->sender_id === Auth::id();
    $senderName = $message->sender ? ($message->sender->first_name . ' ' . $message->sender->last_name) : 'Unknown';
    $isTeacher = $message->sender && $message->sender->role === 'teacher';
@endphp

<div class="flex {{ $isCurrentUser ? 'justify-end' : 'justify-start' }}">
    <div class="max-w-md {{ $isCurrentUser ? 'bg-pink-500 text-white shadow-lg' : 'bg-white/90 backdrop-blur-sm border border-pink-200/50 shadow-md' }} rounded-2xl p-4 transform hover:scale-[1.02] transition-transform">
        @if(!$isCurrentUser)
            <div class="flex items-center gap-2 mb-2">
                <span class="font-semibold {{ $isTeacher ? 'text-pink-600' : 'text-gray-700' }}">{{ $senderName }}</span>
                @if($isTeacher)
                    <span class="text-xs bg-pink-100 text-pink-700 px-2 py-1 rounded-full font-semibold border border-pink-200">Teacher</span>
                @endif
            </div>
        @endif
        
        @if($message->replyTo)
            <div class="mb-2 p-2 {{ $isCurrentUser ? 'bg-pink-400/30 border-pink-400' : 'bg-gray-100/80 border-gray-400' }} border-l-4 rounded-lg text-sm">
                <p class="font-semibold {{ $isCurrentUser ? 'text-white' : 'text-gray-600' }}">
                    {{ $message->replyTo->sender ? ($message->replyTo->sender->first_name . ' ' . $message->replyTo->sender->last_name) : 'Unknown' }}
                </p>
                <p class="{{ $isCurrentUser ? 'text-white/90' : 'text-gray-700' }}">
                    {{ Str::limit($message->replyTo->content, 50) }}
                </p>
            </div>
        @endif
        
        <p class="text-sm {{ $isCurrentUser ? 'text-white' : 'text-gray-800' }} leading-relaxed">{{ $message->content }}</p>
        
        <div class="flex items-center justify-between mt-2">
            <span class="text-xs {{ $isCurrentUser ? 'text-white/80' : 'text-gray-500' }}">
                {{ $message->sent_at->diffForHumans() }}
            </span>
            <div class="flex gap-2">
                <button onclick="showEmojiPicker({{ $message->message_id }})" class="text-lg hover:scale-125 transition-transform" title="Add reaction">😊</button>
                @if(!$isCurrentUser)
                    <button onclick="replyToMessage({{ $message->message_id }}, '{{ Str::limit($message->content, 50) }}')" 
                        class="text-sm {{ $isCurrentUser ? 'text-white/80' : 'text-gray-500' }} hover:text-pink-600 hover:underline transition-colors">
                        Reply
                    </button>
                @endif
                @if($isCurrentUser)
                    <button onclick="deleteMessage({{ $message->message_id }})" 
                        class="text-sm text-white/80 hover:text-white transition-colors">
                        Delete
                    </button>
                @endif
            </div>
        </div>
        
        @if($message->reactions && $message->reactions->count() > 0)
            @php
                $reactionGroups = $message->reactions->groupBy('emoji');
            @endphp
            <div class="flex gap-2 mt-2 flex-wrap">
                @foreach($reactionGroups as $emoji => $reactions)
                    @php
                        $hasUserReaction = $reactions->contains('user_id', Auth::id());
                    @endphp
                    <button onclick="toggleReaction({{ $message->message_id }}, '{{ $emoji }}')" 
                        class="px-2 py-1 rounded-full border {{ $hasUserReaction ? 'bg-pink-100 border-pink-300' : 'bg-white/80 border-pink-200' }} hover:bg-pink-200 transition text-sm shadow-sm">
                        {{ $emoji }} {{ $reactions->count() }}
                    </button>
                @endforeach
            </div>
        @endif
    </div>
</div>
