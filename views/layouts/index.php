<?php
/** @var string $Title */
/** @var string $Content */

use models\Users;
use models\Session;
use models\Customers;
use core\Core;
?>
<!DOCTYPE html>
<html lang="uk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/site/assets/css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <title><?= $Title ?? 'Site' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
        crossorigin="anonymous"></script>
    <style>
        :root {
            --dark-bg: #181a20;
            --darker-bg: #13151a;
            --card-bg: #23272f;
            --text-primary: #f3f6fa;
            --text-secondary: #b3b3b3;
            --accent-color: #4f8cff;
            --accent-hover: #6ed0f6;
            --border-color: #23272f;
            --success-bg: #1e2e1e;
            --danger-bg: #2e1e1e;
        }

        body {
            background: var(--dark-bg);
            color: var(--text-primary);
        }

        .header-color {
            background: var(--darker-bg);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 2rem;
        }

        .navbar {
            padding: 1rem 0;
        }

        .navbar-brand {
            color: var(--text-primary) !important;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .header-buttons {
            display: flex;
            gap: 2rem;
            align-items: center;
            margin-left: auto;
            justify-content: center;
        }

        .header-buttons a {
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.3s ease;
            font-size: 0.95rem;
        }

        .header-buttons a:hover {
            color: var(--accent-color);
        }

        .navbar-nav .nav-link:hover {
            color: var(--accent-color) !important;
        }

        .dropdown-menu {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 0.7rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.2);
        }
        .dropdown-item { color: var(--text-secondary); }
        .dropdown-item:hover { background: var(--darker-bg); color: var(--text-primary); }

        .btn-primary {
            background: linear-gradient(90deg, var(--accent-color) 0%, var(--accent-hover) 100%);
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            color: #fff;
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, var(--accent-hover) 0%, var(--accent-color) 100%);
            box-shadow: 0 2px 8px rgba(79,140,255,0.15);
        }
        .btn-link { color: var(--accent-color); }
        .btn-link:hover { color: var(--accent-hover); }

        main.container {
            margin-top: 150px !important;
            background: var(--card-bg);
            border-radius: 1.2rem;
            box-shadow: 0 4px 32px rgba(0,0,0,0.25);
            padding: 2.5rem 2rem 2rem 2rem;
            margin-top: 2rem;
            margin-bottom: 2rem;
            min-height: 70vh;
            border: 1px solid var(--border-color);
        }

        h1, h2, h3, h4, h5 {
            font-weight: 600;
            color: var(--text-primary);
        }
        h1 {
            color: var(--text-primary);
            border-radius: 0.7rem;
            padding: 0.5rem 1.5rem;
            margin-bottom: 1.2rem;
            display: inline-block;
        }

        .form-control, .form-select {
            background: var(--darker-bg);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            border-radius: 0.5rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 2px var(--accent-color)33;
        }

        .card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            box-shadow: 0 2px 16px rgba(0,0,0,0.25);
            color: #ffffff;
            margin-bottom: 2rem;
            transition: box-shadow 0.2s, transform 0.2s;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .card:hover {
            box-shadow: 0 6px 32px rgba(79,140,255,0.15);
            transform: translateY(-4px);
        }
        .card-img-container {
            background: #ffffff;
            width: 100%;
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem;
            position: relative;
        }
  
        .card-img-top.product-image,
        .product-image.card-img-top,
        .card-img-container .product-image,
        .card-img-container img,
        .card .card-img-top {
            position: absolute !important;
            max-width: 95% !important;
            max-height: 95% !important;
            width: auto !important;
            height: auto !important;
            margin: 0 !important;
            padding: 0 !important;
            background: #ffffff !important;
        }
        .card:hover .card-img-top,
        .card:hover .product-image {
            transform: scale(1.03);
        }
    
        .card > .card-img-top,
        .card > .product-image {
            border-radius: 0 !important;
            border: none !important;
        }
    
        @media all {
            .card-img-top.product-image,
            .product-image.card-img-top,
            .card-img-container img {
                max-width: 95% !important;
                max-height: 95% !important;
                width: auto !important;
                height: auto !important;
            }
        }
        .card-body {
            padding: 1rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            background: var(--card-bg);
            color: #ffffff;
        }
        .no-image, .no-image-placeholder {
            width: 95%;
            height: 95%;
            background: var(--darker-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            font-size: 1.2rem;
        }

        .card-title {
            color: #ffffff;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .card-text {
            color: #ffffff !important;
            margin-bottom: 0.5rem;
        }

        .price {
            font-size: 1.3rem;
            font-weight: bold;
            color: #6edc7b;
            margin: 0.5rem 0;
        }
   
        .card p, 
        .card span:not(.badge),
        .card small {
            color: #ffffff;
        }

        .btn-group {
            display: flex;
            gap: 0.5rem;
            margin-top: auto;
        }
        .card .stock { background: var(--success-bg); color: #6edc7b; border-radius: 0.5rem; padding: 0.2rem 0.7rem; display: inline-block; margin-top: 0.5rem; }
        .card .nostock { background: var(--danger-bg); color: #ff6b6b; border-radius: 0.5rem; padding: 0.2rem 0.7rem; display: inline-block; margin-top: 0.5rem; }

        .alert {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            border-radius: 0.7rem;
        }

        .breadcrumb {
            background: var(--darker-bg);
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 0.5rem;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .breadcrumb-item {
            color: var(--text-secondary);
        }
        .breadcrumb-item a {
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .breadcrumb-item a:hover {
            color: var(--accent-color);
        }
        .breadcrumb-item.active {
            color: var(--text-primary);
        }
        .breadcrumb-item + .breadcrumb-item::before {
            color: var(--accent-color);
            content: "›";
            font-size: 1.2rem;
            line-height: 1;
            padding: 0 0.5rem;
        }

        .table {
            color: var(--text-primary);
            border-color: var(--border-color);
        }
        .table th, .table td { border-color: var(--border-color); }
        .table-striped > tbody > tr:nth-of-type(odd) { background-color: var(--darker-bg); }

        .img-thumbnail {
            background: var(--darker-bg);
            border: 1px solid var(--border-color);
            border-radius: 0.7rem;
        }
        .badge.bg-primary { background: var(--accent-color) !important; }
        .dropdown-menu .dropdown-item.text-danger { color: #ff6b6b; }
        .dropdown-menu .dropdown-item.text-danger:hover { background: rgba(255,107,107,0.1); }


        .filter-block, .search-block {
            background: var(--darker-bg);
            border-radius: 0.7rem;
            padding: 1.2rem 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.10);
            border: 1px solid var(--border-color);
        }
        .filter-block label, .search-block label {
            color: var(--text-secondary);
            font-weight: 500;
        }

        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--darker-bg); }
        ::-webkit-scrollbar-thumb { background: var(--accent-color); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--accent-hover); }

        @media (max-width: 768px) {
            main.container { padding: 1rem 0.5rem; }
            .card { margin-bottom: 1.2rem; }
        }

        .search-filters {
            background: var(--card-bg);
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
            border: 1px solid rgba(255,255,255,0.1);
        }

        .search-filters h2 {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            color: var(--text-primary);
            border-bottom: 2px solid var(--accent-color);
            padding-bottom: 0.5rem;
            display: inline-block;
        }

        .search-filters .form-control,
        .search-filters .form-select {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            color: var(--text-primary);
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .search-filters .form-control::placeholder {
            color: rgba(255,255,255,0.4);
            font-weight: 300;
        }

        .search-filters .form-select {
            color: rgba(255,255,255,0.4);
        }

        .search-filters .form-select option {
            background: var(--card-bg);
            color: var(--text-primary);
        }

        .search-filters .form-select option:first-child {
            color: rgba(255,255,255,0.4);
        }

        .search-filters .form-control:focus::placeholder {
            color: rgba(255,255,255,0.6);
        }

        .search-filters .form-control:focus,
        .search-filters .form-select:focus {
            background: rgba(255,255,255,0.1);
            border-color: var(--accent-color);
            box-shadow: 0 0 0 2px rgba(79,140,255,0.25);
            color: var(--text-primary);
        }

        .search-filters .input-group-text {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.1);
            color: var(--accent-color);
        }

        .search-filters .btn {
            padding: 0.75rem 2rem;
            font-size: 1rem;
            margin-right: 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .search-filters .btn-primary {
            background: linear-gradient(135deg, var(--accent-color) 0%, var(--accent-hover) 100%);
            border: none;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(79,140,255,0.2);
        }

        .search-filters .btn-secondary {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            color: var(--text-secondary);
        }

        .search-filters .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(79,140,255,0.4);
        }

        .search-filters label {
            color: var(--accent-color);
            margin-bottom: 0.5rem;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .price-range {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .price-range .form-control {
            margin-bottom: 0;
        }

        .price-range span {
            color: var(--accent-color);
            font-weight: 500;
        }

        .alert-light {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            color: var(--text-primary);
        }

        .alert-light strong {
            color: var(--accent-color);
        }

        .alert-light i {
            color: var(--accent-color);
            margin-right: 0.5rem;
        }

        .category-header {
            background: linear-gradient(180deg, rgba(96, 165, 250, 0.15) 0%, rgba(59, 130, 246, 0.05) 100%);
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
            box-shadow: 
                0 4px 24px rgba(0, 0, 0, 0.2),
                inset 0 1px 1px rgba(255, 255, 255, 0.1),
                0 0 40px rgba(59, 130, 246, 0.1);
        }

        .category-header h1 {
            color: var(--text-primary);
            font-size: 2rem;
            font-weight: 600;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .category-header p {
            color: var(--text-secondary);
            margin: 0.5rem 0 0 0;
            font-size: 1rem;
            opacity: 0.9;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .category-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, 
                transparent, 
                rgba(255, 255, 255, 0.1), 
                transparent
            );
        }

        .category-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, 
                transparent, 
                rgba(0, 0, 0, 0.2), 
                transparent
            );
        }


        .category-banner {
            background: linear-gradient(180deg, 
                rgba(59, 130, 246, 0.1) 0%, 
                rgba(37, 99, 235, 0.05) 100%
            );
            padding: 2rem;
            margin-bottom: 2rem;
            border-radius: 1rem;
            position: relative;
            box-shadow: 
                0 4px 20px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        .category-banner::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(
                circle at top right,
                rgba(59, 130, 246, 0.1),
                transparent 70%
            );
            border-radius: inherit;
            pointer-events: none;
        }

        .category-banner h2 {
            color: var(--text-primary);
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            position: relative;
        }

        .category-banner p {
            color: var(--text-secondary);
            margin: 0;
            font-size: 1rem;
            position: relative;
            opacity: 0.9;
        }

     
        .auth-form {
            max-width: 700px;
            margin: 2rem auto;
            padding: 2.5rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 0.75rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.1);
        }

        .auth-form h1 {
            text-align: center;
            color: var(--text-primary);
            margin-bottom: 2rem;
            font-size: 2rem;
            font-weight: 600;
            background: none;
            padding: 0;
            box-shadow: none;
            border-radius: 0;
            display: block;
        }

        .auth-form .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .auth-form .form-group {
            margin-bottom: 1.25rem;
        }

        .auth-form .form-control {
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.15);
            color: var(--text-primary);
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            border-radius: 0.5rem;
            width: 100%;
            min-height: 42px;
        }

        .auth-form .form-control:focus {
            background: rgba(255,255,255,0.1);
            border-color: var(--accent-color);
            box-shadow: 0 0 0 2px rgba(79,140,255,0.25);
        }

        .auth-form .form-control::placeholder {
            color: rgba(255,255,255,0.4);
            font-weight: 300;
        }

        .auth-form .btn-primary {
            background: none;
            border: none;
            color: var(--accent-color);
            padding: 0;
            margin: 0;
            font-size: 1rem;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .auth-form .btn-primary:hover {
            color: var(--accent-hover);
            transform: none;
        }

        .auth-form .alert {
            background: rgba(220,53,69,0.1);
            border: 1px solid rgba(220,53,69,0.2);
            color: #dc3545;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }

        .auth-form p {
            text-align: center;
            margin-top: 1rem;
            color: var(--text-secondary);
        }

        .auth-form p a {
            color: var(--accent-color);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .auth-form p a:hover {
            color: var(--accent-hover);
        }

        .auth-form .required-field::after {
            content: " *";
            color: var(--accent-color);
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main.container {
            flex: 1;
        }

        .footer {
            background: var(--darker-bg);
            border-top: 1px solid var(--border-color);
            padding: 2rem 0;
            margin-top: auto;
        }

        .footer .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .footer-section {
            flex: 1;
            min-width: 200px;
        }

        .footer-section h3 {
            color: var(--text-primary);
            font-size: 1.1rem;
            margin-bottom: 0.8rem;
            font-weight: 600;
        }

        .footer-section p {
            color: var(--text-secondary);
            font-size: 0.9rem;
            line-height: 1.4;
            margin-bottom: 0.5rem;
            max-width: 300px;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 0.5rem;
        }

        .footer-links a {
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .footer-links a:hover {
            color: var(--accent-color);
        }

        .footer-links i {
            font-size: 1.1rem;
            color: var(--accent-color);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            margin-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .social-links a {
            color: var(--text-secondary);
            font-size: 1.5rem;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            color: var(--accent-color);
            transform: translateY(-3px);
        }

        @media (max-width: 768px) {
            .footer-content {
                flex-direction: column;
                text-align: center;
            }

            .footer-section {
                min-width: 100%;
            }

            .social-links {
                justify-content: center;
            }

            .footer-links a {
                justify-content: center;
            }

            .auth-form {
                max-width: 95%;
                padding: 1.5rem;
                margin: 1rem auto;
            }

            .auth-form .form-row {
                grid-template-columns: 1fr;
            }
        }

        .cart-table {
            background: var(--card-bg);
            border-radius: 1rem;
            overflow: hidden;
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .cart-table .table {
            margin-bottom: 0;
            background: var(--card-bg);
        }

        .cart-table th {
            background: var(--darker-bg);
            color: var(--text-primary);
            font-weight: 500;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1rem;
        }

        .cart-table td {
            vertical-align: middle;
            padding: 1.25rem;
            color: var(--text-primary);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: var(--card-bg);
            font-size: 1.1rem;
        }

        .cart-table .product-image {
            width: 120px;
            height: 120px;
            object-fit: contain;
            border-radius: 0.5rem;
            background: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 0.5rem;
        }

        .cart-table .product-name {
            font-weight: 500;
            font-size: 1.15rem;
            color: var(--text-primary);
            text-decoration: none;
            transition: color 0.3s ease;
            display: block;
            margin-top: 0.5rem;
        }

        .cart-quantity {
            margin-top: 28px;
            width: 140px;
            display: flex;
            align-items: center;
            background: var(--card-bg);
            border-radius: 0.5rem;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .cart-quantity button {
            background: none;
            border: none;
            color: var(--text-secondary);
            padding: 0.75rem 1rem;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .cart-quantity input {
            width: 50px;
            text-align: center;
            border: none;
            background: none;
            color: var(--text-primary);
            padding: 0.5rem 0;
            font-size: 1.1rem;
        }

        .cart-summary {
            background: var(--darker-bg);
            border-radius: 1rem;
            padding: 1.5rem;
            position: sticky;
            top: 2rem;
        }

        .cart-summary h5 {
            color: var(--text-primary);
            font-size: 1.4rem;
            margin-bottom: 1.5rem;
        }

        .cart-summary .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            color: var(--text-secondary);
            font-size: 1.1rem;
        }

        .cart-summary .total-price {
            font-size: 1.6rem;
            font-weight: 600;
            color: var(--accent-color);
        }

        .item-price, .item-total {
            font-size: 1.15rem;
            font-weight: 500;
        }

        .cart-summary .btn {
            margin-top: 1rem;
            width: 100%;
            padding: 0.75rem;
        }

        .cart-summary .btn-primary {
            background: var(--accent-color);
            border: none;
            font-weight: 500;
        }

        .cart-summary .btn-primary:hover {
            background: var(--accent-hover);
            transform: translateY(-1px);
        }

        .cart-summary .btn-outline-secondary {
            background: none;
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: var(--text-secondary);
        }

        .cart-summary .btn-outline-secondary:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.3);
            color: var(--text-primary);
        }

        .remove-item-btn {
            background: none;
            border: none;
            color: var(--text-secondary);
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .remove-item-btn:hover {
            color: #dc3545;
            background: rgba(220, 53, 69, 0.1);
        }

        @media (max-width: 768px) {
            .cart-table {
                margin: -1rem;
            }
            
            .cart-table td {
                padding: 0.75rem;
            }

            .cart-table .product-image {
                width: 60px;
                height: 60px;
            }

            .cart-quantity {
                width: 100px;
            }

            .cart-summary {
                margin-top: 2rem;
                position: static;
            }
        }

        .checkout-container {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 2rem;
            margin-top: 2rem;
        }

        .checkout-form {
            background: var(--card-bg);
            border-radius: 1rem;
            padding: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .checkout-form h2 {
            color: var(--text-primary);
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .checkout-form .form-group {
            margin-bottom: 1.5rem;
        }

        .checkout-form label {
            display: block;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }

        .checkout-form .form-control {
            background: var(--darker-bg);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
            padding: 0.75rem 1rem;
            font-size: 1.1rem;
            border-radius: 0.5rem;
            width: 100%;
        }

        .checkout-form .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 2px rgba(79,140,255,0.25);
        }

        .checkout-form .form-select {
            background-color: var(--darker-bg);
            color: var(--text-primary);
            border: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 1.1rem;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            width: 100%;
        }

        .checkout-form .form-select:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 2px rgba(79,140,255,0.25);
        }

        .checkout-summary {
            background: var(--card-bg);
            border-radius: 1rem;
            padding: 1.5rem;
            position: sticky;
            top: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .checkout-summary h2 {
            color: var(--text-primary);
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .checkout-summary table {
            width: 100%;
            margin-bottom: 1.5rem;
        }

        .checkout-summary th,
        .checkout-summary td {
            padding: 0.75rem 0;
            color: var(--text-primary);
            font-size: 1.1rem;
        }

        .checkout-summary tr:not(:last-child) {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .checkout-summary .total-row {
            font-weight: 600;
            font-size: 1.2rem;
            color: var(--accent-color);
        }

        .checkout-summary .product-row td {
            padding: 1rem 0;
        }

        .checkout-summary .product-name {
            color: var(--text-primary);
            font-size: 1.1rem;
        }

        .checkout-summary .product-quantity {
            color: var(--text-secondary);
            font-size: 0.95rem;
        }

        .btn-confirm-order {
            background: linear-gradient(90deg, var(--accent-color) 0%, var(--accent-hover) 100%);
            color: #fff;
            border: none;
            width: 100%;
            padding: 1rem;
            font-size: 1.2rem;
            font-weight: 500;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-confirm-order:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(79,140,255,0.4);
        }

        @media (max-width: 992px) {
            .checkout-container {
                grid-template-columns: 1fr;
            }

            .checkout-summary {
                position: relative;
                top: 0;
                margin-top: 2rem;
            }
        }

        .header-auth-link {
            color: var(--dark-text-secondary);
            background: #23262b;
            border: none !important;
            font-weight: 600;
            font-size: 1.1rem;
            padding: 0.7rem 2.2rem;
            border-radius: 1.5rem;
            transition: color 0.2s, background 0.2s, box-shadow 0.2s;
            box-shadow: 0 2px 16px rgba(79,140,255,0.10);
            margin-left: 0.5rem;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        .header-auth-link:hover, .header-auth-link:focus {
            color: #ffbc00 !important;
            background: #181a20;
            text-decoration: none;
            box-shadow: 0 4px 24px rgba(255,188,0,0.15);
        }
        .nav-link.me-4 {
            margin-right: 2rem !important;
        }

        .badge#cart-count {
            min-width: 1.7em;
            padding: 0.35em 0.6em;
            text-align: center;
            display: inline-block;
            vertical-align: middle;
            line-height: 1.2;
            font-weight: 700;
            letter-spacing: 0.03em;
            margin-left: 0.2em;
        }

        .header-main-link {
            margin-top: 6px !important;
            color: #fff !important;
            transition: color 0.2s;
        }
        .header-main-link:hover, .header-main-link:focus {
            color: #ffbc00 !important;
        }
    </style>
</head>

<body>
    <header class="header-color">
        <nav class="navbar navbar-expand-lg container">
            <a class="navbar-brand" href="/site/home">ТехОк</a>
            <div class="header-buttons">
                <a href="/site/profile/login">Вхід</a>
                <a href="/site/profile/register">Реєстрація</a>
            </div>
        </nav>
    </header>

    <header class="header-color  border-bottom mb-3">
        <nav class="navbar navbar-expand-lg navbar-light container">
            <a class="navbar-brand text-primary fw-bold" href="/site">ТехОк</a>


            <?php if (Customers::isUserLogged() && Core::get()->session->get('customer')['is_admin']): ?>

                <div class="container-fluid">
                    <a class="navbar-brand" href="/site/admin">Адмін-панель</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                </div>

            <?php endif; ?>


            <div class="ms-auto d-flex align-items-center">
                <?php if (Customers::isUserLogged()): ?>
    
                    <?php $customer = Core::get()->session->get('customer'); ?>
                    <ul class="navbar-nav me-3">
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center header-main-link" href="/site/category">Категорії</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center header-main-link" href="/site/cart">
                                <span>Кошик</span>
                                <span class="badge bg-primary ms-2" id="cart-count">0</span>
                            </a>
                        </li>
                    </ul>
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <span class="me-2">Привіт, <?= htmlspecialchars($customer['FirstName']) ?>!</span>
                            <img src="<?= !empty($customer['ProfilePhoto']) ? htmlspecialchars($customer['ProfilePhoto']) : '/site/assets/img/default-avatar.png' ?>" alt="avatar" class="rounded-circle" width="32" height="32">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/site/profile">Мій профіль</a></li>
                            <li><a class="dropdown-item" href="/site/profile/orders">Мої замовлення</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-danger" href="/site/profile/logout">Вийти</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a class="nav-link me-4" href="/site/category">Категорії</a>
                    <a class="header-auth-link" href="/site/profile/login">Вхід</a>
                    <a class="header-auth-link ms-2" href="/site/profile/register">Реєстрація</a>
                <?php endif; ?>
            </div>

            </div>
        </nav>
    </header>


    <?php if (isset($error_message) && !empty($error_message)): ?>
        <div class="alert alert-danger text-center" style="font-size:1.5rem; font-weight:700; margin-top:32px; margin-bottom:32px; letter-spacing:0.5px;">
            <?= $error_message ?>
        </div>
    <?php endif; ?>
    <?php if (isset($success_message) && !empty($success_message)): ?>
        <div class="alert alert-success text-center" style="font-size:1.5rem; font-weight:700; margin-top:32px; margin-bottom:32px; letter-spacing:0.5px;">
            <?= $success_message ?>
        </div>
    <?php endif; ?>
    <main class="container">
        <?= $Content ?? '' ?>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Про нас</h3>
                    <p>ТехОк - ваш надійний партнер у світі техніки. Ми пропонуємо широкий асортимент якісних товарів за найкращими цінами.</p>
                    <div class="social-links">
                        <a href="#" title="Facebook"><i class="fab fa-facebook"></i></a>
                        <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" title="Telegram"><i class="fab fa-telegram"></i></a>
                        <a href="#" title="YouTube"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>

                <div class="footer-section">
                    <h3>Корисні посилання</h3>
                    <ul class="footer-links">
                        <li><a href="/site/home"><i class="fas fa-home"></i> Головна</a></li>
                        <li><a href="/site/category"><i class="fas fa-th-large"></i> Категорії</a></li>
                        <li><a href="/site/cart"><i class="fas fa-shopping-cart"></i> Кошик</a></li>
                        <li><a href="/site/profile"><i class="fas fa-user"></i> Особистий кабінет</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h3>Контакти</h3>
                    <ul class="footer-links">
                        <li><a href="tel:+380123456789"><i class="fas fa-phone"></i> +38 (012) 345-67-89</a></li>
                        <li><a href="#"><i class="fas fa-map-marker-alt"></i> м. Житомир</a></li>
                        <li><a href="#"><i class="fas fa-clock"></i> Пн-Пт: 9:00 - 20:00</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> ТехОк. Всі права захищені.</p>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            updateCartCount();
            document.querySelectorAll('.add-to-cart').forEach(button => {
                button.addEventListener('click', function () {
                    const productId = this.dataset.productId;
                    fetch(`/site/cart/add?product_id=${productId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Додано в кошик!');
                                updateCartCount();
                            } else {
                                alert(data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error adding to cart:', error);
                        });
                });
            });
        });

        function updateCartCount() {
            fetch('/site/cart/count')
                .then(response => response.json())
                .then(data => {
                    const cartCount = document.getElementById('cart-count');
                    if (cartCount) {
                        cartCount.textContent = data.count || 0;
                    }
                })
                .catch(error => {
                    console.error('Error updating cart count:', error);
                });
        }
    </script>
</body>

</html>