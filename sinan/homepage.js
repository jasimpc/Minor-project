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
        image: "home_image/home_films.jpg"
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
    console.log(`Updated to: ${topic.title}, Image: ${topic.image}`);
}

// Call updateHomeSection function every 5 seconds
setInterval(updateHomeSection, 5000);
// Call once immediately to load the first topic
updateHomeSection();