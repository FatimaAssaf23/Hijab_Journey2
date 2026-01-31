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

        /* Animated Background - Light Theme */
        .hero-bg {
            background: linear-gradient(135deg, #fdf2f8 0%, #ecfeff 30%, #f0fdfa 100%);
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
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ec4899' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            animation: patternMove 20s linear infinite;
        }

        @keyframes patternMove {
            0% { transform: translate(0, 0); }
            100% { transform: translate(60px, 60px); }
        }

        /* Floating Orbs - Light Theme (More Darker) */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(90px);
            opacity: 0.3;
            animation: float 8s ease-in-out infinite;
            pointer-events: none;
        }

        .orb-1 {
            width: 400px;
            height: 400px;
            background: linear-gradient(135deg, #f472b6, #ec4899);
            top: -150px;
            right: -150px;
            animation-delay: 0s;
        }

        .orb-2 {
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, #22d3ee, #06b6d4);
            bottom: -100px;
            left: -100px;
            animation: floatReverse 10s ease-in-out infinite;
            animation-delay: -2s;
        }

        .orb-3 {
            width: 200px;
            height: 200px;
            background: linear-gradient(135deg, #2dd4bf, #14b8a6);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: float 12s ease-in-out infinite;
            animation-delay: -4s;
            opacity: 0.25;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1) rotate(0deg); }
            25% { transform: translateY(-20px) scale(1.05) rotate(5deg); }
            50% { transform: translateY(-40px) scale(1.1) rotate(0deg); }
            75% { transform: translateY(-20px) scale(1.05) rotate(-5deg); }
        }

        @keyframes floatReverse {
            0%, 100% { transform: translateY(0) scale(1) rotate(0deg); }
            25% { transform: translateY(20px) scale(1.05) rotate(-5deg); }
            50% { transform: translateY(40px) scale(1.1) rotate(0deg); }
            75% { transform: translateY(20px) scale(1.05) rotate(5deg); }
        }

        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }

        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(236, 72, 153, 0.3); }
            50% { box-shadow: 0 0 40px rgba(236, 72, 153, 0.6), 0 0 60px rgba(6, 182, 212, 0.3); }
        }

        /* Navbar - Light Theme with Glassmorphism */
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
            backdrop-filter: blur(20px) saturate(180%);
            background: rgba(255, 255, 255, 0.75);
            border-bottom: 1px solid rgba(236, 72, 153, 0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.05);
        }

        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.9);
            padding: 0.75rem 2rem;
            box-shadow: 0 8px 32px rgba(236, 72, 153, 0.1);
            border-bottom: 1px solid rgba(236, 72, 153, 0.2);
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
            filter: drop-shadow(0 4px 8px rgba(236, 72, 153, 0.3));
            transition: transform 0.3s ease;
        }

        .logo:hover .logo-icon {
            transform: scale(1.15) rotate(5deg);
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1) rotate(0deg); }
            50% { transform: scale(1.1) rotate(5deg); }
        }

        .logo-text {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #ec4899, #06b6d4);
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
            color: #64748b;
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
            background: linear-gradient(90deg, #ec4899, #06b6d4);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover {
            color: #ec4899;
        }

        .nav-link:hover::before {
            width: 80%;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 14px 32px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
            border: none;
            position: relative;
            overflow: hidden;
            transform-style: preserve-3d;
        }

        .btn::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn:hover::after {
            width: 300px;
            height: 300px;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s ease;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: linear-gradient(135deg, #ec4899, #f472b6, #ec4899);
            background-size: 200% auto;
            color: #fff;
            box-shadow: 0 4px 20px rgba(236, 72, 153, 0.3), 0 0 0 0 rgba(236, 72, 153, 0.5);
            animation: shimmer 3s linear infinite;
        }

        .btn-primary:hover {
            transform: translateY(-4px) scale(1.05);
            box-shadow: 0 12px 40px rgba(236, 72, 153, 0.5), 0 0 0 8px rgba(236, 72, 153, 0.1);
            animation: shimmer 1.5s linear infinite;
        }

        .btn-primary:active {
            transform: translateY(-2px) scale(1.02);
        }

        .btn-secondary {
            background: transparent;
            color: #64748b;
            border: 2px solid rgba(236, 72, 153, 0.3);
        }

        .btn-secondary:hover {
            background: rgba(236, 72, 153, 0.1);
            border-color: rgba(236, 72, 153, 0.5);
            color: #ec4899;
            transform: translateY(-3px);
        }

        .btn-accent {
            background: linear-gradient(135deg, #06b6d4, #0891b2, #06b6d4);
            background-size: 200% auto;
            color: #fff;
            box-shadow: 0 4px 20px rgba(6, 182, 212, 0.3), 0 0 0 0 rgba(6, 182, 212, 0.5);
            animation: shimmer 3s linear infinite;
        }

        .btn-accent:hover {
            transform: translateY(-4px) scale(1.05);
            box-shadow: 0 12px 40px rgba(6, 182, 212, 0.5), 0 0 0 8px rgba(6, 182, 212, 0.1);
            animation: shimmer 1.5s linear infinite;
        }

        .btn-accent:active {
            transform: translateY(-2px) scale(1.02);
        }

        /* Hero Section - Enhanced */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 120px 2rem 80px;
            position: relative;
            z-index: 1;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 20%;
            left: 10%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(236, 72, 153, 0.18) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(60px);
            animation: pulse-glow 4s ease-in-out infinite;
            opacity: 0.7;
        }

        .hero::after {
            content: '';
            position: absolute;
            bottom: 20%;
            right: 15%;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(6, 182, 212, 0.18) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(60px);
            animation: pulse-glow 5s ease-in-out infinite;
            animation-delay: 1s;
            opacity: 0.7;
        }

        .hero-content {
            text-align: center;
            max-width: 900px;
            animation: fadeInUp 1s ease-out;
            position: relative;
            z-index: 2;
        }

        /* Decorative elements in hero */
        .hero-content::before {
            content: '✨';
            position: absolute;
            top: -50px;
            left: -50px;
            font-size: 3rem;
            opacity: 0.3;
            animation: float 6s ease-in-out infinite;
        }

        .hero-content::after {
            content: '🌟';
            position: absolute;
            bottom: -50px;
            right: -50px;
            font-size: 3rem;
            opacity: 0.3;
            animation: floatReverse 8s ease-in-out infinite;
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
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.7));
            backdrop-filter: blur(20px) saturate(180%);
            padding: 10px 24px;
            border-radius: 50px;
            border: 1px solid rgba(236, 72, 153, 0.2);
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 2rem;
            animation: fadeInUp 1s ease-out 0.2s both;
            box-shadow: 0 4px 20px rgba(236, 72, 153, 0.1), inset 0 1px 0 rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
        }

        .hero-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(236, 72, 153, 0.15), inset 0 1px 0 rgba(255, 255, 255, 0.9);
            border-color: rgba(236, 72, 153, 0.3);
        }

        .hero-badge-dot {
            width: 8px;
            height: 8px;
            background: #06b6d4;
            border-radius: 50%;
            animation: blink 1.5s ease-in-out infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        .hero-title {
            font-size: clamp(2.5rem, 6vw, 5rem);
            font-weight: 800;
            color: #1e293b;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            animation: fadeInUp 1s ease-out 0.4s both;
            letter-spacing: -0.02em;
        }

        .hero-title span {
            background: linear-gradient(135deg, #ec4899, #f472b6, #ec4899, #06b6d4, #0891b2, #06b6d4);
            background-size: 300% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientFlow 4s ease-in-out infinite;
            position: relative;
            display: inline-block;
        }

        .hero-title span::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #ec4899, #06b6d4);
            border-radius: 2px;
            opacity: 0.5;
            animation: gradientFlow 4s ease-in-out infinite;
        }

        @keyframes gradientFlow {
            0%, 100% { background-position: 0% center; }
            50% { background-position: 100% center; }
        }

        @keyframes slideInFromLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInFromRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .hero-description {
            font-size: 1.3rem;
            color: #475569;
            max-width: 650px;
            margin: 0 auto 3.5rem;
            line-height: 1.8;
            animation: fadeInUp 1s ease-out 0.6s both;
            font-weight: 400;
        }

        .hero-buttons {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
            animation: fadeInUp 1s ease-out 0.8s both;
            margin-top: 2rem;
        }

        .hero-buttons .btn {
            min-width: 200px;
            padding: 16px 36px;
            font-size: 1rem;
        }

        /* Features Section - Light Theme */
        .features {
            padding: 120px 2rem;
            background: linear-gradient(180deg, #fdf2f8 0%, #ecfeff 50%, #f0fdfa 100%);
            position: relative;
            z-index: 1;
        }

        .features::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(236, 72, 153, 0.2), transparent);
        }

        .section-header {
            text-align: center;
            margin-bottom: 5rem;
            position: relative;
            z-index: 2;
        }

        .section-subtitle {
            color: #06b6d4;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 1rem;
        }

        .section-title {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1rem;
        }

        .section-description {
            color: #64748b;
            max-width: 600px;
            margin: 0 auto;
            font-size: 1.1rem;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2.5rem;
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        .feature-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.85));
            backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(236, 72, 153, 0.1);
            border-radius: 28px;
            padding: 2.5rem;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08), inset 0 1px 0 rgba(255, 255, 255, 0.9);
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #ec4899, #f472b6, #06b6d4, #0891b2);
            background-size: 200% auto;
            transform: scaleX(0);
            transition: transform 0.5s ease;
            animation: shimmer 3s linear infinite;
        }

        .feature-card::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(236, 72, 153, 0.1) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .feature-card:hover {
            transform: translateY(-12px) scale(1.02);
            background: linear-gradient(135deg, rgba(255, 255, 255, 1), rgba(255, 255, 255, 0.95));
            border-color: rgba(236, 72, 153, 0.4);
            box-shadow: 0 20px 60px rgba(236, 72, 153, 0.2), 0 0 0 1px rgba(236, 72, 153, 0.1), inset 0 1px 0 rgba(255, 255, 255, 1);
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-card:hover::after {
            opacity: 1;
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .feature-icon::before {
            content: '';
            position: absolute;
            inset: -2px;
            border-radius: 24px;
            padding: 2px;
            background: linear-gradient(135deg, #ec4899, #06b6d4);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.15) rotate(8deg) translateY(-5px);
            box-shadow: 0 8px 30px rgba(236, 72, 153, 0.3);
        }

        .feature-card:hover .feature-icon::before {
            opacity: 1;
        }

        .feature-icon.pink {
            background: linear-gradient(135deg, rgba(236, 72, 153, 0.15), rgba(244, 114, 182, 0.15));
        }

        .feature-icon.teal {
            background: linear-gradient(135deg, rgba(6, 182, 212, 0.15), rgba(8, 145, 178, 0.15));
        }

        .feature-icon.purple {
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.15), rgba(167, 139, 250, 0.15));
        }

        .feature-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.75rem;
        }

        .feature-text {
            color: #64748b;
            line-height: 1.7;
        }

        /* Stats Section - Light Theme with Creative Design */
        .stats {
            padding: 100px 2rem;
            background: linear-gradient(135deg, #fce7f3 0%, #f0f9ff 50%, #ecfeff 100%);
            position: relative;
            z-index: 1;
            overflow: hidden;
        }

        .stats::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 30%, rgba(236, 72, 153, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(6, 182, 212, 0.1) 0%, transparent 50%);
            animation: float 20s ease-in-out infinite;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 2.5rem;
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
            position: relative;
            z-index: 2;
        }

        .stat-item {
            padding: 2rem;
            position: relative;
            z-index: 2;
            transition: all 0.4s ease;
        }

        .stat-item::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 24px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.6), rgba(255, 255, 255, 0.4));
            backdrop-filter: blur(10px);
            border: 1px solid rgba(236, 72, 153, 0.1);
            opacity: 0;
            transition: opacity 0.4s ease;
            z-index: -1;
        }

        .stat-item:hover::before {
            opacity: 1;
        }

        .stat-item:hover {
            transform: translateY(-8px) scale(1.05);
        }

        .stat-number {
            font-size: 4rem;
            font-weight: 800;
            background: linear-gradient(135deg, #ec4899, #f472b6, #06b6d4, #0891b2);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            margin-bottom: 0.75rem;
            animation: shimmer 3s linear infinite;
            filter: drop-shadow(0 2px 4px rgba(236, 72, 153, 0.2));
            transition: all 0.3s ease;
        }

        .stat-item:hover .stat-number {
            font-size: 4.5rem;
            filter: drop-shadow(0 4px 8px rgba(236, 72, 153, 0.3));
        }

        .stat-label {
            color: #64748b;
            font-weight: 600;
            font-size: 1.15rem;
            letter-spacing: 0.5px;
            transition: color 0.3s ease;
        }

        .stat-item:hover .stat-label {
            color: #ec4899;
        }

        /* CTA Section - Light Theme with Enhanced Design */
        .cta {
            padding: 120px 2rem;
            background: linear-gradient(135deg, #fdf2f8 0%, #ecfeff 100%);
            text-align: center;
            position: relative;
            z-index: 1;
            overflow: hidden;
        }

        .cta::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(236, 72, 153, 0.08) 0%, transparent 70%);
            animation: float 15s ease-in-out infinite;
        }

        .cta::after {
            content: '';
            position: absolute;
            bottom: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(6, 182, 212, 0.08) 0%, transparent 70%);
            animation: floatReverse 18s ease-in-out infinite;
        }

        .cta-content {
            max-width: 700px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.6), rgba(255, 255, 255, 0.4));
            backdrop-filter: blur(20px) saturate(180%);
            padding: 3rem;
            border-radius: 32px;
            border: 1px solid rgba(236, 72, 153, 0.1);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1), inset 0 1px 0 rgba(255, 255, 255, 0.9);
        }

        .cta-title {
            font-size: clamp(2rem, 4vw, 2.5rem);
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1.5rem;
        }

        .cta-text {
            color: #64748b;
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


        /* Footer - Enhanced Light Theme */
        .footer {
            padding: 4rem 2rem 2rem;
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            text-align: center;
            border-top: 1px solid rgba(236, 72, 153, 0.1);
            position: relative;
        }

        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, #ec4899, #06b6d4);
            border-radius: 0 0 2px 2px;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .footer-link {
            color: #64748b;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }

        .footer-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #ec4899, #06b6d4);
            transition: width 0.3s ease;
        }

        .footer-link:hover {
            color: #ec4899;
        }

        .footer-link:hover::after {
            width: 100%;
        }

        .footer-text {
            color: #94a3b8;
            font-size: 0.9rem;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(236, 72, 153, 0.05);
        }

        .footer-text a {
            color: #06b6d4;
            text-decoration: none;
            transition: color 0.3s ease;
            font-weight: 500;
        }

        .footer-text a:hover {
            color: #ec4899;
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
            background: #1e293b;
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        /* Additional Creative Elements */
        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: linear-gradient(135deg, #ec4899, #06b6d4);
            border-radius: 50%;
            opacity: 0.6;
            animation: particleFloat 8s ease-in-out infinite;
        }

        @keyframes particleFloat {
            0%, 100% { transform: translate(0, 0) scale(1); opacity: 0.6; }
            50% { transform: translate(20px, -30px) scale(1.5); opacity: 0.3; }
        }

        /* Enhanced gradient text effect */
        .gradient-text {
            background: linear-gradient(135deg, #ec4899, #f472b6, #06b6d4, #0891b2);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientFlow 3s ease-in-out infinite;
        }

        /* Glow effect for interactive elements */
        .glow-on-hover {
            transition: all 0.3s ease;
        }

        .glow-on-hover:hover {
            filter: drop-shadow(0 0 20px rgba(236, 72, 153, 0.5));
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
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(20px);
                flex-direction: column;
                justify-content: center;
                align-items: center;
                gap: 2rem;
                transition: right 0.4s ease;
                z-index: 99;
                box-shadow: -4px 0 20px rgba(0, 0, 0, 0.1);
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

            .hero-content::before,
            .hero-content::after {
                display: none;
            }

            .stat-number {
                font-size: 3rem;
            }

            .stat-item:hover .stat-number {
                font-size: 3.2rem;
            }

            .feature-card {
                padding: 2rem;
            }

            .cta-content {
                padding: 2rem;
            }

        }

        @media (max-width: 480px) {
            .hero-title {
                font-size: 2rem;
            }

            .stat-number {
                font-size: 2.5rem;
            }

            .feature-icon {
                width: 60px;
                height: 60px;
                font-size: 2rem;
            }
        }

        /* Enhanced Scroll animations */
        .scroll-reveal {
            opacity: 0;
            transform: translateY(50px) scale(0.95);
            transition: all 1s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .scroll-reveal.revealed {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        /* Stagger animation for feature cards */
        .features-grid .feature-card:nth-child(1) { transition-delay: 0.1s; }
        .features-grid .feature-card:nth-child(2) { transition-delay: 0.2s; }
        .features-grid .feature-card:nth-child(3) { transition-delay: 0.3s; }
        .features-grid .feature-card:nth-child(4) { transition-delay: 0.4s; }
        .features-grid .feature-card:nth-child(5) { transition-delay: 0.5s; }
        .features-grid .feature-card:nth-child(6) { transition-delay: 0.6s; }

        /* Authenticated user styles */
        .auth-notice {
            background: rgba(6, 182, 212, 0.1);
            border: 1px solid rgba(6, 182, 212, 0.3);
            border-radius: 12px;
            padding: 1rem 1.5rem;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 2rem;
            color: #06b6d4;
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
                    @if(Auth::user()->role === 'teacher')
                        <a href="{{ route('teacher.dashboard') }}" class="btn btn-secondary">Dashboard</a>
                    @elseif(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Dashboard</a>
                    @else
                        <a href="{{ route('student.dashboard') }}" class="btn btn-secondary">Dashboard</a>
                    @endif
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
                        @if(Auth::user()->role === 'teacher')
                            <a href="{{ route('teacher.dashboard') }}" class="btn btn-primary">
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                                Go to Dashboard
                            </a>
                        @elseif(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                                Go to Dashboard
                            </a>
                        @else
                            <a href="{{ route('student.dashboard') }}" class="btn btn-primary">
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                                Go to Dashboard
                            </a>
                        @endif
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
            <h2 class="section-title">Everything You Need to <span class="gradient-text">Succeed</span></h2>
            <p class="section-description">
                Our platform provides a comprehensive learning experience with cutting-edge tools designed 
                for both students and teachers. Join thousands of learners on their journey to excellence.
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
                <div class="stat-number" data-value="{{ $totalStudents ?? 0 }}">{{ $totalStudents ?? 0 }}+</div>
                <div class="stat-label">Active Students</div>
            </div>
            <div class="stat-item scroll-reveal">
                <div class="stat-number" data-value="{{ $totalTeachers ?? 0 }}">{{ $totalTeachers ?? 0 }}+</div>
                <div class="stat-label">Expert Teachers</div>
            </div>
            <div class="stat-item scroll-reveal">
                <div class="stat-number" data-value="{{ $totalLessons ?? 0 }}">{{ $totalLessons ?? 0 }}+</div>
                <div class="stat-label">Lessons Available</div>
            </div>
            <div class="stat-item scroll-reveal">
                <div class="stat-number" data-value="{{ $satisfactionRate ?? 0 }}">{{ $satisfactionRate ?? 0 }}%</div>
                <div class="stat-label">Satisfaction Rate</div>
            </div>
        </div>
    </section>

    <!-- About/CTA Section -->
    <section class="cta" id="about">
        <div class="cta-content scroll-reveal">
            <h2 class="cta-title">Ready to Begin Your <span class="gradient-text">Journey</span>?</h2>
            <p class="cta-text">
                Whether you're a student eager to learn or a teacher ready to share your knowledge, 
                Hijab Journey welcomes you. Join our growing family of learners and educators 
                making a difference in Islamic education.
            </p>
            <div class="cta-buttons">
                @auth
                    @if(Auth::user()->role === 'teacher')
                        <a href="{{ route('teacher.dashboard') }}" class="btn btn-primary">Go to Dashboard</a>
                    @elseif(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Go to Dashboard</a>
                    @else
                        <a href="{{ route('student.dashboard') }}" class="btn btn-primary">Go to Dashboard</a>
                    @endif
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
        <div class="footer-content">
            <div class="footer-links">
                <a href="#features" class="footer-link">Features</a>
                <a href="#about" class="footer-link">About Us</a>
                @auth
                    @if(Auth::user()->role === 'teacher')
                        <a href="{{ route('teacher.dashboard') }}" class="footer-link">Dashboard</a>
                    @elseif(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="footer-link">Dashboard</a>
                    @else
                        <a href="{{ route('student.dashboard') }}" class="footer-link">Dashboard</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="footer-link">Login</a>
                    <a href="{{ route('register') }}" class="footer-link">Sign Up</a>
                @endauth
            </div>
            <p class="footer-text">
                © {{ date('Y') }} Hijab Journey. Crafted with ❤️ for the Muslim community.
            </p>
        </div>
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
                // Get the actual value from data-value attribute or parse from text
                const dataValue = counter.getAttribute('data-value');
                const numericValue = dataValue ? parseInt(dataValue) : parseInt(counter.innerText.replace(/\D/g, ''));
                const originalText = counter.innerText;
                const suffix = originalText.replace(/[0-9]/g, '');
                
                // Store original suffix for percentage
                const isPercentage = originalText.includes('%');
                const isPlus = originalText.includes('+');
                
                let current = 0;
                const increment = Math.max(1, numericValue / 50);
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= numericValue) {
                        // Use the correct suffix based on original text
                        if (isPercentage) {
                            counter.innerText = numericValue + '%';
                        } else if (isPlus) {
                            counter.innerText = numericValue + '+';
                        } else {
                            counter.innerText = numericValue;
                        }
                        clearInterval(timer);
                    } else {
                        // Use the correct suffix during animation
                        if (isPercentage) {
                            counter.innerText = Math.floor(current) + '%';
                        } else if (isPlus) {
                            counter.innerText = Math.floor(current) + '+';
                        } else {
                            counter.innerText = Math.floor(current);
                        }
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

        // Add parallax effect to floating orbs
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const orbs = document.querySelectorAll('.orb');
            orbs.forEach((orb, index) => {
                const speed = (index + 1) * 0.5;
                orb.style.transform = `translateY(${scrolled * speed}px)`;
            });
        });

        // Add interactive cursor effect on feature cards
        const featureCards = document.querySelectorAll('.feature-card');
        featureCards.forEach(card => {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                
                const rotateX = (y - centerY) / 10;
                const rotateY = (centerX - x) / 10;
                
                card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-12px) scale(1.02)`;
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = '';
            });
        });

        // Add ripple effect to buttons
        const buttons = document.querySelectorAll('.btn');
        buttons.forEach(button => {
            button.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.classList.add('ripple');
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });

        // Add dynamic particles on scroll
        function createParticle() {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.top = Math.random() * 100 + '%';
            particle.style.animationDelay = Math.random() * 8 + 's';
            particle.style.animationDuration = (Math.random() * 4 + 6) + 's';
            document.querySelector('.hero-bg').appendChild(particle);
            
            setTimeout(() => {
                particle.remove();
            }, 10000);
        }

        // Create particles periodically
        setInterval(createParticle, 2000);

        // Add entrance animation to stats
        const statItems = document.querySelectorAll('.stat-item');
        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0) scale(1)';
                    }, index * 100);
                }
            });
        }, { threshold: 0.3 });

        statItems.forEach(item => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(30px) scale(0.9)';
            item.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
            statsObserver.observe(item);
        });
    </script>
    <style>
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.6);
            transform: scale(0);
            animation: ripple-animation 0.6s ease-out;
            pointer-events: none;
        }

        @keyframes ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    </style>
</body>
</html>
