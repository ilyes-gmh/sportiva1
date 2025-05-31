document.addEventListener('DOMContentLoaded', function() {
    const userIcon = document.getElementById('userIcon');
    const loginForm = document.getElementById('loginForm');
    const overlay = document.getElementById('overlay');
    const closeBtn = document.getElementById('closeBtn');

    // Afficher le formulaire quand on clique sur l'icône utilisateur
    userIcon.addEventListener('click', function(e) {
        e.preventDefault();
        loginForm.style.display = 'block';
        overlay.style.display = 'block';
        document.body.style.overflow = 'hidden'; // Empêche le défilement
    });

    // Fermer le formulaire
    function closeForm() {
        loginForm.style.display = 'none';
        overlay.style.display = 'none';
        document.body.style.overflow = 'auto'; // Rétablit le défilement
    }

    // Fermer avec le bouton ×
    closeBtn.addEventListener('click', closeForm);

    // Fermer en cliquant sur l'overlay
    overlay.addEventListener('click', closeForm);

    // Empêcher la propagation du clic dans le formulaire
    loginForm.addEventListener('click', function(e) {
        e.stopPropagation();
    });
});