<?php
?>
<footer class="site-footer" style="font-size:1.1rem;">
    <div class="footer-container">
        <div class="footer-section" style="order:3">
            <h3>About Software.sys</h3>
            <p>Your one-stop solution for professional software development services.</p>
        </div>
        
        <div class="footer-section" style="order:1">
            <h3>Contact Us</h3>
            <ul class="contact-info" style="padding:0px;">
                <li><i class="fas fa-envelope"></i> Email: <a href="mailto:kamalsoma2005@gmail.com">kamalsoma2005@gmail.com</a></li>
                <li><i class="fas fa-phone"></i> Phone: <a href="tel:+1234567890">+1 (234) 567-890</a></li>
                <li><i class="fas fa-map-marker-alt"></i> Address: 123 Tech Street, Silicon Valley, CA</li>
            </ul>
        </div>
        
        <div class="footer-section" style="order:2; padding-left:40px;">
            <h3>Quick Links</h3>
            <ul class="footer-links">
                <li><a href="home.php">Home</a></li>
                <li><a href="projects.php">Projects</a></li>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="register.php">Register</a></li>
            </ul>
        </div>
    </div>
    
    <div class="footer-bottom">
        <p>&copy; <?= date('Y') ?> Software.sys. All rights reserved.</p>
    </div>
</footer>

<style>
.site-footer {
    background: #001f3f;
    color: white;
    padding: 40px 0 0;
    font-family: 'Verdana', 'Arial Narrow', Arial, sans-serif;
    margin-top: auto;
}

.footer-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    gap:20px;
}

.footer-section {
    flex: 1;
    min-width: 250px;
    margin-bottom: 30px;
    padding: 0 15px;
}

.footer-section h3 {
    color: #3a86ff;
    margin-bottom: 20px;
    font-size: 1.2rem;
}

.footer-section p, 
.footer-section li {
    margin-bottom: 10px;
    line-height: 1.6;
}

.contact-info li {
    display: flex;
    align-items: center;
    gap: 10px;
}

.footer-links li {
    margin-bottom: 8px;
}

.footer-links a, 
.contact-info a {
    color: white;
    text-decoration: none;
    transition: color 0.3s;
}

.footer-links a:hover, 
.contact-info a:hover {
    color: #3a86ff;
    text-decoration: underline;
}

.footer-bottom {
    background: #001025;
    text-align: center;
    padding: 15px 0;
    margin-top: 30px;
}

.fas {
    width: 20px;
    text-align: center;
}

@media (max-width: 768px) {
    .footer-section {
        flex: 100%;
        text-align: center;
    }
    
    .contact-info li {
        justify-content: center;
    }
}
</style>

<!-- Font Awesome for icons (add this in head of your main template) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">