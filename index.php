<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="description" content="Light & Style is a premium lighting store in Bangalore offering designer chandeliers and lamps, pendant lights, wall lights, and commercial lighting solutions." />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="keywords" content="lighting store Bangalore, chandeliers, decorative lights, Light & Style">
  <title>Light & Style | Lighting Store in Bangalore</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <link rel="stylesheet" href="style.css" />
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>
  <style>
    /* Enhanced Animations and Effects */
    
    /* Global Font Override - Use Playfair Display throughout */
    * {
      font-family: 'Playfair Display', serif !important;
    }
    
    /* Loading Animation */
    .loading-screen {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, #1a1a1a 0%, #2c2c2c 100%);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 9999;
      opacity: 1;
      transition: opacity 0.8s ease;
    }

    .loading-screen.fade-out {
      opacity: 0;
      pointer-events: none;
    }

    .loader {
      width: 80px;
      height: 80px;
      border: 4px solid rgba(255,255,255,0.1);
      border-top: 4px solid #e9bb24;
      border-radius: 50%;
      animation: spin 1.2s linear infinite;
      box-shadow: 0 0 20px rgba(233, 187, 36, 0.3);
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    /* Page entrance animations */
    .page-content {
      opacity: 0;
      animation: pageLoad 1s ease 0.5s forwards;
    }

    @keyframes pageLoad {
      to { opacity: 1; }
    }

    /* Subtle section reveals */
    .section-reveal {
      opacity: 0;
      transform: translateY(20px);
      transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .section-reveal.visible {
      opacity: 1;
      transform: translateY(0);
    }

    /* Global Animation Classes */
    .fade-in {
      opacity: 0;
      transform: translateY(20px);
      animation: fadeInUp 0.8s ease forwards;
    }

    .fade-in-left {
      opacity: 0;
      transform: translateX(-30px);
      animation: fadeInLeft 0.8s ease forwards;
    }

    .fade-in-right {
      opacity: 0;
      transform: translateX(30px);
      animation: fadeInRight 0.8s ease forwards;
    }

    .scale-in {
      opacity: 0;
      transform: scale(0.95);
      animation: scaleIn 0.6s ease forwards;
    }

    @keyframes fadeInUp {
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes fadeInText {
      to {
        opacity: 1;
      }
    }

    @keyframes fadeInLeft {
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    @keyframes fadeInRight {
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    @keyframes scaleIn {
      to {
        opacity: 1;
        transform: scale(1);
      }
    }

    /* Staggered Animation Delays */
    .fade-in:nth-child(1) { animation-delay: 0.1s; }
    .fade-in:nth-child(2) { animation-delay: 0.2s; }
    .fade-in:nth-child(3) { animation-delay: 0.3s; }
    .fade-in:nth-child(4) { animation-delay: 0.4s; }
    .fade-in:nth-child(5) { animation-delay: 0.5s; }

    /* Glowing Text Effect */
    .highlight {
      background: linear-gradient(45deg, #e9bb24, #f0d04a, #e9bb24);
      background-size: 200% 200%;
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    /* Navigation Hover Effects */
    .navigation {
      position: relative;
      transition: all 0.3s ease;
      padding: 4px 8px;
      border-radius: 4px;
    }

    .navigation::after {
      content: '';
      position: absolute;
      bottom: -2px;
      left: 50%;
      width: 0;
      height: 1px;
      background: #e9bb24;
      transition: all 0.3s ease;
      transform: translateX(-50%);
    }

    .navigation:hover::after {
      width: 80%;
    }

    .navigation:hover {
      color: #e9bb24;
      background: rgba(233, 187, 36, 0.05);
    }

    /* ========================================
       MOBILE HAMBURGER MENU STYLES
       ======================================== */
    
    /* Mobile menu button */
    .mobile-menu-btn {
      display: none;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      width: 40px;
      height: 40px;
      background: transparent;
      border: none;
      cursor: pointer;
      padding: 0;
      z-index: 1001;
      transition: all 0.3s ease;
    }
    
    .hamburger-line {
      width: 25px;
      height: 3px;
      background: #e9bb24;
      margin: 3px 0;
      transition: all 0.3s ease;
      transform-origin: center;
    }
    
    /* Hamburger animation */
    .mobile-menu-btn.active .hamburger-line:nth-child(1) {
      transform: rotate(45deg) translate(6px, 6px);
    }
    
    .mobile-menu-btn.active .hamburger-line:nth-child(2) {
      opacity: 0;
    }
    
    .mobile-menu-btn.active .hamburger-line:nth-child(3) {
      transform: rotate(-45deg) translate(6px, -6px);
    }
    
    /* Desktop navigation */
    .desktop-nav {
      display: flex;
      gap: 2rem;
      align-items: center;
    }
    
    /* Navigation container for desktop/mobile switching */
    .nav-container {
      display: flex;
      align-items: center;
      gap: 1rem;
    }
    
    /* Mobile navigation dropdown */
    .mobile-nav {
      display: none;
      position: absolute;
      top: 100%;
      left: 0;
      right: 0;
      background: rgba(17, 17, 17, 0.98);
      backdrop-filter: blur(10px);
      border-top: 1px solid rgba(233, 187, 36, 0.3);
      padding: 1rem;
      transform: translateY(-100%);
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
      z-index: 1000;
    }
    
    .mobile-nav.active {
      transform: translateY(0);
      opacity: 1;
      visibility: visible;
    }
    
    .mobile-nav a {
      display: block;
      padding: 1rem;
      color: #fff;
      text-decoration: none;
      font-family: 'Playfair Display', serif;
      font-size: 1.2rem;
      border-bottom: 1px solid rgba(233, 187, 36, 0.1);
      transition: all 0.3s ease;
    }
    
    .mobile-nav a:last-child {
      border-bottom: none;
    }
    
    .mobile-nav a:hover {
      color: #e9bb24;
      background: rgba(233, 187, 36, 0.1);
      padding-left: 1.5rem;
    }
    
    /* Responsive header layout */
    .navbar .container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: relative;
      gap: 1rem;
    }
    
    /* Mobile responsive breakpoints */
    @media (max-width: 768px) {
      .mobile-menu-btn {
        display: flex;
      }
      
      .desktop-nav {
        display: none;
      }
      
      .mobile-nav {
        display: block;
      }
      
      .logo img {
        height: 35px;
      }
      
      .brand-name {
        font-size: clamp(1.5rem, 4vw, 2.5rem);
      }
    }
    
    @media (max-width: 480px) {
      .navbar .container {
        gap: 0.5rem;
      }
      
      .logo img {
        height: 30px;
      }
      
      .brand-name {
        font-size: clamp(1.2rem, 5vw, 2rem);
      }
      
      .mobile-menu-btn {
        width: 35px;
        height: 35px;
      }
      
      .hamburger-line {
        width: 20px;
        height: 2px;
      }
    }

    /* Logo Animation */
    .logo {
      transition: all 0.3s ease;
    }

    .logo:hover {
      transform: scale(1.02);
    }

    .logo img {
      transition: all 0.3s ease;
    }

    .brand-name {
      transition: color 0.3s ease;
      color: #e9bb24;
    }

    .logo:hover .brand-name {
      color: #f0d04a;
    }

    /* Category Card Animations - Subtle with Goldish Effect */
    .category {
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
      opacity: 0;
      transform: translateY(15px);
      animation: slideInUp 0.6s ease forwards;
      border: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* Subtle gold border effect on hover */
    .category::before {
      content: '';
      position: absolute;
      top: -1px;
      left: -1px;
      right: -1px;
      bottom: -1px;
      background: linear-gradient(45deg, rgba(233, 187, 36, 0.3), rgba(240, 208, 74, 0.3), rgba(233, 187, 36, 0.3));
      background-size: 300% 300%;
      z-index: -1;
      border-radius: inherit;
      opacity: 0;
      transition: opacity 0.4s ease;
      animation: subtleGradient 4s ease infinite;
    }

    /* Subtle inner glow */
    .category::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: radial-gradient(circle at center, rgba(233, 187, 36, 0.05), transparent 70%);
      opacity: 0;
      transition: opacity 0.4s ease;
      border-radius: inherit;
      pointer-events: none;
    }

    @keyframes subtleGradient {
      0%, 100% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
    }

    /* Staggered animation delays for each category */
    .category-link:nth-child(1) .category { animation-delay: 0.1s; }
    .category-link:nth-child(2) .category { animation-delay: 0.2s; }
    .category-link:nth-child(3) .category { animation-delay: 0.3s; }
    .category-link:nth-child(4) .category { animation-delay: 0.4s; }

    @keyframes slideInUp {
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .category:hover::before {
      opacity: 1;
    }

    .category:hover::after {
      opacity: 1;
    }

    .category:hover {
      transform: translateY(-5px) scale(1.02);
      box-shadow: 
        0 8px 25px rgba(0, 0, 0, 0.2),
        0 0 20px rgba(233, 187, 36, 0.15);
      border-color: rgba(233, 187, 36, 0.3);
    }

    .category-images {
      overflow: hidden;
      border-radius: 8px;
      position: relative;
    }

    .category-images img {
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      transform: scale(1);
      filter: brightness(0.95) saturate(1.1);
    }

    .category:hover .category-images img {
      transform: scale(1.05);
      filter: brightness(1.05) saturate(1.2) contrast(1.1);
    }

    .category h3 {
      transition: all 0.4s ease;
      position: relative;
      z-index: 2;
    }

    .category:hover h3 {
      color: #e9bb24;
      transform: translateY(-1px);
      text-shadow: 0 0 8px rgba(233, 187, 36, 0.3);
    }

    /* Section Titles Animation */
    .section-title {
      opacity: 0;
      transform: translateY(30px);
      animation: fadeInUp 0.8s ease forwards;
    }

    /* Button Effects - Better and Elegant */
    .btn {
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
      border: 2px solid transparent;
      background: linear-gradient(135deg, #e9bb24, #f0d04a);
    }

    /* Gradient border effect */
    .btn::before {
      content: '';
      position: absolute;
      top: -2px;
      left: -2px;
      right: -2px;
      bottom: -2px;
      background: linear-gradient(45deg, #e9bb24, #f0d04a, #e9bb24, #f0d04a);
      background-size: 400% 400%;
      z-index: -1;
      border-radius: inherit;
      opacity: 0;
      transition: opacity 0.4s ease;
      animation: gradientRotate 3s ease infinite;
    }

    /* Glow effect */
    .btn::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: inherit;
      border-radius: inherit;
      filter: blur(15px);
      opacity: 0;
      z-index: -2;
      transition: opacity 0.4s ease;
    }

    @keyframes gradientRotate {
      0%, 100% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
    }

    .btn:hover::before {
      opacity: 1;
    }

    .btn:hover::after {
      opacity: 0.6;
    }

    .btn:hover {
      background: linear-gradient(135deg, #f0d04a, #e9bb24);
      transform: translateY(-2px) scale(1.02);
      box-shadow: 
        0 8px 25px rgba(233, 187, 36, 0.4),
        0 0 0 1px rgba(233, 187, 36, 0.3);
      color: #000;
    }

    .btn:active {
      transform: translateY(0) scale(1);
    }

    /* Explore Products Section Animation */
    .explore-text {
      opacity: 0;
      transform: translateY(15px);
      animation: fadeInUp 0.6s ease 0.2s forwards;
    }

    .explore-image {
      opacity: 0;
      transform: translateY(15px);
      animation: fadeInUp 0.6s ease 0.4s forwards;
    }

    .explore-image img {
      transition: all 0.4s ease;
    }

    .explore-image:hover img {
      transform: scale(1.02);
      filter: brightness(1.05);
    }

    /* Form Input Effects - Subtle */
    .enquiry-form {
      opacity: 0;
      transform: translateY(15px);
      animation: fadeInUp 0.6s ease 0.3s forwards;
    }

    .enquiry-form input, .enquiry-form textarea {
      transition: all 0.3s ease;
      border: 2px solid transparent;
    }

    .enquiry-form input:focus, .enquiry-form textarea:focus {
      border-color: #e9bb24;
      box-shadow: 0 0 8px rgba(233, 187, 36, 0.2);
      transform: translateY(-1px);
      background: rgba(0,0,0,0.6) !important;
      outline: none;
    }

    .enquiry-form button {
      transition: all 0.3s ease;
    }

    .enquiry-form button:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(233, 187, 36, 0.3);
    }

    /* Force dark backgrounds for all form inputs */
    input, textarea, input[type="text"], input[type="email"], input[type="file"], textarea {
      background: rgba(0,0,0,0.8) !important;
      color: #fff !important;
      border: 0.5px solid rgba(233, 187, 36, 0.5) !important;
    }

    input:focus, textarea:focus, input[type="text"]:focus, input[type="email"]:focus {
      background: rgba(0,0,0,0.9) !important;
      color: #fff !important;
      border: 0.5px solid rgba(233, 187, 36, 0.5) !important;
      box-shadow: 0.5px solid rgba(233, 187, 36, 0.5) !important;
    }

    /* Override browser autocomplete/autofill styling */
    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus,
    input:-webkit-autofill:active {
      -webkit-box-shadow: 0 0 0 30px rgba(0,0,0,0.9) inset !important;
      -webkit-text-fill-color: #fff !important;
      background-color: rgb0 0 8px rgba(233, 187, 36, 0.4)a(0,0,0,0.9) !important;
      background: rgba(0,0,0,0.9) !important;
      border: 0.5px solid rgba(233, 187, 36, 0.5) !important;
    }

    /* Firefox autofill override */
    input:-moz-autofill,
    input:-moz-autofill:hover,
    input:-moz-autofill:focus {
      background-color: rgba(0,0,0,0.9) !important;
      color: #fff !important;
      border: 0.5px solid rgba(233, 187, 36, 0.5) !important;
    }

    /* Additional autocomplete overrides */
    input[autocomplete]:not([autocomplete=""]) {
      background: rgba(0,0,0,0.9) !important;
      color: #fff !important;
      border: 0.5px solid rgba(233, 187, 36, 0.5) !important;
    }

    /* Ensure filled inputs remain dark with gold border */
    input:not(:placeholder-shown) {
      background: rgba(0,0,0,0.9) !important;
      color: #fff !important;
      border: 0.5px solid rgba(233, 187, 36, 0.5) !important;
    }

    textarea:not(:placeholder-shown) {
      background: rgba(0,0,0,0.9) !important;
      color: #fff !important;
      border: 0.5px solid rgba(233, 187, 36, 0.5) !important;
    }

    /* Placeholder styling */
    input::placeholder, textarea::placeholder {
      color: rgba(255, 255, 255, 0.6) !important;
      opacity: 1;
    }

    input:focus::placeholder, textarea:focus::placeholder {
      color: rgba(255, 255, 255, 0.4) !important;
    }

    /* Contact sections animations - Single column layout */
    .contact-section-item {
      opacity: 0;
      transform: translateY(30px);
      animation: fadeInUp 0.8s ease forwards;
      margin-bottom: 3rem;
      padding: 2rem;
      background: rgba(255, 255, 255, 0.03);
      border-radius: 12px;
      border: 1px solid rgba(233, 187, 36, 0.1);
      transition: all 0.4s ease;
      max-width: 100%;
    }

    .contact-section-item:hover {
      background: rgba(255, 255, 255, 0.05);
      border-color: rgba(233, 187, 36, 0.2);
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }

    .contact-us-section {
      width: 100%;
      max-width: 1200px;
      margin: 0 auto;
      padding: 2rem 1rem;
      box-sizing: border-box;
    }

    /* Staggered animation delays for each section */
    .contact-section-item:nth-child(1) { animation-delay: 0.2s; }
    .contact-section-item:nth-child(2) { animation-delay: 0.4s; }
    .contact-section-item:nth-child(3) { animation-delay: 0.6s; }
    .contact-section-item:nth-child(4) { animation-delay: 0.8s; }

    /* Section headers with underline effect */
    .section-header {
      position: relative;
      margin-bottom: 1.5rem;
      padding-bottom: 0.5rem;
      text-align: center !important;
    }

    .section-header::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 50px;
      height: 2px;
      background: #e9bb24;
      transition: width 0.3s ease;
    }

    .contact-section-item:hover .section-header::after {
      width: 100px;
    }

    /* Contact enquiry container - single column */
    .contact-enquiry-container {
      max-width: 800px;
      margin: 0 auto;
    }

    /* Mobile responsive for contact section */
    @media (max-width: 768px) {
      .contact-section-item {
        padding: 1.5rem 1rem !important;
        margin-bottom: 2rem;
      }
      
      .section-header {
        font-size: 1.5em !important;
        padding: 0 0.5rem;
      }

      .contact-us-section {
        min-width: 100% !important;
        height: auto !important;
        min-height: auto !important;
      }

      .contact-us-section > div {
        padding: 0.5rem !important;
      }

      .contact-us-section > div > div {
        padding: 1.5rem !important;
        margin-bottom: 1.5rem !important;
      }

      .contact-us-section p {
        font-size: 1rem !important;
        line-height: 1.5 !important;
      }

      .contact-us-section span {
        font-size: 1.1em !important;
      }

      /* About section mobile */
      .contact-section-item p {
        text-align: left !important;
        font-size: 1rem !important;
        padding: 0 0.5rem;
      }

      /* Family carousel mobile */
      .family-carousel-container {
        height: auto !important;
        min-height: 200px !important;
      }

      /* Enquiry form mobile */
      #enquiryForm input,
      #enquiryForm textarea {
        font-size: 16px !important;
      }

      #enquiryForm button {
        width: 100%;
        padding: 16px !important;
        font-size: 1.1em !important;
      }
    }
    
    @media (max-width: 480px) {
      .contact-section-item {
        padding: 1rem 0.5rem !important;
        margin-bottom: 1.5rem;
      }
      
      .section-header {
        font-size: 1.3em !important;
        padding: 0 0.25rem;
      }

      .contact-us-section > div > div {
        padding: 1rem !important;
        margin-bottom: 1rem !important;
      }

      .contact-us-section > div > div > div:first-child {
        font-size: 1.5rem !important;
        margin-right: 1rem !important;
        min-width: 40px !important;
      }

      .contact-us-section p {
        font-size: 0.95rem !important;
      }

      .contact-us-section span {
        font-size: 1em !important;
      }

      .contact-section-item p {
        font-size: 0.95rem !important;
        padding: 0 0.25rem;
      }

      #enquiryForm > div {
        grid-template-columns: 1fr !important;
      }
    }
    


    /* Removed old contact section styles */

    /* Scroll Progress Bar */
    .scroll-progress {
      position: fixed;
      top: 0;
      left: 0;
      width: 0%;
      height: 2px;
      background: linear-gradient(90deg, #e9bb24, #f0d04a);
      z-index: 1000;
      transition: width 0.1s ease;
    }

    /* Intersection Observer Animation Classes */
    .animate-on-scroll {
      opacity: 0;
      transform: translateY(20px);
      transition: all 0.8s ease;
    }

    .animate-on-scroll.animated {
      opacity: 1;
      transform: translateY(0);
    }

    /* Hover Effects for Interactive Elements */
    .interactive-element {
      transition: transform 0.3s ease;
    }

    .interactive-element:hover {
      transform: translateY(-1px);
    }

    /* Subtle page-wide effects */
    .hero-swiper img {
      transition: transform 8s ease-in-out;
    }

    .hero-swiper img:hover {
      transform: scale(1.02);
    }

    /* Navbar scroll effects */
    .navbar {
      transition: all 0.4s ease;
    }

    .navbar.scrolled {
      backdrop-filter: blur(10px);
      box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
    }

    /* Section titles subtle animation */
    .section-title {
      opacity: 0;
      transform: translateY(20px);
      animation: fadeInUp 0.8s ease forwards;
    }

    /* Contact Section */
    .contact-enquiry {
      position: relative;
    }

    /* File Input */
    .file-input {
      position: relative;
    }

    .file-input input[type="file"] {
      transition: all 0.3s ease;
    }

    .file-input {
  margin-bottom: 15px;
}

.file-input input[type="file"] {
  display: block;
  margin-bottom: 5px;
}

.file-input small {
  color: #666;
  font-size: 0.85em;
}
  /* Increase font size for navigation links */
  .navigation {
    font-size: 2.25em;
  }

  /* Family Carousel Styles */
  .family-photo {
    position: relative;
    overflow: hidden;
  }
  
  .family-carousel-container {
    position: relative;
    width: 100%;
    height: 320px;
    overflow: hidden;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
  }
  
  .family-carousel {
    display: flex;
    transition: transform 0.5s ease;
    height: 100%;
  }
  
  .family-slide {
    min-width: 100%;
    height: 100%;
    position: relative;
  }
  
  .family-slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 8px;
  }
  .contact-section-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  padding: 1.5rem 0;
}

/* Contact cards container - Desktop grid, Mobile horizontal scroll */
.contact-cards-container {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 2.5rem;
  width: 100%;
  max-width: 100%;
  margin: 0;
  padding: 0;
}

.contact-cards-container::-webkit-scrollbar {
  height: 6px;
}

.contact-cards-container::-webkit-scrollbar-track {
  background: rgba(233, 187, 36, 0.1);
  border-radius: 10px;
}

.contact-cards-container::-webkit-scrollbar-thumb {
  background: rgba(233, 187, 36, 0.5);
  border-radius: 10px;
}

.contact-cards-container::-webkit-scrollbar-thumb:hover {
  background: rgba(233, 187, 36, 0.7);
}

.contact-card {
  width: 100%;
  min-width: auto;
  max-width: 100%;
}

/* Desktop: More spacing */
@media (min-width: 769px) {
  .contact-cards-container {
    max-width: 1200px;
    gap: 2.5rem;
    padding: 0 2rem;
  }
  
  .contact-section-item {
    padding: 3rem 2rem;
  }
}

/* Mobile: Horizontal scrolling layout */
@media (max-width: 768px) {
  .contact-cards-container {
    display: flex !important;
    flex-direction: row !important;
    overflow-x: auto !important;
    overflow-y: hidden !important;
    gap: 1rem !important;
    padding: 0.5rem !important;
    -webkit-overflow-scrolling: touch !important;
    scroll-snap-type: x mandatory !important;
    scroll-behavior: smooth !important;
  }
  
  .contact-card {
    min-width: 280px !important;
    max-width: 280px !important;
    flex-shrink: 0 !important;
    scroll-snap-align: center !important;
    padding: 2rem 1.5rem !important;
  }
  
  .contact-card h4 {
    font-size: 1.3em !important;
  }
  
  .contact-card div:first-child {
    font-size: 2.5rem !important;
  }
}

@media (max-width: 480px) {
  .contact-cards-container {
    gap: 0.75rem !important;
    padding: 0.25rem !important;
  }
  
  .contact-card {
    min-width: 260px !important;
    max-width: 260px !important;
    padding: 1.8rem 1.2rem !important;
  }
  
  .contact-card h4 {
    font-size: 1.2em !important;
  }
  
  .contact-card div:first-child {
    font-size: 2.2rem !important;
  }
  
  .contact-card p {
    font-size: 0.95em !important;
  }
}

  .carousel-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(233, 187, 36, 0.8);
    color: #000;
    border: none;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 16px;
    font-weight: bold;
    transition: all 0.3s ease;
    z-index: 2;
  }
  
  .carousel-nav:hover {
    background: #e9bb24;
    transform: translateY(-50%) scale(1.1);
  }
  
  .carousel-nav.prev {
    left: 10px;
  }
  
  .carousel-nav.next {
    right: 10px;
  }
  
  .carousel-dots {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-top: 15px;
  }
  
  .carousel-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #666;
    cursor: pointer;
    transition: all 0.3s ease;
  }
  
  .carousel-dot.active {
    background: #e9bb24;
    transform: scale(1.2);
  }
  
  .carousel-dot:hover {
    background: #e9bb24;
  }
  
    @media (max-width: 768px) {
      .family-carousel-container {
        height: auto;
        min-height: 250px;
      }
      
      .family-slide img {
        object-fit: contain !important;
        height: auto !important;
        width: 100% !important;
        border-radius: 8px;
      }
      
      .carousel-nav {
        width: 35px;
        height: 35px;
        font-size: 14px;
      }
      
      /* Hero text responsive sizing */
      .hero-text h4 {
        font-size: clamp(1.8em, 5vw, 2.5em) !important;
        line-height: 1.2 !important;
      }
    }
    
    @media (max-width: 480px) {
      .family-carousel-container {
        height: auto;
        min-height: 200px;
      }
      
      .family-slide img {
        object-fit: contain !important;
        height: auto !important;
        width: 100% !important;
        border-radius: 8px;
      }
      
      /* Hero text for small mobile screens */
      .hero-text h4 {
        font-size: clamp(1.5em, 6vw, 2em) !important;
        line-height: 1.1 !important;
        text-align: center !important;
      }
      
      .hero-text {
        padding: 0 1rem;
        text-align: center;
      }
    }
  </style>
</head>

<body class="page-content">
  <!-- Loading Screen -->
  <div class="loading-screen" id="loadingScreen">
    <div class="loader"></div>
  </div>

  <!-- Scroll Progress Bar -->
  <div class="scroll-progress" id="scrollProgress"></div>

  <!-- Hero Section -->
  <section class="hero" id="home">
    <div class="hero-wrapper">

      <!-- Header sits inside hero -->
      <header class="navbar">
        <div class="container">
          <div class="logo">
            <img src="images/light-and-style-lighting-solutions-bangalore-logo.jpg" alt="Brand Logo">
            <span class="brand-name">Light & Style</span>
          </div>
          
          <div class="nav-container">
            <!-- Desktop navigation -->
            <nav class="desktop-nav">
              <a class="navigation" href="#product" style="font-family: 'Playfair Display', serif; font-size: larger;">Products</a>
              <a class="navigation" href="#projects" style="font-family: 'Playfair Display', serif; font-size: larger;">Projects</a>
              <a class="navigation" href="testimonials.html" style="font-family: 'Playfair Display', serif; font-size: larger;">Testimonials</a>
              <a class="navigation" href="#contact" style="font-family: 'Playfair Display', serif; font-size: larger;">Contact us</a>
            </nav>
            
            <!-- Mobile hamburger menu button -->
            <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Toggle navigation menu">
              <span class="hamburger-line"></span>
              <span class="hamburger-line"></span>
              <span class="hamburger-line"></span>
            </button>
          </div>
          
          <!-- Mobile navigation dropdown -->
          <nav class="mobile-nav" id="mobileNav">
            <a href="#product">Products</a>
            <a href="#projects">Projects</a>
            <a href="testimonials.html">Testimonials</a>
            <a href="#contact">Contact us</a>
          </nav>
        </div>
      </header>

      <div class="swiper hero-swiper">
        <div class="swiper-wrapper" id="heroSwiperWrapper">
          <!-- Slides will be dynamically loaded by JavaScript -->
        </div>
      </div>

      <div class="hero-overlay"></div>

      <div class="hero-text">
        <h4 style="font-family: 'Playfair Display', serif; font-size: 2.5em; font-weight: normal; opacity: 0; animation: fadeInText 1s ease 2s forwards;">
          <div>Transforming Spaces with</div>
          <div><span class="highlight" style="font-style: normal;">Exquisite Lighting</span></div>
        </h4>
      </div>

      <div class="swiper-button-prev"></div>
      <div class="swiper-button-next"></div>
    </div>
  </section>



  <!-- Projects Section -->
  <section id="projects" class="projects animate-on-scroll section-reveal">
  <div class="container">
    <h4 class="section-title">Our Projects</h4>

    <div class="categories">

      <a href="residential.html" class="category-link">
        <div class="category">
          <div class="category-images">
            <img src="images/residential-living-room-chandelier-lighting-bangalore.jpeg" alt="residential living room chandelier lighting in Bangalore">
          </div>
          <h3 style="font-family: 'Playfair Display', serif;font-size: 1.3em;">Residential</h3>
        </div>
      </a>

      
      <a href="decorative.html" class="category-link">
        <div class="category">
          <div class="category-images">
            <img src="images/decorative-chandelier-pendant-lights-bangalore.jpeg" alt="Decorative">
          </div>
          <h3 style="font-family: 'Playfair Display', serif;font-size: 1.3em;">Decorative</h3>
        </div>
      </a>

      <a href="commercial.html" class="category-link">
        <div class="category">
          <div class="category-images">
            <img src="images/commercial-office-showroom-lighting-bangalore.png" alt="Commercial">
          </div>
          <h3 style="font-family: 'Playfair Display', serif; font-size: 1.3em;">Commercial</h3>
        </div>
      </a>

      


      <a href="outdoor.html" class="category-link">
        <div class="category">
          <div class="category-images">
            <img src="images/outdoor-garden-facade-terrace-lighting-bangalore.jpeg" alt="Outdoor">
          </div>
          <h3 style="font-family: 'Playfair Display', serif; font-size: 1.3em;">Outdoor</h3>
        </div>
      </a>

    </div>
  </div>
</section>

<!-- Explore Products Section -->
<section class="explore-products animate-on-scroll section-reveal" id="product">
  <div class="container explore-products-container">
    <div class="explore-text">
      <h4 class="section-title">Explore Our Products</h4>
      <p style="text-align: justify;">Light & Style, a premium lighting brand in India, brings over 25 years of expertise in designing and developing best-selling lighting and interior ranges. With innovative concepts and designer chandeliers & lamps, the brand has become the go-to choice for elevating both commercial and private spaces.<br><br> Driven by creativity and backed by experience, Light & Style continues to deliver exceptional modern LED lighting fixtures that enhance ambience and meet individual needs.</p>      <a href="products.html" class="btn interactive-element" style="font-size: 1.1em;">View Products</a>
    </div>
    <div class="explore-image">
      <img src="images/lighting-products-catalog-bangalore.jpeg" alt="Explore Products">
    </div>
  </div>
</section>

<br>
<!-- Contact & Enquiry Section -->
<section class="contact-enquiry animate-on-scroll section-reveal">
  <div class="container contact-enquiry-container">

    <!-- About Light & Style -->
    <div class="contact-section-item">
      <h3 class="section-header" style="font-family: 'Playfair Display', serif; color: #e9bb24; font-size: 2em;">About Light & Style</h3>
      <p style="text-align: justify; font-family: 'Playfair Display', serif; line-height: 1.6; font-size: 1.1em;">
        Sushil Bhandari has embarked on this journey in the lighting industry for over 2 decades beginning from the grassroots level and ascending to a level of expertise in the details of lighting products. Moving forward, he brought in his brother Hitesh and together they built a team to cater the needs of Architects and the top builders of Karnataka. The journey has been marked by a relentless pursuit of excellence couples with a deep rooted passion for lighting solutions and designs. We strive to give the most innovative solutions in one of the fastest evolving industries in the world.
      </p>
    </div>

    <!-- Meet Our Family -->
    <div class="contact-section-item">
      <h3 class="section-header" style="font-family: 'Playfair Display', serif; color: #e9bb24; font-size: 2em;">Meet Our Family</h3>
      <div class="family-carousel-container">
        <div class="family-carousel" id="familyCarousel">
          <div class="family-slide">
            <img src="images/lighting-showroom-staff-bangalore.jpeg" alt="Light & Style Family - Photo 1">
          </div>
          <div class="family-slide">
            <img src="images/lighting-design-professionals-bangalore.jpeg" alt="Light & Style Family - Photo 2">
          </div>
        </div>
        <button class="carousel-nav prev" onclick="previousSlide()">&#8249;</button>
        <button class="carousel-nav next" onclick="nextSlide()">&#8250;</button>
      </div>
      <div class="carousel-dots">
        <span class="carousel-dot active" onclick="currentSlide(1)"></span>
        <span class="carousel-dot" onclick="currentSlide(2)"></span>
      </div>
    </div>

    <!-- Send Enquiry -->
    <div class="contact-section-item" section id="contact">
      <h3 class="section-header" style="font-family: 'Playfair Display', serif; color: #e9bb24; font-size: 2em;">Send Enquiry</h3>
      <form id="enquiryForm" onsubmit="return sendEmail(event)">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
          <input type="text" name="name" placeholder="Your Name" required style="font-size: medium; font-family: 'Playfair Display', serif; padding: 12px; border: 2px solid #e9bb24; border-radius: 6px; background: rgba(0,0,0,0.8) !important; color: #fff !important;" />
          <input type="email" name="email" placeholder="Your Email" required style="font-size: medium; font-family: 'Playfair Display', serif; padding: 12px; border: 2px solid #e9bb24; border-radius: 6px; background: rgba(0,0,0,0.8) !important; color: #fff !important;" />
        </div>
        <input type="text" name="phone" placeholder="Phone Number" style="font-size: medium; font-family: 'Playfair Display', serif; padding: 12px; border: 2px solid #e9bb24; border-radius: 6px; background: rgba(0,0,0,0.8) !important; color: #fff !important; width: 100%; margin-bottom: 1rem;" />
        <textarea name="message" placeholder="Your Message" style="font-size: medium; font-family: 'Playfair Display', serif; padding: 12px; border: 2px solid #e9bb24; border-radius: 6px; background: rgba(0,0,0,0.8) !important; color: #fff !important; width: 100%; min-height: 120px; margin-bottom: 1rem; resize: vertical;"></textarea>
        <div class="file-input" style="margin-bottom: 1.5rem;">
          <input type="file" id="attachments" name="attachments[]" multiple style="padding: 8px; border: 2px solid #e9bb24; border-radius: 6px; background: rgba(0,0,0,0.8) !important; color: #fff !important; width: 100%;" />
          <p style="font-family: 'Playfair Display', serif; margin: 0.5rem 0; color: #e9bb24; font-style: italic;">Have a design in mind? Share your reference images — we'll craft a light that's uniquely yours.</p>
          <small style="font-family: 'Playfair Display', serif; color: #999;">Attach up to 3 files (max 5MB each)</small>
        </div>
        <button type="submit" class="interactive-element" style="font-family: 'Playfair Display', serif; background: linear-gradient(135deg, #e9bb24, #f0d04a); color: #000; padding: 14px 30px; border: none; border-radius: 6px; font-weight: 600; font-size: 1.1em; cursor: pointer; transition: all 0.3s ease;">Send Enquiry</button>
      </form>
    </div>

    <!-- Contact Us -->
    <div class="contact-section-item contact-us-section">
      <h3 class="section-header" style="font-family: 'Playfair Display', serif; color: #e9bb24; font-size: 2em;">Contact Us</h3>
      <div style="font-family: 'Playfair Display', serif; padding: 1.4em 1.2em 1.2em 1.2em;">
        
        <!-- Address -->
        <div style="display: flex; align-items: flex-start; margin-bottom: 2.5rem; padding: 2rem; background: linear-gradient(135deg, rgba(233, 187, 36, 0.08), rgba(240, 208, 74, 0.04)); border-radius: 12px; border-left: 4px solid #e9bb24; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(233, 187, 36, 0.2)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0, 0, 0, 0.1)'">
          <div style="font-size: 1.8rem; margin-right: 1.5rem; color: #e9bb24; min-width: 60px; text-align: center; text-shadow: 0 0 10px rgba(233, 187, 36, 0.3);">📍</div>
          <div>
            <p style="color: #fff; margin: 0; line-height: 1.6; font-size: 1.1em;"><span style="color: #e9bb24; font-weight: 600; font-size: 1.2em;">Address:</span> 120/5, Infantry Road, Bangalore - 560001</p>
          </div>
        </div>

        <!-- Phone -->
        <div style="display: flex; align-items: flex-start; margin-bottom: 2.5rem; padding: 2rem; background: linear-gradient(135deg, rgba(233, 187, 36, 0.08), rgba(240, 208, 74, 0.04)); border-radius: 12px; border-left: 4px solid #e9bb24; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(233, 187, 36, 0.2)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0, 0, 0, 0.1)'">
          <div style="font-size: 1.8rem; margin-right: 1.5rem; color: #e9bb24; min-width: 60px; text-align: center; text-shadow: 0 0 10px rgba(233, 187, 36, 0.3);">📞</div>
          <div>
            <p style="color: #fff; margin: 0; line-height: 1.8; font-size: 1.1em;">
              <span style="color: #e9bb24; font-weight: 600; font-size: 1.2em;">Phone:</span> 
              <a href="tel:08041130090" style="color: #fff; text-decoration: none; transition: all 0.3s ease; margin-left: 0.5rem;" onmouseover="this.style.color='#e9bb24'; this.style.textShadow='0 0 5px rgba(233, 187, 36, 0.5)'" onmouseout="this.style.color='#fff'; this.style.textShadow='none'">080-41130090</a> / 
              <a href="tel:08041132190" style="color: #fff; text-decoration: none; transition: all 0.3s ease;" onmouseover="this.style.color='#e9bb24'; this.style.textShadow='0 0 5px rgba(233, 187, 36, 0.5)'" onmouseout="this.style.color='#fff'; this.style.textShadow='none'">080-41132190</a>
            </p>
          </div>
        </div>

        <!-- Email -->
        <div style="display: flex; align-items: flex-start; margin-bottom: 2rem; padding: 2rem; background: linear-gradient(135deg, rgba(233, 187, 36, 0.08), rgba(240, 208, 74, 0.04)); border-radius: 12px; border-left: 4px solid #e9bb24; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(233, 187, 36, 0.2)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0, 0, 0, 0.1)'">
          <div style="font-size: 1.8rem; margin-right: 1.5rem; color: #e9bb24; min-width: 60px; text-align: center; text-shadow: 0 0 10px rgba(233, 187, 36, 0.3);">✉️</div>
          <div>
            <p style="color: #fff; margin: 0; line-height: 1.6; font-size: 1.1em;">
              <span style="color: #e9bb24; font-weight: 600; font-size: 1.2em;">Email:</span> 
              <a href="mailto:neha@lightnstyle.in" style="color: #fff; text-decoration: none; transition: all 0.3s ease; margin-left: 0.5rem;" onmouseover="this.style.color='#e9bb24'; this.style.textShadow='0 0 5px rgba(233, 187, 36, 0.5)'" onmouseout="this.style.color='#fff'; this.style.textShadow='none'">neha@lightnstyle.in</a>
            </p>
          </div>
        </div>

      </div>
    </div>

  </div>
</section>

  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script src="script.js"></script>
  <script>
    // Hero Carousel Responsive Control
    let heroSwiper = null;

    // Define desktop and mobile hero images
    const desktopHeroImages = [
      'images/luxury-chandelier-lighting-showroom-bangalore.jpeg',
      'images/decorative-chandelier-villa-lighting-bangalore.jpeg',
      'images/luxury-living-room-lighting-design-bangalore.jpeg',
      'images/ceiling-lights-home-decoration-bangalore.jpeg',
      'images/modern-pendant-lights-residential-bangalore.jpeg',
      'images/designer-wall-lights-commercial-bangalore.jpeg',
      'images/outdoor-garden-lighting-landscape-bangalore.jpeg'
    ];

    const mobileHeroImages = [
      'images/mobile-chandelier-lighting-display-bangalore.jpeg',
      'images/mobile-luxury-living-room-lights-bangalore.jpeg',
      'images/mobile-decorative-villa-lighting-bangalore.jpeg',
      'images/mobile-ceiling-lights-home-bangalore.jpeg',
      'images/mobile-pendant-lights-residential-bangalore.jpeg',
      'images/mobile-wall-lights-commercial-bangalore.jpeg',
      'images/mobile-outdoor-garden-lighting-bangalore.jpeg'
    ];

    function initHeroSwiper() {
      // Destroy existing swiper if it exists
      if (heroSwiper) {
        heroSwiper.destroy(true, true);
      }

      // Determine which images to use based on screen size
      const isMobile = window.innerWidth <= 768;
      const imagesToUse = isMobile ? mobileHeroImages : desktopHeroImages;

      // Get the swiper wrapper
      const swiperWrapper = document.getElementById('heroSwiperWrapper');
      
      // Clear existing slides
      swiperWrapper.innerHTML = '';

      // Add slides dynamically
      imagesToUse.forEach((imageSrc) => {
        const slide = document.createElement('div');
        slide.className = 'swiper-slide';
        slide.innerHTML = `<img src="${imageSrc}" alt="">`;
        swiperWrapper.appendChild(slide);
      });

      // Initialize new swiper
      heroSwiper = new Swiper('.hero-swiper', {
        loop: true,
        autoplay: {
          delay: 4000,
          disableOnInteraction: false,
        },
        effect: 'fade',
        fadeEffect: {
          crossFade: true
        },
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
        speed: 1000,
      });
    }

    // Initialize on load
    document.addEventListener('DOMContentLoaded', initHeroSwiper);

    // Reinitialize on window resize to handle orientation changes
    let resizeTimer;
    window.addEventListener('resize', function() {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(function() {
        initHeroSwiper();
      }, 250);
    });

    // Loading Screen with enhanced timing
    window.addEventListener('load', function() {
      setTimeout(function() {
        document.getElementById('loadingScreen').classList.add('fade-out');
        setTimeout(function() {
          document.getElementById('loadingScreen').style.display = 'none';
          
          // Start scroll animations after loading is complete
          initScrollAnimations();
        }, 800);
      }, 1800);
    });

    // Enhanced Scroll Progress Bar
    window.addEventListener('scroll', function() {
      const scrollProgress = document.getElementById('scrollProgress');
      const totalHeight = document.body.scrollHeight - window.innerHeight;
      const progress = (window.pageYOffset / totalHeight) * 100;
      scrollProgress.style.width = progress + '%';
    });

    // Smooth Scroll for Navigation with easing
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

    // Intersection Observer for scroll animations
    function initScrollAnimations() {
      const observerOptions = {
        threshold: 0.15,
        rootMargin: '0px 0px -30px 0px'
      };

      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('animated');
            
            // Add visible class for section reveals
            if (entry.target.classList.contains('section-reveal')) {
              entry.target.classList.add('visible');
            }
          }
        });
      }, observerOptions);

      // Observe all elements with animation classes
      document.querySelectorAll('.animate-on-scroll, .section-reveal').forEach(el => {
        observer.observe(el);
      });
    }

    // Subtle parallax effect for hero
    let ticking = false;
    
    function updateParallax() {
      const scrolled = window.pageYOffset;
      const heroText = document.querySelector('.hero-text');
      
      if (heroText && scrolled < window.innerHeight) {
        const rate = scrolled * -0.3;
        heroText.style.transform = `translateY(${rate}px)`;
      }
      
      ticking = false;
    }

    window.addEventListener('scroll', function() {
      if (!ticking) {
        requestAnimationFrame(updateParallax);
        ticking = true;
      }
    });

    // Enhanced navbar scroll effect with smooth transitions
    window.addEventListener('scroll', function() {
      const navbar = document.querySelector('.navbar');
      if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    });

    // Mobile Menu Functionality
    document.addEventListener('DOMContentLoaded', function() {
      const mobileMenuBtn = document.getElementById('mobileMenuBtn');
      const mobileNav = document.getElementById('mobileNav');
      
      if (mobileMenuBtn && mobileNav) {
        mobileMenuBtn.addEventListener('click', function() {
          // Toggle hamburger animation
          mobileMenuBtn.classList.toggle('active');
          
          // Toggle mobile menu visibility
          mobileNav.classList.toggle('active');
          
          // Prevent body scroll when menu is open
          if (mobileNav.classList.contains('active')) {
            document.body.style.overflow = 'hidden';
          } else {
            document.body.style.overflow = '';
          }
        });
        
        // Close mobile menu when clicking on links
        const mobileNavLinks = mobileNav.querySelectorAll('a');
        mobileNavLinks.forEach(link => {
          link.addEventListener('click', function() {
            mobileMenuBtn.classList.remove('active');
            mobileNav.classList.remove('active');
            document.body.style.overflow = '';
          });
        });
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(e) {
          if (!mobileMenuBtn.contains(e.target) && !mobileNav.contains(e.target)) {
            mobileMenuBtn.classList.remove('active');
            mobileNav.classList.remove('active');
            document.body.style.overflow = '';
          }
        });
        
        // Handle escape key to close menu
        document.addEventListener('keydown', function(e) {
          if (e.key === 'Escape' && mobileNav.classList.contains('active')) {
            mobileMenuBtn.classList.remove('active');
            mobileNav.classList.remove('active');
            document.body.style.overflow = '';
          }
        });
      }
    });

    // Initialize EmailJS
    (function() {
      emailjs.init("SL67cZHfeqT4Y3D9k"); // Replace with your EmailJS public key
    })();

    // No additional JavaScript needed for contact cards layout
    // CSS handles horizontal scrolling on mobile automatically

    // Function to send email
    async function sendEmail(e) {
      e.preventDefault();
      
      const form = document.getElementById('enquiryForm');
      const submitButton = form.querySelector('button[type="submit"]');
      const fileInput = document.getElementById('attachments');
      
      // Validate file attachments
      const files = fileInput.files;
      if (files.length > 3) {
        alert('Please select a maximum of 3 files');
        return false;
      }

      for (let i = 0; i < files.length; i++) {
        if (files[i].size > 5 * 1024 * 1024) {
          alert('Each file must be less than 5MB');
          return false;
        }
      }

      // Disable the submit button while sending
      submitButton.disabled = true;
      submitButton.textContent = 'Sending...';

      try {
        // Create FormData object to handle file uploads
        const formData = new FormData();
        
        // Add form fields directly to FormData
        formData.append('name', form.name.value);
        formData.append('email', form.email.value);
        formData.append('phone', form.phone.value);
        formData.append('message', form.message.value);
        
        // Add files if any
        const files = fileInput.files;
        for (let i = 0; i < files.length; i++) {
          formData.append('attachments[]', files[i]);
        }

        // Send the email using our PHP endpoint
        const response = await fetch('send-email.php', {
          method: 'POST',
          body: formData
        });

        const result = await response.json();

        if (!response.ok) {
          throw new Error(result.error || 'Failed to send email');
        }

        alert('Thank you! Your message has been sent.');
        form.reset();
      } catch (error) {
        console.error('Error:', error);
        alert('Sorry, there was an error sending your message: ' + error.message);
      } finally {
        submitButton.disabled = false;
        submitButton.textContent = 'Send';
      }

      return false;
    }
  </script>

  <script>
    // Family Carousel Functionality
    document.addEventListener('DOMContentLoaded', function() {
      let currentSlideIndex = 0;
      const slides = document.querySelectorAll('.family-slide');
      const dots = document.querySelectorAll('.carousel-dot');
      const familyCarouselElement = document.getElementById('familyCarousel');
      let autoSlideInterval;

      function showSlide(index) {
        if (!familyCarouselElement || slides.length === 0) return;
        
        currentSlideIndex = index;
        familyCarouselElement.style.transform = `translateX(-${index * 100}%)`;
        
        // Update dots
        dots.forEach((dot, i) => {
          dot.classList.toggle('active', i === index);
        });
      }

      function nextSlide() {
        currentSlideIndex = (currentSlideIndex + 1) % slides.length;
        showSlide(currentSlideIndex);
      }

      function previousSlide() {
        currentSlideIndex = (currentSlideIndex - 1 + slides.length) % slides.length;
        showSlide(currentSlideIndex);
      }

      function currentSlide(index) {
        showSlide(index - 1);
      }

      // Make functions globally accessible for onclick handlers
      window.nextSlide = nextSlide;
      window.previousSlide = previousSlide;
      window.currentSlide = currentSlide;

      // Start auto-advance carousel every 4 seconds
      function startAutoSlide() {
        autoSlideInterval = setInterval(nextSlide, 4000);
      }

      function stopAutoSlide() {
        clearInterval(autoSlideInterval);
      }

      // Pause auto-slide on hover, resume on mouse leave
      if (familyCarouselElement) {
        familyCarouselElement.addEventListener('mouseenter', stopAutoSlide);
        familyCarouselElement.addEventListener('mouseleave', startAutoSlide);
      }

      // Initialize carousel
      showSlide(0);
      startAutoSlide();
    });
  </script>

<!-- Footer -->
<footer style="background: #000; color: #fff; text-align: center; padding: 2rem 1rem; margin-top: 3rem; border-top: 1px solid rgba(233, 187, 36, 0.3); font-family: 'Playfair Display', serif;">
  <div style="max-width: 1200px; margin: 0 auto;">
    <div style="display: flex; flex-wrap: wrap; justify-content: center; align-items: center; gap: 2rem; margin-bottom: 1rem;">
      <div style="display: flex; align-items: center; gap: 0.5rem;">
        <img src="images/light-and-style-lighting-solutions-bangalore-logo.jpg" alt="Light & Style Logo" style="height: 30px;">
        <span style="font-family: 'Playfair Display', serif; font-size: 1.2rem; color: #e9bb24; font-weight: 700;">Light & Style</span>
      </div>
      <div style="font-size: 0.9rem; color: #ccc; font-family: 'Playfair Display', serif;">
        120/5, Infantry Road, Bangalore - 560001 | Phone: 080-41130090 / 41132190
      </div>
    </div>
    <div style="border-top: 1px solid rgba(255, 255, 255, 0.1); padding-top: 1rem;">
      <p style="margin: 0; font-size: 0.9rem; color: #aaa; font-family: 'Playfair Display', serif;">
        © 2024 Light & Style. All rights reserved.
      </p>
    </div>
  </div>
</footer>

</body>
</html>
