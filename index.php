<?php
require_once 'db.php';
check_auth();

$query = "SELECT c.Customer_ID, c.Customer_Name, o.Order_ID, o.Order_Date 
          FROM ORDERS o 
          RIGHT JOIN CUSTOMER c ON o.Customer_ID = c.Customer_ID";
$result = $conn->query($query);

// Chart Data
$sales_query = $conn->query("SELECT p.Product_Name, SUM(od.Quantity) as Total_Sold FROM ORDER_DETAILS od JOIN PRODUCT p ON od.Product_ID = p.Product_ID GROUP BY p.Product_ID ORDER BY Total_Sold DESC LIMIT 5");
$product_labels = [];
$product_data = [];
if($sales_query) {
    while($row = $sales_query->fetch_assoc()) {
        $product_labels[] = $row['Product_Name'];
        $product_data[] = $row['Total_Sold'];
    }
}

$dates_query = $conn->query("SELECT Order_Date, COUNT(Order_ID) as Daily_Orders FROM ORDERS GROUP BY Order_Date ORDER BY Order_Date ASC LIMIT 7");
$date_labels = [];
$date_data = [];
if($dates_query) {
    while($row = $dates_query->fetch_assoc()) {
        $date_labels[] = $row['Order_Date'];
        $date_data[] = $row['Daily_Orders'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Modular ERP System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            <a href="index.php" class="flex items-center gap-3 px-4 py-3 bg-indigo-600 text-white rounded-lg transition-colors">
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
            <h2 class="text-2xl font-bold text-slate-800">Dashboard Overview</h2>
            <div class="flex items-center gap-4">
                <span class="text-sm font-medium text-slate-600">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold border border-indigo-200">
                    <?php echo strtoupper(substr($_SESSION['username'], 0, 2)); ?>
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Total Customers</p>
                        <h3 class="text-3xl font-bold text-slate-800">
                            <?php
                            $cust_count_query = "SELECT COUNT(*) as count FROM CUSTOMER";
                            $cust_count_result = $conn->query($cust_count_query);
                            echo $cust_count_result ? $cust_count_result->fetch_assoc()['count'] : 0;
                            ?>
                        </h3>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Total Orders</p>
                        <h3 class="text-3xl font-bold text-slate-800">
                            <?php
                            $ord_count_query = "SELECT COUNT(*) as count FROM ORDERS";
                            $ord_count_result = $conn->query($ord_count_query);
                            echo $ord_count_result ? $ord_count_result->fetch_assoc()['count'] : 0;
                            ?>
                        </h3>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Total Products</p>
                        <h3 class="text-3xl font-bold text-slate-800">
                            <?php
                            $prod_count_query = "SELECT COUNT(*) as count FROM PRODUCT";
                            $prod_count_result = $conn->query($prod_count_query);
                            echo $prod_count_result ? $prod_count_result->fetch_assoc()['count'] : 0;
                            ?>
                        </h3>
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Top Selling Products</h3>
                    <div class="relative h-64 w-full">
                        <canvas id="productsChart"></canvas>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Recent Orders Activity</h3>
                    <div class="relative h-64 w-full">
                        <canvas id="ordersChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Customer List -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Customer Engagement Report</h3>
                        <p class="text-sm text-slate-500 mt-1">Showing all customers and their orders (RIGHT JOIN)</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 border-b border-slate-200 text-sm uppercase tracking-wider">
                                <th class="px-6 py-4 font-semibold">Customer ID</th>
                                <th class="px-6 py-4 font-semibold">Customer Name</th>
                                <th class="px-6 py-4 font-semibold">Order ID</th>
                                <th class="px-6 py-4 font-semibold">Order Date</th>
                                <th class="px-6 py-4 font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php if ($result && $result->num_rows > 0): ?>
                                <?php while($row = $result->fetch_assoc()): ?>
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-6 py-4 text-slate-800 font-medium"><?php echo htmlspecialchars($row['Customer_ID']); ?></td>
                                        <td class="px-6 py-4 text-slate-600"><?php echo htmlspecialchars($row['Customer_Name']); ?></td>
                                        <td class="px-6 py-4 text-slate-600">
                                            <?php echo $row['Order_ID'] ? htmlspecialchars($row['Order_ID']) : '<span class="text-slate-400 italic">None</span>'; ?>
                                        </td>
                                        <td class="px-6 py-4 text-slate-600">
                                            <?php echo $row['Order_Date'] ? htmlspecialchars($row['Order_Date']) : '<span class="text-slate-400 italic">None</span>'; ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?php if ($row['Order_ID']): ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">Active</span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800 border border-slate-200">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-slate-500">No data found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <script>
        const ctxProducts = document.getElementById('productsChart').getContext('2d');
        new Chart(ctxProducts, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($product_labels); ?>,
                datasets: [{
                    label: 'Units Sold',
                    data: <?php echo json_encode($product_data); ?>,
                    backgroundColor: 'rgba(79, 70, 229, 0.8)',
                    borderRadius: 4
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
        });

        const ctxOrders = document.getElementById('ordersChart').getContext('2d');
        new Chart(ctxOrders, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($date_labels); ?>,
                datasets: [{
                    label: 'Daily Orders',
                    data: <?php echo json_encode($date_data); ?>,
                    borderColor: 'rgba(16, 185, 129, 1)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
        });
    </script>
</body>
</html>
