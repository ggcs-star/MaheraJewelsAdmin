<script>
document.addEventListener('DOMContentLoaded', function () {

    const imageInput  = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');

    if (!imageInput || !imagePreview) return;

    imagePreview.addEventListener('click', () => {
        imageInput.click();
    });

    imageInput.addEventListener('change', function () {

        if (!this.files || !this.files[0]) return;

        const file = this.files[0];
        const reader = new FileReader();

        reader.onload = e => {
            imagePreview.innerHTML = `
                <img src="${e.target.result}"
                     class="img-fluid rounded"
                     style="max-height:100%; object-fit:contain;">
            `;
        };

        reader.readAsDataURL(file);
    });

});
</script>
