<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight print:hidden">
            Invoice
        </h2>
    </x-slot>

    <div id="invoice-content" class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
        <!-- Store Header -->
        <div class="flex justify-between mb-4">
            <div>
                <h1 class="text-2xl font-bold">My Store</h1>
                <p class="text-gray-600">123 Main Street, Phnom Penh, Cambodia</p>
                <p class="text-gray-600">Phone: +855 123 456 789</p>
                <p class="text-gray-600">Email: info@mystore.com</p>
            </div>
            <div class="text-right">
                <p>Invoice #: 00123</p>
                <p>Date: {{ date('d-m-Y') }}</p>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="mb-4">
            <h3 class="font-semibold">Bill To:</h3>
            <p>Name: John Doe</p>
            <p>Email: john@example.com</p>
            <p>Address: 123 Main Street, Phnom Penh, Cambodia</p>
            <p>Payment Method: Credit Card</p>
        </div>

        <!-- Invoice Items -->
        <div id="invoice-items">
            <table class="w-full border">
                <thead>
                    <tr>
                        <th class="border px-2">Product</th>
                        <th class="border px-2">Qty</th>
                        <th class="border px-2">Price</th>
                        <th class="border px-2">Subtotal</th>
                    </tr>
                </thead>
                <tbody id="invoice-body">
                    <!-- JS will fill items here -->
                </tbody>
            </table>
        </div>

        <!-- Total -->
        <div class="text-right font-bold mt-2">
            Total: $<span id="invoice-total">0.00</span>
        </div>

        <!-- Print Button -->
        <div class="mt-4 text-right print:hidden">
            <button onclick="window.print()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
                Print Invoice
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let cart = JSON.parse(sessionStorage.getItem('cart') || '[]');

            const tbody = document.getElementById('invoice-body');
            const totalEl = document.getElementById('invoice-total');

            let total = 0;
            if (cart.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center">No items in cart</td></tr>';
            } else {
                cart.forEach(item => {
                    let subtotal = item.price * item.quantity;
                    total += subtotal;
                    tbody.innerHTML += `
                        <tr>
                            <td class="border px-2">${item.name}</td>
                            <td class="border px-2">${item.quantity}</td>
                            <td class="border px-2">$${item.price.toFixed(2)}</td>
                            <td class="border px-2">$${subtotal.toFixed(2)}</td>
                        </tr>
                    `;
                });
            }

            totalEl.textContent = total.toFixed(2);
        });
    </script>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #invoice-content,
            #invoice-content * {
                visibility: visible;
            }

            #invoice-content {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .print\\:hidden {
                display: none !important;
            }
        }
    </style>
</x-app-layout>
