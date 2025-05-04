document.addEventListener("DOMContentLoaded", function() {
    const roles = document.querySelectorAll(".role-card a");
    roles.forEach(role => {
        role.addEventListener("click", function(event) {
            event.preventDefault();
            window.location.href = role.getAttribute("href");
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    // Example: Toggle visibility of sections
    const toggleButtons = document.querySelectorAll('.toggle-btn');
    toggleButtons.forEach(button => {
        button.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const target = document.getElementById(targetId);
            if (target) {
                target.classList.toggle('hidden');
            }
        });
    });

    // Example: Carousel initialization
    const carousels = document.querySelectorAll('.carousel');
    carousels.forEach(carousel => {
        const options = {
            interval: 5000,
            wrap: true
        };
        new bootstrap.Carousel(carousel, options);
    });

    // Add more interactivity as needed
});

// Initialize tooltips
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

// Initialize modals
const myModal = new bootstrap.Modal(document.getElementById('myModal'), {
    keyboard: false
});

// Example form validation
const forms = document.querySelectorAll('.needs-validation');
Array.prototype.slice.call(forms).forEach(function (form) {
    form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    }, false);
});
