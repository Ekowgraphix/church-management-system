<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pastor Portal - {{ config('app.name') }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        /* FORCE MOBILE BASE STYLES */
        html {
            font-size: 16px;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        
        html, body {
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
            position: relative;
            margin: 0;
            padding: 0;
        }
        
        body {
            min-width: 320px;
        }
        
        *, *::before, *::after {
            box-sizing: border-box;
            max-width: 100%;
        }
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        /* Default Dark Theme */
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            min-height: 100vh;
            position: relative;
            transition: background 0.3s ease, color 0.3s ease;
        }
        
        /* Light Theme */
        body.theme-light {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 50%, #f1f5f9 100%) !important;
        }
        
        body.theme-light .content-area {
            color: #1e293b !important;
        }
        
        body.theme-light .sidebar {
            background: rgba(255, 255, 255, 0.9) !important;
            border-right: 1px solid rgba(0, 0, 0, 0.1) !important;
        }
        
        body.theme-light .sidebar-item {
            color: #334155 !important;
        }
        
        body.theme-light .sidebar-item:hover {
            background: rgba(59, 130, 246, 0.1) !important;
        }
        
        body.theme-light .sidebar-item.active {
            background: rgba(59, 130, 246, 0.15) !important;
            color: #2563eb !important;
        }
        
        body.theme-light::before {
            background: radial-gradient(circle, rgba(59, 130, 246, 0.08) 0%, transparent 50%),
                        radial-gradient(circle at 80% 80%, rgba(34, 197, 94, 0.08) 0%, transparent 50%) !important;
        }
        
        /* Dark Theme */
        body.theme-dark {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%) !important;
        }
        
        body.theme-dark .content-area {
            color: #e2e8f0 !important;
        }
        
        body.theme-dark .sidebar {
            background: rgba(15, 23, 42, 0.8) !important;
        }
        
        /* Animated gradient background */
        body::before {
            content: '';
            position: fixed;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(34, 197, 94, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 80% 80%, rgba(59, 130, 246, 0.1) 0%, transparent 50%);
            animation: rotate 20s linear infinite;
            z-index: 0;
            transition: background 0.3s ease;
        }
        
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .sidebar {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(40px);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 4px 0 40px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 10;
        }
        
        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 1px;
            height: 100%;
            background: linear-gradient(180deg, 
                transparent 0%, 
                rgba(34, 197, 94, 0.5) 50%, 
                transparent 100%);
        }
        
        .sidebar-item {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .sidebar-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            height: 0%;
            width: 4px;
            background: linear-gradient(180deg, #22c55e 0%, #16a34a 100%);
            transition: height 0.3s ease;
            border-radius: 0 4px 4px 0;
        }
        
        .sidebar-item::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(34, 197, 94, 0.1) 0%, transparent 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .sidebar-item:hover::before {
            height: 70%;
        }
        
        .sidebar-item:hover::after {
            opacity: 1;
        }
        
        .sidebar-item:hover {
            transform: translateX(8px);
            background: rgba(34, 197, 94, 0.05);
        }
        
        .sidebar-item.active {
            background: linear-gradient(90deg, rgba(34, 197, 94, 0.2) 0%, transparent 100%);
        }
        
        .sidebar-item.active::before {
            height: 70%;
        }
        
        .icon-box {
            position: relative;
            transition: all 0.3s ease;
        }
        
        .icon-box::before {
            content: '';
            position: absolute;
            inset: -2px;
            background: linear-gradient(135deg, currentColor, transparent);
            opacity: 0;
            border-radius: inherit;
            transition: opacity 0.3s ease;
        }
        
        .sidebar-item:hover .icon-box {
            transform: scale(1.1) rotate(5deg);
        }
        
        .sidebar-item:hover .icon-box::before {
            opacity: 0.2;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2),
                        inset 0 1px 0 rgba(255, 255, 255, 0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .glass-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3),
                        inset 0 1px 0 rgba(255, 255, 255, 0.2);
            border-color: rgba(34, 197, 94, 0.3);
        }
        
        .top-bar {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(40px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 10;
        }
        
        .top-bar::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, 
                transparent 0%, 
                rgba(34, 197, 94, 0.5) 50%, 
                transparent 100%);
        }
        
        .gradient-green {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            box-shadow: 0 4px 20px rgba(34, 197, 94, 0.4);
        }
        
        .gradient-blue {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            box-shadow: 0 4px 20px rgba(59, 130, 246, 0.4);
        }
        
        .gradient-purple {
            background: linear-gradient(135deg, #a855f7 0%, #9333ea 100%);
            box-shadow: 0 4px 20px rgba(168, 85, 247, 0.4);
        }
        
        .gradient-orange {
            background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%);
            box-shadow: 0 4px 20px rgba(245, 158, 11, 0.4);
        }
        
        .gradient-pink {
            background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);
            box-shadow: 0 4px 20px rgba(236, 72, 153, 0.4);
        }
        
        .gradient-cyan {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
            box-shadow: 0 4px 20px rgba(6, 182, 212, 0.4);
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
        
        .animate-fade-in {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        @keyframes pulse-glow {
            0%, 100% {
                box-shadow: 0 0 20px rgba(34, 197, 94, 0.4);
            }
            50% {
                box-shadow: 0 0 40px rgba(34, 197, 94, 0.6);
            }
        }
        
        .pulse-glow {
            animation: pulse-glow 2s ease-in-out infinite;
        }
        
        .notification-badge {
            animation: pulse 2s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            box-shadow: 0 4px 20px rgba(34, 197, 94, 0.4);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-primary::before {
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
        
        .btn-primary:hover::before {
            width: 300px;
            height: 300px;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(34, 197, 94, 0.5);
        }
        
        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.5);
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #22c55e 0%, #16a34a 100%);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #16a34a 0%, #15803d 100%);
        }
        
        /* Sidebar Navigation Scrollbar */
        .sidebar-nav {
            scrollbar-width: thin;
            scrollbar-color: #22c55e rgba(15, 23, 42, 0.5);
            overflow-y: auto !important;
            overflow-x: hidden;
        }
        
        .sidebar-nav::-webkit-scrollbar {
            width: 8px;
        }
        
        .sidebar-nav::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.5);
            border-radius: 4px;
            margin: 8px 0;
        }
        
        .sidebar-nav::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #22c55e 0%, #16a34a 100%);
            border-radius: 4px;
            border: 2px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #16a34a 0%, #15803d 100%);
            box-shadow: 0 0 10px rgba(34, 197, 94, 0.5);
        }
        
        /* Ensure sidebar has proper height constraints */
        .sidebar {
            max-height: 100vh;
        }
        
        .logo-glow {
            position: relative;
        }
        
        .logo-glow::after {
            content: '';
            position: absolute;
            inset: -4px;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            border-radius: inherit;
            opacity: 0.5;
            filter: blur(12px);
            z-index: -1;
        }
        
        .text-gradient {
            background: linear-gradient(135deg, #22c55e 0%, #3b82f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .shimmer {
            position: relative;
            overflow: hidden;
        }
        
        .shimmer::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, 
                transparent 0%, 
                rgba(255, 255, 255, 0.1) 50%, 
                transparent 100%);
            animation: shimmer 3s infinite;
        }
        
        @keyframes shimmer {
            to {
                left: 100%;
            }
        }
        
        /* Perfect Button Styles */
        .btn {
            position: relative;
            overflow: hidden;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 0.75rem;
            padding: 0.75rem 1.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            cursor: pointer;
            border: none;
            outline: none;
        }
        
        .btn::before {
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
        
        .btn:hover::before {
            width: 300px;
            height: 300px;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        
        .btn:active {
            transform: translateY(0);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: white;
            box-shadow: 0 4px 20px rgba(34, 197, 94, 0.4);
        }
        
        .btn-primary:hover {
            box-shadow: 0 8px 30px rgba(34, 197, 94, 0.6);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            box-shadow: 0 4px 20px rgba(59, 130, 246, 0.4);
        }
        
        .btn-secondary:hover {
            box-shadow: 0 8px 30px rgba(59, 130, 246, 0.6);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            box-shadow: 0 4px 20px rgba(239, 68, 68, 0.4);
        }
        
        .btn-danger:hover {
            box-shadow: 0 8px 30px rgba(239, 68, 68, 0.6);
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%);
            color: white;
            box-shadow: 0 4px 20px rgba(245, 158, 11, 0.4);
        }
        
        .btn-warning:hover {
            box-shadow: 0 8px 30px rgba(245, 158, 11, 0.6);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            box-shadow: 0 4px 20px rgba(16, 185, 129, 0.4);
        }
        
        .btn-purple {
            background: linear-gradient(135deg, #a855f7 0%, #9333ea 100%);
            color: white;
            box-shadow: 0 4px 20px rgba(168, 85, 247, 0.4);
        }
        
        .btn-pink {
            background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);
            color: white;
            box-shadow: 0 4px 20px rgba(236, 72, 153, 0.4);
        }
        
        .btn-cyan {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
            color: white;
            box-shadow: 0 4px 20px rgba(6, 182, 212, 0.4);
        }
        
        .btn-outline {
            background: transparent;
            border: 2px solid rgba(34, 197, 94, 0.5);
            color: #22c55e;
            box-shadow: none;
        }
        
        .btn-outline:hover {
            background: rgba(34, 197, 94, 0.1);
            border-color: #22c55e;
            box-shadow: 0 4px 20px rgba(34, 197, 94, 0.3);
        }
        
        .btn-glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }
        
        .btn-glass:hover {
            background: rgba(255, 255, 255, 0.15);
        }
        
        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }
        
        .btn-lg {
            padding: 1rem 2rem;
            font-size: 1.125rem;
        }
        
        .btn-icon {
            width: 2.5rem;
            height: 2.5rem;
            padding: 0;
        }
        
        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none !important;
        }

        /* Mobile Responsive Styles */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
                width: 280px !important;
            }
            
            .sidebar.mobile-open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0 !important;
                width: 100% !important;
            }
            
            .mobile-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 35;
                backdrop-filter: blur(4px);
            }
            
            .mobile-overlay.active {
                display: block;
            }
            
            .mobile-menu-btn {
                display: flex !important;
            }
            
            .top-bar {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
            
            .top-bar h1 {
                font-size: 1.5rem !important;
            }
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 260px !important;
            }
            
            .top-bar {
                padding-top: 1rem !important;
                padding-bottom: 1rem !important;
            }
            
            .top-bar h1 {
                font-size: 1.25rem !important;
            }
            
            .grid {
                grid-template-columns: 1fr !important;
            }
            
            .hide-mobile {
                display: none !important;
            }
        }
        
        @media (max-width: 640px) {
            .top-bar .user-info {
                display: none !important;
            }
            
            body {
                font-size: 14px;
            }
            
            .glass-card {
                padding: 1rem !important;
            }
        }
        
        .mobile-menu-btn {
            display: none;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .mobile-menu-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        
        .mobile-menu-btn i {
            color: #86efac;
            font-size: 1.5rem;
        }

        /* Aggressive Mobile Fit CSS */
        * {
            box-sizing: border-box !important;
        }
        
        html {
            overflow-x: hidden;
            width: 100%;
        }
        
        body {
            overflow-x: hidden !important;
            width: 100% !important;
            max-width: 100vw !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        
        @media (max-width: 1024px) {
            body, html {
                overflow-x: hidden !important;
                max-width: 100vw !important;
            }
            
            .sidebar {
                position: fixed !important;
                left: 0;
                top: 0;
                height: 100vh;
                z-index: 50 !important;
            }
            
            main, .main-content, .content-area {
                width: 100% !important;
                max-width: 100vw !important;
                margin-left: 0 !important;
                padding-left: 1rem !important;
                padding-right: 1rem !important;
                overflow-x: hidden !important;
            }
            
            .container, .max-w-7xl, .w-full {
                max-width: 100% !important;
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
            }
            
            table {
                display: block;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            img {
                max-width: 100% !important;
                height: auto !important;
            }
        }
        
        @media (max-width: 768px) {
            .top-bar, .header {
                padding: 1rem !important;
                flex-wrap: wrap;
            }
            
            h1 {
                font-size: 1.5rem !important;
                word-wrap: break-word;
            }
            
            h2 {
                font-size: 1.25rem !important;
            }
            
            h3 {
                font-size: 1.1rem !important;
            }
            
            .card, .glass-card, .bg-white {
                margin-left: 0 !important;
                margin-right: 0 !important;
                padding: 1rem !important;
            }
            
            .grid {
                grid-template-columns: 1fr !important;
                gap: 1rem !important;
            }
            
            button, .btn, a.btn {
                width: 100% !important;
                max-width: 100% !important;
            }
            
            input, textarea, select {
                width: 100% !important;
                font-size: 16px !important;
            }
        }
        
        @media (max-width: 640px) {
            body {
                font-size: 14px !important;
            }
            
            .p-10, .p-8, .p-6 {
                padding: 1rem !important;
            }
            
            .px-10, .px-8, .px-6 {
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
            }
            
            .text-3xl {
                font-size: 1.25rem !important;
            }
            
            .text-2xl {
                font-size: 1.125rem !important;
            }
            
            .space-x-6 > * + *, .space-x-4 > * + * {
                margin-left: 0.5rem !important;
            }
            
            .gap-6 {
                gap: 0.75rem !important;
            }
        }

        /* Logout Button Always Visible */
        .logout-form {
            display: inline-block !important;
        }
        
        .logout-form button {
            white-space: nowrap;
        }
        
        /* Clean Mobile Dashboard Layout */
        @media (max-width: 1024px) {
            /* Hide everything except hamburger, profile, and logout on mobile */
            .top-bar .space-x-6 > *:not(.logout-form) {
                display: none !important;
            }
            
            /* Mobile logout button - icon only */
            .logout-form button span {
                display: none;
            }
            
            .logout-form button {
                padding: 0.5rem !important;
                min-width: 40px;
                justify-content: center;
            }
            
            /* Hide everything except hamburger and profile on mobile (legacy) */
            .top-bar .space-x-6, .top-bar .space-x-4 {
                display: none !important;
            }
            
            .top-bar {
                padding: 0.75rem 1rem !important;
                min-height: 60px;
            }
            
            .mobile-menu-btn {
                width: 40px !important;
                height: 40px !important;
                margin-right: 0.5rem;
            }
            
            /* Top bar layout - just hamburger and profile */
            .top-bar > div {
                display: flex;
                align-items: center;
                justify-content: space-between;
                width: 100%;
                gap: 0.5rem;
            }
            
            .top-bar h1 {
                font-size: 1.25rem !important;
                margin: 0 !important;
                flex: 1;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            
            .top-bar p {
                display: none !important;
            }
            
            /* Profile image smaller on mobile */
            .top-bar img, .top-bar > div > div > img {
                width: 35px !important;
                height: 35px !important;
                min-width: 35px !important;
            }
            
            .top-bar .gradient-green {
                width: 35px !important;
                height: 35px !important;
                min-width: 35px !important;
                font-size: 1rem !important;
            }
        }
        
        @media (max-width: 768px) {
            /* Dashboard cards full width on mobile */
            .grid-cols-2, .grid-cols-3, .grid-cols-4 {
                grid-template-columns: 1fr !important;
            }
            
            /* Reduce padding on cards */
            .rounded-lg, .glass-card, .bg-white {
                padding: 1rem !important;
                margin-bottom: 1rem !important;
            }
            
            /* Text sizes */
            h1 { font-size: 1.25rem !important; }
            h2 { font-size: 1.1rem !important; }
            h3 { font-size: 1rem !important; }
            
            /* Buttons full width on mobile */
            .btn, button:not(.mobile-menu-btn) {
                width: 100% !important;
                margin-bottom: 0.5rem;
            }
            
            /* Make tables scrollable */
            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }
        
        @media (max-width: 640px) {
            /* Extra small screens */
            body {
                font-size: 14px !important;
            }
            
            .top-bar h1 {
                font-size: 1.1rem !important;
            }
            
            main, .p-10, .p-8 {
                padding: 0.75rem !important;
            }
            
            /* Compact cards */
            .glass-card, .bg-white, .rounded-lg {
                padding: 0.75rem !important;
            }
        }

        
        /* Desktop First - Sidebar visible, content has margin */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: 320px;
            z-index: 40;
        }
        
        .main-content {
            margin-left: 320px;
            width: calc(100% - 320px);
        }
        
        /* Tablet and Mobile - Sidebar hidden, content full width */
        @media (max-width: 1023px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0 !important;
                width: 100% !important;
            }
        }
        
        /* Desktop - Sidebar always visible */
        @media (min-width: 1024px) {
            .sidebar {
                transform: translateX(0) !important;
            }
            
            .main-content {
                margin-left: 320px !important;
                width: calc(100% - 320px) !important;
            }
        }


        /* ============================================
           PROPER RESPONSIVE DESIGN - AUTO DEVICE DETECTION
           ============================================ */
        
        /* Reset and Base Styles */
        * {
            box-sizing: border-box;
        }
        
        html {
            width: 100%;
            height: 100%;
            overflow-x: hidden;
            -webkit-text-size-adjust: 100%;
        }
        
        body {
            width: 100%;
            min-height: 100%;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }
        
        /* Sidebar Base Styles */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: 320px;
            height: 100vh;
            z-index: 40;
            overflow-y: auto;
        }
        
        /* Main Content Base Styles */
        .main-content {
            min-height: 100vh;
            position: relative;
        }
        
        /* ============================================
           MOBILE FIRST - PHONES (Default < 1024px)
           ============================================ */
        
        /* Mobile: Sidebar hidden by default */
        @media (max-width: 1023px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0 !important;
                width: 100% !important;
                max-width: 100vw !important;
            }
            
            body, html {
                overflow-x: hidden !important;
                max-width: 100vw !important;
            }
            
            /* Mobile: Show hamburger button */
            .mobile-menu-btn {
                display: flex !important;
            }
        }
        
        /* ============================================
           DESKTOP - LARGE SCREENS (≥ 1024px)
           ============================================ */
        
        @media (min-width: 1024px) {
            /* Desktop: Sidebar always visible */
            .sidebar {
                transform: translateX(0) !important;
                display: block !important;
            }
            
            /* Desktop: Content has margin for sidebar */
            .main-content {
                margin-left: 320px !important;
                width: calc(100% - 320px) !important;
            }
            
            /* Desktop: Hide hamburger button */
            .mobile-menu-btn {
                display: none !important;
            }
        }
        
        /* ============================================
           SPECIFIC MOBILE FIXES
           ============================================ */
        
        @media (max-width: 767px) {
            /* Extra small devices */
            .top-bar {
                padding: 0.75rem 1rem !important;
            }
            
            .top-bar h1 {
                font-size: 1.25rem !important;
            }
            
            /* Hide non-essential items in header */
            .top-bar .space-x-6 > button:not(.logout-form) {
                display: none !important;
            }
        }


        /* ============================================
           AGGRESSIVE MOBILE FIX - OVERRIDE EVERYTHING
           ============================================ */
        
        /* Force full width on mobile - Override Tailwind */
        @media (max-width: 1023px) {
            
            /* Force main content to be full width on mobile */
            .main-content,
            div.main-content,
            .flex-1.main-content {
                margin-left: 0 !important;
                margin-right: 0 !important;
                width: 100vw !important;
                max-width: 100vw !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
                left: 0 !important;
                right: 0 !important;
                position: relative !important;
            }
            
            /* Force body and html to prevent horizontal scroll */
            html, body {
                width: 100vw !important;
                max-width: 100vw !important;
                overflow-x: hidden !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            
            /* Force all containers inside main-content to be full width */
            .main-content > * {
                max-width: 100vw !important;
            }
            
            /* Force the top bar to be full width */
            .top-bar {
                margin-left: 0 !important;
                margin-right: 0 !important;
                width: 100vw !important;
                max-width: 100vw !important;
                left: 0 !important;
                right: 0 !important;
            }
            
            /* Force main tag to be full width */
            main {
                margin-left: 0 !important;
                margin-right: 0 !important;
                width: 100% !important;
                max-width: 100vw !important;
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
            
            /* Hide sidebar completely on mobile */
            .sidebar {
                display: none !important;
            }
            
            .sidebar.show {
                display: block !important;
            }
        }

    </style>
</head>
<!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay" onclick="toggleMobileMenu()"></div>
    
<body class="font-sans antialiased">
    <div class="flex min-h-screen relative">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar" w-80 h-screen fixed left-0 top-0 z-40 flex flex-col overflow-hidden">
            <!-- Logo Section - Fixed at top -->
            <div class="p-8 border-b border-white border-opacity-10 flex-shrink-0">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 gradient-green rounded-2xl flex items-center justify-center shadow-2xl logo-glow pulse-glow">
                        <i class="fas fa-church text-white text-3xl"></i>
                    </div>
                    <div>
                        <h2 class="text-green-300 font-black text-2xl tracking-tight">Pastor Portal</h2>
                        <p class="text-green-200 text-xs font-medium">Ministry Leadership Dashboard</p>
                    </div>
                </div>
            </div>
            
            <!-- Scrollable Navigation -->
            <nav class="p-6 space-y-2 flex-1 sidebar-nav" style="overflow-y: scroll !important; overflow-x: hidden !important; max-height: calc(100vh - 250px); min-height: 400px;">
                <a href="{{ route('pastor.dashboard') }}" class="sidebar-item {{ request()->routeIs('pastor.dashboard') ? 'active' : '' }} flex items-center text-green-300 hover:text-green-200 px-6 py-4 rounded-2xl group relative z-10">
                    <div class="icon-box w-12 h-12 flex items-center justify-center rounded-xl {{ request()->routeIs('pastor.dashboard') ? 'gradient-green' : 'bg-white/5' }} group-hover:gradient-green transition-all relative z-10">
                        <i class="fas fa-home text-white"></i>
                    </div>
                    <span class="ml-4 font-semibold text-sm relative z-10">Dashboard</span>
                    <i class="fas fa-chevron-right ml-auto text-xs opacity-0 group-hover:opacity-100 transition-opacity relative z-10 text-green-300"></i>
                </a>
                
                <a href="{{ route('pastor.sermons') }}" class="sidebar-item {{ request()->routeIs('pastor.sermons') ? 'active' : '' }} flex items-center text-green-300 hover:text-green-200 px-6 py-4 rounded-2xl group relative z-10">
                    <div class="icon-box w-12 h-12 flex items-center justify-center rounded-xl {{ request()->routeIs('pastor.sermons') ? 'gradient-blue' : 'bg-white/5' }} group-hover:gradient-blue transition-all relative z-10">
                        <i class="fas fa-book-open text-white"></i>
                    </div>
                    <span class="ml-4 font-semibold text-sm relative z-10">Sermons</span>
                    <i class="fas fa-chevron-right ml-auto text-xs opacity-0 group-hover:opacity-100 transition-opacity relative z-10 text-green-300"></i>
                </a>
                
                <a href="{{ route('pastor.prayer-requests') }}" class="sidebar-item {{ request()->routeIs('pastor.prayer-requests') ? 'active' : '' }} flex items-center text-green-300 hover:text-green-200 px-6 py-4 rounded-2xl group relative z-10">
                    <div class="icon-box w-12 h-12 flex items-center justify-center rounded-xl {{ request()->routeIs('pastor.prayer-requests') ? 'gradient-purple' : 'bg-white/5' }} group-hover:gradient-purple transition-all relative z-10">
                        <i class="fas fa-praying-hands text-white"></i>
                    </div>
                    <span class="ml-4 font-semibold text-sm relative z-10">Prayer Requests</span>
                    <i class="fas fa-chevron-right ml-auto text-xs opacity-0 group-hover:opacity-100 transition-opacity relative z-10 text-green-300"></i>
                </a>
                
                <a href="{{ route('pastor.members') }}" class="sidebar-item {{ request()->routeIs('pastor.members') ? 'active' : '' }} flex items-center text-green-300 hover:text-green-200 px-6 py-4 rounded-2xl group relative z-10">
                    <div class="icon-box w-12 h-12 flex items-center justify-center rounded-xl {{ request()->routeIs('pastor.members') ? 'gradient-blue' : 'bg-white/5' }} group-hover:gradient-blue transition-all relative z-10">
                        <i class="fas fa-users text-white"></i>
                    </div>
                    <span class="ml-4 font-semibold text-sm relative z-10">Members</span>
                    <i class="fas fa-chevron-right ml-auto text-xs opacity-0 group-hover:opacity-100 transition-opacity relative z-10 text-green-300"></i>
                </a>
                
                <a href="{{ route('pastor.events') }}" class="sidebar-item {{ request()->routeIs('pastor.events') ? 'active' : '' }} flex items-center text-green-300 hover:text-green-200 px-6 py-4 rounded-2xl group relative z-10">
                    <div class="icon-box w-12 h-12 flex items-center justify-center rounded-xl {{ request()->routeIs('pastor.events') ? 'gradient-purple' : 'bg-white/5' }} group-hover:gradient-purple transition-all relative z-10">
                        <i class="fas fa-calendar-alt text-white"></i>
                    </div>
                    <span class="ml-4 font-semibold text-sm relative z-10">Events</span>
                    <i class="fas fa-chevron-right ml-auto text-xs opacity-0 group-hover:opacity-100 transition-opacity relative z-10 text-green-300"></i>
                </a>
                
                <a href="{{ route('pastor.counselling') }}" class="sidebar-item {{ request()->routeIs('pastor.counselling') ? 'active' : '' }} flex items-center text-green-300 hover:text-green-200 px-6 py-4 rounded-2xl group relative z-10">
                    <div class="icon-box w-12 h-12 flex items-center justify-center rounded-xl {{ request()->routeIs('pastor.counselling') ? 'gradient-cyan' : 'bg-white/5' }} group-hover:gradient-cyan transition-all relative z-10">
                        <i class="fas fa-hands-helping text-white"></i>
                    </div>
                    <span class="ml-4 font-semibold text-sm relative z-10">Counselling</span>
                    <i class="fas fa-chevron-right ml-auto text-xs opacity-0 group-hover:opacity-100 transition-opacity relative z-10 text-green-300"></i>
                </a>
                
                <a href="{{ route('pastor.finance') }}" class="sidebar-item {{ request()->routeIs('pastor.finance') ? 'active' : '' }} flex items-center text-green-300 hover:text-green-200 px-6 py-4 rounded-2xl group relative z-10">
                    <div class="icon-box w-12 h-12 flex items-center justify-center rounded-xl {{ request()->routeIs('pastor.finance') ? 'gradient-orange' : 'bg-white/5' }} group-hover:gradient-orange transition-all relative z-10">
                        <i class="fas fa-dollar-sign text-white"></i>
                    </div>
                    <span class="ml-4 font-semibold text-sm relative z-10">Finance</span>
                    <i class="fas fa-chevron-right ml-auto text-xs opacity-0 group-hover:opacity-100 transition-opacity relative z-10 text-green-300"></i>
                </a>
                
                <a href="{{ route('pastor.broadcast') }}" class="sidebar-item {{ request()->routeIs('pastor.broadcast') ? 'active' : '' }} flex items-center text-green-300 hover:text-green-200 px-6 py-4 rounded-2xl group relative z-10">
                    <div class="icon-box w-12 h-12 flex items-center justify-center rounded-xl {{ request()->routeIs('pastor.broadcast') ? 'gradient-pink' : 'bg-white/5' }} group-hover:gradient-pink transition-all relative z-10">
                        <i class="fas fa-bullhorn text-white"></i>
                    </div>
                    <span class="ml-4 font-semibold text-sm relative z-10">Broadcast</span>
                    <i class="fas fa-chevron-right ml-auto text-xs opacity-0 group-hover:opacity-100 transition-opacity relative z-10 text-green-300"></i>
                </a>
                
                <a href="{{ route('pastor.ai-assistant') }}" class="sidebar-item {{ request()->routeIs('pastor.ai-assistant') ? 'active' : '' }} flex items-center text-green-300 hover:text-green-200 px-6 py-4 rounded-2xl group relative z-10">
                    <div class="icon-box w-12 h-12 flex items-center justify-center rounded-xl {{ request()->routeIs('pastor.ai-assistant') ? 'gradient-cyan' : 'bg-white/5' }} group-hover:gradient-cyan transition-all relative z-10">
                        <i class="fas fa-robot text-white"></i>
                    </div>
                    <span class="ml-4 font-semibold text-sm relative z-10">AI Assistant</span>
                    <i class="fas fa-chevron-right ml-auto text-xs opacity-0 group-hover:opacity-100 transition-opacity relative z-10 text-green-300"></i>
                </a>
                
                <a href="{{ route('pastor.settings') }}" class="sidebar-item {{ request()->routeIs('pastor.settings') ? 'active' : '' }} flex items-center text-green-300 hover:text-green-200 px-6 py-4 rounded-2xl group relative z-10">
                    <div class="icon-box w-12 h-12 flex items-center justify-center rounded-xl {{ request()->routeIs('pastor.settings') ? 'gradient-orange' : 'bg-white/5' }} group-hover:gradient-orange transition-all relative z-10">
                        <i class="fas fa-cog text-white"></i>
                    </div>
                    <span class="ml-4 font-semibold text-sm relative z-10">Settings</span>
                    <i class="fas fa-chevron-right ml-auto text-xs opacity-0 group-hover:opacity-100 transition-opacity relative z-10 text-green-300"></i>
                </a>
                
            </nav>
            
            <!-- Logout Button in Sidebar - Fixed at bottom -->
            <div class="p-6 border-t border-white border-opacity-10 flex-shrink-0 space-y-3">
                <!-- Logout Button -->
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" 
                            class="w-full flex items-center justify-center space-x-2 px-4 py-3 bg-red-500/20 hover:bg-red-500 rounded-xl text-red-400 hover:text-white transition-all border border-red-500/30 hover:border-red-500 group"
                            onclick="return confirm('Are you sure you want to logout?')">
                        <i class="fas fa-sign-out-alt text-lg"></i>
                        <span class="font-semibold text-sm">Logout</span>
                    </button>
                </form>
                
            <!-- Premium Badge - Fixed at bottom -->
            <div class="p-6 border-t border-white border-opacity-10 flex-shrink-0">
                <div class="glass-card p-4 rounded-2xl shimmer">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 gradient-green rounded-xl flex items-center justify-center">
                            <i class="fas fa-crown text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="text-white text-xs font-bold">Premium Plan</p>
                            <p class="text-emerald-300 text-xs">Unlimited Access</p>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 main-content relative z-10">
            <!-- Top Bar -->
            <header class="top-bar sticky top-0 z-30">
                <div class="flex items-center justify-between px-10 py-4">
                    <!-- Mobile Menu Button -->
                    <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <div class="flex-1 flex items-center gap-2">
                        <h1 class="text-xl font-black text-green-300">@yield('page-title', 'Dashboard')</h1>
                        <span class="text-xs text-green-200">@yield('page-subtitle', 'Welcome back to your workspace')</span>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <button onclick="toggleSearch()" class="w-12 h-12 bg-white/5 rounded-xl flex items-center justify-center hover:bg-white/10 transition-all text-green-300 hover:text-green-200">
                            <i class="fas fa-search text-lg"></i>
                        </button>
                        
                        <button onclick="toggleNotifications()" class="relative w-12 h-12 bg-white/5 rounded-xl flex items-center justify-center hover:bg-white/10 transition-all text-green-300 hover:text-green-200">
                            <i class="fas fa-bell text-lg"></i>
                            <span class="absolute -top-1 -right-1 w-6 h-6 bg-gradient-to-br from-red-500 to-pink-600 rounded-full text-white text-xs flex items-center justify-center font-bold shadow-lg">3</span>
                        </button>
                        
                        <div class="text-right">
                            <p class="text-sm font-semibold text-green-300 whitespace-nowrap">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-green-200">Pastor</p>
                        </div>
                        
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('uploads/profiles/' . auth()->user()->profile_photo) }}" 
                                 alt="{{ auth()->user()->name }}"
                                 class="w-12 h-12 rounded-2xl object-cover border-2 border-green-500 shadow-xl cursor-pointer hover:scale-105 transition-transform">
                        @else
                            <div class="w-12 h-12 gradient-green rounded-2xl flex items-center justify-center text-white font-black text-lg shadow-xl cursor-pointer hover:scale-105 transition-transform">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                        
                        <!-- Logout Button -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="w-12 h-12 bg-red-500/20 hover:bg-red-500 rounded-xl flex items-center justify-center text-red-400 hover:text-white transition-all border border-red-500/30 hover:border-red-500"
                                    onclick="return confirm('Are you sure you want to logout?')" title="Logout">
                                <i class="fas fa-sign-out-alt text-lg"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="animate-fade-in">
                <div class="max-w-[1920px] mx-auto px-8 py-8 space-y-6">
                    @if (session('success'))
                    <div class="mb-8 glass-card border-l-4 border-green-500 px-6 py-5 rounded-2xl flex items-center space-x-4 shimmer">
                        <div class="w-12 h-12 gradient-green rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-check-circle text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="font-bold text-green-300 text-lg">Success!</p>
                            <p class="text-sm text-green-200">{{ session('success') }}</p>
                        </div>
                        <button class="ml-auto text-gray-400 hover:text-white transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-8 glass-card border-l-4 border-red-500 px-6 py-5 rounded-2xl flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-pink-600 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="font-bold text-red-300 text-lg">Error!</p>
                            <p class="text-sm text-red-200">{{ session('error') }}</p>
                        </div>
                        <button class="ml-auto text-gray-400 hover:text-white transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    
    <!-- Search Modal -->
    <div id="searchModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-start justify-center pt-20">
        <div class="glass-card max-w-2xl w-full mx-4 p-6 rounded-2xl animate-fade-in">
            <!-- Mobile Menu Button -->
                    <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-green-300">Search</h3>
                <button onclick="toggleSearch()" class="text-gray-400 hover:text-white transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Search members, visitors, transactions..." 
                       class="w-full px-4 py-3 pl-12 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500 transition-all">
                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
            <div class="mt-4 space-y-2">
                <p class="text-sm text-gray-400">Quick links:</p>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('members.index') }}" class="btn btn-outline btn-sm">
                        <i class="fas fa-users"></i> Members
                    </a>
                    <a href="{{ route('attendance.index') }}" class="btn btn-outline btn-sm">
                        <i class="fas fa-calendar-check"></i> Attendance
                    </a>
                    <a href="{{ route('donations.index') }}" class="btn btn-outline btn-sm">
                        <i class="fas fa-dollar-sign"></i> Donations
                    </a>
                    <a href="{{ route('visitors.index') }}" class="btn btn-outline btn-sm">
                        <i class="fas fa-user-plus"></i> Visitors
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Notifications Dropdown -->
    <div id="notificationsDropdown" class="hidden fixed top-20 right-8 w-96 glass-card rounded-2xl p-6 z-50 animate-fade-in">
        <!-- Mobile Menu Button -->
                    <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-green-300">Notifications</h3>
            <button onclick="toggleNotifications()" class="text-gray-400 hover:text-white transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="space-y-3">
            <div class="bg-white/5 p-4 rounded-xl hover:bg-white/10 transition-all cursor-pointer border-l-4 border-green-500">
                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 gradient-green rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-user-plus text-white"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-white font-semibold text-sm">New Member Added</p>
                        <p class="text-gray-400 text-xs mt-1">John Doe joined the church</p>
                        <p class="text-green-300 text-xs mt-1">2 hours ago</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white/5 p-4 rounded-xl hover:bg-white/10 transition-all cursor-pointer border-l-4 border-blue-500">
                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 gradient-blue rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-calendar-check text-white"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-white font-semibold text-sm">Attendance Marked</p>
                        <p class="text-gray-400 text-xs mt-1">150 members present today</p>
                        <p class="text-green-300 text-xs mt-1">5 hours ago</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white/5 p-4 rounded-xl hover:bg-white/10 transition-all cursor-pointer border-l-4 border-orange-500">
                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 gradient-orange rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-dollar-sign text-white"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-white font-semibold text-sm">New Donation</p>
                        <p class="text-gray-400 text-xs mt-1">₵500 received from Sarah</p>
                        <p class="text-green-300 text-xs mt-1">1 day ago</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-white/10">
            <a href="#" class="text-green-300 hover:text-green-200 text-sm font-semibold flex items-center justify-center">
                View All Notifications
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
    
    <script>
        function toggleSearch() {
            const modal = document.getElementById('searchModal');
            const input = document.getElementById('searchInput');
            modal.classList.toggle('hidden');
            if (!modal.classList.contains('hidden')) {
                setTimeout(() => input.focus(), 100);
            }
        }
        
        function toggleNotifications() {
            const dropdown = document.getElementById('notificationsDropdown');
            dropdown.classList.toggle('hidden');
        }
        
        // Close modals when clicking outside
        document.addEventListener('click', function(event) {
            const searchModal = document.getElementById('searchModal');
            const notificationsDropdown = document.getElementById('notificationsDropdown');
            
            if (event.target === searchModal) {
                searchModal.classList.add('hidden');
            }
        });
        
        // Close search modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                document.getElementById('searchModal').classList.add('hidden');
                document.getElementById('notificationsDropdown').classList.add('hidden');
            }
        });
        
        // Search functionality
        document.getElementById('searchInput')?.addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase();
            // Add your search logic here
            console.log('Searching for:', query);
        });
        
        // ==========================================
        // THEME SYSTEM - Load and Apply Theme
        // ==========================================
        function loadTheme() {
            let theme = localStorage.getItem('theme') || 'dark';
            
            // Handle auto theme
            if (theme === 'auto') {
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                theme = prefersDark ? 'dark' : 'light';
            }
            
            applyThemeToPage(theme);
        }
        
        function applyThemeToPage(theme) {
            const body = document.body;
            
            // Remove all theme classes
            body.classList.remove('theme-light', 'theme-dark');
            
            // Add the appropriate theme class
            if (theme === 'light') {
                body.classList.add('theme-light');
            } else {
                body.classList.add('theme-dark');
            }
        }
        
        // Apply theme immediately (before page fully loads)
        loadTheme();
        
        // Also apply theme when page is fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            loadTheme();
        });
        
        // Listen for storage changes (when theme is changed in another tab or settings page)
        window.addEventListener('storage', function(e) {
            if (e.key === 'theme') {
                loadTheme();
            }
        });
    </script>

    <script>
        // Mobile menu toggle
        function toggleMobileMenu() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobileOverlay');
            
            sidebar.classList.toggle('mobile-open');
            overlay.classList.toggle('active');
        }
        
        // Close mobile menu when clicking a link
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarLinks = document.querySelectorAll('.sidebar a');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 1024) {
                        toggleMobileMenu();
                    }
                });
            });
            
            // Close menu on window resize if desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth > 1024) {
                    const sidebar = document.getElementById('sidebar');
                    const overlay = document.getElementById('mobileOverlay');
                    if (sidebar) sidebar.classList.remove('mobile-open');
                    if (overlay) overlay.classList.remove('active');
                }
            });
        });
    </script>
</body>
</html>
