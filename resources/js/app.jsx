import React from 'react'
import { createRoot } from 'react-dom/client'
import './bootstrap'
import '../css/app.css'
import AdminDashboard from './pages/AdminDashboard'

const rootElement = document.getElementById('admin-root');
if (rootElement) {
    const root = createRoot(rootElement);
    root.render(<AdminDashboard />);
}
