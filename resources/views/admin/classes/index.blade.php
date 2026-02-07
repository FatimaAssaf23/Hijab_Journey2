<!-- Modal and class management scripts: placed here for guaranteed global access -->
<script>

// Use only the Blade-generated studentsData and classesData for reliability
const studentsData = {
    @foreach($classes as $class)
    {{ $class['id'] }}: [
        @foreach($class['studentsList'] as $student)
            { name: @json($student['name']), email: @json($student['email']), id: {{ $student['id'] }} },
        @endforeach
    ],
    @endforeach
};
const classesData = {
    @foreach($classes as $class)
    {{ $class['id'] }}: {
        name: "{{ $class['name'] }}",
        teacher: "@php $teacher = collect($teachers)->firstWhere('id', $class['teacherId']); echo $teacher ? $teacher['name'] : 'Unassigned'; @endphp",
        students: {{ count($class['studentsList']) }},
        capacity: {{ $class['capacity'] ?? 30 }},
        status: "{{ $class['status'] ?? 'active' }}",
        description: "{{ $class['description'] ?? 'No description available' }}"
    },
    @endforeach
};
let currentClassId = null;

function showStudentsList(classId) {
    const students = studentsData[classId] || [];
    let content = '';
    if (students.length > 0) {
        content = '<div class="space-y-2">';
        students.forEach((student, index) => {
            content += `
                <div class="flex items-center gap-3 p-4 bg-gradient-to-r from-pink-50 to-rose-50 rounded-xl hover:shadow-lg transition-all transform hover:scale-[1.02] border-2 border-pink-200/50 student-card">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-pink-400 to-rose-400 flex items-center justify-center text-white font-bold shadow-md">
                        ${index + 1}
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-gray-800">${student.name}</p>
                        <p class="text-xs text-gray-600 font-medium">${student.email}</p>
                    </div>
                    <div class="text-2xl">👤</div>
                </div>
            `;
        });
        content += '</div>';
    } else {
        content = '<div class="text-center py-8"><p class="text-gray-500">No students enrolled yet</p><p class="text-sm text-gray-400 mt-2">Students will appear here once they join this class</p></div>';
    }
    document.getElementById('studentsContent').innerHTML = content;
    document.getElementById('studentsModal').classList.remove('hidden');
}
window.showStudentsList = showStudentsList;

function closeStudentsList() {
    document.getElementById('studentsModal').classList.add('hidden');
}
window.closeStudentsList = closeStudentsList;

function showClassInfo(classId) {
    const classInfo = classesData[classId];
    if (!classInfo) return;
    const statusColors = {
        'active': 'bg-green-100 text-green-800',
        'full': 'bg-yellow-100 text-yellow-800',
        'closed': 'bg-red-100 text-red-800',
        'empty': 'bg-gray-300 text-gray-900'
    };
    const content = `
        <div class="space-y-4">
            <div class="bg-gradient-to-r from-pink-50 to-purple-50 rounded-lg p-4">
                <p class="text-sm text-gray-500 mb-1">Class Name</p>
                <p class="text-xl font-bold text-gray-800">${classInfo.name}</p>
            </div>
            <div class="bg-blue-50 rounded-lg p-4">
                <p class="text-sm text-gray-500 mb-1">👨‍🏫 Teacher</p>
                <p class="font-semibold text-gray-800">${classInfo.teacher}</p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-purple-50 rounded-lg p-4">
                    <p class="text-sm text-gray-500 mb-1">👥 Current Students</p>
                    <p class="text-2xl font-bold text-purple-600">${classInfo.students}</p>
                </div>
                <div class="bg-pink-50 rounded-lg p-4">
                    <p class="text-sm text-gray-500 mb-1">📊 Max Capacity</p>
                    <p class="text-2xl font-bold text-pink-600">${classInfo.capacity}</p>
                </div>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-500 mb-2">Status</p>
                <span class="inline-block px-4 py-2 rounded-full font-semibold ${statusColors[classInfo.status] || statusColors['active']}">
                    ${classInfo.status.charAt(0).toUpperCase() + classInfo.status.slice(1)}
                </span>
            </div>
            <div class="bg-gradient-to-r from-pink-50 to-teal-50 rounded-lg p-4">
                <p class="text-sm text-gray-500 mb-2">📝 Description</p>
                <p class="text-gray-700 leading-relaxed">${classInfo.description}</p>
            </div>
        </div>
    `;
    document.getElementById('classInfoContent').innerHTML = content;
    document.getElementById('classInfoModal').classList.remove('hidden');
}
window.showClassInfo = showClassInfo;

function closeClassInfo() {
    document.getElementById('classInfoModal').classList.add('hidden');
}
window.closeClassInfo = closeClassInfo;

function manageClass(classId) {
    currentClassId = classId;
    const students = studentsData[classId] || [];
    renderManageStudents(students);
    document.getElementById('manageClassModal').classList.remove('hidden');
}
window.manageClass = manageClass;

function renderManageStudents(students) {
    document.getElementById('studentCount').textContent = students.length;
    let content = '';
    if (students.length > 0) {
        students.forEach((student, index) => {
                    content += `
                <div class="flex items-center gap-3 p-4 bg-gradient-to-r from-pink-50 to-rose-50 rounded-xl hover:shadow-lg transition-all transform hover:scale-[1.02] border-2 border-pink-200/50 student-card">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-pink-400 to-rose-400 flex items-center justify-center text-white font-bold shadow-md">
                        ${index + 1}
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-gray-800">${student.name}</p>
                        <p class="text-xs text-gray-600 font-medium">${student.email}</p>
                    </div>
                    <button onclick="changeStudentClass(${student.id}, '${student.name}')" class="bg-gradient-to-r from-cyan-500 to-teal-500 hover:from-cyan-600 hover:to-teal-600 text-white px-4 py-2 rounded-xl font-bold transition-all transform hover:scale-105 shadow-md">🔄 Change</button>
                    <button onclick="removeStudent(${student.id})" class="bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white px-4 py-2 rounded-xl font-bold transition-all transform hover:scale-105 shadow-md">🗑️ Remove</button>
                </div>
            `;
        });
    } else {
        content = '<div class="text-center py-8"><p class="text-gray-500">No students enrolled yet</p><p class="text-sm text-gray-400 mt-2">Add students using the form above</p></div>';
    }
    document.getElementById('manageStudentsContent').innerHTML = content;
}
window.renderManageStudents = renderManageStudents;

function removeStudent(studentId) {
    if (!confirm('Are you sure you want to remove this student from the class?')) {
        return;
    }
    fetch(`/admin/classes/${currentClassId}/students/remove`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({ student_ids: [studentId] })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            studentsData[currentClassId] = studentsData[currentClassId].filter(s => s.id !== studentId);
            renderManageStudents(studentsData[currentClassId]);
            // Update class info modal student count if open
            if (document.getElementById('classInfoModal') && !document.getElementById('classInfoModal').classList.contains('hidden')) {
                if (classesData[currentClassId]) {
                    classesData[currentClassId].students = studentsData[currentClassId].length;
                }
                // Update the DOM directly if the modal is open
                const classInfoStudents = document.querySelector('#classInfoContent .bg-purple-50 .text-2xl');
                if (classInfoStudents) {
                    classInfoStudents.textContent = studentsData[currentClassId].length;
                }
            }
            // Update class card count
            const studentCountElem = document.getElementById('studentCount_' + currentClassId);
            if (studentCountElem) {
                studentCountElem.textContent = studentsData[currentClassId].length + ' students';
            }
            alert('Student removed successfully!');
        } else {
            alert('Failed to remove student: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(() => {
        alert('Failed to remove student due to network error.');
    });
}
window.removeStudent = removeStudent;

function changeStudentClass(studentId, studentName) {
    let classOptions = '';
    for (const classId in classesData) {
        if (parseInt(classId) !== currentClassId) {
            classOptions += `<option value="${classId}">${classesData[classId].name}</option>`;
        }
    }
    const dialog = `
        <div id="changeClassDialog" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[60] flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl p-8 max-w-md w-full shadow-2xl transform transition-all">
                <h3 class="text-xl font-bold text-gray-800 mb-4">🔄 Change Class</h3>
                <p class="text-gray-600 mb-4">Move <strong>${studentName}</strong> to:</p>
                <select id="targetClassSelect" class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 mb-6 focus:outline-none focus:border-blue-500">
                    <option value="">Select target class...</option>
                    ${classOptions}
                </select>
                <div class="flex gap-3">
                    <button onclick="confirmClassChange(${studentId})" class="flex-1 bg-gradient-to-r from-blue-500 to-purple-500 hover:shadow-lg text-white font-semibold py-3 rounded-lg transition-all">Confirm</button>
                    <button onclick="closeChangeClassDialog()" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-3 rounded-lg transition-all">Cancel</button>
                </div>
            </div>
        </div>
    `;
    document.body.insertAdjacentHTML('beforeend', dialog);
}
window.changeStudentClass = changeStudentClass;

function confirmClassChange(studentId) {
    const targetClassId = document.getElementById('targetClassSelect').value;
    if (!targetClassId) {
        alert('Please select a target class');
        return;
    }
    // Call backend to change student class
    fetch(`/admin/students/${studentId}/change-class`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({ new_class_id: targetClassId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove student from old class
            studentsData[currentClassId] = studentsData[currentClassId].filter(s => s.id !== studentId);
            // Add student to new class
            if (!studentsData[targetClassId]) {
                studentsData[targetClassId] = [];
            }
            studentsData[targetClassId].push({
                id: studentId,
                name: data.student ? data.student.name : '',
                email: data.student ? data.student.email : ''
            });
            renderManageStudents(studentsData[currentClassId]);
            // Update student count in both classes
            const oldCountElem = document.getElementById('studentCount_' + currentClassId);
            if (oldCountElem) oldCountElem.textContent = studentsData[currentClassId].length + ' students';
            const newCountElem = document.getElementById('studentCount_' + targetClassId);
            if (newCountElem) newCountElem.textContent = studentsData[targetClassId].length + ' students';
            closeChangeClassDialog();
            alert('Student moved to new class successfully!');
        } else {
            alert('Failed to move student: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(() => {
        alert('Failed to move student due to network error.');
    });
}
window.confirmClassChange = confirmClassChange;

function closeChangeClassDialog() {
    const dialog = document.getElementById('changeClassDialog');
    if (dialog) {
        dialog.remove();
    }
}
window.closeChangeClassDialog = closeChangeClassDialog;

function closeManageClass() {
    document.getElementById('manageClassModal').classList.add('hidden');
    currentClassId = null;
}
window.closeManageClass = closeManageClass;

// Modal close on backdrop click
document.getElementById('studentsModal').addEventListener('click', function(e) {
    if (e.target === this) closeStudentsList();
});
document.getElementById('classInfoModal').addEventListener('click', function(e) {
    if (e.target === this) closeClassInfo();
});
document.getElementById('manageClassModal').addEventListener('click', function(e) {
    if (e.target === this) closeManageClass();
});
</script>
@extends('layouts.admin')

@section('content')
<div class="min-h-screen" style="background: linear-gradient(135deg, #FFF4FA 0%, #FDF2F8 30%, #F0F9FF 70%, #E0F7FA 100%);">
    <!-- Header -->
    <div class="bg-gradient-to-r from-pink-200/90 via-rose-100/80 to-cyan-200/90 shadow-2xl border-b-4 border-pink-300/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-6 text-center md:text-left">
                    <!-- Classes Icon -->
                    <div class="hidden md:flex items-center justify-center w-24 h-24 rounded-3xl bg-gradient-to-br from-pink-500 via-rose-400 to-cyan-500 shadow-2xl transform hover:scale-105 transition-all duration-300 border-4 border-white/50">
                        <div class="text-6xl filter drop-shadow-2xl">🏫</div>
                    </div>
                    <div>
                        <h1 class="text-5xl font-extrabold text-gray-800 mb-3 drop-shadow-lg flex items-center gap-4 justify-center md:justify-start">
                            <span class="md:hidden flex items-center justify-center w-20 h-20 rounded-2xl bg-gradient-to-br from-pink-500 via-rose-400 to-cyan-500 shadow-xl border-4 border-white/50">
                                <span class="text-5xl">🏫</span>
                            </span>
                            <span class="bg-clip-text text-transparent bg-gradient-to-r from-pink-600 via-rose-500 to-cyan-600">Classes Manager</span>
                        </h1>
                        <p class="text-gray-700 text-lg font-medium">Create and manage your classes • Organize students and assign teachers</p>
                    </div>
                </div>
                <a href="{{ route('admin.classes.create') }}" class="bg-gradient-to-r from-pink-400 to-rose-400 hover:from-pink-500 hover:to-rose-500 text-white px-8 py-4 rounded-2xl font-bold shadow-xl hover:shadow-2xl transition-all transform hover:scale-105 flex items-center gap-3 text-lg border-2 border-pink-300/50">
                    <span class="text-2xl">➕</span> Create New Class
                </a>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-800 px-6 py-4 rounded-xl shadow-lg backdrop-blur-lg">
                <div class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-semibold">{{ session('success') }}</span>
                </div>
            </div>
        </div>
    @endif

    <!-- Search Bar -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <form method="GET" action="{{ route('admin.classes') }}" class="bg-white/90 backdrop-blur-md rounded-2xl p-6 shadow-lg border-2 border-pink-200/50">
            <div class="flex flex-col md:flex-row gap-4 items-center">
                <div class="flex-1 w-full">
                    <div class="relative">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ $search ?? '' }}" 
                            placeholder="Search by class name, teacher name, or status. For capacity, enter a number (e.g., 30)" 
                            class="w-full border-2 border-pink-300 rounded-xl px-6 py-4 pl-12 focus:outline-none focus:border-pink-500 focus:ring-2 focus:ring-pink-200 text-gray-800 font-medium"
                        >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-pink-500 absolute left-4 top-1/2 transform -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button 
                        type="submit" 
                        class="bg-gradient-to-r from-pink-400 to-rose-400 hover:from-pink-500 hover:to-rose-500 text-white px-8 py-4 rounded-xl font-bold shadow-lg hover:shadow-xl transition-all transform hover:scale-105 border-2 border-pink-300/50 flex items-center gap-2"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Search
                    </button>
                    @if(!empty($search))
                        <a 
                            href="{{ route('admin.classes') }}" 
                            class="bg-gradient-to-r from-gray-300 to-gray-400 hover:from-gray-400 hover:to-gray-500 text-gray-800 px-8 py-4 rounded-xl font-bold shadow-lg hover:shadow-xl transition-all transform hover:scale-105 border-2 border-gray-300/50 flex items-center gap-2"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Clear
                        </a>
                    @endif
                </div>
            </div>
            @if(!empty($search))
                <div class="mt-4 text-sm text-gray-600 font-medium">
                    <span class="text-pink-600 font-bold">{{ count($classes) }}</span> class(es) found for "<span class="text-pink-600 font-bold">{{ $search }}</span>"
                </div>
            @endif
        </form>
    </div>

    <!-- Classes Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($classes as $class)
                <div class="group relative bg-white/90 backdrop-blur-md rounded-3xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105 border-2 border-pink-200/50 hover:border-cyan-300/50">
                    <!-- Gradient Top Border -->
                    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-pink-400 via-rose-400 to-cyan-400 rounded-t-3xl"></div>
                    
                    <!-- Card Header -->
                    <div class="flex justify-between items-start mb-5 mt-2">
                        <div class="flex-1">
                            <h3 class="text-2xl font-extrabold text-gray-800 mb-1 group-hover:text-pink-600 transition-colors">{{ $class['name'] }}</h3>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.classes.edit', $class['id']) }}" class="bg-gradient-to-r from-cyan-400 to-teal-400 hover:from-cyan-500 hover:to-teal-500 text-white rounded-xl transition-all transform hover:scale-110 shadow-lg hover:shadow-xl border-2 border-cyan-300/50 flex items-center justify-center" style="width: 48px; height: 48px;">
                                <span class="text-lg">✏️</span>
                            </a>
                            <form method="POST" action="{{ route('admin.classes.delete', $class['id']) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this class?')" class="bg-gradient-to-r from-pink-400 to-rose-400 hover:from-pink-500 hover:to-rose-500 text-white rounded-xl transition-all transform hover:scale-110 shadow-lg hover:shadow-xl border-2 border-pink-300/50 flex items-center justify-center" style="width: 48px; height: 48px;">
                                    <span class="text-lg">🗑️</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Card Stats -->
                    <div class="space-y-3 mb-5">
                        <div class="bg-gradient-to-r from-cyan-50 to-teal-50 rounded-xl p-4 shadow-md border-2 border-cyan-200/50">
                            <p class="text-cyan-700 text-xs font-semibold mb-1 uppercase tracking-wide">👨‍🏫 Teacher</p>
                            <p class="text-gray-800 font-bold text-lg">
                                @php
                                    $teacher = collect($teachers)->firstWhere('id', $class['teacherId']);
                                    echo $teacher ? $teacher['name'] : 'Unassigned';
                                @endphp
                            </p>
                        </div>
                        <div class="bg-gradient-to-r from-pink-50 to-rose-50 rounded-xl p-4 shadow-md border-2 border-pink-200/50">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-pink-700 text-xs font-semibold mb-1 uppercase tracking-wide">👥 Students</p>
                                    <p class="text-gray-800 font-bold text-lg" id="studentCount_{{ $class['id'] }}">
                                        <script>document.write((studentsData[{{ $class['id'] }}] ? studentsData[{{ $class['id'] }}].length : 0) + ' students');</script>
                                    </p>
                                </div>
                                <button onclick="showStudentsList({{ $class['id'] }})" class="bg-gradient-to-r from-pink-400 to-rose-400 hover:from-pink-500 hover:to-rose-500 text-white px-4 py-2 rounded-lg text-xs font-bold transition-all transform hover:scale-105 shadow-md">
                                    View List
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="grid grid-cols-2 gap-3">
                        <button onclick="showClassInfo({{ $class['id'] }})" class="bg-gradient-to-r from-cyan-50 to-teal-50 hover:from-cyan-100 hover:to-teal-100 text-cyan-700 font-bold py-3 rounded-xl transition-all transform hover:scale-105 shadow-md border-2 border-cyan-200/50 text-sm">
                            📋 Class Info
                        </button>
                        <button onclick="manageClass({{ $class['id'] }})" class="bg-gradient-to-r from-pink-50 to-rose-50 hover:from-pink-100 hover:to-rose-100 text-pink-700 font-bold py-3 rounded-xl transition-all transform hover:scale-105 shadow-md border-2 border-pink-200/50 text-sm">
                            ⚙️ Manage
                        </button>
                    </div>

                    <!-- Hover Effect Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-br from-pink-200/0 to-cyan-200/0 group-hover:from-pink-200/10 group-hover:to-cyan-200/10 rounded-3xl transition-all duration-300 pointer-events-none"></div>
                </div>
            @empty
                <div class="col-span-full bg-gradient-to-br from-pink-100/90 via-rose-50/80 to-cyan-100/90 backdrop-blur-lg rounded-3xl p-12 text-center border-2 border-pink-300/50 shadow-xl">
                    <div class="text-6xl mb-4">🏫</div>
                    <p class="text-gray-700 text-xl font-bold">No classes created yet</p>
                    <p class="text-gray-500 mt-2">Start by creating your first class!</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Students List Modal -->
    <div id="studentsModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl p-8 max-w-lg w-full shadow-2xl transform transition-all border-2 border-pink-200/50">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-pink-600 via-rose-500 to-cyan-600">👥 Students List</h2>
                <button onclick="closeStudentsList()" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>
            
            <div id="studentsContent" class="max-h-96 overflow-y-auto space-y-2">
                <!-- Content will be inserted here -->
            </div>

            <button onclick="closeStudentsList()" class="w-full mt-6 bg-gradient-to-r from-pink-400 to-rose-400 hover:from-pink-500 hover:to-rose-500 text-white font-bold py-3 rounded-xl transition-all transform hover:scale-105 shadow-lg border-2 border-pink-300/50">
                Close
            </button>
        </div>
    </div>

    <!-- Class Info Modal -->
    <div id="classInfoModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl p-8 max-w-2xl w-full shadow-2xl transform transition-all border-2 border-pink-200/50">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-pink-600 via-rose-500 to-cyan-600">📋 Class Information</h2>
                <button onclick="closeClassInfo()" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>
            
            <div id="classInfoContent" class="space-y-4">
                <!-- Content will be inserted here -->
            </div>

            <button onclick="closeClassInfo()" class="w-full mt-6 bg-gradient-to-r from-pink-400 to-rose-400 hover:from-pink-500 hover:to-rose-500 text-white font-bold py-3 rounded-xl transition-all transform hover:scale-105 shadow-lg border-2 border-pink-300/50">
                Close
            </button>
        </div>
    </div>

    <!-- Manage Class Modal -->
    <div id="manageClassModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl p-8 max-w-3xl w-full max-h-[90vh] overflow-y-auto shadow-2xl transform transition-all border-2 border-pink-200/50">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-pink-600 via-rose-500 to-cyan-600">⚙️ Manage Class Students</h2>
                <button onclick="closeManageClass()" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>
            

            <!-- Current Students List -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">👥 Current Students (<span id="studentCount" class="text-pink-600">0</span>)</h3>
                <div id="manageStudentsContent" class="space-y-3">
                    <!-- Content will be inserted here -->
                </div>
            </div>

            <button onclick="closeManageClass()" class="w-full mt-6 bg-gradient-to-r from-pink-400 to-rose-400 hover:from-pink-500 hover:to-rose-500 text-white font-bold py-3 rounded-xl transition-all transform hover:scale-105 shadow-lg border-2 border-pink-300/50">
                Done
            </button>
        </div>
    </div>


    <script>
        // Get actual student count from studentsData
        function getActualStudentCount(classId) {
            return studentsData[classId] ? studentsData[classId].length : 0;
        }

        function showStudentsList(classId) {
            const students = studentsData[classId] || [];
            let content = '';
            if (students.length > 0) {
                content = '<div class="space-y-2">';
                students.forEach((student, index) => {
                    content += `
                        <div class="flex items-center gap-3 p-4 bg-gradient-to-r from-pink-50 to-rose-50 rounded-xl hover:shadow-lg transition-all transform hover:scale-[1.02] border-2 border-pink-200/50 student-card">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-pink-400 to-rose-400 flex items-center justify-center text-white font-bold shadow-md">
                                ${index + 1}
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-gray-800">${student.name}</p>
                                <p class="text-xs text-gray-600 font-medium">${student.email}</p>
                            </div>
                            <div class="text-2xl">👤</div>
                        </div>
                    `;
                });
                content += '</div>';
            } else {
                content = '<div class="text-center py-8"><p class="text-gray-500">No students enrolled yet</p><p class="text-sm text-gray-400 mt-2">Students will appear here once they join this class</p></div>';
            }

            document.getElementById('studentsContent').innerHTML = content;
            document.getElementById('studentsModal').classList.remove('hidden');
        }

        function closeStudentsList() {
            document.getElementById('studentsModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('studentsModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeStudentsList();
            }
        });

        // Class info data - will be populated from controller
        const classesData = {
            @foreach($classes as $class)
            {{ $class['id'] }}: {
                name: "{{ $class['name'] }}",
                teacher: "@php $teacher = collect($teachers)->firstWhere('id', $class['teacherId']); echo $teacher ? $teacher['name'] : 'Unassigned'; @endphp",
                students: {{ $class['students'] }},
                capacity: {{ $class['capacity'] ?? 30 }},
                status: "{{ $class['status'] ?? 'active' }}",
                description: "{{ $class['description'] ?? 'No description available' }}"
            },
            @endforeach
        };

        function showClassInfo(classId) {
            const classInfo = classesData[classId];
            if (!classInfo) return;

            const statusColors = {
                'active': 'bg-green-100 text-green-800',
                'full': 'bg-yellow-100 text-yellow-800',
                'closed': 'bg-red-100 text-red-800',
                'empty': 'bg-gray-300 text-gray-900'
            };

            const content = `
                <div class="space-y-4">
                    <!-- Class Name -->
                    <div class="bg-gradient-to-r from-pink-50 to-purple-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500 mb-1">Class Name</p>
                        <p class="text-xl font-bold text-gray-800">${classInfo.name}</p>
                    </div>

                    <!-- Teacher -->
                    <div class="bg-blue-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500 mb-1">👨‍🏫 Teacher</p>
                        <p class="font-semibold text-gray-800">${classInfo.teacher}</p>
                    </div>

                    <!-- Grid: Students & Capacity -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-purple-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500 mb-1">👥 Current Students</p>
                            <p class="text-2xl font-bold text-purple-600">${classInfo.students}</p>
                        </div>
                        <div class="bg-pink-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500 mb-1">📊 Max Capacity</p>
                            <p class="text-2xl font-bold text-pink-600">${classInfo.capacity}</p>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500 mb-2">Status</p>
                        <span class="inline-block px-4 py-2 rounded-full font-semibold ${statusColors[classInfo.status] || statusColors['active']}">
                            ${classInfo.status.charAt(0).toUpperCase() + classInfo.status.slice(1)}
                        </span>
                    </div>

                    <!-- Description -->
                    <div class="bg-gradient-to-r from-pink-50 to-teal-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500 mb-2">📝 Description</p>
                        <p class="text-gray-700 leading-relaxed">${classInfo.description}</p>
                    </div>
                </div>
            `;

            document.getElementById('classInfoContent').innerHTML = content;
            document.getElementById('classInfoModal').classList.remove('hidden');
        }

        function closeClassInfo() {
            document.getElementById('classInfoModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('classInfoModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeClassInfo();
            }
        });

        // Manage Class Functions
        let currentClassId = null;

        function manageClass(classId) {
            currentClassId = classId;
            const students = studentsData[classId] || [];
            
            renderManageStudents(students);
            document.getElementById('manageClassModal').classList.remove('hidden');
        }

        function renderManageStudents(students) {
            document.getElementById('studentCount').textContent = students.length;
            
            let content = '';
            if (students.length > 0) {
                students.forEach((student, index) => {
                    content += `
                        <div class="flex items-center gap-3 p-4 bg-gradient-to-r from-pink-50 to-rose-50 rounded-xl hover:shadow-lg transition-all transform hover:scale-[1.02] border-2 border-pink-200/50 student-card">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-pink-400 to-rose-400 flex items-center justify-center text-white font-bold shadow-md">
                                ${index + 1}
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-gray-800">${student.name}</p>
                                <p class="text-xs text-gray-600 font-medium">${student.email}</p>
                            </div>
                            <button onclick="changeStudentClass(${student.id}, '${student.name}')" class="bg-gradient-to-r from-cyan-500 to-teal-500 hover:from-cyan-600 hover:to-teal-600 text-white px-4 py-2 rounded-xl font-bold transition-all transform hover:scale-105 shadow-md">
                                🔄 Change
                            </button>
                            <button onclick="removeStudent(${student.id})" class="bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white px-4 py-2 rounded-xl font-bold transition-all transform hover:scale-105 shadow-md">
                                🗑️ Remove
                            </button>
                        </div>
                    `;
                });
            } else {
                content = '<div class="text-center py-8"><p class="text-gray-500">No students enrolled yet</p></div>';
            }

            document.getElementById('manageStudentsContent').innerHTML = content;
        }

        function removeStudent(studentId) {
            if (!confirm('Are you sure you want to remove this student from the class?')) {
                return;
            }

            // Make AJAX call to backend to remove student
            fetch(`/admin/classes/${currentClassId}/remove-students`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ student_ids: [studentId] })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove student from local data and re-render
                    studentsData[currentClassId] = studentsData[currentClassId].filter(s => s.id !== studentId);
                    renderManageStudents(studentsData[currentClassId]);
                    alert('Student removed successfully!');
                } else {
                    alert('Failed to remove student: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(() => {
                alert('Failed to remove student due to network error.');
            });
        }
    <meta name="csrf-token" content="{{ csrf_token() }}">

        function changeStudentClass(studentId, studentName) {
            // Build class options HTML
            let classOptions = '';
            @foreach($classes as $class)
                if ({{ $class['id'] }} != currentClassId) {
                    classOptions += `<option value="{{ $class['id'] }}">{{ $class['name'] }}</option>`;
                }
            @endforeach

            // Create a custom dialog
            const dialog = `
                <div id="changeClassDialog" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[60] flex items-center justify-center p-4">
                    <div class="bg-white rounded-2xl p-8 max-w-md w-full shadow-2xl transform transition-all">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">🔄 Change Class</h3>
                        <p class="text-gray-600 mb-4">Move <strong>${studentName}</strong> to:</p>
                        <select id="targetClassSelect" class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 mb-6 focus:outline-none focus:border-blue-500">
                            <option value="">Select target class...</option>
                            ${classOptions}
                        </select>
                        <div class="flex gap-3">
                            <button onclick="confirmClassChange(${studentId})" class="flex-1 bg-gradient-to-r from-blue-500 to-purple-500 hover:shadow-lg text-white font-semibold py-3 rounded-lg transition-all">
                                Confirm
                            </button>
                            <button onclick="closeChangeClassDialog()" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-3 rounded-lg transition-all">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', dialog);
        }

        function confirmClassChange(studentId) {
            const targetClassId = document.getElementById('targetClassSelect').value;

            if (!targetClassId) {
                alert('Please select a target class');
                return;
            }

            // Find the student
            const student = studentsData[currentClassId].find(s => s.id === studentId);
            if (!student) return;

            // Remove from current class
            studentsData[currentClassId] = studentsData[currentClassId].filter(s => s.id !== studentId);

            // Add to target class
            if (!studentsData[targetClassId]) {
                studentsData[targetClassId] = [];
            }
            studentsData[targetClassId].push(student);

            // Re-render the current class list
            renderManageStudents(studentsData[currentClassId]);

            // Close dialog
            closeChangeClassDialog();

            // Show success message
            alert('Student moved to new class successfully!');
        }

        function closeChangeClassDialog() {
            const dialog = document.getElementById('changeClassDialog');
            if (dialog) {
                dialog.remove();
            }
        }

        function closeManageClass() {
            document.getElementById('manageClassModal').classList.add('hidden');
            currentClassId = null;
        }

        // Close modal when clicking outside
        document.getElementById('manageClassModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeManageClass();
            }
        });

</div>

<style>
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    
    @keyframes iconPulse {
        0%, 100% { transform: scale(1) rotate(0deg); }
        25% { transform: scale(1.05) rotate(-2deg); }
        75% { transform: scale(1.05) rotate(2deg); }
    }
    
    /* Header icon subtle animation on hover */
    .bg-gradient-to-br.from-pink-500:hover {
        animation: iconPulse 2s ease-in-out infinite;
    }
    
    /* Card hover animations */
    .group:hover {
        animation: none;
    }
    
    /* Smooth transitions for all interactive elements */
    button, a {
        transition: all 0.3s ease;
    }
    
    /* Modal backdrop animation */
    #studentsModal, #classInfoModal, #manageClassModal {
        animation: fadeIn 0.3s ease-out;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
    
    /* Student card styling in modals */
    .student-card {
        background: linear-gradient(135deg, #fdf2f8 0%, #fce7f3 100%);
        border: 2px solid rgba(236, 72, 153, 0.2);
    }
    
    .student-card:hover {
        background: linear-gradient(135deg, #fce7f3 0%, #fbcfe8 100%);
        border-color: rgba(236, 72, 153, 0.4);
        transform: translateX(4px);
    }
</style>
@endsection

