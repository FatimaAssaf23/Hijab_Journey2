
@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto mt-10">
    <div class="shadow-xl mb-8" style="background: linear-gradient(90deg, #EC769A 0%, #FC9EAC 50%, #F8C5C8 100%);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-4xl font-extrabold text-white mb-2 drop-shadow-lg">⚠️ Emergency Reassignment</h1>
            <p class="text-white drop-shadow">Handle teacher unavailability and reassign coverage</p>
        </div>
    </div>
    <h2 class="text-3xl font-bold mb-8 text-[#197D8C]">Emergency Absence Requests</h2>
    <div class="bg-white rounded-xl shadow-lg p-6">
        <table class="min-w-full table-auto">
            <thead>
                <tr class="bg-[#F8C5C8] text-[#197D8C]">
                    <th class="px-4 py-2 text-left">Teacher</th>
                    <th class="px-4 py-2 text-left">Start Date</th>
                    <th class="px-4 py-2 text-left">End Date</th>
                    <th class="px-4 py-2 text-left">Reason</th>
                    <th class="px-4 py-2 text-left">Requested At</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Affected Classes</th>
                    <th class="px-4 py-2 text-left">Reassign</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $request)
                    <tr class="border-b">
                        <td class="px-4 py-2">
                            @if($request->teacher)
                                {{ $request->teacher->first_name }} {{ $request->teacher->last_name }}
                            @else
                                Unknown
                            @endif
                        </td>
                        <td class="px-4 py-2">{{ $request->start_date }}</td>
                        <td class="px-4 py-2">{{ $request->end_date }}</td>
                        <td class="px-4 py-2">{{ $request->reason }}</td>
                        <td class="px-4 py-2">{{ $request->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-2">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $request->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($request->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($request->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-2">
                            @if($request->affected_classes && count($request->affected_classes))
                                @foreach($request->affected_classes as $class)
                                    <span class="inline-block bg-pink-100 text-pink-800 px-2 py-1 rounded-full text-xs font-semibold mr-1 mb-1">{{ $class }}</span>
                                @endforeach
                            @else
                                <span class="text-gray-400">None</span>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            <div class="flex flex-col gap-2">
                                @if($request->status === 'pending')
                                    <form method="POST" action="{{ route('admin.emergency.approve', $request->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs font-bold">Approve</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.emergency.reject', $request->id) }}" class="inline mt-1">
                                        @csrf
                                        <input type="text" name="rejection_reason" placeholder="Reason for rejection" class="rounded border-gray-300 px-2 py-1 text-xs mb-1" required>
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs font-bold">Reject</button>
                                    </form>
                                @endif
                                @if($request->affected_classes && count($request->affected_classes))
                                    <form method="POST" action="{{ route('admin.emergency.reassign') }}" class="flex flex-col sm:flex-row items-start sm:items-center gap-2 mt-2">
                                        @csrf
                                        <input type="hidden" name="emergency_request_id" value="{{ $request->id }}">
                                        <label for="teacher_id_{{ $request->id }}" class="text-xs font-semibold text-gray-700">Reassign to:</label>
                                        <select name="teacher_id" id="teacher_id_{{ $request->id }}" class="rounded border-gray-300 focus:border-pink-400 focus:ring focus:ring-pink-200 focus:ring-opacity-50">
                                            <option value="">Select teacher</option>
                                            @foreach(\App\Models\User::where('role', 'teacher')->where('user_id', '!=', $request->teacher_id)->get() as $teacher)
                                                <option value="{{ $teacher->user_id }}">{{ $teacher->first_name }} {{ $teacher->last_name }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="bg-pink-500 hover:bg-pink-600 text-white px-3 py-1 rounded text-xs font-bold">Reassign</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-gray-400 py-6">No emergency requests found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<!-- Success Message -->
@if (session('success'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="bg-green-500 text-white rounded-lg p-4 shadow-lg">
            ✓ {{ session('success') }}
        </div>
    </div>
@endif
@endsection
