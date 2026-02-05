    let currentProductId = null
let currentImageIndex = 0
const form = document.getElementById('productFilterForm')
const search = document.getElementById('productSearch')
const clear = document.getElementById('clearProductSearch')

if (search.value) clear.style.display = 'block'

let t
search.addEventListener('input', () => {
    clear.style.display = search.value ? 'block' : 'none'
    clearTimeout(t)
    t = setTimeout(() => form.submit(), 400)
})

clear.addEventListener('click', () => {
    search.value = ''
    form.submit()
})

document.querySelectorAll('.auto-submit').forEach(el => {
    el.addEventListener('change', () => form.submit())
})

const selectAll = document.getElementById('selectAllProducts')
const checkboxes = document.querySelectorAll('.product-row-checkbox')
const bulkBtn = document.getElementById('bulkDeleteBtn')

const toggleBulk = () => {
    bulkBtn.disabled = !document.querySelectorAll('.product-row-checkbox:checked').length
}

selectAll.addEventListener('change', () => {
    checkboxes.forEach(cb => cb.checked = selectAll.checked)
    toggleBulk()
})

checkboxes.forEach(cb => cb.addEventListener('change', toggleBulk))

bulkBtn.addEventListener('click', () => {
    if (confirm('Delete selected products?')) {
        document.getElementById('productBulkDeleteForm').submit()
    }
})
const productSidebar = document.getElementById('productFilterSidebar')
const openProductFilter = document.getElementById('openProductFilterSidebar')
const closeProductFilter = document.getElementById('closeProductFilterSidebar')

openProductFilter.addEventListener('click', () => {
    productSidebar.classList.add('open')
})

closeProductFilter.addEventListener('click', () => {
    productSidebar.classList.remove('open')
})

document.getElementById('applyProductAdvancedFilter')
.addEventListener('click', () => {

    const field = document.getElementById('productAdvField').value
    const condition = document.getElementById('productAdvCondition').value
    const value = document.getElementById('productAdvValue').value

    if (!value) return

    const url = new URL(window.location.href)
    url.searchParams.set('adv_field', field)
    url.searchParams.set('adv_condition', condition)
    url.searchParams.set('adv_value', value)

    window.location.href = url.toString()
})

const openFilterBtn = document.getElementById('openProductFilterSidebar')
const closeFilterBtn = document.getElementById('closeProductFilterSidebar')
const filterSidebar = document.getElementById('productFilterSidebar')

openFilterBtn.addEventListener('click', () => {
    filterSidebar.classList.add('open')
})

closeFilterBtn.addEventListener('click', () => {
    filterSidebar.classList.remove('open')
})

document.getElementById('applyProductAdvancedFilter')
    .addEventListener('click', () => {

        const field = document.getElementById('advField').value
        const condition = document.getElementById('advCondition').value
        const value = document.getElementById('advValue').value

        if (!value) return

        const url = new URL(window.location.href)

        url.searchParams.set('adv_field', field)
        url.searchParams.set('adv_condition', condition)
        url.searchParams.set('adv_value', value)

        window.location = url.toString()
    })
document.addEventListener('DOMContentLoaded', function () {



    const modal = document.getElementById('imagePreviewModal')
    const modalImg = document.getElementById('imagePreviewModalImg')
    const prevBtn = document.querySelector('.modal-nav.left')
    const nextBtn = document.querySelector('.modal-nav.right')
    const closeBtn = document.querySelector('.modal-close')

    if (!modal || !modalImg || !prevBtn || !nextBtn || !closeBtn) return

    let images = []
    let currentIndex = 0

    const updateImage = () => {
        modalImg.src = images[currentIndex]
    }

    const toggleNav = () => {
        const show = images.length > 1
        prevBtn.style.display = show ? 'block' : 'none'
        nextBtn.style.display = show ? 'block' : 'none'
    }

    document.querySelectorAll('.product-image-preview').forEach(img => {
    img.addEventListener('click', function (e) {
        e.stopPropagation()

        images = JSON.parse(this.dataset.images || '[]')
        if (!images.length) return

        currentIndex = 0
        currentImageIndex = 0

        // 👇 product id checkbox se uthao
        const row = this.closest('tr')
        currentProductId = row.querySelector('.product-row-checkbox')?.value

        updateImage()
        toggleNav()

        modal.classList.add('show')
    })
})

    prevBtn.addEventListener('click', e => {
    e.stopPropagation()
    currentIndex = (currentIndex - 1 + images.length) % images.length
    currentImageIndex = currentIndex
    updateImage()
})
nextBtn.addEventListener('click', e => {
    e.stopPropagation()
    currentIndex = (currentIndex + 1) % images.length
    currentImageIndex = currentIndex
    updateImage()
})


    closeBtn.addEventListener('click', () => {
        modal.classList.remove('show')
        modalImg.src = ''
    })

    modal.addEventListener('click', e => {
        if (e.target === modal) {
            modal.classList.remove('show')
            modalImg.src = ''
        }
    })
const deleteBtn = document.getElementById('deleteModalImageBtn')

if (deleteBtn) {
    deleteBtn.addEventListener('click', () => {

        if (!currentProductId) return

        if (!confirm('Delete this image?')) return

        fetch(
            `/admin/products/${currentProductId}/image/${currentImageIndex}`,
            {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content')
                }
            }
        )
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('Image deleted successfully')
                location.reload()
            } else {
                alert('Unable to delete image')
            }
        })
    })
}

})


