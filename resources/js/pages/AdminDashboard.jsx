import React, { useState } from 'react';
import LessonsManager from '../components/Admin/LessonsManager';
import ClassesManager from '../components/Admin/ClassesManager';
import TeacherRequestsManager from '../components/Admin/TeacherRequestsManager';
import EmergencyReassignment from '../components/Admin/EmergencyReassignment';

export default function AdminDashboard() {
  const [activeTab, setActiveTab] = useState('lessons');

  const tabs = [
    { id: 'lessons', label: 'Lessons', icon: '📚' },
    { id: 'classes', label: 'Classes', icon: '🏫' },
    { id: 'requests', label: 'Teacher Requests', icon: '✓' },
    { id: 'emergency', label: 'Emergency', icon: '⚠️' },
  ];

  return (
    <div className="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
      {/* Header */}
      <div className="bg-gradient-to-r from-pink-300 via-pink-200 to-teal-300 shadow-xl">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
          <h1 className="text-4xl font-extrabold text-white mb-2">Admin Dashboard</h1>
          <p className="text-pink-50">Manage lessons, classes, and teacher assignments</p>
        </div>
      </div>

      {/* Tab Navigation */}
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
        <div className="flex gap-2 flex-wrap">
          {tabs.map((tab) => (
            <button
              key={tab.id}
              onClick={() => setActiveTab(tab.id)}
              className={`px-6 py-3 rounded-lg font-semibold transition-all ${
                activeTab === tab.id
                  ? 'bg-gradient-to-r from-pink-300 to-teal-300 text-white shadow-lg'
                  : 'bg-white/10 text-white hover:bg-white/20'
              }`}
            >
              <span className="mr-2">{tab.icon}</span>
              {tab.label}
            </button>
          ))}
        </div>
      </div>

      {/* Content Area */}
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {activeTab === 'lessons' && <LessonsManager />}
        {activeTab === 'classes' && <ClassesManager />}
        {activeTab === 'requests' && <TeacherRequestsManager />}
        {activeTab === 'emergency' && <EmergencyReassignment />}
      </div>
    </div>
  );
}
