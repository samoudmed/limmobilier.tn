// favorite-toggle.js
// Script AJAX pour le bouton favoris

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.favorite-toggle-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const id = btn.getAttribute('data-id');
            fetch(`/favorite/toggle/${id}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                // Met à jour l'icône selon la réponse
                const svg = btn.querySelector('svg');
                if (data.isFavorite) {
                    svg.setAttribute('fill', '#e74c3c');
                } else {
                    svg.setAttribute('fill', 'none');
                }
            })
            .catch(() => {
                alert('Erreur lors de la mise à jour des favoris');
            });
        });
    });
});
