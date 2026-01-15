<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\GroupChatMessage;
use App\Models\ChatMessageReaction;
use App\Models\StudentClass;
use App\Models\Student;

class GroupChatController extends Controller
{
    /**
     * Show the group chat page for a class
     */
    public function index($classId = null)
    {
        $user = Auth::user();
        
        // If user is a student, get their class
        if ($user->role === 'student') {
            $student = Student::where('user_id', $user->user_id)->first();
            if (!$student || !$student->class_id) {
                return redirect()->route('student.dashboard')->with('error', 'You are not enrolled in any class.');
            }
            $classId = $student->class_id;
        }
        
        // If user is a teacher without a classId, show class selector
        if ($user->role === 'teacher' && !$classId) {
            $teacherClasses = StudentClass::where('teacher_id', $user->user_id)
                ->orderBy('class_name')
                ->get();
            
            if ($teacherClasses->isEmpty()) {
                return redirect()->route('teacher.dashboard')->with('error', 'You are not assigned to any classes.');
            }
            
            // If only one class, redirect to it
            if ($teacherClasses->count() === 1) {
                return redirect()->route('group-chat.index', $teacherClasses->first()->class_id);
            }
            
            // Show class selector
            return view('group-chat.select-class', compact('teacherClasses'));
        }
        
        // If user is a teacher with a classId, verify they own it
        if ($user->role === 'teacher' && $classId) {
            $class = StudentClass::where('class_id', $classId)
                ->where('teacher_id', $user->user_id)
                ->firstOrFail();
        } else {
            // Get the class
            $class = StudentClass::findOrFail($classId);
            
            // Verify access: student must be in this class, teacher must own it
            if ($user->role === 'student') {
                $student = Student::where('user_id', $user->user_id)
                    ->where('class_id', $classId)
                    ->first();
                if (!$student) {
                    abort(403, 'You are not enrolled in this class.');
                }
            } elseif ($user->role === 'teacher' && $class->teacher_id !== $user->user_id) {
                abort(403, 'You do not have access to this class.');
            }
        }
        
        // Get all teacher's classes for the class selector
        $teacherClasses = null;
        if ($user->role === 'teacher') {
            $teacherClasses = StudentClass::where('teacher_id', $user->user_id)
                ->orderBy('class_name')
                ->get();
        }
        
        // Load messages with sender, replies, and reactions
        $messages = GroupChatMessage::where('class_id', $classId)
            ->where('is_deleted', false)
            ->with(['sender', 'replyTo.sender', 'reactions.user'])
            ->orderBy('sent_at', 'asc')
            ->get();
        
        // Get class members (students + teacher)
        $students = $class->students()->with('user')->get();
        $teacher = $class->teacher;
        
        return view('group-chat.index', compact('class', 'messages', 'students', 'teacher', 'teacherClasses'));
    }

    /**
     * Store a new message
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'class_id' => 'required|exists:student_classes,class_id',
            'content' => 'required|string|max:2000',
            'reply_to_message_id' => 'nullable|exists:group_chat_messages,message_id',
        ]);
        
        // Verify user has access to this class
        $class = StudentClass::findOrFail($request->class_id);
        
        if ($user->role === 'student') {
            $student = Student::where('user_id', $user->user_id)
                ->where('class_id', $request->class_id)
                ->first();
            if (!$student) {
                return response()->json(['error' => 'You are not enrolled in this class.'], 403);
            }
        } elseif ($user->role === 'teacher' && $class->teacher_id !== $user->user_id) {
            return response()->json(['error' => 'You do not have access to this class.'], 403);
        }
        
        $message = GroupChatMessage::create([
            'class_id' => $request->class_id,
            'sender_id' => $user->user_id,
            'content' => $request->content,
            'reply_to_message_id' => $request->reply_to_message_id,
            'sent_at' => now(),
        ]);
        
        // Load relationships for response
        $message->load(['sender', 'replyTo.sender', 'reactions.user']);
        
        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    /**
     * Get new messages (for polling/real-time updates)
     */
    public function getMessages(Request $request, $classId)
    {
        $user = Auth::user();
        $lastMessageId = $request->get('last_message_id', 0);
        
        // Verify access
        $class = StudentClass::findOrFail($classId);
        if ($user->role === 'student') {
            $student = Student::where('user_id', $user->user_id)
                ->where('class_id', $classId)
                ->first();
            if (!$student) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
        } elseif ($user->role === 'teacher' && $class->teacher_id !== $user->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $messages = GroupChatMessage::where('class_id', $classId)
            ->where('is_deleted', false)
            ->where('message_id', '>', $lastMessageId)
            ->with(['sender', 'replyTo.sender', 'reactions.user'])
            ->orderBy('sent_at', 'asc')
            ->get();
        
        return response()->json([
            'success' => true,
            'messages' => $messages,
        ]);
    }

    /**
     * Add a reaction to a message
     */
    public function addReaction(Request $request, $messageId)
    {
        $user = Auth::user();
        $request->validate([
            'emoji' => 'required|string|max:10',
        ]);
        
        $message = GroupChatMessage::findOrFail($messageId);
        
        // Verify user has access to this message's class
        $class = $message->studentClass;
        if ($user->role === 'student') {
            $student = Student::where('user_id', $user->user_id)
                ->where('class_id', $class->class_id)
                ->first();
            if (!$student) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
        } elseif ($user->role === 'teacher' && $class->teacher_id !== $user->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        // Check if reaction already exists
        $existingReaction = ChatMessageReaction::where('message_id', $messageId)
            ->where('user_id', $user->user_id)
            ->where('emoji', $request->emoji)
            ->first();
        
        if ($existingReaction) {
            // Remove reaction if it already exists (toggle)
            $existingReaction->delete();
            return response()->json([
                'success' => true,
                'action' => 'removed',
            ]);
        }
        
        // Create new reaction
        $reaction = ChatMessageReaction::create([
            'message_id' => $messageId,
            'user_id' => $user->user_id,
            'emoji' => $request->emoji,
        ]);
        
        $reaction->load('user');
        
        return response()->json([
            'success' => true,
            'action' => 'added',
            'reaction' => $reaction,
        ]);
    }

    /**
     * Delete a message (soft delete)
     */
    public function deleteMessage($messageId)
    {
        $user = Auth::user();
        $message = GroupChatMessage::findOrFail($messageId);
        
        // Only sender or teacher can delete
        if ($message->sender_id !== $user->user_id) {
            $class = $message->studentClass;
            if ($user->role !== 'teacher' || $class->teacher_id !== $user->user_id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
        }
        
        $message->is_deleted = true;
        $message->save();
        
        return response()->json([
            'success' => true,
        ]);
    }
}
