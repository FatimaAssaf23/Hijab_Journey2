import React, { useState } from 'react';

const INITIAL_TEACHERS = [
  { id: 1, name: 'Sarah Johnson', email: 'sarah@school.com', subject: 'Math' },
  { id: 2, name: 'Ahmad Hassan', email: 'ahmad@school.com', subject: 'English' },
  { id: 3, name: 'Fatima Ali', email: 'fatima@school.com', subject: 'Science' },
  { id: 4, name: 'Michael Brown', email: 'michael@school.com', subject: 'Math' },
];

const INITIAL_CLASSES = [
  { id: 1, name: 'Grade 1 - Section A', teacherId: 1, grade: 1, students: 25, color: 'from-pink-400 to-rose-400' },
  { id: 2, name: 'Grade 2 - Section B', teacherId: 2, grade: 2, students: 28, color: 'from-cyan-400 to-teal-400' },
  { id: 3, name: 'Grade 1 - Section C', teacherId: 3, grade: 1, students: 23, color: 'from-purple-400 to-indigo-400' },
];

const CLASS_COLORS = [
  'from-pink-400 to-rose-400',
  'from-cyan-400 to-teal-400',
  'from-purple-400 to-indigo-400',
  'from-amber-400 to-orange-400',
  'from-emerald-400 to-green-400',
];

export default function ClassesManager() {
  const [classes, setClasses] = useState(INITIAL_CLASSES);
  const [teachers] = useState(INITIAL_TEACHERS);
  const [editingClass, setEditingClass] = useState(null);
  const [showModal, setShowModal] = useState(false);
  const [formData, setFormData] = useState({
    name: '',
    grade: '',
    students: '',
    teacherId: '',
  });

  const getTeacherName = (teacherId) => {
    return teachers.find((t) => t.id === teacherId)?.name || 'Unassigned';
  };

  const handleEdit = (classItem) => {
    setEditingClass(classItem);
    setFormData({
      name: classItem.name,
      grade: classItem.grade,
      students: classItem.students,
      teacherId: classItem.teacherId,
    });
    setShowModal(true);
  };

  const handleDelete = (id) => {
    if (confirm('Are you sure you want to delete this class?')) {
      setClasses(classes.filter((c) => c.id !== id));
    }
  };

  const handleSave = () => {
    if (!formData.name || !formData.grade || !formData.students || !formData.teacherId) {
      alert('Please fill in all fields');
      return;
    }

    if (editingClass) {
      setClasses(
        classes.map((c) =>
          c.id === editingClass.id
            ? {
                ...c,
                name: formData.name,
                grade: parseInt(formData.grade),
                students: parseInt(formData.students),
                teacherId: parseInt(formData.teacherId),
              }
            : c
        )
      );
    } else {
      const colorIndex = classes.length % CLASS_COLORS.length;
      setClasses([
        ...classes,
        {
          id: Math.max(...classes.map((c) => c.id), 0) + 1,
          name: formData.name,
          grade: parseInt(formData.grade),
          students: parseInt(formData.students),
          teacherId: parseInt(formData.teacherId),
          color: CLASS_COLORS[colorIndex],
        },
      ]);
    }

    setShowModal(false);
    setEditingClass(null);
    setFormData({ name: '', grade: '', students: '', teacherId: '' });
  };

  const handleOpenNew = () => {
    setEditingClass(null);
    setFormData({ name: '', grade: '', students: '', teacherId: teachers[0]?.id || '' });
    setShowModal(true);
  };

  return (
    <div className="space-y-6">
      {/* Add Button */}
      <div className="flex justify-end">
        <button
          onClick={handleOpenNew}
          className="bg-gradient-to-r from-pink-500 to-teal-400 text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition-all"
        >
          + Add New Class
        </button>
      </div>

      {/* Classes Grid */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {classes.map((classItem) => (
          <div
            key={classItem.id}
            className={`bg-gradient-to-br ${classItem.color} rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all transform hover:scale-105`}
          >
            {/* Card Header */}
            <div className="flex justify-between items-start mb-4">
              <div className="flex-1">
                <h3 className="text-2xl font-bold text-white mb-1">{classItem.name}</h3>
                <p className="text-white/90 text-sm">Grade {classItem.grade}</p>
              </div>
              <div className="flex gap-2">
                <button
                  onClick={() => handleEdit(classItem)}
                  className="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-lg transition-all"
                  title="Edit"
                >
                  ✏️
                </button>
                <button
                  onClick={() => handleDelete(classItem.id)}
                  className="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition-all"
                  title="Delete"
                >
                  🗑️
                </button>
              </div>
            </div>

            {/* Card Stats */}
            <div className="space-y-3 mb-4">
              <div className="bg-white/30 backdrop-blur rounded-lg p-3">
                <p className="text-white/80 text-sm font-medium">👨‍🏫 Teacher</p>
                <p className="text-white font-semibold">{getTeacherName(classItem.teacherId)}</p>
              </div>
              <div className="bg-white/30 backdrop-blur rounded-lg p-3">
                <p className="text-white/80 text-sm font-medium">👥 Students</p>
                <p className="text-white font-semibold">{classItem.students} students</p>
              </div>
            </div>

            {/* Action Button */}
            <button className="w-full bg-white/90 text-gray-800 font-semibold py-2 rounded-lg hover:bg-white transition-all">
              Manage Class
            </button>
          </div>
        ))}

        {classes.length === 0 && (
          <div className="col-span-full bg-white/10 backdrop-blur rounded-lg p-8 text-center text-white/70">
            No classes created yet. Add your first class!
          </div>
        )}
      </div>

      {/* Modal */}
      {showModal && (
        <div className="fixed inset-0 bg-black/50 backdrop-blur flex items-center justify-center z-50">
          <div className="bg-white rounded-2xl p-8 max-w-lg w-full mx-4 shadow-2xl">
            <h2 className="text-2xl font-bold text-gray-800 mb-6">
              {editingClass ? 'Edit Class' : 'Add New Class'}
            </h2>

            <div className="space-y-4">
              <div>
                <label className="block text-sm font-semibold text-gray-700 mb-2">Class Name</label>
                <input
                  type="text"
                  value={formData.name}
                  onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                  className="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-500"
                  placeholder="e.g., Grade 1 - Section A"
                />
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-sm font-semibold text-gray-700 mb-2">Grade</label>
                  <input
                    type="number"
                    value={formData.grade}
                    onChange={(e) => setFormData({ ...formData, grade: e.target.value })}
                    className="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-500"
                    placeholder="e.g., 1"
                  />
                </div>

                <div>
                  <label className="block text-sm font-semibold text-gray-700 mb-2">Students</label>
                  <input
                    type="number"
                    value={formData.students}
                    onChange={(e) => setFormData({ ...formData, students: e.target.value })}
                    className="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-500"
                    placeholder="e.g., 25"
                  />
                </div>
              </div>

              <div>
                <label className="block text-sm font-semibold text-gray-700 mb-2">Assign Teacher</label>
                <select
                  value={formData.teacherId}
                  onChange={(e) => setFormData({ ...formData, teacherId: e.target.value })}
                  className="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-500"
                >
                  {teachers.map((teacher) => (
                    <option key={teacher.id} value={teacher.id}>
                      {teacher.name} ({teacher.subject})
                    </option>
                  ))}
                </select>
              </div>
            </div>

            <div className="flex gap-3 mt-6">
              <button
                onClick={() => {
                  setShowModal(false);
                  setEditingClass(null);
                }}
                className="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 rounded-lg transition-all"
              >
                Cancel
              </button>
              <button
                onClick={handleSave}
                className="flex-1 bg-gradient-to-r from-pink-500 to-teal-400 hover:shadow-lg text-white font-semibold py-2 rounded-lg transition-all"
              >
                {editingClass ? 'Update' : 'Create'}
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
