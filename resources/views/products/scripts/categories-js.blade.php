<script>
document.addEventListener('DOMContentLoaded', function () {

    const categories = @json($categories);

    const main = document.getElementById('mainCategory');
    const sub  = document.getElementById('subCategory');
    const finalCat = document.getElementById('finalCategoryId');

    if (!main || !sub || !finalCat) return;

    const savedId = finalCat.value; 

    function loadSubs(parentId, selectedSubId = null) {

        let html = '<option value="">Select sub category</option>';
        const subs = categories.filter(c => c.parent_id == parentId);

        if (subs.length === 0) {
            sub.innerHTML = html;
            sub.disabled = true;
            return;
        }

        subs.forEach(c => {
            const sel = selectedSubId == c.id ? 'selected' : '';
            html += `<option value="${c.id}" ${sel}>${c.name}</option>`;
        });

        sub.innerHTML = html;
        sub.disabled = false;
    }

    if (savedId) {

        const selected = categories.find(c => c.id == savedId);

        if (selected) {

            if (selected.parent_id) {

                main.value = selected.parent_id;

                loadSubs(selected.parent_id, selected.id);

            }
            else {

                main.value = selected.id;
                sub.disabled = true;

            }
        }
    }

    main.addEventListener('change', function () {

        const mainId = this.value;
        finalCat.value = mainId;

        if (!mainId) {
            sub.innerHTML = '<option value="">Select sub category</option>';
            sub.disabled = true;
            finalCat.value = '';
            return;
        }

        loadSubs(mainId);
    });

    sub.addEventListener('change', function () {

        finalCat.value = this.value || main.value;
    });

});
</script>
