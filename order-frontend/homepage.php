<?php
// Start the session
session_start();

// Check if the user is logged in, and if not, redirect to login page
// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php");
//     exit();
// }

// Database connection
include_once 'api/config/database.php';

// Instantiate the database object
$database = new Database();
$db = $database->getConnection();

// Fetch menu items from the database
$query = "SELECT * FROM foods";
$stmt = $db->prepare($query);
$stmt->execute();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Menu</title>
    <?php include 'includes/css.php'; ?>
    <style>
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .menu-card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 15px;
            transition: transform 0.3s;
        }

        .menu-card:hover {
            transform: translateY(-5px);
        }

        .menu-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }

        .menu-card h5 {
            margin: 15px 0 10px;
            font-size: 18px;
            color: #333;
        }

        .menu-card p {
            font-size: 14px;
            color: #777;
            text-align: center;
        }

        .menu-card .price {
            font-size: 16px;
            font-weight: bold;
            margin: 10px 0;
            color: #333;
        }

        .menu-card .quantity {
            margin: 10px 0;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .menu-card .quantity input {
            width: 60%;
            padding: 5px;
        }

        .menu-card .quantity label {
            font-size: 14px;
            font-weight: bold;
            color: #555;
        }

        .cart-summary {
            margin-top: 30px;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .cart-summary h3 {
            margin-bottom: 15px;
        }

        .cart-summary ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .cart-summary .total {
            font-size: 18px;
            font-weight: bold;
            margin-top: 15px;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <?php include 'includes/navbar.php'; ?>
    <div class="content-wrapper">
        <section class="content">
            <div class="container">
                <h2 class="text-center mb-5">Our Delicious Menu</h2>

                <div class="menu-grid">
                    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <div class="menu-card">
                            <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                            <h5><?php echo htmlspecialchars($row['name']); ?></h5>
                            <p><?php echo htmlspecialchars($row['description']); ?></p>
                            <div class="price">$<?php echo number_format($row['price'], 2); ?></div>
                            <div class="quantity">
                                <label for="quantity_<?php echo $row['id']; ?>">Qty:</label>
                                <input type="number" id="quantity_<?php echo $row['id']; ?>" class="quantity-input" min="1" value="1">
                            </div>
                            <button type="button" class="btn btn-success btn-add-to-cart" data-id="<?php echo $row['id']; ?>" data-name="<?php echo htmlspecialchars($row['name']); ?>" data-price="<?php echo $row['price']; ?>">Add to Cart</button>
                        </div>
                    <?php endwhile; ?>
                </div>

                <div class="cart-summary">
                    <h3>Cart Summary</h3>
                    <ul class="cart-items"></ul>
                    <div class="total">Total: $0.00</div>
                    <form method="POST" action="place_order.php">
                        <input type="hidden" name="cart_data" id="cart_data">
                        <button type="submit" class="btn btn-primary mt-4">Place Order</button>
                    </form>
                </div>
            </div>
        </section>
    </div>
    <?php include 'includes/footer.php'; ?>
</div>

<?php include 'includes/js.php'; ?>
<script>
    const cartItems = [];
    const cartItemsList = document.querySelector('.cart-items');
    const totalDisplay = document.querySelector('.cart-summary .total');
    const cartDataInput = document.getElementById('cart_data');

    document.querySelectorAll('.btn-add-to-cart').forEach(button => {
        button.addEventListener('click', function () {
            const itemId = this.dataset.id;
            const itemName = this.dataset.name;
            const itemPrice = parseFloat(this.dataset.price);
            const quantityInput = document.querySelector(`#quantity_${itemId}`);
            const quantity = parseInt(quantityInput.value) || 1;

            if (quantity <= 0) {
                alert("Quantity must be greater than 0.");
                return;
            }

            // Check if the item already exists in the cart
            const existingItem = cartItems.find(item => item.id === itemId);
            if (existingItem) {
                existingItem.quantity += quantity;
            } else {
                cartItems.push({ id: itemId, name: itemName, price: itemPrice, quantity });
            }

            updateCartSummary();
        });
    });

    function updateCartSummary() {
        let total = 0;
        cartItemsList.innerHTML = '';

        cartItems.forEach(item => {
            const subtotal = item.price * item.quantity;
            total += subtotal;

            const listItem = document.createElement('li');
            listItem.textContent = `${item.name} - ${item.quantity} x $${item.price.toFixed(2)} = $${subtotal.toFixed(2)}`;
            cartItemsList.appendChild(listItem);
        });

        totalDisplay.textContent = `Total: $${total.toFixed(2)}`;
        cartDataInput.value = JSON.stringify(cartItems);
    }
</script>
</body>
</html>
