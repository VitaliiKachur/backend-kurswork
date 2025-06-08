<?php
/** @var array $customer */
$this->Title = 'Мій профіль';
?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0 bg-dark text-light p-4 profile-card">
                <h1 class="mb-4 text-center fw-bold">Мій профіль</h1>
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <form method="post" action="/site/profile/update" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="first_name" class="form-label">Ім'я</label>
                                <input type="text" class="form-control bg-dark text-light border-secondary" id="first_name" name="first_name" value="<?= htmlspecialchars($customer['FirstName']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Прізвище</label>
                                <input type="text" class="form-control bg-dark text-light border-secondary" id="last_name" name="last_name" value="<?= htmlspecialchars($customer['LastName']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control bg-dark text-light border-secondary" id="email" name="email" value="<?= htmlspecialchars($customer['Email']) ?>" required>
                                <div class="email-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Телефон</label>
                                <input type="tel" class="form-control bg-dark text-light border-secondary" id="phone" name="phone" value="<?= htmlspecialchars($customer['Phone']) ?>">
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Адреса</label>
                                <input type="text" class="form-control bg-dark text-light border-secondary" id="address" name="address" value="<?= htmlspecialchars($customer['AddressClient']) ?>">
                            </div>
                            <div class="mb-3">
                                <label for="city" class="form-label">Місто</label>
                                <input type="text" class="form-control bg-dark text-light border-secondary" id="city" name="city" value="<?= htmlspecialchars($customer['City']) ?>">
                            </div>
                            <div class="mb-3">
                                <label for="profile_photo" class="form-label">Фото профілю</label>
                                <input type="file" class="form-control bg-dark text-light border-secondary" id="profile_photo" name="profile_photo" accept="image/*">
                            </div>
                            <button type="submit" class="btn btn-primary px-4 py-2 mt-2">Оновити дані</button>
                        </form>
                    </div>
                    <div class="col-md-4 text-center">
                        <img src="<?= !empty($customer['ProfilePhoto']) ? htmlspecialchars($customer['ProfilePhoto']) : '/site/assets/img/default-avatar.png' ?>" alt="Фото профілю" class="rounded-circle border border-3 border-warning shadow-lg bg-white profile-avatar-big">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
.profile-card {
    background: #23262b !important;
    border-radius: 18px;
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.18);
}
.profile-avatar-big {
    width: 260px;
    height: 260px;
    object-fit: cover;
    background: #fff;
    margin-bottom: 2rem;
    margin-top: 1rem;
}
@media (max-width: 768px) {
    .profile-card .row {
        flex-direction: column-reverse;
    }
    .profile-card .col-md-4 {
        margin-bottom: 2rem;
    }
    .profile-avatar-big {
        width: 180px;
        height: 180px;
    }
}
.email-feedback {
    display: none;
    margin-top: 0.25rem;
    font-size: 0.875em;
}

.email-feedback.invalid-feedback {
    color: #dc3545;
}

.email-feedback.valid-feedback {
    color: #198754;
}

input.is-checking {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%236c757d'%3E%3Cpath d='M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const emailInput = document.getElementById('email');
    const emailFeedback = emailInput.nextElementSibling;
    const currentEmail = emailInput.value.trim();
    
    let checkEmailTimeout;
    
    emailInput.addEventListener('input', function() {
        const email = this.value.trim();
        
        // Очищаємо попередній таймаут
        clearTimeout(checkEmailTimeout);
        
        // Очищаємо попередні класи та повідомлення
        this.classList.remove('is-invalid', 'is-valid', 'is-checking');
        emailFeedback.style.display = 'none';
        
        // Якщо email не змінився, не перевіряємо
        if (email === currentEmail) {
            return;
        }
        
        if (email.length === 0) {
            return;
        }
        
        // Базова валідація email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            this.classList.add('is-invalid');
            emailFeedback.className = 'email-feedback invalid-feedback';
            emailFeedback.textContent = 'Будь ласка, введіть коректний email';
            emailFeedback.style.display = 'block';
            return;
        }
        
        // Додаємо індикатор перевірки
        this.classList.add('is-checking');
        
        // Встановлюємо затримку перед відправкою запиту
        checkEmailTimeout = setTimeout(() => {
            fetch(`/site/profile/check-email?email=${encodeURIComponent(email)}`)
                .then(response => response.json())
                .then(data => {
                    this.classList.remove('is-checking');
                    
                    if (data.exists && email !== currentEmail) {
                        this.classList.add('is-invalid');
                        emailFeedback.className = 'email-feedback invalid-feedback';
                        emailFeedback.textContent = 'Цей email вже використовується';
                        emailFeedback.style.display = 'block';
                    } else {
                        this.classList.add('is-valid');
                        emailFeedback.className = 'email-feedback valid-feedback';
                        emailFeedback.textContent = 'Email доступний';
                        emailFeedback.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.classList.remove('is-checking');
                });
        }, 500); // Затримка 500мс
    });
    
    // Додаємо перевірку форми перед відправкою
    const form = emailInput.closest('form');
    form.addEventListener('submit', function(e) {
        if (emailInput.classList.contains('is-invalid')) {
            e.preventDefault();
            emailInput.focus();
        }
    });
});
</script>