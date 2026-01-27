<script>
const addGalleryBtn = document.getElementById('addGalleryBtn');
const galleryInput = document.getElementById('galleryInput');
const galleryPreview = document.getElementById('galleryPreview');
let selectedFiles = [];

addGalleryBtn.onclick = () => galleryInput.click();

galleryInput.onchange = e => {
    Array.from(e.target.files).forEach(f => selectedFiles.push(f));
    galleryPreview.innerHTML = '';
    selectedFiles.forEach(file => {
        const r = new FileReader();
        r.onload = e => galleryPreview.innerHTML += `<img src="${e.target.result}" width="100">`;
        r.readAsDataURL(file);
    });
};
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const input = document.querySelector('input[name="image_url"]');
    const preview = document.getElementById('galleryPreview');

    if (!input || !preview) return;

    input.addEventListener('change', function () {
        preview.innerHTML = '';

        if (!this.files || !this.files[0]) {
            preview.innerHTML = '<div class="text-muted small">No image selected</div>';
            return;
        }

        const file = this.files[0];
        const reader = new FileReader();

        reader.onload = function (e) {
            preview.innerHTML = `
                <img src="${e.target.result}"
                     class="img-thumbnail"
                     style="width:120px;height:120px;object-fit:cover">
            `;
        };

        reader.readAsDataURL(file);
    });

});
</script>
