document.addEventListener('DOMContentLoaded', function () {
    const userIcon = document.getElementById('userIcon');
    const userDropdown = document.getElementById('userDropdown');
    const loginForm = document.getElementById('login-form');
    const overlay = document.getElementById('popup-overlay');
    const closeBtn = document.getElementById('close-popup');

    if (userIcon) {
        userIcon.addEventListener('click', function (e) {
            e.preventDefault();

            if (IS_LOGGED_IN) {
                userDropdown.classList.toggle('show'); // toggle the class
            } else {
                loginForm.style.display = 'block';
                overlay.style.display = 'flex';
                document.body.style.overflow = 'hidden'; // Empêche le défilement
            }
        });
        // Close when clicking elsewhere
        document.addEventListener('click', (e) => {
            // if dropdown is open and the click target is NOT the icon or inside the dropdown
            if (
                userDropdown.classList.contains('show') &&
                !userIcon.contains(e.target) &&
                !userDropdown.contains(e.target)
            ) {
                userDropdown.classList.remove('show');
            }
        });
    }

    function closeForm() {
        loginForm?.classList.remove('show');
        overlay?.classList.remove('show');
        loginForm.style.display = 'none';
        overlay.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    closeBtn?.addEventListener('click', closeForm);
    overlay?.addEventListener('click', closeForm);
    loginForm?.addEventListener('click', e => e.stopPropagation());
});
