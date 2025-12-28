import React, { useState } from 'react';

const INITIAL_TEACHERS = [
  { id: 1, name: 'Sarah Johnson', subject: 'Math', classes: ['Grade 1 - A', 'Grade 2 - B'], status: 'available' },
  { id: 2, name: 'Ahmad Hassan', subject: 'English', classes: ['Grade 1 - C'], status: 'available' },
  { id: 3, name: 'Fatima Ali', subject: 'Science', classes: ['Grade 2 - A', 'Grade 3 - B'], status: 'available' },
  { id: 4, name: 'Michael Brown', subject: 'Math', classes: ['Grade 3 - C'], status: 'on-leave' },
];

const INITIAL_EMERGENCY_CASES = [
  { id: 1, teacher: 'Emma Wilson', classes: ['Grade 2 - D', 'Grade 3 - A'], reason: 'Medical Emergency', date: '2024-12-23' },
];

export default function EmergencyReassignment() {
  const [teachers] = useState(INITIAL_TEACHERS);
  const [emergencyCases, setEmergencyCases] = useState(INITIAL_EMERGENCY_CASES);
  const [reassignments, setReassignments] = useState([]);
  const [showModal, setShowModal] = useState(false);
  const [selectedCase, setSelectedCase] = useState(null);
  const [selectedTeacher, setSelectedTeacher] = useState('');

  const availableTeachers = teachers.filter((t) => t.status === 'available');

  const handleOpenReassign = (emergencyCase) => {
    setSelectedCase(emergencyCase);
    setSelectedTeacher('');
    setShowModal(true);
  };

  const handleReassign = () => {
    if (!selectedTeacher) {
      alert('Please select a teacher');
      return;
    }

    const teacher = teachers.find((t) => t.id === parseInt(selectedTeacher));
    setReassignments([
      ...reassignments,
      {
        id: Math.max(...reassignments.map((r) => r.id), 0) + 1,
        originalTeacher: selectedCase.teacher,
        replacementTeacher: teacher.name,
        classes: selectedCase.classes,
        date: new Date().toLocaleDateString(),
        status: 'active',
      },
    ]);

    setEmergencyCases(emergencyCases.filter((e) => e.id !== selectedCase.id));
    setShowModal(false);
  };

  return (
    <div className="space-y-8">
      {/* Emergency Cases */}
      <div>
        <div className="mb-6">
          <h2 className="text-3xl font-extrabold text-white mb-2">⚠️ Emergency Cases ({emergencyCases.length})</h2>
          <p className="text-pink-100">Teachers unable to teach - need immediate reassignment</p>
        </div>

        {emergencyCases.length === 0 ? (
          <div className="bg-white/10 backdrop-blur rounded-lg p-12 text-center text-white/70">
            <p className="text-lg">✅ All emergency cases have been handled!</p>
          </div>
        ) : (
          <div className="grid grid-cols-1 gap-4">
            {emergencyCases.map((emergencyCase) => (
              <div
                key={emergencyCase.id}
                className="bg-gradient-to-r from-red-500/20 to-orange-500/20 backdrop-blur border-2 border-red-400 rounded-xl p-6 hover:shadow-lg transition-all"
              >
                <div className="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                  <div className="flex-1">
                    <div className="flex items-center gap-3 mb-3">
                      <div className="text-4xl animate-pulse">🚨</div>
                      <div>
                        <h3 className="text-2xl font-bold text-white">{emergencyCase.teacher}</h3>
                        <p className="text-red-200">UNAVAILABLE</p>
                      </div>
                    </div>

                    <div className="grid grid-cols-2 gap-4 mt-4 text-white/90">
                      <div>
                        <p className="text-white/70 text-sm">Reason</p>
                        <p className="font-semibold text-red-200">{emergencyCase.reason}</p>
                      </div>
                      <div>
                        <p className="text-white/70 text-sm">Date</p>
                        <p className="font-semibold">{emergencyCase.date}</p>
                      </div>
                    </div>

                    <div className="mt-4">
                      <p className="text-white/70 text-sm mb-2">Affected Classes:</p>
                      <div className="flex flex-wrap gap-2">
                        {emergencyCase.classes.map((cls, idx) => (
                          <span key={idx} className="bg-red-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                            {cls}
                          </span>
                        ))}
                      </div>
                    </div>
                  </div>

                  <button
                    onClick={() => handleOpenReassign(emergencyCase)}
                    className="w-full lg:w-auto bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white font-semibold py-3 px-8 rounded-lg transition-all whitespace-nowrap"
                  >
                    🔄 Reassign Teacher
                  </button>
                </div>
              </div>
            ))}
          </div>
        )}
      </div>

      {/* Active Reassignments */}
      {reassignments.length > 0 && (
        <div>
          <div className="mb-6">
            <h2 className="text-3xl font-extrabold text-white mb-2">
              ✅ Active Reassignments ({reassignments.length})
            </h2>
            <p className="text-pink-100">Temporary teacher assignments</p>
          </div>

          <div className="grid grid-cols-1 gap-4">
            {reassignments.map((reassignment) => (
              <div key={reassignment.id} className="bg-gradient-to-r from-emerald-500/20 to-teal-500/20 backdrop-blur border border-emerald-300/30 rounded-xl p-6">
                <div className="flex items-start justify-between mb-4">
                  <div>
                    <h3 className="text-lg font-bold text-white mb-2">Emergency Coverage</h3>
                    <p className="text-white/80 text-sm">Reassigned: {reassignment.date}</p>
                  </div>
                  <span className="bg-emerald-600 text-white px-4 py-1 rounded-full text-xs font-semibold">
                    {reassignment.status.toUpperCase()}
                  </span>
                </div>

                <div className="grid grid-cols-1 lg:grid-cols-3 gap-4">
                  <div>
                    <p className="text-white/70 text-sm mb-1">Original Teacher</p>
                    <p className="text-white font-semibold">{reassignment.originalTeacher}</p>
                  </div>

                  <div>
                    <p className="text-white/70 text-sm mb-1">Replacement Teacher</p>
                    <p className="text-white font-semibold text-emerald-300">{reassignment.replacementTeacher}</p>
                  </div>

                  <div>
                    <p className="text-white/70 text-sm mb-1">Classes Covered</p>
                    <div className="flex flex-wrap gap-1">
                      {reassignment.classes.map((cls, idx) => (
                        <span key={idx} className="bg-emerald-600 text-white px-2 py-1 rounded text-xs font-semibold">
                          {cls}
                        </span>
                      ))}
                    </div>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      )}

      {/* Modal */}
      {showModal && selectedCase && (
        <div className="fixed inset-0 bg-black/50 backdrop-blur flex items-center justify-center z-50">
          <div className="bg-white rounded-2xl p-8 max-w-lg w-full mx-4 shadow-2xl">
            <h2 className="text-2xl font-bold text-gray-800 mb-2">Reassign Teacher</h2>
            <p className="text-gray-600 mb-6">
              Emergency: <span className="font-semibold text-red-600">{selectedCase.teacher}</span> is unavailable
            </p>

            <div className="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
              <p className="text-sm text-gray-700 mb-2">
                <span className="font-semibold">Affected Classes:</span>
              </p>
              <div className="flex flex-wrap gap-2">
                {selectedCase.classes.map((cls, idx) => (
                  <span key={idx} className="bg-red-200 text-red-800 px-3 py-1 rounded text-sm font-semibold">
                    {cls}
                  </span>
                ))}
              </div>
            </div>

            <div className="mb-6">
              <label className="block text-sm font-semibold text-gray-700 mb-2">
                Select Available Teacher
              </label>
              <select
                value={selectedTeacher}
                onChange={(e) => setSelectedTeacher(e.target.value)}
                className="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-pink-500"
              >
                <option value="">Choose a teacher...</option>
                {availableTeachers.map((teacher) => (
                  <option key={teacher.id} value={teacher.id}>
                    {teacher.name} ({teacher.subject})
                  </option>
                ))}
              </select>

              {availableTeachers.length === 0 && (
                <div className="mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                  <p className="text-sm text-yellow-800">⚠️ No available teachers. Consider extending school hours or hiring temporary staff.</p>
                </div>
              )}
            </div>

            <div className="flex gap-3">
              <button
                onClick={() => {
                  setShowModal(false);
                  setSelectedCase(null);
                }}
                className="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 rounded-lg transition-all"
              >
                Cancel
              </button>
              <button
                onClick={handleReassign}
                disabled={!selectedTeacher}
                className="flex-1 bg-gradient-to-r from-emerald-500 to-teal-500 disabled:opacity-50 disabled:cursor-not-allowed hover:from-emerald-600 hover:to-teal-600 text-white font-semibold py-2 rounded-lg transition-all"
              >
                ✓ Confirm Reassignment
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
