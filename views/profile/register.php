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
        <a href="/chatter/profile/login">Увійти</a>
    </p>
</div>