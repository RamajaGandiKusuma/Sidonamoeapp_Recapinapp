document.addEventListener('DOMContentLoaded', function () {

    // === POPUP SUCCESS ===
    if (window.flashSuccess) {
        alert(window.flashSuccess);

        // reset form setelah sukses
        const form = document.getElementById('form-pengajuan');
        if (form) {
            form.reset();
        }
    }

    // === POPUP ERROR (SERVER ERROR) ===
    if (window.flashError) {
        alert(window.flashError);
    }

});