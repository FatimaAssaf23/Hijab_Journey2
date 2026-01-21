@php
    $isCurrentUser = $message->sender_id === Auth::id();
    $senderName = $message->sender ? ($message->sender->first_name . ' ' . $message->sender->last_name) : 'Unknown';
    $isTeacher = $message->sender && $message->sender->role === 'teacher';
@endphp

<div class="flex {{ $isCurrentUser ? 'justify-end' : 'justify-start' }}">
    <div class="max-w-md {{ $isCurrentUser ? 'bg-[#FDB5C8] text-white' : 'bg-white border border-gray-200' }} rounded-2xl p-4 shadow-sm">
        @if(!$isCurrentUser)
            <div class="flex items-center gap-2 mb-2">
                <span class="font-semibold {{ $isTeacher ? 'text-purple-600' : 'text-gray-700' }}">{{ $senderName }}</span>
                @if($isTeacher)
                    <span class="text-xs bg-purple-100 text-purple-700 px-2 py-1 rounded">Teacher</span>
                @endif
            </div>
        @endif
        
        @if($message->replyTo)
            <div class="mb-2 p-2 {{ $isCurrentUser ? 'bg-[#FC8EAC]' : 'bg-gray-100' }} border-l-4 {{ $isCurrentUser ? 'border-[#FDB5C8]' : 'border-gray-400' }} rounded text-sm">
                <p class="font-semibold {{ $isCurrentUser ? 'text-white' : 'text-gray-600' }}">
                    {{ $message->replyTo->sender ? ($message->replyTo->sender->first_name . ' ' . $message->replyTo->sender->last_name) : 'Unknown' }}
                </p>
                <p class="{{ $isCurrentUser ? 'text-white' : 'text-gray-700' }}">
                    {{ Str::limit($message->replyTo->content, 50) }}
                </p>
            </div>
        @endif
        
        <p class="text-sm {{ $isCurrentUser ? 'text-white' : 'text-gray-800' }}">{{ $message->content }}</p>
        
        <div class="flex items-center justify-between mt-2">
            <span class="text-xs {{ $isCurrentUser ? 'text-pink-50' : 'text-gray-500' }}">
                {{ $message->sent_at->diffForHumans() }}
            </span>
            <div class="flex gap-2">
                <button onclick="showEmojiPicker({{ $message->message_id }})" class="text-lg hover:scale-125 transition" title="Add reaction">😊</button>
                @if(!$isCurrentUser)
                    <button onclick="replyToMessage({{ $message->message_id }}, '{{ Str::limit($message->content, 50) }}')" 
                        class="text-sm {{ $isCurrentUser ? 'text-pink-50' : 'text-gray-500' }} hover:underline">
                        Reply
                    </button>
                @endif
                @if($isCurrentUser)
                    <button onclick="deleteMessage({{ $message->message_id }})" 
                        class="text-sm text-pink-50 hover:text-white">
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
                        class="px-2 py-1 rounded-full border {{ $hasUserReaction ? 'bg-blue-100 border-blue-300' : 'bg-gray-100 border-gray-300' }} hover:bg-blue-200 transition text-sm">
                        {{ $emoji }} {{ $reactions->count() }}
                    </button>
                @endforeach
            </div>
        @endif
    </div>
</div>
