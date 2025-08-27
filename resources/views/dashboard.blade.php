<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white-200 dark:text-gray-100 leading-tight">
            {{ __('POS System') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Left: Product List -->
                <div class="col-span-2 bg-black dark:bg-gray-800 p-4 rounded-lg shadow">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Products</h3>
                    <div id="product-list" class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <!-- Products will be rendered by JS -->
                    </div>
                </div>

                <!-- Right: Cart -->
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Cart</h3>
                    <div id="cart-items" class="space-y-2">
                        <p class="text-gray-500">No items yet.</p>
                    </div>
                    <div class="mt-4 pt-4">
                        <p class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                            Total: $<span id="cart-total">0.00</span>
                        </p>
                        <button class="mt-3 w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded">
                            Checkout
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ✅ Product Array
        const products = [{
                id: 1,
                name: "ARCTITS7",
                price: 400.00,
                image: "https://i.pinimg.com/736x/92/6a/17/926a17839b9298ac329c68e447ce3675.jpg"
            },
            {
                id: 2,
                name: "Horco",
                price: 450.00,
                image: "https://i.pinimg.com/474x/df/63/77/df63775219f3f45011cc88bb01262e4f.jpg"
            },
            {
                id: 3,
                name: "realme",
                price: 190.00,
                image: "https://i.pinimg.com/736x/72/b8/68/72b868cb73df8fb768a911cd55248b86.jpg"
            },
            {
                id: 4,
                name: "AirPods Pro 2",
                price: 459.00,
                image: "https://i.pinimg.com/736x/ff/ea/d3/ffead3a866e9b51562317288d35ecd8c.jpg"
            },
            {
                id: 5,
                name: "AirPods Max",
                price: 250.00,
                image: "https://i.pinimg.com/1200x/b3/74/0d/b3740d32a2e52db738187be50c422509.jpg"
            }
        ];

        let cart = [];

        // ✅ Render Products
        const productList = document.getElementById('product-list');
        products.forEach(product => {
            const div = document.createElement('div');
            div.className = "border rounded-lg p-3 text-center bg-gray-100 dark:bg-gray-700";
            div.innerHTML = `
            <img src="${product.image}" alt="${product.name}" class="w-full h-32 object-cover rounded">
            <h4 class="mt-2 font-semibold text-white-800 dark:text-gray-200">${product.name}</h4>
            <p class="text-gray-600 dark:text-gray-300">$${product.price.toFixed(2)}</p>
            <button onclick="addToCart(${product.id})"
                class="mt-2 bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">
                Add to Cart
            </button>
        `;
            productList.appendChild(div);
        });

        // ✅ Add to Cart
        function addToCart(id) {
            const product = products.find(p => p.id === id);
            const existing = cart.find(item => item.id === id);
            if (existing) {
                existing.quantity += 1;
            } else {
                cart.push({
                    ...product,
                    quantity: 1
                });
            }
            updateCartUI();
        }

        // ✅ Update Cart UI
        function updateCartUI() {
            const cartContainer = document.getElementById('cart-items');
            const totalElement = document.getElementById('cart-total');
            cartContainer.innerHTML = '';
            let total = 0;

            if (cart.length === 0) {
                cartContainer.innerHTML = '<p class="text-gray-500">No items yet.</p>';
            }

            cart.forEach(item => {
                total += item.price * item.quantity;
                cartContainer.innerHTML += `
            <div class="flex justify-between items-center border-b pb-2 text-white">
                <div class="flex items-center gap-3" >
                    <img src="${item.image}" alt="${item.name}" class="w-16 h-16 object-cover rounded">
                    <span class="font-semibold">${item.name}</span>
                    <div class="flex items-center gap-2 ml-4">
                        <button onclick="decreaseQty(${item.id})" class="bg-gray-400 text-black px-2 rounded">-</button>
                        <span>${item.quantity}</span>
                        <button onclick="increaseQty(${item.id})" class="bg-gray-400 text-black px-2 rounded">+</button>
                    </div>
                </div>
                <div class="text-right">
                    <span>$${(item.price * item.quantity).toFixed(2)}</span>
                    <button onclick="removeFromCart(${item.id})" class="ml-2 text-red-500" style="font-size:24px">x</button>
                </div>
            </div>
        `;
            });

            totalElement.textContent = total.toFixed(2);
        }


        function increaseQty(id) {
            const item = cart.find(i => i.id === id);
            if (item) {
                item.quantity += 1;
                updateCartUI();
            }
        }

        function decreaseQty(id) {
            const item = cart.find(i => i.id === id);
            if (item && item.quantity > 1) {
                item.quantity -= 1;
            } else {
                cart = cart.filter(i => i.id !== id);
            }
            updateCartUI();
        }

        function removeFromCart(id) {
            cart = cart.filter(i => i.id !== id);
            updateCartUI();
        }
    </script>

</x-app-layout>
