
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    document.addEventListener("DOMContentLoaded", function() {
        const topics = [
            {
                title: "Politics",
                description: "Explore the latest political news and discussions.",
                image: "home_image/home_politics.jpeg"
            },
            {
                title: "Education",
                description: "Discover educational resources and insights.",
                image: "home_image/home_edu.jpg"
            },
            {
                title: "Sports",
                description: "Stay updated with the latest sports events and news.",
                image: "home_image/home_sports.jpg"
            },
            {
                title: "Films",
                description: "Dive into the world of films and entertainment.",
                image: "home_image/home_file.jpeg"
            }
        ];

        let currentIndex = 0;
        const homeSection = document.querySelector(".home");
        const homeDescription = document.getElementById("home-description");
        const changeContent = document.querySelector(".changecontent");

        function updateHomeSection() {
            const topic = topics[currentIndex];
            homeSection.style.backgroundImage = `linear-gradient(rgba(0,0,0,0.3),rgba(0,0,0,0.2)), url(${topic.image})`;
            homeDescription.textContent = topic.description;
            changeContent.textContent = topic.title;
            currentIndex = (currentIndex + 1) % topics.length;
        }

        setInterval(updateHomeSection, 10000); // Change every 5 seconds
        updateHomeSection(); // Initial call
    });
