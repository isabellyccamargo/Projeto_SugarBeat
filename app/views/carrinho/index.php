<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Ancizar+Serif:ital,wght@0,300..900;1,300..900&family=Bitter:ital,wght@0,100..900;1,100..900&family=Caudex:ital,wght@0,400;0,700;1,400;1,700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Marcellus&family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&family=Noto+Serif:ital,wght@0,100..900;1,100..900&family=Padauk:wght@400;700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <?php
    include '../header/index.php';
    ?>
    <main class="main-content">
        <div class="container">
            <div class="shopping-cart">
                <h1 class="cart-title">Carrinho de Compras</h1>
                <p class="items-count">3 Itens</p>
                <div class="cart-items">
                    <div class="cart-item">
                        <div class="product-details">
                            <img src="https://via.placeholder.com/100" alt="Fifa 19">
                            <div class="product-info">
                                <h3 class="product-name">Fifa 19</h3>
                                <p class="product-category">PS4</p>
                                <a href="#" class="remove-link">Remover</a>
                            </div>
                        </div>
                        <div class="item-controls">
                            <div class="quantity-control">
                                <button class="quantity-btn">-</button>
                                <input type="text" value="2" class="quantity-input">
                                <button class="quantity-btn">+</button>
                            </div>
                            <p class="item-price">£44.00</p>
                            <p class="item-total">£88.00</p>
                        </div>
                    </div>

                    <div class="cart-item">
                        <div class="product-details">
                            <img src="https://via.placeholder.com/100" alt="Glacier White 500GB">
                            <div class="product-info">
                                <h3 class="product-name">Glacier White 500GB</h3>
                                <p class="product-category">PS4</p>
                                <a href="#" class="remove-link">Remover</a>
                            </div>
                        </div>
                        <div class="item-controls">
                            <div class="quantity-control">
                                <button class="quantity-btn">-</button>
                                <input type="text" value="1" class="quantity-input">
                                <button class="quantity-btn">+</button>
                            </div>
                            <p class="item-price">£249.99</p>
                            <p class="item-total">£249.99</p>
                        </div>
                    </div>

                    <div class="cart-item">
                        <div class="product-details">
                            <img src="https://via.placeholder.com/100" alt="Platinum Headset">
                            <div class="product-info">
                                <h3 class="product-name">Platinum Headset</h3>
                                <p class="product-category">PS4</p>
                                <a href="#" class="remove-link">Remover</a>
                            </div>
                        </div>
                        <div class="item-controls">
                            <div class="quantity-control">
                                <button class="quantity-btn">-</button>
                                <input type="text" value="1" class="quantity-input">
                                <button class="quantity-btn">+</button>
                            </div>
                            <p class="item-price">£119.99</p>
                            <p class="item-total">£119.99</p>
                        </div>
                    </div>
                </div>
                <a href="#" class="continue-shopping">
                    <i class="fas fa-arrow-left"></i> Continuar Comprando
                </a>
            </div>

            <div class="order-summary">
                <h2 class="summary-title">Resumo do Pedido</h2>
                <div class="summary-details">
                    <div class="summary-row">
                        <span>ITENS 3</span>
                        <span>£457.98</span>
                    </div>
                    <div class="summary-row">
                        <span>FRETE</span>
                        <select name="shipping" id="shipping-select">
                            <option value="standard">Standard Delivery - £5.00</option>
                            <option value="express">Express Delivery - £15.00</option>
                        </select>
                    </div>
                    <div class="promo-code">
                        <span>CÓDIGO PROMOCIONAL</span>
                        <div class="promo-input-group">
                            <input type="text" placeholder="Digite seu código" class="promo-input">
                            <button class="apply-btn">APLICAR</button>
                        </div>
                    </div>
                    <div class="summary-row total-row">
                        <span>TOTAL</span>
                        <span>£462.98</span>
                    </div>
                    <button class="checkout-btn">Finalizar Compra</button>
                </div>
            </div>
        </div>
    </main>
    <?php
    include '../footer/index.php';
    ?>
</body>


</html>