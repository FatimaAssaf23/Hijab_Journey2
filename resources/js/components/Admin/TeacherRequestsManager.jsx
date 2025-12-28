import React, { useState } from 'react';

const INITIAL_REQUESTS = [
  {
    id: 1,
    name: 'Zainab Ahmed',
    email: 'zainab@school.com',
    subject: 'Arabic',
    experience: '5 years',
    status: 'pending',
    appliedAt: '2024-12-20',
  },
  {
    id: 2,
    name: 'Hassan Mohamed',
    email: 'hassan@school.com',
    subject: 'Islamic Studies',
    experience: '3 years',
    status: 'pending',
    appliedAt: '2024-12-19',
  },
  {
    id: 3,
    name: 'Layla Ibrahim',
    email: 'layla@school.com',
    subject: 'English',
    experience: '7 years',
    status: 'pending',
    appliedAt: '2024-12-18',
  },
];

export default function TeacherRequestsManager() {
  const [requests, setRequests] = useState(INITIAL_REQUESTS);
  const [approvedRequests, setApprovedRequests] = useState([]);
  const [rejectedRequests, setRejectedRequests] = useState([]);

  const handleApprove = (id) => {
    const request = requests.find((r) => r.id === id);
    if (request) {
      setApprovedRequests([...approvedRequests, { ...request, approvedAt: new Date().toLocaleDateString() }]);
      setRequests(requests.filter((r) => r.id !== id));
    }
  };

  const handleReject = (id) => {
    const request = requests.find((r) => r.id === id);
    if (request) {
      setRejectedRequests([...rejectedRequests, { ...request, rejectedAt: new Date().toLocaleDateString() }]);
      setRequests(requests.filter((r) => r.id !== id));
    }
  };

  return (
    <div className="space-y-8">
      {/* Pending Requests */}
      <div>
        <div className="mb-6">
          <h2 className="text-3xl font-extrabold text-white mb-2">
            ⏳ Pending Requests ({requests.length})
          </h2>
          <p className="text-pink-100">Review and approve new teacher applications</p>
        </div>

        {requests.length === 0 ? (
          <div className="bg-white/10 backdrop-blur rounded-lg p-12 text-center text-white/70">
            <p className="text-lg">✅ All requests have been processed!</p>
          </div>
        ) : (
          <div className="grid grid-cols-1 gap-4">
            {requests.map((request) => (
              <div key={request.id} className="bg-gradient-to-r from-pink-500/20 to-teal-400/20 backdrop-blur border border-pink-300/30 rounded-xl p-6 hover:border-pink-300/60 transition-all">
                <div className="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                  <div className="flex-1">
                    <h3 className="text-xl font-bold text-white mb-2">{request.name}</h3>
                    <div className="grid grid-cols-2 gap-4 text-white/90 text-sm">
                      <div>
                        <p className="text-white/70">Subject</p>
                        <p className="font-semibold">{request.subject}</p>
                      </div>
                      <div>
                        <p className="text-white/70">Experience</p>
                        <p className="font-semibold">{request.experience}</p>
                      </div>
                      <div>
                        <p className="text-white/70">Email</p>
                        <p className="font-semibold">{request.email}</p>
                      </div>
                      <div>
                        <p className="text-white/70">Applied</p>
                        <p className="font-semibold">{request.appliedAt}</p>
                      </div>
                    </div>
                  </div>

                  <div className="flex gap-3 w-full lg:w-auto">
                    <button
                      onClick={() => handleApprove(request.id)}
                      className="flex-1 lg:flex-none bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-semibold py-2 px-6 rounded-lg transition-all"
                    >
                      ✓ Approve
                    </button>
                    <button
                      onClick={() => handleReject(request.id)}
                      className="flex-1 lg:flex-none bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white font-semibold py-2 px-6 rounded-lg transition-all"
                    >
                      ✗ Reject
                    </button>
                  </div>
                </div>
              </div>
            ))}
          </div>
        )}
      </div>

      {/* Approved Requests */}
      {approvedRequests.length > 0 && (
        <div>
          <div className="mb-6">
            <h2 className="text-3xl font-extrabold text-white mb-2">
              ✅ Approved Teachers ({approvedRequests.length})
            </h2>
            <p className="text-pink-100">Successfully onboarded teachers</p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            {approvedRequests.map((request) => (
              <div key={request.id} className="bg-gradient-to-br from-emerald-500/20 to-teal-500/20 backdrop-blur border border-emerald-300/30 rounded-xl p-6">
                <div className="flex items-start gap-4">
                  <div className="text-3xl">✅</div>
                  <div className="flex-1">
                    <h3 className="text-lg font-bold text-white mb-1">{request.name}</h3>
                    <p className="text-white/80 text-sm">{request.subject}</p>
                    <p className="text-emerald-300 text-xs mt-2">Approved: {request.approvedAt}</p>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      )}

      {/* Rejected Requests */}
      {rejectedRequests.length > 0 && (
        <div>
          <div className="mb-6">
            <h2 className="text-3xl font-extrabold text-white mb-2">
              ❌ Rejected Applications ({rejectedRequests.length})
            </h2>
            <p className="text-pink-100">Application history</p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            {rejectedRequests.map((request) => (
              <div key={request.id} className="bg-gradient-to-br from-red-500/20 to-pink-500/20 backdrop-blur border border-red-300/30 rounded-xl p-6">
                <div className="flex items-start gap-4">
                  <div className="text-3xl">❌</div>
                  <div className="flex-1">
                    <h3 className="text-lg font-bold text-white mb-1">{request.name}</h3>
                    <p className="text-white/80 text-sm">{request.subject}</p>
                    <p className="text-red-300 text-xs mt-2">Rejected: {request.rejectedAt}</p>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      )}
    </div>
  );
}
