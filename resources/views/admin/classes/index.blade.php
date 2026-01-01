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
                <div class="flex items-center gap-3 p-3 bg-gradient-to-r from-pink-50 to-purple-50 rounded-lg hover:shadow-md transition-all">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-pink-400 to-purple-400 flex items-center justify-center text-white font-bold">
                        ${index + 1}
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800">${student.name}</p>
                        <p class="text-xs text-gray-500">${student.email}</p>
                    </div>
                    <div class="text-pink-500">👤</div>
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
        'closed': 'bg-red-100 text-red-800'
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
                <div class="flex items-center gap-3 p-4 bg-gradient-to-r from-pink-50 to-purple-50 rounded-lg hover:shadow-md transition-all">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-pink-400 to-purple-400 flex items-center justify-center text-white font-bold">
                        ${index + 1}
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800">${student.name}</p>
                        <p class="text-xs text-gray-500">${student.email}</p>
                    </div>
                    <button onclick="changeStudentClass(${student.id}, '${student.name}')" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold transition-all">🔄 Change Class</button>
                    <button onclick="removeStudent(${student.id})" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-semibold transition-all">🗑️ Remove</button>
                </div>
            `;
        });
    } else {
        content = '<div class="text-center py-8"><p class="text-gray-500">No students enrolled yet</p><p class="text-sm text-gray-400 mt-2">Add students using the form above</p></div>';
    }
    document.getElementById('manageStudentsContent').innerHTML = content;
}
window.renderManageStudents = renderManageStudents;

function addStudentToClass() {
    const select = document.getElementById('newStudentSelect');
    const studentId = select.value;
    const studentName = select.options[select.selectedIndex].text;
    if (!studentId) {
        alert('Please select a student');
        return;
    }
    const students = studentsData[currentClassId] || [];
    if (students.find(s => s.id == studentId)) {
        alert('Student is already in this class');
        return;
    }
    // Send POST request to backend to update class_id
    fetch(`/admin/classes/${currentClassId}/students/add`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({ student_ids: [parseInt(studentId)] })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const newStudent = {
                id: parseInt(studentId),
                name: studentName,
                email: studentName.toLowerCase().replace(' ', '') + '@student.com'
            };
            if (!studentsData[currentClassId]) {
                studentsData[currentClassId] = [];
            }
            studentsData[currentClassId].push(newStudent);
            renderManageStudents(studentsData[currentClassId]);
            select.value = '';
            alert('Student added successfully!');
        } else {
            alert('Failed to add student: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(() => {
        alert('Failed to add student due to network error.');
    });
}
window.addStudentToClass = addStudentToClass;

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
    const student = studentsData[currentClassId].find(s => s.id === studentId);
    if (!student) return;
    studentsData[currentClassId] = studentsData[currentClassId].filter(s => s.id !== studentId);
    if (!studentsData[targetClassId]) {
        studentsData[targetClassId] = [];
    }
    studentsData[targetClassId].push(student);
    renderManageStudents(studentsData[currentClassId]);
    closeChangeClassDialog();
    alert('Student moved to new class successfully!');
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
<div class="min-h-screen">
    <!-- Header -->
    <div class="bg-gradient-to-r from-[#FC8EAC] via-[#EC769A] to-[#6EC6C5] shadow-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-extrabold text-white mb-2">🏫 Classes Manager</h1>
                <p class="text-pink-100">Create and manage your classes</p>
            </div>
            <a href="{{ route('admin.classes.create') }}">
                <span class="bg-[#EC769A] hover:bg-[#FC8EAC] text-white px-8 py-4 rounded-xl font-bold shadow-xl transition-all flex items-center gap-2 text-lg" style="box-shadow: 0 8px 24px 0 rgba(236,118,154,0.18);">+ Add New Class</span>
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-emerald-100 border border-emerald-400 rounded-lg p-4 text-emerald-700 font-medium">
                ✓ {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- Classes Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($classes as $class)
                <div class="bg-gradient-to-br {{ $class['color_gradient'] ?? 'from-pink-100 to-pink-200' }} rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all transform hover:scale-105">
                    <!-- Card Header -->
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-white mb-1">{{ $class['name'] }}</h3>
                            <p class="text-white/90 text-sm">Grade {{ $class['grade'] }}</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.classes.edit', $class['id']) }}" class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-lg transition-all">
                                ✏️
                            </a>
                            <form method="POST" action="{{ route('admin.classes.delete', $class['id']) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Delete this class?')" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition-all">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Card Stats -->
                    <div class="space-y-3 mb-4">
                        <div class="bg-white/30 backdrop-blur rounded-lg p-3">
                            <p class="text-white/80 text-sm font-medium">👨‍🏫 Teacher</p>
                            <p class="text-white font-semibold">
                                @php
                                    $teacher = collect($teachers)->firstWhere('id', $class['teacherId']);
                                    echo $teacher ? $teacher['name'] : 'Unassigned';
                                @endphp
                            </p>
                        </div>
                        <div class="bg-white/30 backdrop-blur rounded-lg p-3">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-white/80 text-sm font-medium">👥 Students</p>
                                    <p class="text-white font-semibold" id="studentCount_{{ $class['id'] }}">
                                        <script>document.write((studentsData[{{ $class['id'] }}] ? studentsData[{{ $class['id'] }}].length : 0) + ' students');</script>
                                    </p>
                                </div>
                                <button onclick="showStudentsList({{ $class['id'] }})" class="bg-white/50 hover:bg-white text-gray-800 px-3 py-1 rounded-lg text-xs font-semibold transition-all">
                                    View List
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="grid grid-cols-2 gap-2">
                        <!-- Removed old Class Info button and re-added below for clean markup and event binding -->
                        <button onclick="showClassInfo({{ $class['id'] }})" class="bg-white/90 text-gray-800 font-semibold py-2 rounded-lg hover:bg-white transition-all text-sm">
                            📋 Class Info
                        </button>
                        <button onclick="manageClass({{ $class['id'] }})" class="bg-white/90 text-gray-800 font-semibold py-2 rounded-lg hover:bg-white transition-all text-sm">
                            ⚙️ Manage Class
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white/10 backdrop-blur rounded-lg p-8 text-center text-white/70">
                    No classes created yet. Add your first class!
                </div>
            @endforelse
        </div>
    </div>

    <!-- Students List Modal -->
    <div id="studentsModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl p-8 max-w-lg w-full shadow-2xl transform transition-all">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Students List</h2>
                <button onclick="closeStudentsList()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            
            <div id="studentsContent" class="max-h-96 overflow-y-auto">
                <!-- Content will be inserted here -->
            </div>

            <button onclick="closeStudentsList()" class="w-full mt-6 bg-gradient-to-r from-pink-500 to-teal-400 hover:shadow-lg text-white font-semibold py-3 rounded-lg transition-all">
                Close
            </button>
        </div>
    </div>

    <!-- Class Info Modal -->
    <div id="classInfoModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl p-8 max-w-2xl w-full shadow-2xl transform transition-all">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">📋 Class Information</h2>
                <button onclick="closeClassInfo()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            
            <div id="classInfoContent" class="space-y-4">
                <!-- Content will be inserted here -->
            </div>

            <button onclick="closeClassInfo()" class="w-full mt-6 bg-gradient-to-r from-pink-500 to-teal-400 hover:shadow-lg text-white font-semibold py-3 rounded-lg transition-all">
                Close
            </button>
        </div>
    </div>

    <!-- Manage Class Modal -->
    <div id="manageClassModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl p-8 max-w-3xl w-full max-h-[90vh] overflow-y-auto shadow-2xl transform transition-all">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">⚙️ Manage Class Students</h2>
                <button onclick="closeManageClass()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            
            <!-- Add Student Section -->
            <div class="bg-gradient-to-r from-pink-50 to-purple-50 rounded-xl p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">➕ Add New Student</h3>
                <div class="flex gap-3">
                    <select id="newStudentSelect" class="flex-1 border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-500">
                        <option value="">Select a student...</option>
                        <option value="1">Amina Ahmed</option>
                        <option value="2">Fatima Hassan</option>
                        <option value="3">Zainab Ali</option>
                        <option value="4">Mariam Ibrahim</option>
                        <option value="5">Aisha Mohamed</option>
                        <option value="6">Sara Ahmed</option>
                        <option value="7">Layla Hassan</option>
                        <option value="8">Huda Ali</option>
                        <option value="9">Noor Ibrahim</option>
                        <option value="10">Yasmin Mohamed</option>
                    </select>
                    <button onclick="addStudentToClass()" class="bg-gradient-to-r from-pink-500 to-purple-500 hover:shadow-lg text-white font-semibold px-6 py-2 rounded-lg transition-all">
                        Add Student
                    </button>
                </div>
            </div>

            <!-- Current Students List -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4">👥 Current Students (<span id="studentCount">0</span>)</h3>
                <div id="manageStudentsContent" class="space-y-2">
                    <!-- Content will be inserted here -->
                </div>
            </div>

            <button onclick="closeManageClass()" class="w-full mt-6 bg-gradient-to-r from-pink-500 to-teal-400 hover:shadow-lg text-white font-semibold py-3 rounded-lg transition-all">
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
                        <div class="flex items-center gap-3 p-3 bg-gradient-to-r from-pink-50 to-purple-50 rounded-lg hover:shadow-md transition-all">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-pink-400 to-purple-400 flex items-center justify-center text-white font-bold">
                                ${index + 1}
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800">${student.name}</p>
                                <p class="text-xs text-gray-500">${student.email}</p>
                            </div>
                            <div class="text-pink-500">
                                👤
                            </div>
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
                'closed': 'bg-red-100 text-red-800'
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
                        <div class="flex items-center gap-3 p-4 bg-gradient-to-r from-pink-50 to-purple-50 rounded-lg hover:shadow-md transition-all">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-pink-400 to-purple-400 flex items-center justify-center text-white font-bold">
                                ${index + 1}
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800">${student.name}</p>
                                <p class="text-xs text-gray-500">${student.email}</p>
                            </div>
                            <button onclick="changeStudentClass(${student.id}, '${student.name}')" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold transition-all">
                                🔄 Change Class
                            </button>
                            <button onclick="removeStudent(${student.id})" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-semibold transition-all">
                                🗑️ Remove
                            </button>
                        </div>
                    `;
                });
            } else {
                content = '<div class="text-center py-8"><p class="text-gray-500">No students enrolled yet</p><p class="text-sm text-gray-400 mt-2">Add students using the form above</p></div>';
            }

            document.getElementById('manageStudentsContent').innerHTML = content;
        }

        function addStudentToClass() {
            const select = document.getElementById('newStudentSelect');
            const studentId = select.value;
            const studentName = select.options[select.selectedIndex].text;

            if (!studentId) {
                alert('Please select a student');
                return;
            }

            // Check if student already in class
            const students = studentsData[currentClassId] || [];
            if (students.find(s => s.id == studentId)) {
                alert('Student is already in this class');
                return;
            }

            // Add student to class (in real app, this would be an API call)
            const newStudent = {
                id: parseInt(studentId),
                name: studentName,
                email: studentName.toLowerCase().replace(' ', '') + '@student.com'
            };

            if (!studentsData[currentClassId]) {
                studentsData[currentClassId] = [];
            }
            studentsData[currentClassId].push(newStudent);

            // Re-render the list
            renderManageStudents(studentsData[currentClassId]);

            // Reset select
            select.value = '';

            // Show success message
            alert('Student added successfully!');
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
@endsection

