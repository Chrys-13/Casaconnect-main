<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CasaConnect - Real Estate</title>
    <link rel="stylesheet" href="Css/front.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">CasaConnect</div>
            <ul class="nav-links">
                <li><a href="Views/about.html">About</a></li>
                <li><a href="Views/services.html">Services</a></li>
                <li><a href="#">Explore</a></li>
                <li><a href="Actions/login.php">Login</a></li>
            </ul>
        </nav>
        <div class="video-background">
            <video autoplay loop muted playsinline>
                <source src="../Assets/Drone Residential Neighborhood.mp4" type="video/mp4" alt="Drone shot of neighbouhood">
            </video>
        </div>
        <div class="hero-section">
            <div class="hero-content">
                <h1>Discover Your Dream Home</h1>
                <p>With CasaConnect</p>
                <button class="explore-btn">Explore</button>
            </div>
        </div>
    </header>
    <main>
        <section class="estates-section">
            <h2>New Estates</h2>
            <div class="estates-grid">
                <div class="estate-card">
                    <img src="../Assets/mch.jpg" alt="Modern Clubhouse, Paleto Bay">
                    <div class="estate-info">
                        <h3>Modern Clubhouse, Paleto Bay</h3>
                        <p>$12,399,999</p>
                    </div>
                </div>
                <div class="estate-card">
                    <img src="../Assets/wdf.jpg" alt="Waterfront Property with Dock, La Puerta">
                    <div class="estate-info">
                        <h3>Waterfront Property with Dock, La Puerta</h3>
                        <p>$7,650,000</p>
                    </div>
                </div>
                <div class="estate-card">
                    <img src="../Assets/denton.jpg" alt="Image of property">
                    <div class="estate-info">
                        <h3>Scenic Forest Villa, Denton Woodlands</h3>
                        <p>$2,510,000</p>
                    </div>
                </div>
            </div>
            <button class="load-more-btn">Load More</button>
        </section>
    </main>
</body>
</html>
