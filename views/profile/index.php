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
                        <form method="post" action="/chatter/profile/update" enctype="multipart/form-data">
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
                        <img src="<?= !empty($customer['ProfilePhoto']) ? htmlspecialchars($customer['ProfilePhoto']) : '/chatter/assets/img/default-avatar.png' ?>" alt="Фото профілю" class="rounded-circle border border-3 border-warning shadow-lg bg-white profile-avatar-big">
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
</style>