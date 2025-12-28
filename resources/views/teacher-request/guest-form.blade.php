<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Become a Teacher - Hijab Journey</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated Background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
        }

        /* Floating Orbs */
        .orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.4;
            animation: float 10s ease-in-out infinite;
            z-index: 0;
        }

        .orb-1 {
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, #e94560, #ff6b9d);
            top: -100px;
            right: -50px;
        }

        .orb-2 {
            width: 250px;
            height: 250px;
            background: linear-gradient(135deg, #0f9b8e, #14b8a6);
            bottom: -50px;
            left: -50px;
            animation-delay: -3s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-20px) scale(1.02); }
        }

        /* Header */
        .header {
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 10;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .logo-icon {
            font-size: 2rem;
        }

        .logo-text {
            font-size: 1.3rem;
            font-weight: 700;
            background: linear-gradient(135deg, #e94560, #14b8a6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 50px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            border-color: rgba(255, 255, 255, 0.4);
        }

        /* Main Container */
        .main-container {
            max-width: 700px;
            margin: 0 auto;
            padding: 2rem;
            position: relative;
            z-index: 10;
        }

        /* Form Card */
        .form-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 3rem;
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .form-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            display: block;
        }

        .form-title {
            font-size: 2rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 0.5rem;
        }

        .form-subtitle {
            color: rgba(255, 255, 255, 0.6);
            font-size: 1rem;
        }

        /* Alert Messages */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideIn 0.4s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .alert-success {
            background: rgba(20, 184, 166, 0.15);
            border: 1px solid rgba(20, 184, 166, 0.3);
            color: #14b8a6;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #ef4444;
        }

        .alert svg {
            width: 24px;
            height: 24px;
            flex-shrink: 0;
        }

        /* Form Groups */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        @media (max-width: 600px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            display: block;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-label .required {
            color: #e94560;
            margin-left: 2px;
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 14px 18px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            color: #fff;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-input::placeholder,
        .form-textarea::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: #14b8a6;
            background: rgba(255, 255, 255, 0.12);
            box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.2);
        }

        .form-select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23ffffff'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            background-size: 20px;
            padding-right: 45px;
        }

        .form-select option {
            background: #1a1a2e;
            color: #fff;
        }

        .form-textarea {
            resize: vertical;
            min-height: 120px;
        }

        .form-error {
            color: #ef4444;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .form-hint {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.85rem;
            margin-top: 0.5rem;
        }

        /* Submit Button */
        .submit-btn {
            width: 100%;
            padding: 16px 32px;
            background: linear-gradient(135deg, #14b8a6, #0f9b8e);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-family: 'Poppins', sans-serif;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 1rem;
            position: relative;
            overflow: hidden;
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(20, 184, 166, 0.4);
        }

        .submit-btn:hover::before {
            left: 100%;
        }

        .submit-btn:active {
            transform: translateY(-1px);
        }

        .submit-btn svg {
            width: 22px;
            height: 22px;
        }

        /* Info Box */
        .info-box {
            background: rgba(139, 92, 246, 0.1);
            border: 1px solid rgba(139, 92, 246, 0.2);
            border-radius: 12px;
            padding: 1.25rem;
            margin-top: 2rem;
            display: flex;
            gap: 12px;
        }

        .info-box svg {
            width: 24px;
            height: 24px;
            color: #a78bfa;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .info-box-content h4 {
            color: #a78bfa;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .info-box-content p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
            line-height: 1.6;
        }

        /* Input states */
        .form-input.error,
        .form-select.error,
        .form-textarea.error {
            border-color: #ef4444;
            background: rgba(239, 68, 68, 0.1);
        }
    </style>
</head>
<body>
    <!-- Floating Orbs -->
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <!-- Header -->
    <header class="header">
        <a href="/" class="logo">
            <span class="logo-icon">🧕</span>
            <span class="logo-text">Hijab Journey</span>
        </a>
        <a href="/" class="back-btn">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Home
        </a>
    </header>

    <!-- Main Container -->
    <main class="main-container">
        <div class="form-card">
            <div class="form-header">
                <span class="form-icon">👩‍🏫</span>
                <h1 class="form-title">Become a Teacher</h1>
                <p class="form-subtitle">Join our community of dedicated Islamic educators</p>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Error Message -->
            @if(session('error'))
                <div class="alert alert-error">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('teacher-request.guest.store') }}" method="POST" id="teacherRequestForm">
                @csrf

                <!-- Personal Information -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">
                            Full Name <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="full_name" 
                            class="form-input @error('full_name') error @enderror" 
                            placeholder="Enter your full name"
                            value="{{ old('full_name') }}"
                            required
                        >
                        @error('full_name')
                            <div class="form-error">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Age <span class="required">*</span>
                        </label>
                        <input 
                            type="number" 
                            name="age" 
                            class="form-input @error('age') error @enderror" 
                            placeholder="Your age"
                            value="{{ old('age') }}"
                            min="18"
                            max="100"
                            required
                        >
                        @error('age')
                            <div class="form-error">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">
                            Email Address <span class="required">*</span>
                        </label>
                        <input 
                            type="email" 
                            name="email" 
                            class="form-input @error('email') error @enderror" 
                            placeholder="your.email@example.com"
                            value="{{ old('email') }}"
                            required
                        >
                        @error('email')
                            <div class="form-error">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Phone Number
                        </label>
                        <input 
                            type="tel" 
                            name="phone" 
                            class="form-input @error('phone') error @enderror" 
                            placeholder="+1 234 567 8900"
                            value="{{ old('phone') }}"
                        >
                        @error('phone')
                            <div class="form-error">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Professional Information -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">
                            Primary Teaching Language <span class="required">*</span>
                        </label>
                        <select name="language" class="form-select @error('language') error @enderror" required>
                            <option value="">Select a language</option>
                            <option value="Arabic" {{ old('language') == 'Arabic' ? 'selected' : '' }}>Arabic</option>
                            <option value="English" {{ old('language') == 'English' ? 'selected' : '' }}>English</option>
                            <option value="French" {{ old('language') == 'French' ? 'selected' : '' }}>French</option>
                            <option value="Urdu" {{ old('language') == 'Urdu' ? 'selected' : '' }}>Urdu</option>
                            <option value="Malay" {{ old('language') == 'Malay' ? 'selected' : '' }}>Malay</option>
                            <option value="Indonesian" {{ old('language') == 'Indonesian' ? 'selected' : '' }}>Indonesian</option>
                            <option value="Turkish" {{ old('language') == 'Turkish' ? 'selected' : '' }}>Turkish</option>
                            <option value="Other" {{ old('language') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('language')
                            <div class="form-error">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Years of Experience <span class="required">*</span>
                        </label>
                        <input 
                            type="number" 
                            name="experience_years" 
                            class="form-input @error('experience_years') error @enderror" 
                            placeholder="Years of teaching experience"
                            value="{{ old('experience_years') }}"
                            min="0"
                            max="50"
                            required
                        >
                        @error('experience_years')
                            <div class="form-error">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">
                            Area of Specialization <span class="required">*</span>
                        </label>
                        <select name="specialization" class="form-select @error('specialization') error @enderror" required>
                            <option value="">Select specialization</option>
                            <option value="Quran Recitation" {{ old('specialization') == 'Quran Recitation' ? 'selected' : '' }}>Quran Recitation (Tilawah)</option>
                            <option value="Quran Memorization" {{ old('specialization') == 'Quran Memorization' ? 'selected' : '' }}>Quran Memorization (Hifz)</option>
                            <option value="Tajweed" {{ old('specialization') == 'Tajweed' ? 'selected' : '' }}>Tajweed</option>
                            <option value="Islamic Studies" {{ old('specialization') == 'Islamic Studies' ? 'selected' : '' }}>Islamic Studies</option>
                            <option value="Arabic Language" {{ old('specialization') == 'Arabic Language' ? 'selected' : '' }}>Arabic Language</option>
                            <option value="Fiqh" {{ old('specialization') == 'Fiqh' ? 'selected' : '' }}>Fiqh (Islamic Jurisprudence)</option>
                            <option value="Hadith" {{ old('specialization') == 'Hadith' ? 'selected' : '' }}>Hadith Sciences</option>
                            <option value="Seerah" {{ old('specialization') == 'Seerah' ? 'selected' : '' }}>Seerah (Prophetic Biography)</option>
                            <option value="Islamic History" {{ old('specialization') == 'Islamic History' ? 'selected' : '' }}>Islamic History</option>
                            <option value="Multiple" {{ old('specialization') == 'Multiple' ? 'selected' : '' }}>Multiple Areas</option>
                        </select>
                        @error('specialization')
                            <div class="form-error">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            University Major <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="university_major" 
                            class="form-input @error('university_major') error @enderror" 
                            placeholder="e.g., Islamic Studies, Arabic, Education"
                            value="{{ old('university_major') }}"
                            required
                        >
                        @error('university_major')
                            <div class="form-error">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="form-group full-width">
                    <label class="form-label">
                        Relevant Courses & Certifications
                    </label>
                    <textarea 
                        name="courses_done" 
                        class="form-textarea @error('courses_done') error @enderror" 
                        placeholder="List any relevant courses, certifications, or training you have completed..."
                    >{{ old('courses_done') }}</textarea>
                    <div class="form-hint">Optional: Include ijazas, certifications, or specialized training</div>
                    @error('courses_done')
                        <div class="form-error">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="submit-btn">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Submit Application
                </button>
            </form>

            <div class="info-box">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="info-box-content">
                    <h4>What happens next?</h4>
                    <p>After submitting your application, our admin team will review your qualifications. You will receive an email notification once your application has been processed. This usually takes 2-3 business days.</p>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Form validation enhancement
        document.getElementById('teacherRequestForm').addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('error');
                    isValid = false;
                } else {
                    field.classList.remove('error');
                }
            });

            if (!isValid) {
                e.preventDefault();
                // Scroll to first error
                const firstError = this.querySelector('.error');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                }
            }
        });

        // Remove error class on input
        document.querySelectorAll('.form-input, .form-select, .form-textarea').forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('error');
            });
        });
    </script>
</body>
</html>
