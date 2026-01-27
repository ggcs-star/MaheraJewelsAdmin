<script>
const categories = @json($categories);
const main = document.getElementById('mainCategory');
const sub = document.getElementById('subCategory');
const finalCat = document.getElementById('finalCategoryId');

main.onchange = () => {
    finalCat.value = main.value;
    let html = '<option value="">Select Sub Category</option>';
    categories.filter(c => c.parent_id == main.value)
        .forEach(c => html += `<option value="${c.id}">${c.name}</option>`);
    sub.innerHTML = html;
};

sub.onchange = () => {
    finalCat.value = sub.value || main.value;
};
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const main = document.getElementById('mainCategory');
    const sub  = document.getElementById('subCategory');

    main.addEventListener('change', function () {
        sub.disabled = !this.value;
    });

});
</script>
