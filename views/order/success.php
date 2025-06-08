<?php
$this->Title = 'Замовлення успішно оформлено';
?>

<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/chatter/home">Головна</a></li>
            <li class="breadcrumb-item active" aria-current="page">Замовлення успішно оформлено</li>
        </ol>
    </nav>

    <div class="alert alert-success text-center">
        <h2>Дякуємо за ваше замовлення!</h2>
        <p>Ваше замовлення успішно оформлено. Номер вашого замовлення: #<?= $orderId ?></p>
        <p>Ми зв'яжемося з вами для підтвердження.</p>
        <a href="/chatter/home" class="btn btn-primary">На головну</a>
        <a href="/chatter/profile/orders" class="btn btn-outline-secondary">Мої замовлення</a>
    </div>
</div>