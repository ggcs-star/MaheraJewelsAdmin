<script>
document.addEventListener('DOMContentLoaded', function () {

    const imageInput   = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');

    if (!imageInput || !imagePreview) return;

    imagePreview.addEventListener('click', function (e) {
        if (e.target.classList.contains('selectable-gallery-image')) {
            return;
        }
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
                img.className = 'rounded border selectable-gallery-image';
                img.dataset.path = e.target.result; 
                img.style.width = '100px';
                img.style.height = '100px';
                img.style.objectFit = 'cover';
                img.style.marginRight = '8px';
                img.style.marginBottom = '8px';
                img.style.cursor = 'pointer';
                imagePreview.appendChild(img);
            };
            reader.readAsDataURL(file);
            setTimeout(() => {
                autoSelectFirstImage();
                if (typeof autoCreateFirstVariant === 'function') {
                autoCreateFirstVariant();
            }
                setTimeout(() => {
                    if (typeof autoFillFirstVariant === 'function') {
                        autoFillFirstVariant();
                    }
                }, 200);
            }, 300);
        });
    });

    window.selectedGalleryImage = null;

   document.addEventListener('click', function (e) {
        if (!e.target.classList.contains('selectable-gallery-image')) return;
        
        document.querySelectorAll('.selectable-gallery-image').forEach(img => {
            img.style.border = '2px solid #e5e7eb';
            img.style.boxShadow = 'none';
        });
        e.target.style.border = '3px solid #ff3f6c';
        e.target.style.boxShadow = '0 0 0 3px rgba(255,63,108,0.4)';
        
        window.selectedGalleryImage = e.target.dataset.path;
        document.querySelectorAll('.selected-gallery-image').forEach(input => {
            input.value = window.selectedGalleryImage;
        });
    });

    function autoSelectFirstImage() {
        const firstImage = document.querySelector('.selectable-gallery-image');
        if (!firstImage) return;
        
        document.querySelectorAll('.selectable-gallery-image').forEach(img => {
            img.style.border = '2px solid #e5e7eb';
            img.style.boxShadow = 'none';
        });
        firstImage.style.border = '3px solid #ff3f6c';
        firstImage.style.boxShadow = '0 0 0 3px rgba(255,63,108,0.4)';
        
        window.selectedGalleryImage = firstImage.dataset.path;
        syncSelectedImageToVariants();
        document.querySelectorAll('.selected-gallery-image').forEach(input => {
            input.value = window.selectedGalleryImage;
        });
    }

    const productPrice = document.querySelector('input[name="product_price"]');
    const productSku = document.querySelector('input[name="sku"]');

    if (productPrice) {
        productPrice.addEventListener('input', function() {
            if (typeof autoFillFirstVariant === 'function') {
                autoFillFirstVariant();
            }
        });
    }

    if (productSku) {
        productSku.addEventListener('input', function() {
            if (typeof autoFillFirstVariant === 'function') {
                autoFillFirstVariant();
            }
        });
    }

});
</script>