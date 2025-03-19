<?php
session_start();
include("connect.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debate Platform</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Bootstrap Link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Bootstrap Link -->

    <!-- Font Awesome Cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <!-- Font Awesome Cdn -->

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap" rel="stylesheet">
    <!-- Google Fonts -->
    <script>    
    const topics = [
        {
            title: "Politics",
            description: "Explore the latest political news and discussions.",
            image: "home_image/home_politics.jpeg"
        },
        {
            title: "Education",
            description: "Discover educational resources and insights.",
            image: "home_image/home_edu.jpeg"
        },
        {
            title: "Sports",
            description: "Stay updated with the latest sports events and news.",
            image: "home_image/home_sports.jpeg"
        },
        {
            title: "Films",
            description: "Dive into the world of films and entertainment.",
            image: "home_image/home_films.jpeg"
        }
    ];
    let currentIndex = 0;
    function updateHomeSection() {
      const homeSection = document.querySelector(".home");
      const homeDescription = document.getElementById("home-description");
      const changeContent = document.querySelector(".changecontent");
    try {
        const topic = topics[currentIndex];
        
        if (!homeSection) {
            throw new Error("homeSection element is not found.");
        }
        
        if (!topic || !topic.image || !topic.description || !topic.title) {
            throw new Error("Topic data is missing essential properties.");
        }

        homeSection.style.backgroundImage = `linear-gradient(rgba(0,0,0,0.3),rgba(0,0,0,0.2)), url(${topic.image})`;
        homeDescription.textContent = topic.description;

        changeContent.textContent = topic.title;

      // Remove the fade-in class to reset the animation
      changeContent.classList.remove("changecontent");

      // Force a reflow to reset the animation
      void changeContent.offsetWidth; // Trigger reflow

      // Re-add the fade-in class after a small delay to ensure the browser has reset
    changeContent.classList.add("changecontent");
        currentIndex = (currentIndex + 1) % topics.length;
        console.log("Success");
    } catch (error) {
        console.error("Error updating home section:", error);
    }
}
    
</script>
</head>
<body>
    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg" id="navbar">
        <div class="container">
          <a class="navbar-brand" href="homepage.php" id="logo">Gen<span>Z</span>Sphere</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
            <span><i class="fa-solid fa-bars"></i></span>
          </button>
          <div class="collapse navbar-collapse" id="mynavbar">
            <ul class="navbar-nav me-auto">
              <li class="nav-item">
                <a class="nav-link active" href="homepage.php">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#main-txt">Topics</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="index.php">Sign Up</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#about">About</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="logout.php">Log Out</a>
              </li>
            </ul>
            <form class="d-flex">
              <input class="form-control me-2" type="text" placeholder="Search">
              <button class="btn btn-primary" type="button">Search</button>
            </form>
          </div>
        </div>
      </nav>
    <!-- Navbar End -->

    <!-- Home Section Start -->
    <div class="home">
        <div class="content">
            <h1>Explore <span class="changecontent"></span></h1>
            <p id="home-description">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Quae, nisi.</p>
            <a href="#main-txt">Topics</a>
        </div>
    </div>
    <!-- Home Section End -->
    <script>
      // Call updateHomeSection function every 5 seconds
      setInterval(updateHomeSection, 3000);
      // Call once immediately to load the first topic
      updateHomeSection();
    </script>
    <div class="container">
        <div class="main-txt">
          <h1><span>T</span>opics</h1>
        </div>

        <!-- Topics Section Start -->
        <div class="row">
            <?php
            $sql = "SELECT * FROM cards";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="<?php echo $row['image_path']; ?>" class="card-img-top" alt="<?php echo $row['name']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row['name']; ?></h5>
                        <p class="card-text"><?php echo $row['description']; ?></p>
                        <a href="blog.php?topic=<?php echo urlencode($row['name']); ?>" class="btn btn-primary">Go to link</a>
                    </div>
                </div>
            </div>
            <?php
            }
            ?>
        </div>
        <!-- Topics Section End -->

        <!-- About Start -->
        <section class="about" id="about">
            <div class="container">
              <div class="main-txt">
                <h1>About <span>Us</span></h1>
              </div>
              <div class="row" style="margin-top: 50px;">
                <div class="col-md-6 py-3 py-md-0">
                  <div class="card">
                    <img src="./images/about-img.png" alt="">
                  </div>
                </div>
                <div class="col-md-6 py-3 py-md-0">
                  <h2>How Debate Section Work</h2>
                  <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Provident perferendis dolorem, numquam earum at nam beatae voluptate natus consectetur facere, saepe cupiditate ut exercitationem deserunt, facilis quam perspiciatis autem iure illo harum minima. Quas, vitae aperiam laudantium alias asperiores nulla rerum, nihil eveniet perferendis sint illum accusamus officiis aliquam nam.</p>
                  <button id="about-btn">Read More...</button>
                </div>
              </div>
            </div>
        </section>
        <!-- About End -->

        <!-- Footer Start -->
        <footer id="footer">
            <h1><span>T</span>Topics</h1>
            <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Temporibus fugiat, ipsa quos nulla qui alias.</p>
            <div class="social-links">
              <i class="fa-brands fa-twitter"></i>
              <i class="fa-brands fa-facebook"></i>
              <i class="fa-brands fa-instagram"></i>
              <i class="fa-brands fa-youtube"></i>
              <i class="fa-brands fa-pinterest-p"></i>
            </div>
            <div class="credit">
              <p>Designed By <a href="#"> Jasim</a></p>
            </div>
            <div class="copyright">
              <p>&copy;Copyright . All Rights Reserved</p>
            </div>
        </footer>
        <!-- Footer End -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>