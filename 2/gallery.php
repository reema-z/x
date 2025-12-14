<?php
session_start();

require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="refresh" content="43200">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery</title>
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/gallery.css">
</head>
<body>
    <header>
        <div class="top-bar">
            <a href="mailto:445001472@sm.edu.imamu.sa">Email: 445001472@sm.edu.imamu.sa</a> |
            <a href="tel:+966552616596">Phone: +966 552616596</a> |
            <a href="https://www.linkedin.com/in/reema-alzoman-6b30732a7?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=ios_app" target="_blank">LinkedIn</a> |
            <a href="https://github.com/reema-z/web-devolpment-project.git" target="_blank">GitHub</a>
        </div>
    </header>
    
    <?php include 'navbar.php'; ?>
    
    <section id="gallery-figure">
        <div class="video-wrapper">
            <iframe src="https://www.youtube.com/embed/om8opAhsxPI?si=2tfy7NaTGLVVg6Rf" 
                    title="Visit Saudi" frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen>
            </iframe>
        </div>
        
        <div>
            <figure>
                <figcaption><h2>Al-Sadu</h2></figcaption>
                <img src="img/Sadu.jpg" alt="Al-Sadu">
                <a href="https://www.youtube.com/watch?v=JZwZfNUpAVQ">
                    <h3><strong>Click here</strong> to watch a video on how Al-Sadu is made</h3>
                </a>
            </figure>
        </div>
        
        <div>
            <figure>
                <figcaption><h2>Riyadh Metro</h2></figcaption>
                <img src="img/Rmetro.jpg" alt="Riyadh Metro">
                <a href="https://youtu.be/KHmX8WipcIw">
                    <h3><strong>Click here</strong> to watch a video about the Riyadh Metro</h3>
                </a>
            </figure>
        </div>
        
        <div>
            <figure>
                <figcaption><h2>Najdi Ardah</h2></figcaption>
                <img src="img/Nardah.jpg" alt="Najdi Ardah">
                <a href="https://www.youtube.com/watch?v=TiKsts5xJR8&list=RDTiKsts5xJR8&start_radio=1">
                    <h3><strong>Click here</strong> to watch a video on how the Najdi Ardah is performed</h3>
                </a>
            </figure>
        </div>
    </section>
    
    <footer>
        &copy;2025-26 / IMSIU / CCIS<sup>TM</sup>
    </footer>
</body>
</html>