// assets/js/favoris.js

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-favoris').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const annonceId = btn.dataset.annonceId;
            fetch('/favoris/toggle/' + annonceId, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': btn.dataset.csrfToken || ''
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'added') {
                    btn.classList.add('favoris-active');
                    btn.innerHTML = '<i class="fa fa-heart"></i>';
                } else if (data.status === 'removed') {
                    btn.classList.remove('favoris-active');
                    btn.innerHTML = '<i class="fa fa-heart-o"></i>';
                }
            })
            .catch(() => {
                alert('Erreur lors de la mise à jour des favoris.');
            });
        });
    });
});
