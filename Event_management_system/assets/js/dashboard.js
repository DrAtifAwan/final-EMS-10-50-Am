function toggleNav() {
    const sideNav = document.getElementById('side-nav');
    const contentWrapper = document.getElementById('contentWrapper');
    const navbar = document.querySelector('.navbar');
    
    sideNav.classList.toggle('open');
    contentWrapper.classList.toggle('shifted');
    navbar.classList.toggle('shifted');
}

// Close menu when clicking outside
document.addEventListener('click', function(event) {
    const sideNav = document.getElementById('side-nav');
    const toggleBtn = document.querySelector('.side-nav-toggle');
    
    if (!sideNav.contains(event.target) && event.target !== toggleBtn) {
        if (sideNav.classList.contains('open')) {
            toggleNav();
        }
    }
});

function toggleNav() {
    const sideNav = document.getElementById('side-nav');
    const contentWrapper = document.getElementById('contentWrapper');
    const navbar = document.querySelector('.navbar');
    
    sideNav.classList.toggle('open');
    contentWrapper.classList.toggle('shifted');
    navbar.classList.toggle('shifted');
}

// Close menu when clicking outside
document.addEventListener('click', function(event) {
    const sideNav = document.getElementById('side-nav');
    const toggleBtn = document.querySelector('.side-nav-toggle');
    
    if (!sideNav.contains(event.target) && event.target !== toggleBtn) {
        if (sideNav.classList.contains('open')) {
            toggleNav();
        }
    }
});

// Toggle notifications list
document.getElementById('toggle-notifications').addEventListener('click', function () {
    const notificationsList = document.getElementById('notifications-list');
    if (notificationsList.classList.contains('hidden')) {
        notificationsList.classList.remove('hidden');
        this.textContent = "Hide Notifications";
    } else {
        notificationsList.classList.add('hidden');
        this.textContent = "Show Notifications";
    }
});

// Toggle events list
document.getElementById('toggle-events').addEventListener('click', function () {
    const eventsList = document.getElementById('events-list');
    if (eventsList.classList.contains('hidden')) {
        eventsList.classList.remove('hidden');
        this.textContent = "Hide Events";
    } else {
        eventsList.classList.add('hidden');
        this.textContent = "Show Events";
    }
});

// Set background images for hero slides
document.querySelectorAll('.hero-slide[data-bg-image]').forEach(slide => {
    const bgImage = slide.getAttribute('data-bg-image');
    if (bgImage) {
        slide.style.backgroundImage = `url('${bgImage}')`;
    }
});

function toggleNav() {
    const sideNav = document.getElementById('side-nav');
    const mainContent = document.getElementById('main-content');
    
    if (sideNav.classList.contains('open')) {
        sideNav.classList.remove('open'); // Close the slider
        mainContent.classList.remove('shifted'); // Shift content back to original position
    } else {
        sideNav.classList.add('open'); // Open the slider
        mainContent.classList.add('shifted'); // Shift content to the right
    }
}

function toggleNav() {
    const sideNav = document.getElementById('side-nav');
    if (sideNav.classList.contains('open')) {
        sideNav.classList.remove('open');
    } else {
        sideNav.classList.add('open');
    }
}

function initializeEventSlider() {
    const carousel = document.getElementById('carouselExampleAutoplaying');
    
    if (!carousel) return;

    let currentIndex = 0;
    const items = carousel.querySelectorAll('.carousel-item');
    const totalItems = items.length;

    function showSlide(index) {
        items.forEach(item => item.classList.remove('active'));
        items[index].classList.add('active');
    }

    document.querySelector('.carousel-control-next').addEventListener('click', () => {
        currentIndex = (currentIndex + 1) % totalItems;
        showSlide(currentIndex);
    });

    document.querySelector('.carousel-control-prev').addEventListener('click', () => {
        currentIndex = (currentIndex - 1 + totalItems) % totalItems;
        showSlide(currentIndex);
    });
    setInterval(() => {
        currentIndex = (currentIndex + 1) % totalItems;
        showSlide(currentIndex);
    }, 5000); 
}