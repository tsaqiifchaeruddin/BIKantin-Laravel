// Konfirmasi untuk aksi yang tidak bisa dibatalkan (hapus menu, validasi bayar).
// Pesan diambil dari atribut data-confirm pada <form>.
document.querySelectorAll('form[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (!window.confirm(form.dataset.confirm)) {
            event.preventDefault();
        }
    });
});
