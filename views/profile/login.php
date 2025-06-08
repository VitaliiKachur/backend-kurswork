<?php
/** @var string $error_message Повідомлення про помилку*/
$this->Title = 'Вхід';
?>

<div class="auth-form">
    <h1>Вхід</h1>
    
    <form method="POST" action="">
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger mb-4" role="alert">
                <i class="fas fa-exclamation-circle"></i>
                <?=$error_message; ?>
            </div>
        <?php endif; ?>
        
        <div class="mb-4">
            <label for="InputEmail" class="form-label required-field">Email</label>
            <input name="email" 
                   type="email" 
                   class="form-control" 
                   id="InputEmail" 
                   placeholder="Введіть ваш email"
                   required>
        </div>
        
        <div class="mb-4">
            <label for="InputPassword" class="form-label required-field">Пароль</label>
            <input name="password" 
                   type="password" 
                   class="form-control" 
                   id="InputPassword" 
                   placeholder="Введіть ваш пароль"
                   required>
        </div>
        
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-sign-in-alt"></i> Увійти
        </button>
    </form>

    <p>
        Ще не зареєстровані?
        <a href="/chatter/profile/register">Зареєструватися</a>
    </p>
</div>