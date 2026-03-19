document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.js-image-input').forEach(input => {

        input.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
            if (!file.type.startsWith('image/')) return;

            const previewId = this.dataset.preview;
            const preview = document.getElementById(previewId);

            if (!preview) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        });

    });

});