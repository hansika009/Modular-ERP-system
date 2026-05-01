<?php
require_once 'db.php';
check_auth();

$message = '';
$status = '';

$customers = $conn->query("SELECT Customer_ID, Customer_Name FROM CUSTOMER");
$products = $conn->query("SELECT Product_ID, Product_Name, Stock_Quantity, Price FROM PRODUCT WHERE Stock_Quantity > 0");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'];
    $customer_id = $_POST['customer_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $order_date = date("Y-m-d");

    if (!empty($order_id) && !empty($customer_id) && !empty($product_id) && !empty($quantity) && $quantity > 0) {
        $conn->begin_transaction();
        try {
            $stmt_check = $conn->prepare("SELECT Order_ID FROM ORDERS WHERE Order_ID = ?");
            $stmt_check->bind_param("i", $order_id);
            $stmt_check->execute();
            if ($stmt_check->get_result()->num_rows == 0) {
                $stmt_order = $conn->prepare("INSERT INTO ORDERS (Order_ID, Order_Date, Customer_ID) VALUES (?, ?, ?)");
                $stmt_order->bind_param("isi", $order_id, $order_date, $customer_id);
                $stmt_order->execute();
            }

            $stmt_details = $conn->prepare("INSERT INTO ORDER_DETAILS (Order_ID, Product_ID, Quantity) VALUES (?, ?, ?)");
            $stmt_details->bind_param("iii", $order_id, $product_id, $quantity);
            $stmt_details->execute();

            $stmt_stock = $conn->prepare("UPDATE PRODUCT SET Stock_Quantity = Stock_Quantity - ? WHERE Product_ID = ?");
            $stmt_stock->bind_param("ii", $quantity, $product_id);
            $stmt_stock->execute();

            $conn->commit();
            $message = "Order created and stock updated successfully.";
            $status = "success";
        } catch (Exception $e) {
            $conn->rollback();
            $message = "Error creating order: " . $e->getMessage();
            $status = "error";
        }
    } else {
        $message = "Please fill in all fields correctly.";
        $status = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Order - Modular ERP System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased flex h-screen overflow-hidden">
    <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col shadow-2xl h-full hidden md:flex">
        <div class="h-16 flex items-center px-6 border-b border-slate-800">
            <h1 class="text-xl font-bold text-white tracking-wider uppercase">Modular ERP</h1>
        </div>
        <nav class="flex-1 py-6 space-y-2 px-3 overflow-y-auto">
            <a href="index.php" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                <span class="font-medium">Dashboard</span>
            </a>
            <a href="register.php" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                <span class="font-medium">Register Customer</span>
            </a>
            <a href="add_product.php" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                <span class="font-medium">Add Product</span>
            </a>
            <a href="create_order.php" class="flex items-center gap-3 px-4 py-3 bg-indigo-600 text-white rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span class="font-medium">Create Order</span>
            </a>
            <a href="inventory.php" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                <span class="font-medium">Inventory</span>
            </a>
            <a href="billing.php" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span class="font-medium">Billing & Invoices</span>
            </a>
        </nav>
        <div class="p-4 border-t border-slate-800">
            <a href="logout.php" class="flex items-center gap-3 px-4 py-2 hover:bg-red-900/50 text-red-400 hover:text-red-300 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                <span class="font-medium">Logout</span>
            </a>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-full bg-slate-50 overflow-hidden">
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8 shadow-sm">
            <h2 class="text-2xl font-bold text-slate-800">Order Processing</h2>
        </header>

        <div class="flex-1 overflow-y-auto p-8 flex items-start justify-center">
            <div class="w-full max-w-lg bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden mt-10">
                <div class="bg-indigo-600 px-8 py-6 text-white text-center">
                    <h3 class="text-2xl font-bold">New Order</h3>
                    <p class="text-indigo-100 mt-1 opacity-90">Process a customer purchase.</p>
                </div>
                
                <div class="p-8">
                    <?php if ($message): ?>
                        <div class="mb-6 p-4 rounded-lg <?php echo $status === 'success' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200'; ?> flex items-center shadow-sm">
                            <?php if ($status === 'success'): ?>
                                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <?php else: ?>
                                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <?php endif; ?>
                            <span class="font-medium text-sm"><?php echo $message; ?></span>
                        </div>
                    <?php endif; ?>

                    <form action="create_order.php" method="POST" class="space-y-6">
                        <div>
                            <label for="order_id" class="block text-sm font-semibold text-slate-700 mb-2">Order ID</label>
                            <input type="number" id="order_id" name="order_id" required class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow outline-none text-slate-800 bg-slate-50 focus:bg-white" placeholder="e.g. 10001">
                        </div>
                        
                        <div>
                            <label for="customer_id" class="block text-sm font-semibold text-slate-700 mb-2">Select Customer</label>
                            <select id="customer_id" name="customer_id" required class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow outline-none text-slate-800 bg-slate-50 focus:bg-white">
                                <option value="" disabled selected>Choose a customer...</option>
                                <?php
                                if ($customers && $customers->num_rows > 0) {
                                    while($row = $customers->fetch_assoc()) {
                                        echo "<option value='" . $row['Customer_ID'] . "'>" . htmlspecialchars($row['Customer_Name']) . " (ID: " . $row['Customer_ID'] . ")</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div>
                            <label for="product_id" class="block text-sm font-semibold text-slate-700 mb-2">Select Product</label>
                            <select id="product_id" name="product_id" required class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow outline-none text-slate-800 bg-slate-50 focus:bg-white">
                                <option value="" disabled selected>Choose a product...</option>
                                <?php
                                if ($products && $products->num_rows > 0) {
                                    while($row = $products->fetch_assoc()) {
                                        echo "<option value='" . $row['Product_ID'] . "'>" . htmlspecialchars($row['Product_Name']) . " - $" . number_format($row['Price'], 2) . " (" . $row['Stock_Quantity'] . " in stock)</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div>
                            <label for="quantity" class="block text-sm font-semibold text-slate-700 mb-2">Quantity</label>
                            <input type="number" id="quantity" name="quantity" min="1" required class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow outline-none text-slate-800 bg-slate-50 focus:bg-white" placeholder="e.g. 1">
                        </div>

                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg shadow-lg shadow-indigo-200 transition-all transform hover:-translate-y-0.5 mt-4 focus:ring-4 focus:ring-indigo-300 outline-none">
                            Process Order
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
