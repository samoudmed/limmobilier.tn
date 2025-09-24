// Gestion AJAX du bouton favoris
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
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
