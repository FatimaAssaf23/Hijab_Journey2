@extends('layouts.admin')

@section('content')

<div class="container mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6">Word/Definition Pairs</h2>

    @if (!empty($pairs))
        <div class="mb-8">
            <h3 class="text-lg font-semibold mb-2">Saved Pairs:</h3>
            <div class="grid gap-4 md:grid-cols-2">
                @foreach ($pairs as $pair)
                    <div class="pair-row border rounded p-4 flex flex-col gap-2 relative" data-id="{{ $pair['id'] }}" style="background-color:#D1F7F3;">
                        <div class="flex flex-col gap-1 pair-view">
                            <div class="font-bold word-text">{{ $pair['word'] }}</div>
                            <div class="text-gray-700 def-text">{{ $pair['definition'] }}</div>
                        </div>
                        <form class="pair-edit-form hidden flex-col gap-2" method="POST" action="#">
                            @csrf
                            <input type="text" name="word" class="form-input border rounded px-2 py-1 mb-1" value="{{ $pair['word'] }}" required>
                            <input type="text" name="definition" class="form-input border rounded px-2 py-1 mb-1" value="{{ $pair['definition'] }}" required>
                            <div class="flex gap-2">
                                <button type="submit" class="saveEditBtn bg-green-500 text-white px-2 py-1 rounded">Save</button>
                                <button type="button" class="cancelEditBtn bg-gray-400 text-white px-2 py-1 rounded">Cancel</button>
                            </div>
                        </form>
                        <div class="flex gap-2 mt-2">
                            <button type="button" class="editPairBtn text-white px-2 py-1 rounded" style="background-color:#F8C5C8;color:#b91c1c;">Edit</button>
                            <form class="inline deletePairForm" method="POST" action="{{ route('teacher.games.delete', $pair['id']) }}" onsubmit="return confirm('Are you sure you want to delete this pair?');">
                                @csrf
                                <button type="submit" class="deletePairBtn text-white px-2 py-1 rounded" style="background-color:#FC8EAC;">Delete</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <form id="wordDefForm" method="POST" action="{{ route('teacher.games.store') }}">
        @csrf
        <div id="pairsContainer">
            @php
                $oldWords = old('words', []);
                $oldDefs = old('definitions', []);
                $count = max(count($oldWords), count($oldDefs), 1);
            @endphp
            @for ($i = 0; $i < $count; $i++)
                <div class="flex flex-col md:flex-row gap-4 mb-4 pair-box bg-white shadow rounded p-4">
                    <input type="text" name="words[]" class="form-input border rounded px-3 py-2 w-full md:w-1/3" placeholder="Word" value="{{ $oldWords[$i] ?? '' }}" required>
                    <input type="text" name="definitions[]" class="form-input border rounded px-3 py-2 w-full md:w-2/3" placeholder="Definition" value="{{ $oldDefs[$i] ?? '' }}" required>
                    <button type="button" class="removePairBtn text-red-500 hover:text-red-700 hidden md:block">Remove</button>
                </div>
            @endfor
        </div>
        <button type="button" id="addPairBtn" class="px-4 py-2 rounded" style="background-color:#F8C5C8;color:#b91c1c;">Add Another Pair</button>
        <button type="submit" class="px-4 py-2 rounded ml-2" style="background-color:#FC8EAC;color:white;">Submit</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const pairsContainer = document.getElementById('pairsContainer');
    const addPairBtn = document.getElementById('addPairBtn');

    addPairBtn.addEventListener('click', function() {
        const pairBox = document.createElement('div');
        pairBox.className = 'flex flex-col md:flex-row gap-4 mb-4 pair-box bg-white shadow rounded p-4';
        pairBox.innerHTML = `
            <input type="text" name="words[]" class="form-input border rounded px-3 py-2 w-full md:w-1/3" placeholder="Word" required>
            <input type="text" name="definitions[]" class="form-input border rounded px-3 py-2 w-full md:w-2/3" placeholder="Definition" required>
            <button type="button" class="removePairBtn text-red-500 hover:text-red-700 hidden md:block">Remove</button>
        `;
        pairsContainer.appendChild(pairBox);
    });

    pairsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('removePairBtn')) {
            e.target.parentElement.remove();
        }
    });

    // Edit and delete logic for saved pairs
    document.querySelectorAll('.editPairBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            const row = btn.closest('.pair-row');
            row.querySelector('.pair-view').classList.add('hidden');
            row.querySelector('.pair-edit-form').classList.remove('hidden');
        });
    });
    document.querySelectorAll('.cancelEditBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            const row = btn.closest('.pair-row');
            row.querySelector('.pair-edit-form').classList.add('hidden');
            row.querySelector('.pair-view').classList.remove('hidden');
        });
    });
    // TODO: AJAX for saveEditBtn and deletePairBtn
});
</script>
@endsection
