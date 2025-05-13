document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('#sidebar');
    const backdrop = document.querySelector('.backdrop');
    const toggleBtn = document.querySelector('.toggle-btn');

    // Verifica se os elementos existem antes de adicionar os eventos
    if (sidebar && backdrop && toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            console.log('Toggle button clicked');
            sidebar.classList.toggle('expanded');
            backdrop.classList.toggle('show-backdrop');
        });
    } else {
        console.warn('Alguns elementos da sidebar não foram encontrados nesta página.');
    }
});
