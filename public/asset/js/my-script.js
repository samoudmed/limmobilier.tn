$('.select2-selection__rendered').select2({
    placeholder: 'Select an option'
});


/* main menu mobile */
document.addEventListener('DOMContentLoaded', function () {
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    const mainMenu = document.querySelector('#pbr-mainmenu');

    // --- Toggle the main mobile menu ---
    if (menuToggle && mainMenu) {
        menuToggle.addEventListener('click', function () {
            // Toggle the class. CSS will handle the rest.
            mainMenu.classList.toggle('is-open');

            // Update the aria-expanded attribute for accessibility
            const isOpen = mainMenu.classList.contains('is-open');
            this.setAttribute('aria-expanded', isOpen);
        });
    }

    // --- NEW: Toggle dropdown sub-menus (.level-1) on mobile ---
    const dropdownToggles = document.querySelectorAll('#pbr-mainmenu .dropdown > a');

    dropdownToggles.forEach(function (toggle) {
        toggle.addEventListener('click', function (event) {
            // Only activate this behavior on mobile screen sizes
            if (window.innerWidth <= 768) {
                // Prevent the link from being followed on the first tap
                event.preventDefault();

                // Get the parent <li> element
                const parentLi = this.parentElement;

                // Toggle the 'is-open' class on the parent <li>
                if (parentLi) {
                    parentLi.classList.toggle('is-open');
                }
            }
        });
    });
});