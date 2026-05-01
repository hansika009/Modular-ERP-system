<?php
require_once 'db.php';
check_auth();

// Handle Delete
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_product_id'])) {
    $del_id = $_POST['delete_product_id'];
    $del_stmt = $conn->prepare("DELETE FROM PRODUCT WHERE Product_ID = ?");
    $del_stmt->bind_param("i", $del_id);
    $del_stmt->execute();
}

$query = "SELECT p.Product_ID, p.Product_Name, p.Stock_Quantity, p.Price, od.Order_ID 
          FROM PRODUCT p 
          LEFT JOIN ORDER_DETAILS od ON p.Product_ID = od.Product_ID";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory - Modular ERP System</title>
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
            <a href="create_order.php" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span class="font-medium">Create Order</span>
            </a>
            <a href="inventory.php" class="flex items-center gap-3 px-4 py-3 bg-indigo-600 text-white rounded-lg transition-colors">
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
            <h2 class="text-2xl font-bold text-slate-800">Inventory Viewer</h2>
        </header>

        <div class="flex-1 overflow-y-auto p-8">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Product Analysis</h3>
                        <p class="text-sm text-slate-500 mt-1">Showing all products (LEFT JOIN to identify Dead Stock)</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <input type="text" id="searchInput" placeholder="Search products..." class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-sm w-64">
                        <span class="w-3 h-3 rounded-full bg-red-500"></span>
                        <span class="text-sm font-medium text-slate-600">Dead Stock</span>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse" id="inventoryTable">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 border-b border-slate-200 text-sm uppercase tracking-wider">
                                <th class="px-6 py-4 font-semibold">Product ID</th>
                                <th class="px-6 py-4 font-semibold">Product Name</th>
                                <th class="px-6 py-4 font-semibold">Stock</th>
                                <th class="px-6 py-4 font-semibold">Price</th>
                                <th class="px-6 py-4 font-semibold">Status</th>
                                <th class="px-6 py-4 font-semibold text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php if ($result && $result->num_rows > 0): ?>
                                <?php while($row = $result->fetch_assoc()): ?>
                                    <?php $is_dead_stock = is_null($row['Order_ID']); ?>
                                    <tr class="transition-colors <?php echo $is_dead_stock ? 'bg-red-50/50 hover:bg-red-50' : 'hover:bg-slate-50'; ?>">
                                        <td class="px-6 py-4 text-slate-800 font-medium"><?php echo htmlspecialchars($row['Product_ID']); ?></td>
                                        <td class="px-6 py-4 text-slate-800 font-medium"><?php echo htmlspecialchars($row['Product_Name']); ?></td>
                                        <td class="px-6 py-4 text-slate-600"><?php echo htmlspecialchars($row['Stock_Quantity']); ?></td>
                                        <td class="px-6 py-4 text-slate-600">$<?php echo number_format($row['Price'], 2); ?></td>
                                        <td class="px-6 py-4">
                                            <?php if ($is_dead_stock): ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">DEAD STOCK</span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">Active</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <form method="POST" action="inventory.php" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                                <input type="hidden" name="delete_product_id" value="<?php echo $row['Product_ID']; ?>">
                                                <button type="submit" class="text-red-500 hover:text-red-700 font-medium text-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-slate-500">No products found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#inventoryTable tbody tr');
            
            rows.forEach(row => {
                let text = row.innerText.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    </script>
</body>
</html>
