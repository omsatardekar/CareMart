<?php
include "includes/head.php";
?>

<body>
    <div class="site-wrap">
        <?php
        include "includes/header.php";
        $data = get_user($_SESSION['user_id']);
        ?>

        <div class="bg-light py-3">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 mb-0">
                        <a href="index.php">Home</a> <span class="mx-2 mb-0">/</span>
                        <strong class="text-black">Checkout</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="site-section">
            <div class="container">
                <div class="row">
                    <!-- Delivery Details -->
                    <div class="col-md-6">
                        <h2 class="h3 mb-3 text-black">Delivery Details</h2>
                        <div class="p-3 p-lg-5 border">
                            <table class="table site-block-order-table mb-5">
                                <thead>
                                    <tr>
                                        <th>Customer Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>First Name</td>
                                        <td><?php echo $data[0]['user_fname']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Last Name</td>
                                        <td><?php echo $data[0]['user_lname']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Email</td>
                                        <td><?php echo $data[0]['email']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Address</td>
                                        <td><?php echo $data[0]['user_address']; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="col-md-6">
                        <h2 class="h3 mb-3 text-black">Your Order</h2>
                        <div class="p-3 p-lg-5 border">
                            <table class="table site-block-order-table mb-5">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($_SESSION['cart'])) {
                                        $cart_data = get_cart();
                                        foreach ($cart_data as $index => $item) {
                                    ?>
                                    <tr>
                                        <td><?php echo $item[0]['item_title']; ?>
                                            <strong class="mx-2">x</strong><?php echo $_SESSION['cart'][$index]['quantity']; ?>
                                        </td>
                                        <td>₹<?php echo ($item[0]['item_price'] * $_SESSION['cart'][$index]['quantity']); ?>
                                        </td>
                                    </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <td class="text-black font-weight-bold"><strong>Cart Subtotal</strong></td>
                                        <td class="text-black">₹<?php echo total_price($cart_data); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-black font-weight-bold"><strong>Delivery Fees</strong></td>
                                        <td class="text-black">₹<?php echo delivery_fees($cart_data); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-black font-weight-bold"><strong>Order Total</strong></td>
                                        <td class="text-black font-weight-bold">
                                            <strong>₹<?php echo delivery_fees($cart_data) + total_price($cart_data); ?></strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- Razorpay Payment Button -->
                            <button id="rzp-button" class="btn btn-primary btn-lg btn-block">Pay Now</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Razorpay Script -->
        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const options = {
                    key: "rzp_test_5PqllnPIYLwNHf", // Replace with your Razorpay API key
                    amount: "<?php echo (delivery_fees($cart_data) + total_price($cart_data)) * 100; ?>", // Amount in paisa
                    currency: "INR",
                    name: "CareMart",
                    description: "Order Payment",
                    image: "/images/logo.png", // Relative or absolute path to logo
                    prefill: {
                        name: "<?php echo $data[0]['user_fname'] . ' ' . $data[0]['user_lname']; ?>",
                        email: "<?php echo $data[0]['email']; ?>",
                        contact: "<?php echo $data[0]['contact'] ?? ''; ?>" // Ensure a valid contact value
                    },
                    theme: {
                        color: "#528FF0"
                    },
                    handler: function (response) {
                        // Handle successful payment
                        window.location.href = "thankyou.php?order=done" + response.razorpay_payment_id;
                    },
                    modal: {
                        ondismiss: function () {
                            alert("Payment popup closed. Please try again.");
                        }
                    }
                };

                const rzp = new Razorpay(options);

                document.getElementById("rzp-button").onclick = function (e) {
                    e.preventDefault(); // Prevent default button behavior
                    rzp.open(); // Open Razorpay payment gateway
                };
            });
        </script>
    </div>
    <?php include "includes/footer.php"; ?>
</body>

</html>
