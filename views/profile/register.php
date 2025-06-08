<?php
/** @var string $error_message Повідомлення про помилку*/
$this->Title = 'Реєстрація';
?>

<div class="auth-form">
    <h1>Реєстрація</h1>
    
    <form method="POST" action="">
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger mb-4" role="alert">
                <i class="fas fa-exclamation-circle"></i>
                <?=$error_message; ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6 mb-4">
                <label for="InputFirstName" class="form-label required-field">Ім'я</label>
                <input value="<?=$this->controller->post->first_name ?? '' ?>" 
                       name="first_name" 
                       type="text" 
                       class="form-control" 
                       id="InputFirstName" 
                       placeholder="Введіть ваше ім'я"
                       required>
            </div>
            
            <div class="col-md-6 mb-4">
                <label for="InputLastName" class="form-label required-field">Прізвище</label>
                <input value="<?=$this->controller->post->last_name ?? '' ?>" 
                       name="last_name" 
                       type="text" 
                       class="form-control" 
                       id="InputLastName" 
                       placeholder="Введіть ваше прізвище"
                       required>
            </div>
        </div>

        <div class="mb-4">
            <label for="InputEmail" class="form-label required-field">Email</label>
            <input value="<?=$this->controller->post->email ?? '' ?>" 
                   name="email" 
                   type="email" 
                   class="form-control" 
                   id="InputEmail" 
                   placeholder="Введіть ваш email"
                   required>
        </div>

        <div class="mb-4">
            <label for="InputPhone" class="form-label">Телефон</label>
            <input value="<?=$this->controller->post->phone ?? '' ?>" 
                   name="phone" 
                   type="tel" 
                   class="form-control" 
                   id="InputPhone" 
                   placeholder="Введіть ваш номер телефону">
        </div>

        <div class="mb-4">
            <label for="InputAddress" class="form-label">Адреса</label>
            <input value="<?=$this->controller->post->address ?? '' ?>" 
                   name="address" 
                   type="text" 
                   class="form-control" 
                   id="InputAddress" 
                   placeholder="Введіть вашу адресу">
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <label for="InputCity" class="form-label">Місто</label>
                <input value="<?=$this->controller->post->city ?? '' ?>" 
                       name="city" 
                       type="text" 
                       class="form-control" 
                       id="InputCity" 
                       placeholder="Введіть ваше місто">
            </div>

            <div class="col-md-6 mb-4">
                <label for="InputDateOfBirth" class="form-label">Дата народження</label>
                <input value="<?=$this->controller->post->date_of_birth ?? '' ?>" 
                       name="date_of_birth" 
                       type="date" 
                       class="form-control" 
                       id="InputDateOfBirth">
            </div>
        </div>

        <div class="mb-4">
            <label for="InputPassword" class="form-label required-field">Пароль</label>
            <input name="password" 
                   type="password" 
                   class="form-control" 
                   id="InputPassword" 
                   placeholder="Введіть пароль"
                   required>
        </div>

        <div class="mb-4">
            <label for="InputPassword2" class="form-label required-field">Підтвердження пароля</label>
            <input name="password2" 
                   type="password" 
                   class="form-control" 
                   id="InputPassword2" 
                   placeholder="Введіть пароль ще раз"
                   required>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Зареєструватися
        </button>
    </form>

    <p>
        Вже зареєстровані?
        <a href="/site/profile/login">Увійти</a>
    </p>
</div>

<style>
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
    const emailInput = document.getElementById('InputEmail');
    const emailFeedback = document.createElement('div');
    emailFeedback.className = 'email-feedback';
    emailInput.parentNode.appendChild(emailFeedback);
    
    let checkEmailTimeout;
    
    emailInput.addEventListener('input', function() {
        const email = this.value.trim();
        
        clearTimeout(checkEmailTimeout);
        
        this.classList.remove('is-invalid', 'is-valid', 'is-checking');
        emailFeedback.style.display = 'none';
        
        if (email.length === 0) {
            return;
        }
        
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            this.classList.add('is-invalid');
            emailFeedback.className = 'email-feedback invalid-feedback';
            emailFeedback.textContent = 'Будь ласка, введіть коректний email';
            emailFeedback.style.display = 'block';
            return;
        }
        
        this.classList.add('is-checking');
        
        checkEmailTimeout = setTimeout(() => {
            fetch(`/site/profile/check-email?email=${encodeURIComponent(email)}`)
                .then(response => response.json())
                .then(data => {
                    this.classList.remove('is-checking');
                    
                    if (data.exists) {
                        this.classList.add('is-invalid');
                        emailFeedback.className = 'email-feedback invalid-feedback';
                        emailFeedback.textContent = 'Цей email вже зареєстрований';
                    } else {
                        this.classList.add('is-valid');
                        emailFeedback.className = 'email-feedback valid-feedback';
                        emailFeedback.textContent = 'Email доступний';
                    }
                    emailFeedback.style.display = 'block';
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.classList.remove('is-checking');
                });
        }, 500); 
    });
    
    const form = emailInput.closest('form');
    form.addEventListener('submit', function(e) {
        if (emailInput.classList.contains('is-invalid')) {
            e.preventDefault();
            emailInput.focus();
        }
    });
});
</script>