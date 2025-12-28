import React, { useState } from 'react';

const INITIAL_LEVELS = [
  { id: 1, name: 'Pre Lesson', color: 'from-yellow-300 to-orange-300' },
  { id: 2, name: 'Lesson K', color: 'from-blue-300 to-cyan-300' },
  { id: 3, name: 'Lesson 1', color: 'from-pink-300 to-rose-300' },
  { id: 4, name: 'Lesson 2', color: 'from-purple-300 to-indigo-300' },
];

const INITIAL_LESSONS = [
  { id: 1, levelId: 1, title: 'Addition', skills: 21, icon: '➕', image: 'bg-gradient-to-br from-yellow-200 to-lime-200' },
  { id: 2, levelId: 1, title: 'Subtraction', skills: 8, icon: '➖', image: 'bg-gradient-to-br from-blue-200 to-cyan-200' },
  { id: 3, levelId: 2, title: 'Multiplication', skills: 15, icon: '✖️', image: 'bg-gradient-to-br from-pink-200 to-rose-200' },
  { id: 4, levelId: 2, title: 'Division', skills: 10, icon: '➗', image: 'bg-gradient-to-br from-purple-200 to-indigo-200' },
  { id: 5, levelId: 3, title: 'Fractions', skills: 20, icon: '½', image: 'bg-gradient-to-br from-green-200 to-teal-200' },
];

const COLORS = [
  'from-yellow-200 to-lime-200',
  'from-blue-200 to-cyan-200',
  'from-pink-200 to-rose-200',
  'from-purple-200 to-indigo-200',
  'from-green-200 to-teal-200',
  'from-orange-200 to-red-200',
];

export default function LessonsManager() {
  const [lessons, setLessons] = useState(INITIAL_LESSONS);
  const [levels, setLevels] = useState(INITIAL_LEVELS);
  const [editingLesson, setEditingLesson] = useState(null);
  const [showModal, setShowModal] = useState(false);
  const [formData, setFormData] = useState({ title: '', skills: '', levelId: '1', icon: '' });

  const handleEdit = (lesson) => {
    setEditingLesson(lesson);
    setFormData({
      title: lesson.title,
      skills: lesson.skills,
      levelId: lesson.levelId,
      icon: lesson.icon,
    });
    setShowModal(true);
  };

  const handleDelete = (id) => {
    if (confirm('Are you sure you want to delete this lesson?')) {
      setLessons(lessons.filter((l) => l.id !== id));
    }
  };

  const handleSave = () => {
    if (!formData.title || !formData.skills) {
      alert('Please fill in all fields');
      return;
    }

    if (editingLesson) {
      setLessons(
        lessons.map((l) =>
          l.id === editingLesson.id
            ? {
                ...l,
                title: formData.title,
                skills: parseInt(formData.skills),
                levelId: parseInt(formData.levelId),
                icon: formData.icon,
              }
            : l
        )
      );
    } else {
      const colorIndex = lessons.length % COLORS.length;
      setLessons([
        ...lessons,
        {
          id: Math.max(...lessons.map((l) => l.id), 0) + 1,
          title: formData.title,
          skills: parseInt(formData.skills),
          levelId: parseInt(formData.levelId),
          icon: formData.icon,
          image: COLORS[colorIndex],
        },
      ]);
    }

    setShowModal(false);
    setEditingLesson(null);
    setFormData({ title: '', skills: '', levelId: '1', icon: '' });
  };

  const handleOpenNew = () => {
    setEditingLesson(null);
    setFormData({ title: '', skills: '', levelId: '1', icon: '' });
    setShowModal(true);
  };

  const groupedLessons = levels.map((level) => ({
    level,
    lessons: lessons.filter((l) => l.levelId === level.id),
  }));

  return (
    <div className="space-y-8">
      {/* Add Button */}
      <div className="flex justify-end">
        <button
          onClick={handleOpenNew}
          className="bg-gradient-to-r from-pink-500 to-teal-400 text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition-all"
        >
          + Add New Lesson
        </button>
      </div>

      {/* Levels with Lessons */}
      {groupedLessons.map(({ level, lessons: levelLessons }) => (
        <div key={level.id} className="space-y-4">
          <div className={`bg-gradient-to-r ${level.color} rounded-lg p-6 shadow-lg`}>
            <h2 className="text-2xl font-extrabold text-gray-800">{level.name}</h2>
            <p className="text-gray-700">Math Curriculum</p>
          </div>

          {levelLessons.length === 0 ? (
            <div className="bg-white/10 backdrop-blur rounded-lg p-8 text-center text-white/70">
              No lessons for this level. Add one to get started!
            </div>
          ) : (
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              {levelLessons.map((lesson) => (
                <div
                  key={lesson.id}
                  className={`bg-gradient-to-br ${lesson.image} rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all transform hover:scale-105`}
                >
                  {/* Card Header */}
                  <div className="flex justify-between items-start mb-4">
                    <div className="text-5xl">{lesson.icon || '📖'}</div>
                    <div className="flex gap-2">
                      <button
                        onClick={() => handleEdit(lesson)}
                        className="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-lg transition-all"
                        title="Edit"
                      >
                        ✏️
                      </button>
                      <button
                        onClick={() => handleDelete(lesson.id)}
                        className="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition-all"
                        title="Delete"
                      >
                        🗑️
                      </button>
                    </div>
                  </div>

                  {/* Card Content */}
                  <h3 className="text-xl font-bold text-gray-800 mb-2">{lesson.title}</h3>
                  <div className="bg-black/20 rounded-lg px-3 py-2 inline-block">
                    <span className="text-gray-700 font-semibold">{lesson.skills} skills</span>
                  </div>
                </div>
              ))}
            </div>
          )}
        </div>
      ))}

      {/* Modal */}
      {showModal && (
        <div className="fixed inset-0 bg-black/50 backdrop-blur flex items-center justify-center z-50">
          <div className="bg-white rounded-2xl p-8 max-w-lg w-full mx-4 shadow-2xl">
            <h2 className="text-2xl font-bold text-gray-800 mb-6">
              {editingLesson ? 'Edit Lesson' : 'Add New Lesson'}
            </h2>

            <div className="space-y-4">
              <div>
                <label className="block text-sm font-semibold text-gray-700 mb-2">Lesson Title</label>
                <input
                  type="text"
                  value={formData.title}
                  onChange={(e) => setFormData({ ...formData, title: e.target.value })}
                  className="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-500"
                  placeholder="e.g., Addition"
                />
              </div>

              <div>
                <label className="block text-sm font-semibold text-gray-700 mb-2">Icon Emoji</label>
                <input
                  type="text"
                  value={formData.icon}
                  onChange={(e) => setFormData({ ...formData, icon: e.target.value })}
                  maxLength="2"
                  className="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-500"
                  placeholder="e.g., ➕"
                />
              </div>

              <div>
                <label className="block text-sm font-semibold text-gray-700 mb-2">Number of Skills</label>
                <input
                  type="number"
                  value={formData.skills}
                  onChange={(e) => setFormData({ ...formData, skills: e.target.value })}
                  className="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-500"
                  placeholder="e.g., 21"
                />
              </div>

              <div>
                <label className="block text-sm font-semibold text-gray-700 mb-2">Grade Level</label>
                <select
                  value={formData.levelId}
                  onChange={(e) => setFormData({ ...formData, levelId: e.target.value })}
                  className="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-500"
                >
                  {levels.map((level) => (
                    <option key={level.id} value={level.id}>
                      {level.name}
                    </option>
                  ))}
                </select>
              </div>
            </div>

            <div className="flex gap-3 mt-6">
              <button
                onClick={() => {
                  setShowModal(false);
                  setEditingLesson(null);
                }}
                className="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 rounded-lg transition-all"
              >
                Cancel
              </button>
              <button
                onClick={handleSave}
                className="flex-1 bg-gradient-to-r from-pink-500 to-teal-400 hover:shadow-lg text-white font-semibold py-2 rounded-lg transition-all"
              >
                {editingLesson ? 'Update' : 'Create'}
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
