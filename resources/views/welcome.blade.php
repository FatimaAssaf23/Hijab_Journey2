<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hijab Journey - Your Path to Islamic Learning</title>
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
            overflow-x: hidden;
        }

        /* Animated Background */
        .hero-bg {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }

        .hero-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            animation: patternMove 20s linear infinite;
        }

        @keyframes patternMove {
            0% { transform: translate(0, 0); }
            100% { transform: translate(60px, 60px); }
        }

        /* Floating Orbs */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.5;
            animation: float 8s ease-in-out infinite;
        }

        .orb-1 {
            width: 400px;
            height: 400px;
            background: linear-gradient(135deg, #e94560, #ff6b9d);
            top: -100px;
            right: -100px;
            animation-delay: 0s;
        }

        .orb-2 {
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, #0f9b8e, #14b8a6);
            bottom: -50px;
            left: -50px;
            animation-delay: -2s;
        }

        .orb-3 {
            width: 200px;
            height: 200px;
            background: linear-gradient(135deg, #8b5cf6, #a78bfa);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation-delay: -4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-30px) scale(1.05); }
        }

        /* Navbar */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            background: rgba(26, 26, 46, 0.95);
            padding: 0.75rem 2rem;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .logo-icon {
            font-size: 2.5rem;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .logo-text {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #e94560, #14b8a6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-links {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #e94560, #14b8a6);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover {
            color: #fff;
        }

        .nav-link:hover::before {
            width: 80%;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 28px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: linear-gradient(135deg, #e94560, #ff6b9d);
            color: #fff;
            box-shadow: 0 4px 20px rgba(233, 69, 96, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 8px 30px rgba(233, 69, 96, 0.5);
        }

        .btn-secondary {
            background: transparent;
            color: #fff;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-3px);
        }

        .btn-accent {
            background: linear-gradient(135deg, #14b8a6, #0f9b8e);
            color: #fff;
            box-shadow: 0 4px 20px rgba(20, 184, 166, 0.4);
        }

        .btn-accent:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 8px 30px rgba(20, 184, 166, 0.5);
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 120px 2rem 80px;
            position: relative;
            z-index: 1;
        }

        .hero-content {
            text-align: center;
            max-width: 900px;
            animation: fadeInUp 1s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 8px 20px;
            border-radius: 50px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.9rem;
            margin-bottom: 2rem;
            animation: fadeInUp 1s ease-out 0.2s both;
        }

        .hero-badge-dot {
            width: 8px;
            height: 8px;
            background: #14b8a6;
            border-radius: 50%;
            animation: blink 1.5s ease-in-out infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        .hero-title {
            font-size: clamp(2.5rem, 6vw, 4.5rem);
            font-weight: 800;
            color: #fff;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            animation: fadeInUp 1s ease-out 0.4s both;
        }

        .hero-title span {
            background: linear-gradient(135deg, #e94560, #ff6b9d, #14b8a6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            background-size: 200% auto;
            animation: gradientFlow 3s ease-in-out infinite;
        }

        @keyframes gradientFlow {
            0%, 100% { background-position: 0% center; }
            50% { background-position: 100% center; }
        }

        .hero-description {
            font-size: 1.25rem;
            color: rgba(255, 255, 255, 0.7);
            max-width: 600px;
            margin: 0 auto 3rem;
            line-height: 1.7;
            animation: fadeInUp 1s ease-out 0.6s both;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            animation: fadeInUp 1s ease-out 0.8s both;
        }

        .hero-buttons .btn {
            min-width: 180px;
        }

        /* Features Section */
        .features {
            padding: 100px 2rem;
            background: linear-gradient(180deg, #0f3460 0%, #1a1a2e 100%);
            position: relative;
            z-index: 1;
        }

        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-subtitle {
            color: #14b8a6;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 1rem;
        }

        .section-title {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 700;
            color: #fff;
            margin-bottom: 1rem;
        }

        .section-description {
            color: rgba(255, 255, 255, 0.6);
            max-width: 600px;
            margin: 0 auto;
            font-size: 1.1rem;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            padding: 2.5rem;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #e94560, #14b8a6);
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(255, 255, 255, 0.15);
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .feature-icon.pink {
            background: linear-gradient(135deg, rgba(233, 69, 96, 0.2), rgba(255, 107, 157, 0.2));
        }

        .feature-icon.teal {
            background: linear-gradient(135deg, rgba(20, 184, 166, 0.2), rgba(15, 155, 142, 0.2));
        }

        .feature-icon.purple {
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.2), rgba(167, 139, 250, 0.2));
        }

        .feature-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #fff;
            margin-bottom: 0.75rem;
        }

        .feature-text {
            color: rgba(255, 255, 255, 0.6);
            line-height: 1.7;
        }

        /* Stats Section */
        .stats {
            padding: 80px 2rem;
            background: linear-gradient(135deg, #e94560 0%, #ff6b9d 50%, #14b8a6 100%);
            position: relative;
            z-index: 1;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            max-width: 1000px;
            margin: 0 auto;
            text-align: center;
        }

        .stat-item {
            padding: 1.5rem;
        }

        .stat-number {
            font-size: 3.5rem;
            font-weight: 800;
            color: #fff;
            line-height: 1;
            margin-bottom: 0.5rem;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .stat-label {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            font-size: 1.1rem;
        }

        /* CTA Section */
        .cta {
            padding: 100px 2rem;
            background: #1a1a2e;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .cta-content {
            max-width: 700px;
            margin: 0 auto;
        }

        .cta-title {
            font-size: clamp(2rem, 4vw, 2.5rem);
            font-weight: 700;
            color: #fff;
            margin-bottom: 1.5rem;
        }

        .cta-text {
            color: rgba(255, 255, 255, 0.7);
            font-size: 1.15rem;
            margin-bottom: 2.5rem;
            line-height: 1.7;
        }

        .cta-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        /* Footer */
        .footer {
            padding: 3rem 2rem;
            background: #111122;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        .footer-text {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.9rem;
        }

        .footer-text a {
            color: #14b8a6;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-text a:hover {
            color: #e94560;
        }

        /* Mobile Menu */
        .mobile-menu-btn {
            display: none;
            flex-direction: column;
            gap: 5px;
            padding: 10px;
            background: none;
            border: none;
            cursor: pointer;
        }

        .mobile-menu-btn span {
            width: 25px;
            height: 2px;
            background: #fff;
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-links {
                position: fixed;
                top: 0;
                right: -100%;
                width: 80%;
                max-width: 400px;
                height: 100vh;
                background: rgba(26, 26, 46, 0.98);
                backdrop-filter: blur(20px);
                flex-direction: column;
                justify-content: center;
                align-items: center;
                gap: 2rem;
                transition: right 0.4s ease;
                z-index: 99;
            }

            .nav-links.active {
                right: 0;
            }

            .mobile-menu-btn {
                display: flex;
                z-index: 100;
            }

            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }

            .hero-buttons .btn {
                width: 100%;
                max-width: 280px;
            }

            .navbar {
                padding: 1rem;
            }

            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }

            .cta-buttons .btn {
                width: 100%;
                max-width: 280px;
            }
        }

        /* Scroll animations */
        .scroll-reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease;
        }

        .scroll-reveal.revealed {
            opacity: 1;
            transform: translateY(0);
        }

        /* Authenticated user styles */
        .auth-notice {
            background: rgba(20, 184, 166, 0.15);
            border: 1px solid rgba(20, 184, 166, 0.3);
            border-radius: 12px;
            padding: 1rem 1.5rem;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 2rem;
            color: #14b8a6;
            font-weight: 500;
        }

        .auth-notice svg {
            width: 20px;
            height: 20px;
        }
    </style>
</head>
<body>
    <div class="hero-bg">
        <!-- Floating Orbs -->
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>

        <!-- Navbar -->
        <nav class="navbar" id="navbar">
            <a href="/" class="logo">
                <span class="logo-icon">🧕</span>
                <span class="logo-text">Hijab Journey</span>
            </a>
            <div class="nav-links" id="navLinks">
                <a href="#features" class="nav-link">Features</a>
                <a href="#about" class="nav-link">About Us</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-primary">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-secondary">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Get Started</a>
                @endauth
            </div>
            <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Toggle menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </nav>

        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-content">
                <div class="hero-badge">
                    <span class="hero-badge-dot"></span>
                    Empowering Islamic Education
                </div>
                
                @auth
                    <div class="auth-notice">
                        <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Welcome back, {{ Auth::user()->first_name ?? Auth::user()->email }}!
                    </div>
                @endauth

                <h1 class="hero-title">
                    Your Path to<br>
                    <span>Islamic Learning</span>
                </h1>
                
                <p class="hero-description">
                    Join our vibrant community where students flourish and teachers inspire. 
                    Experience a modern approach to Islamic education with interactive lessons, 
                    engaging activities, and meaningful connections.
                </p>

                <div class="hero-buttons">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Login
                        </a>
                        <a href="#about" class="btn btn-secondary">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                            About Us
                        </a>
                        <a href="{{ route('teacher-request.guest') }}" class="btn btn-accent">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/></svg>
                            Become a Teacher
                        </a>
                    @endauth
                </div>
            </div>
        </section>
    </div>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="section-header scroll-reveal">
            <p class="section-subtitle">Why Choose Us</p>
            <h2 class="section-title">Everything You Need to Succeed</h2>
            <p class="section-description">
                Our platform provides a comprehensive learning experience with tools designed 
                for both students and teachers.
            </p>
        </div>

        <div class="features-grid">
            <div class="feature-card scroll-reveal">
                <div class="feature-icon pink">📚</div>
                <h3 class="feature-title">Interactive Lessons</h3>
                <p class="feature-text">
                    Engage with beautifully crafted lessons featuring multimedia content, 
                    quizzes, and hands-on activities that make learning enjoyable.
                </p>
            </div>

            <div class="feature-card scroll-reveal">
                <div class="feature-icon teal">👩‍🏫</div>
                <h3 class="feature-title">Expert Teachers</h3>
                <p class="feature-text">
                    Learn from qualified and passionate educators who bring years of 
                    experience and dedication to Islamic studies.
                </p>
            </div>

            <div class="feature-card scroll-reveal">
                <div class="feature-icon purple">🎮</div>
                <h3 class="feature-title">Gamified Learning</h3>
                <p class="feature-text">
                    Stay motivated with progress tracking, achievements, and fun games 
                    that reinforce your learning journey.
                </p>
            </div>

            <div class="feature-card scroll-reveal">
                <div class="feature-icon pink">💬</div>
                <h3 class="feature-title">Community Support</h3>
                <p class="feature-text">
                    Connect with fellow students and teachers through group discussions, 
                    private messaging, and collaborative activities.
                </p>
            </div>

            <div class="feature-card scroll-reveal">
                <div class="feature-icon teal">📊</div>
                <h3 class="feature-title">Progress Tracking</h3>
                <p class="feature-text">
                    Monitor your growth with detailed analytics, grade reports, and 
                    personalized feedback from your teachers.
                </p>
            </div>

            <div class="feature-card scroll-reveal">
                <div class="feature-icon purple">🏆</div>
                <h3 class="feature-title">Certificates & Rewards</h3>
                <p class="feature-text">
                    Earn certificates upon course completion and unlock rewards as you 
                    advance through different levels.
                </p>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="stats-grid">
            <div class="stat-item scroll-reveal">
                <div class="stat-number">500+</div>
                <div class="stat-label">Active Students</div>
            </div>
            <div class="stat-item scroll-reveal">
                <div class="stat-number">50+</div>
                <div class="stat-label">Expert Teachers</div>
            </div>
            <div class="stat-item scroll-reveal">
                <div class="stat-number">100+</div>
                <div class="stat-label">Lessons Available</div>
            </div>
            <div class="stat-item scroll-reveal">
                <div class="stat-number">98%</div>
                <div class="stat-label">Satisfaction Rate</div>
            </div>
        </div>
    </section>

    <!-- About/CTA Section -->
    <section class="cta" id="about">
        <div class="cta-content scroll-reveal">
            <h2 class="cta-title">Ready to Begin Your Journey?</h2>
            <p class="cta-text">
                Whether you're a student eager to learn or a teacher ready to share your knowledge, 
                Hijab Journey welcomes you. Join our growing family of learners and educators 
                making a difference in Islamic education.
            </p>
            <div class="cta-buttons">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">Go to Dashboard</a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-primary">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/></svg>
                        Join as Student
                    </a>
                    <a href="{{ route('teacher-request.guest') }}" class="btn btn-accent">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/></svg>
                        Become a Teacher
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p class="footer-text">
            © {{ date('Y') }} Hijab Journey. Crafted with ❤️ for the Muslim community.
        </p>
    </footer>

    <script>
        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const navLinks = document.getElementById('navLinks');

        mobileMenuBtn.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            mobileMenuBtn.classList.toggle('active');
        });

        // Close mobile menu when clicking a link
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                navLinks.classList.remove('active');
                mobileMenuBtn.classList.remove('active');
            });
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Scroll reveal animation
        const scrollRevealElements = document.querySelectorAll('.scroll-reveal');

        const revealOnScroll = () => {
            scrollRevealElements.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                const windowHeight = window.innerHeight;

                if (elementTop < windowHeight - 100) {
                    element.classList.add('revealed');
                }
            });
        };

        window.addEventListener('scroll', revealOnScroll);
        window.addEventListener('load', revealOnScroll);

        // Counter animation for stats
        const animateCounters = () => {
            const counters = document.querySelectorAll('.stat-number');
            
            counters.forEach(counter => {
                const target = counter.innerText;
                const numericValue = parseInt(target.replace(/\D/g, ''));
                const suffix = target.replace(/[0-9]/g, '');
                
                let current = 0;
                const increment = numericValue / 50;
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= numericValue) {
                        counter.innerText = numericValue + suffix;
                        clearInterval(timer);
                    } else {
                        counter.innerText = Math.floor(current) + suffix;
                    }
                }, 30);
            });
        };

        // Trigger counter animation when stats section is visible
        const statsSection = document.querySelector('.stats');
        let counterAnimated = false;

        const observerCallback = (entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !counterAnimated) {
                    animateCounters();
                    counterAnimated = true;
                }
            });
        };

        const observer = new IntersectionObserver(observerCallback, { threshold: 0.5 });
        if (statsSection) {
            observer.observe(statsSection);
        }
    </script>
</body>
</html>
