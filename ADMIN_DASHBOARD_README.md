# Admin Dashboard - React + Tailwind CSS

A modern, interactive admin panel for managing lessons, classes, teacher requests, and emergency situations. Built with React and styled with Tailwind CSS using pink and turquoise colors.

## Features

### 📚 Lessons Manager
- View lessons organized by grade levels
- Add new lessons with custom emoji icons
- Edit lesson details (title, skills count, icon, grade level)
- Delete lessons with confirmation
- Beautiful gradient cards inspired by Highlights Library design
- Each level displays with a colorful header

### 🏫 Classes Manager
- Create and manage classes
- Assign teachers to classes
- View student count per class
- Edit class details (name, grade, students, teacher)
- Delete classes
- Colorful card-based UI with teacher information

### ✅ Teacher Requests Manager
- Review pending teacher applications
- Approve or reject applications
- View approved teachers in a separate section
- Track rejected applications
- Display teacher info: name, subject, experience, email

### ⚠️ Emergency Reassignment
- Handle teacher unavailability cases
- Reassign available teachers to affected classes
- Track active reassignments
- Show affected classes for each emergency
- Prevent assigning unavailable teachers

## Installation

### Prerequisites
- Laravel 10+
- Node.js & npm
- React (via Vite)
- Tailwind CSS

### Setup Steps

1. **Copy component files** to your Laravel project:
   ```
   resources/js/pages/AdminDashboard.jsx
   resources/js/components/Admin/
   ```

2. **Install dependencies** (if not already done):
   ```bash
   npm install
   npm install -D tailwindcss postcss autoprefixer
   ```

3. **Create a route** in `routes/web.php`:
   ```php
   Route::get('/admin/dashboard', function () {
       return view('admin.dashboard');
   })->middleware('auth', 'admin');
   ```

4. **Build assets**:
   ```bash
   npm run dev    # Development
   npm run build  # Production
   ```

5. **Access the dashboard**:
   ```
   http://localhost:8000/admin/dashboard
   ```

## Component Structure

```
AdminDashboard (Main container)
├── LessonsManager
├── ClassesManager
├── TeacherRequestsManager
└── EmergencyReassignment
```

### LessonsManager
- **Props:** None (uses local state)
- **State:** 
  - `lessons`: Array of lesson objects
  - `levels`: Array of grade level objects
  - `editingLesson`: Currently editing lesson
  - `showModal`: Modal visibility
  - `formData`: Form input data

### ClassesManager
- **Props:** None
- **State:**
  - `classes`: Array of class objects
  - `teachers`: Static teacher list
  - `editingClass`: Currently editing class
  - `showModal`: Modal visibility
  - `formData`: Form input data

### TeacherRequestsManager
- **Props:** None
- **State:**
  - `requests`: Pending applications
  - `approvedRequests`: Approved teachers
  - `rejectedRequests`: Rejected applications

### EmergencyReassignment
- **Props:** None
- **State:**
  - `teachers`: Available and unavailable teachers
  - `emergencyCases`: Emergency situations
  - `reassignments`: Active reassignments

## Data Structures

### Lesson Object
```javascript
{
  id: 1,
  levelId: 1,
  title: "Addition",
  skills: 21,
  icon: "➕",
  image: "bg-gradient-to-br from-yellow-200 to-lime-200"
}
```

### Class Object
```javascript
{
  id: 1,
  name: "Grade 1 - Section A",
  teacherId: 1,
  grade: 1,
  students: 25,
  color: "from-pink-400 to-rose-400"
}
```

### Teacher Object
```javascript
{
  id: 1,
  name: "Sarah Johnson",
  email: "sarah@school.com",
  subject: "Math",
  classes: ["Grade 1 - A", "Grade 2 - B"],
  status: "available" // or "on-leave"
}
```

### Teacher Request Object
```javascript
{
  id: 1,
  name: "Zainab Ahmed",
  email: "zainab@school.com",
  subject: "Arabic",
  experience: "5 years",
  status: "pending",
  appliedAt: "2024-12-20"
}
```

## Color Scheme

### Primary Colors
- **Pink:** `from-pink-500 to-pink-300`
- **Teal/Turquoise:** `from-teal-400 to-teal-300`
- **Gradient:** `from-pink-500 via-pink-400 to-teal-400`

### Card Colors (for lessons and classes)
- Yellow: `from-yellow-200 to-lime-200`
- Blue: `from-blue-200 to-cyan-200`
- Pink: `from-pink-200 to-rose-200`
- Purple: `from-purple-200 to-indigo-200`
- Green: `from-green-200 to-teal-200`
- Orange: `from-orange-200 to-red-200`

## Customization

### Change Colors
Edit the color constants in each component:
```javascript
const CLASS_COLORS = [
  'from-pink-400 to-rose-400',
  'from-cyan-400 to-teal-400',
  // Add more colors...
];
```

### Modify Grade Levels
Update `INITIAL_LEVELS` in `LessonsManager.jsx`:
```javascript
const INITIAL_LEVELS = [
  { id: 1, name: 'Grade Pre-K', color: 'from-yellow-300 to-orange-300' },
  // Add more levels...
];
```

### Add More Teachers
Update `INITIAL_TEACHERS` in `ClassesManager.jsx` or `EmergencyReassignment.jsx`

## Features Walkthrough

### Adding a Lesson
1. Click "+ Add New Lesson"
2. Fill in lesson title, icon, skills count, and grade level
3. Click "Create"
4. Lesson appears as a colorful card under the appropriate grade level

### Creating a Class
1. Click "+ Add New Class"
2. Enter class name, grade, student count, and select a teacher
3. Click "Create"
4. Class card appears with teacher information

### Approving Teacher Requests
1. Go to "Teacher Requests" tab
2. Review pending applications
3. Click "Approve" to hire or "Reject" to decline
4. Approved teachers appear in the "Approved Teachers" section

### Handling Emergencies
1. Go to "Emergency" tab
2. Review emergency cases (teacher unavailable)
3. Click "Reassign Teacher"
4. Select an available teacher from the dropdown
5. Click "Confirm Reassignment"
6. Active reassignment is tracked in the "Active Reassignments" section

## Styling Notes

- All components use **Tailwind CSS utility classes**
- **No external CSS files** needed (except Tailwind in app.css)
- **Responsive design** - works on mobile, tablet, and desktop
- **Dark theme** with gradient backgrounds
- **Smooth transitions** and hover effects

## Browser Support

- Chrome/Edge (latest)
- Firefox (latest)
- Safari (latest)
- Mobile browsers

## Future Enhancements

- [ ] Backend API integration for persistence
- [ ] Search and filter functionality
- [ ] Bulk operations (multi-select, bulk delete)
- [ ] Export data to CSV/PDF
- [ ] User activity logs
- [ ] Real-time notifications
- [ ] Advanced scheduling for emergency coverage
- [ ] Teacher performance metrics
- [ ] Student progress tracking

## Troubleshooting

### Components not rendering?
- Ensure `npm run dev` is running
- Check browser console for errors
- Verify React is properly imported in `app.js`

### Styles not applying?
- Clear browser cache (Cmd+Shift+R)
- Run `npm run dev` again
- Check that Tailwind is configured in `tailwind.config.js`

### Modal not showing?
- Ensure z-50 is not being overridden
- Check that backdrop-blur is supported in your Tailwind config

## License

© 2024 CapHijab Learning Platform. All rights reserved.
