<?php
require_once 'db.php';
check_auth();

if (!isset($_GET['order_id'])) {
    die("Invalid Order ID.");
}

$order_id = $_GET['order_id'];

$order_query = $conn->prepare("SELECT o.Order_Date, c.Customer_Name, cp.Phone_Number
                               FROM ORDERS o
                               JOIN CUSTOMER c ON o.Customer_ID = c.Customer_ID
                               LEFT JOIN CUSTOMER_PHONE_NO cp ON c.Customer_ID = cp.Customer_ID
                               WHERE o.Order_ID = ? LIMIT 1");
$order_query->bind_param("i", $order_id);
$order_query->execute();
$order_result = $order_query->get_result();

if ($order_result->num_rows == 0) {
    die("Order not found.");
}
$order_data = $order_result->fetch_assoc();

$items_query = $conn->prepare("SELECT p.Product_Name, p.Price, od.Quantity, (p.Price * od.Quantity) as Line_Total
                               FROM ORDER_DETAILS od
                               JOIN PRODUCT p ON od.Product_ID = p.Product_ID
                               WHERE od.Order_ID = ?");
$items_query->bind_param("i", $order_id);
$items_query->execute();
$items_result = $items_query->get_result();
$total_amount = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?php echo htmlspecialchars($order_id); ?> - Modular ERP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f1f5f9; }
        @media print {
            body { background-color: white; }
            .no-print { display: none !important; }
            .print-shadow-none { box-shadow: none !important; border: none !important; }
        }
    </style>
</head>
<body class="p-8 flex justify-center">

    <div class="max-w-4xl w-full">
        <div class="mb-6 flex justify-end no-print">
            <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print Invoice
            </button>
        </div>

        <div class="bg-white p-10 rounded-2xl shadow-xl print-shadow-none border border-slate-200">
            <div class="flex justify-between items-start border-b border-slate-200 pb-8 mb-8">
                <div>
                    <h1 class="text-4xl font-bold text-slate-900 tracking-tight">INVOICE</h1>
                    <p class="text-slate-500 mt-2 text-sm uppercase tracking-widest font-semibold">Modular ERP System</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Invoice Number</p>
                    <p class="text-2xl font-bold text-slate-800">#<?php echo htmlspecialchars($order_id); ?></p>
                    <p class="text-sm font-medium text-slate-500 uppercase tracking-wider mt-4">Date of Issue</p>
                    <p class="text-lg font-semibold text-slate-800"><?php echo htmlspecialchars($order_data['Order_Date']); ?></p>
                </div>
            </div>

            <div class="mb-10">
                <p class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-3">Billed To:</p>
                <h3 class="text-xl font-bold text-slate-800"><?php echo htmlspecialchars($order_data['Customer_Name']); ?></h3>
                <p class="text-slate-600 mt-1"><?php echo htmlspecialchars($order_data['Phone_Number'] ?? 'No phone provided'); ?></p>
            </div>

            <table class="w-full text-left mb-10">
                <thead>
                    <tr class="border-b-2 border-slate-800 text-sm uppercase tracking-wider text-slate-800">
                        <th class="py-3 font-bold">Item Description</th>
                        <th class="py-3 font-bold text-center">Qty</th>
                        <th class="py-3 font-bold text-right">Unit Price</th>
                        <th class="py-3 font-bold text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php while($item = $items_result->fetch_assoc()): ?>
                        <?php $total_amount += $item['Line_Total']; ?>
                        <tr>
                            <td class="py-4 text-slate-800 font-medium"><?php echo htmlspecialchars($item['Product_Name']); ?></td>
                            <td class="py-4 text-slate-600 text-center"><?php echo htmlspecialchars($item['Quantity']); ?></td>
                            <td class="py-4 text-slate-600 text-right">$<?php echo number_format($item['Price'], 2); ?></td>
                            <td class="py-4 text-slate-800 font-bold text-right">$<?php echo number_format($item['Line_Total'], 2); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="flex justify-end border-t-2 border-slate-800 pt-6">
                <div class="w-1/2">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-slate-500 font-medium">Subtotal</span>
                        <span class="text-slate-800 font-semibold">$<?php echo number_format($total_amount, 2); ?></span>
                    </div>
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-slate-500 font-medium">Tax (0%)</span>
                        <span class="text-slate-800 font-semibold">$0.00</span>
                    </div>
                    <div class="flex justify-between items-center border-t border-slate-200 pt-4">
                        <span class="text-xl font-bold text-slate-800 uppercase">Total Due</span>
                        <span class="text-3xl font-black text-indigo-600">$<?php echo number_format($total_amount, 2); ?></span>
                    </div>
                </div>
            </div>

            <div class="mt-16 pt-8 border-t border-slate-200 text-center">
                <p class="text-slate-500 font-medium">Thank you for your business!</p>
                <p class="text-slate-400 text-sm mt-1">For any inquiries, please contact support@modularerp.com</p>
            </div>
        </div>
    </div>

</body>
</html>
