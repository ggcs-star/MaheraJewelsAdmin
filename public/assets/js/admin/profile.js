document.addEventListener('DOMContentLoaded', function () {

    const { userInitials, hasProfileImage } = window.profileConfig || {};

    const editBtn = document.getElementById('editProfileBtn');
    const saveBtn = document.getElementById('saveProfileBtn');
    const cancelBtn = document.getElementById('cancelEditBtn');
    const formInputs = document.querySelectorAll('#profileForm input, #profileForm textarea');
    const imageInput = document.getElementById('profileImageInput');
    const removeImageInput = document.getElementById('removeProfileImage');
    const avatarLabel = document.getElementById('avatarLabel');
    const avatarHint = document.getElementById('avatarHint');
    const removeImageBtn = document.getElementById('removeImageBtn');
    const avatarContainer = document.getElementById('avatarContainer');

    /* =========================
       Auto-hide alerts
    ========================= */
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            alert.classList.remove('show');
            alert.classList.add('fade');
            setTimeout(() => alert.remove(), 500);
        }, 4000);
    });

    /* =========================
       Reset form
    ========================= */
    function resetFormToNormalMode() {
        formInputs.forEach(el => {
            if (el.name) el.setAttribute('readonly', true);
        });

        imageInput.disabled = true;
        avatarLabel.style.cursor = 'default';
        avatarHint.classList.add('d-none');
        removeImageBtn.classList.add('d-none');
        removeImageInput.value = '0';

        editBtn.classList.remove('d-none');
        saveBtn.classList.add('d-none');
        cancelBtn.classList.add('d-none');

        imageInput.value = '';
    }

    if (document.querySelector('.alert')) {
        resetFormToNormalMode();
    }

    /* =========================
       Edit profile
    ========================= */
    editBtn?.addEventListener('click', () => {
        formInputs.forEach(el => el.name && el.removeAttribute('readonly'));

        imageInput.disabled = false;
        avatarLabel.style.cursor = 'pointer';
        avatarHint.classList.remove('d-none');

        // ✅ SHOW REMOVE BUTTON ONLY IF IMAGE EXISTS
        if (hasProfileImage) {
            removeImageBtn.classList.remove('d-none');
        }

        editBtn.classList.add('d-none');
        saveBtn.classList.remove('d-none');
        cancelBtn.classList.remove('d-none');
    });

    /* =========================
       Cancel edit
    ========================= */
    cancelBtn?.addEventListener('click', () => {
        resetFormToNormalMode();
        location.reload();
    });

    /* =========================
       Remove profile image
    ========================= */
    removeImageBtn?.addEventListener('click', function () {
        removeImageInput.value = '1';

        avatarContainer.innerHTML = `
            <div class="position-relative d-inline-block">
                <div
                    id="profileAvatarPreview"
                    class="d-flex align-items-center justify-content-center
                           rounded-circle shadow
                           text-white fw-bold"
                    style="
                        width:140px;
                        height:140px;
                        font-size: 3rem;
                        background: linear-gradient(135deg,#4f46e5,#7c3aed);
                    "
                >
                    ${userInitials || ''}
                </div>
                <div class="position-absolute bottom-0 end-0 bg-primary rounded-circle p-2 border border-3 border-white">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="white">
                        <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-.39-1.02-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                    </svg>
                </div>
            </div>
        `;

        this.classList.add('d-none');
        imageInput.value = '';
    });

    /* =========================
       Mobile number validation
    ========================= */
    const mobileInput = document.querySelector('input[name="mobile"]');
    mobileInput?.addEventListener('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
    });

});
