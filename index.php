<?php
// index.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Andrew Braza - Portfolio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        * {margin: 0; padding: 0; box-sizing: border-box;}
        body {font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; color: #333; scroll-behavior: smooth;}
        .navbar {position: fixed; top: 0; width: 100%; background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); z-index: 1000; padding: 1rem 0; border-bottom: 1px solid #eee;}
        .nav-content {display: flex; justify-content: space-between; align-items: center;}
        .logo {font-size: 1.5rem; font-weight: bold; color: #2563eb;}
        .nav-links {display: flex; list-style: none; gap: 2rem;}
        .nav-links a {text-decoration: none; color: #666; font-weight: 500; transition: color 0.3s;}
        .nav-links a:hover {color: #2563eb;}
        .hero {min-height: 100vh; display: flex; align-items: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-align: center;}
        .hero-content h1 {font-size: 3.5rem; margin-bottom: 1rem; animation: fadeInUp 1s ease;}
        .hero-content p {font-size: 1.2rem; margin-bottom: 2rem; opacity: 0.9; animation: fadeInUp 1s ease 0.2s both;}
        .btn {display: inline-block; padding: 12px 30px; margin: 0 10px; border: none; border-radius: 5px; text-decoration: none; font-weight: 600; transition: all 0.3s; cursor: pointer;}
        .btn-primary {background: #2563eb; color: white;}
        .btn-primary:hover {background: #1d4ed8; transform: translateY(-2px);}
        .btn-secondary {background: transparent; color: white; border: 2px solid white;}
        .btn-secondary:hover {background: white; color: #2563eb;}
        .section {padding: 80px 0;}
        .section-title {text-align: center; font-size: 2.5rem; margin-bottom: 3rem; color: #333;}
        .about {background: #f8f9fa;}
        .about-content {display: grid; grid-template-columns: 1fr 2fr; gap: 4rem; align-items: center;}
        .about-image img {width: 300px; height: 300px; border-radius: 50%; object-fit: cover; box-shadow: 0 10px 30px rgba(0,0,0,0.1);}
        .contact-content {display: grid; grid-template-columns: 1fr 1fr; gap: 4rem;}
        .contact-form {background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);}
        .form-group {margin-bottom: 1.5rem;}
        .form-group label {display: block; margin-bottom: 0.5rem; color: #333; font-weight: 500;}
        .form-group input, .form-group textarea {width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem;}
        .form-group textarea {height: 120px; resize: vertical;}
        .footer {background: #333; color: white; text-align: center; padding: 2rem 0;}
        @keyframes fadeInUp {from{opacity:0;transform:translateY(30px);} to{opacity:1;transform:translateY(0);}}
        @media (max-width:768px){.contact-content{grid-template-columns:1fr;}.about-content{grid-template-columns:1fr;}}
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container nav-content">
            <div class="logo">Portfolio</div>
            <ul class="nav-links">
                <li><a href="#home">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#skills">Skills</a></li>
                <li><a href="#projects">Projects</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero -->
    <section id="home" class="hero">
        <div class="container hero-content">
            <h1>Hi, I'm Andrew Braza</h1>
            <p>Full-time Faculty Teacher & UI/UX Designer passionate about creating beautiful, functional web experiences.</p>
            <div>
                <a href="#projects" class="btn btn-primary">View My Work</a>
                <a href="#contact" class="btn btn-secondary">Get In Touch</a>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="section">
        <div class="container">
            <h2 class="section-title">Get In Touch</h2>
            <div class="contact-content">
                <div class="contact-info">
                    <h3>Let's Connect</h3>
                    <p>Have a project or opportunity in mind? Let's talk!</p>
                    <p>📧 brazaandrew@gmail.com</p>
                </div>
<form method="POST" action="/NewPortfolio10/process_contact.php">
  <input name="name" required>
  <input type="email" name="email" required>
  <textarea name="message" required></textarea>
  <button type="submit">Send</button>
</form>

            </div>
        </div>
    </section>

    <footer class="footer">
        <p>&copy; 2025 Andrew Braza. All rights reserved.</p>
    </footer>

    <script>
        // AJAX Contact Form
        document.getElementById('contactForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            try {
                const response = await fetch('process_contact.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();

                if (data.status === 'success') {
                    alert(data.message);
                    this.reset();
                } else {
                    alert('⚠️ ' + data.message);
                }
            } catch (error) {
                alert('❌ Error sending message. Please try again.');
                console.error(error);
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

