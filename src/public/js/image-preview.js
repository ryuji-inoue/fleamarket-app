document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.js-image-input').forEach(input => {

        input.addEventListener('change', function () {

            // 選択されたファイルを取得（単一ファイル想定）
            const file = this.files[0];

            // ファイル未選択の場合は処理を中断
            if (!file) return;

            // 画像ファイルでない場合は処理を中断
            if (!file.type.startsWith('image/')) return;

            // プレビュー対象の <img> 要素を取得
            const previewId = this.dataset.preview;
            const preview = document.getElementById(previewId);
            if (!preview) return;

            // FileReader を使って選択画像を読み込む
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
            };

            // ファイルを Data URL として読み込む（画像プレビュー用）
            reader.readAsDataURL(file);
        });

    });

});