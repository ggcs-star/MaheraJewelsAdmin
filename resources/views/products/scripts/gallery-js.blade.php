<script>
document.addEventListener('DOMContentLoaded', function () {

    const imageInput   = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');

    if (!imageInput || !imagePreview) return;

    imagePreview.addEventListener('click', () => {
        imageInput.click();
    });

    imageInput.addEventListener('change', function () {

        if (!this.files || this.files.length === 0) return;

        const placeholder = document.getElementById('imagePlaceholder');
        if (placeholder) placeholder.remove();

        Array.from(this.files).forEach(file => {

            const reader = new FileReader();

            reader.onload = e => {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'rounded border';
                img.style.width = '100px';
                img.style.height = '100px';
                img.style.objectFit = 'cover';
                img.style.marginRight = '8px';
                img.style.marginBottom = '8px';

                imagePreview.appendChild(img);
            };

            reader.readAsDataURL(file);
        });
    });

});
</script>
