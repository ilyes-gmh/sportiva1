document.addEventListener('DOMContentLoaded', function() {
    // Navigation dynamique
    const navLinks = document.querySelectorAll('.sidebar ul li a');
    const sections = document.querySelectorAll('.content section');
    
    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            
            // Retirer la classe active de tous les liens et sections
            navLinks.forEach(l => l.classList.remove('active'));
            sections.forEach(s => s.classList.add('hidden-section'));
            
            // Ajouter la classe active au lien cliqué
            link.classList.add('active');
            
            // Afficher la section correspondante
            const targetId = link.getAttribute('href').substring(1);
            const targetSection = document.getElementById(targetId);
            targetSection.classList.remove('hidden-section');
            
            // Mettre à jour le titre de la page
            document.getElementById('page-title').textContent = link.textContent.trim();
        });
    });
    
})