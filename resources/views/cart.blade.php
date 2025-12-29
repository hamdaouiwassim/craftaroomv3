<x-main-layout>
    @push('head')
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endpush
    <!-- Cart Header -->
    <section class="relative bg-gradient-to-br from-purple-900 via-indigo-900 to-teal-900 text-white py-16 lg:py-20 overflow-hidden">
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="inline-block mb-4">
                    <span class="px-4 py-2 bg-gradient-to-r from-purple-500/20 to-indigo-500/20 backdrop-blur-sm border border-purple-300/30 rounded-full text-sm font-semibold text-purple-200">
                        🛒 Shopping Cart
                    </span>
                </div>
                <h1 class="text-4xl md:text-6xl font-bold mb-4 bg-gradient-to-r from-white via-purple-100 to-indigo-100 bg-clip-text text-transparent">
                    Your Cart
                </h1>
            </div>
        </div>
    </section>

    <!-- Cart Content -->
    <section class="py-16 bg-gradient-to-b from-white via-purple-50/30 to-indigo-50/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(count($cartItems) > 0)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Cart Items -->
                    <div class="lg:col-span-2 space-y-4">
                        @foreach($cartItems as $item)
                            <div class="bg-white rounded-2xl shadow-lg p-6 border border-purple-100 hover:shadow-xl transition-all duration-300">
                                <div class="flex flex-col sm:flex-row gap-6">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0">
                                        <div class="w-32 h-32 bg-gradient-to-br from-purple-100 via-indigo-100 to-teal-100 rounded-xl overflow-hidden">
                                            @if($item['product']->photos->count() > 0)
                                                <img src="{{ $item['product']->photos->first()->url }}" 
                                                     alt="{{ $item['product']->name }}"
                                                     class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-purple-400">
                                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Product Info -->
                                    <div class="flex-1">
                                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $item['product']->name }}</h3>
                                        @if($item['product']->category)
                                            <p class="text-sm text-indigo-600 mb-2">{{ $item['product']->category->name }}</p>
                                        @endif
                                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ Str::limit($item['product']->description, 100) }}</p>
                                        
                                        <!-- Quantity Controls -->
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <label class="text-sm font-semibold text-gray-700">Quantity:</label>
                                                <div class="flex items-center gap-2">
                                                    <button onclick="updateQuantity({{ $item['product']->id }}, {{ $item['quantity'] - 1 }})" 
                                                            class="w-8 h-8 rounded-lg bg-gradient-to-r from-purple-100 to-indigo-100 text-purple-600 hover:from-purple-500 hover:to-indigo-500 hover:text-white transition-all duration-300 font-bold">
                                                        -
                                                    </button>
                                                    <span id="quantity-{{ $item['product']->id }}" class="w-12 text-center font-bold text-gray-900">{{ $item['quantity'] }}</span>
                                                    <button onclick="updateQuantity({{ $item['product']->id }}, {{ $item['quantity'] + 1 }})" 
                                                            class="w-8 h-8 rounded-lg bg-gradient-to-r from-purple-100 to-indigo-100 text-purple-600 hover:from-purple-500 hover:to-indigo-500 hover:text-white transition-all duration-300 font-bold">
                                                        +
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <div class="text-right">
                                                <p class="text-sm text-gray-500">Subtotal</p>
                                                <p id="subtotal-{{ $item['product']->id }}" class="text-xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">
                                                    {{ $item['product']->currency }}{{ number_format($item['subtotal'], 2) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Remove Button -->
                                    <div class="flex items-start">
                                        <button onclick="removeFromCart({{ $item['product']->id }})" 
                                                class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-all duration-300">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <!-- Clear Cart Button -->
                        <div class="mt-6">
                            <button onclick="clearCart()" class="px-6 py-3 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-xl font-semibold hover:from-red-600 hover:to-pink-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                                Clear Cart
                            </button>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-2xl shadow-lg p-6 border border-purple-100 sticky top-24">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Order Summary</h2>
                            
                            <div class="space-y-4 mb-6">
                                <div class="flex justify-between text-gray-600">
                                    <span>Subtotal ({{ count($cartItems) }} items)</span>
                                    <span id="cart-total" class="font-semibold">{{ $cartItems[0]['product']->currency ?? '$' }}{{ number_format($total, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>Shipping</span>
                                    <span class="font-semibold">Free</span>
                                </div>
                                <div class="border-t border-gray-200 pt-4">
                                    <div class="flex justify-between">
                                        <span class="text-lg font-bold text-gray-900">Total</span>
                                        <span id="cart-grand-total" class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">
                                            {{ $cartItems[0]['product']->currency ?? '$' }}{{ number_format($total, 2) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <button class="w-full py-4 bg-gradient-to-r from-purple-500 via-indigo-500 to-teal-500 text-white rounded-xl font-bold hover:from-purple-600 hover:via-indigo-600 hover:to-teal-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 mb-4">
                                Proceed to Checkout
                            </button>

                            <a href="{{ route('products.index') }}" class="block text-center text-purple-600 hover:text-purple-700 font-semibold">
                                Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty Cart -->
                <div class="text-center py-16">
                    <div class="inline-block p-6 bg-gradient-to-br from-purple-100 to-indigo-100 rounded-full mb-6">
                        <svg class="w-24 h-24 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-4">Your cart is empty</h3>
                    <p class="text-gray-600 mb-8">Looks like you haven't added any items to your cart yet.</p>
                    <a href="{{ route('products.index') }}" class="inline-flex items-center gap-3 bg-gradient-to-r from-purple-500 via-indigo-500 to-teal-500 text-white px-8 py-4 rounded-xl font-bold hover:from-purple-600 hover:via-indigo-600 hover:to-teal-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        Start Shopping
                    </a>
                </div>
            @endif
        </div>
    </section>

    <script>
        async function updateQuantity(productId, quantity) {
            if (quantity <= 0) {
                removeFromCart(productId);
                return;
            }

            try {
                const response = await fetch(`/cart/update/${productId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ quantity: quantity })
                });

                const data = await response.json();
                
                if (data.success) {
                    // Update quantity display
                    document.getElementById(`quantity-${productId}`).textContent = quantity;
                    
                    // Update subtotal
                    document.getElementById(`subtotal-${productId}`).textContent = data.subtotal;
                    
                    // Update cart total
                    document.getElementById('cart-total').textContent = data.total;
                    document.getElementById('cart-grand-total').textContent = data.total;
                    
                    // Update cart count
                    const cartCountElements = document.querySelectorAll('#cart-count, #cart-count-mobile');
                    cartCountElements.forEach(el => {
                        if (el) el.textContent = data.cartCount;
                    });
                } else {
                    showNotification(data.message || 'Failed to update cart', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('An error occurred', 'error');
            }
        }

        async function removeFromCart(productId) {
            if (!confirm('Are you sure you want to remove this item from your cart?')) {
                return;
            }

            try {
                const response = await fetch(`/cart/remove/${productId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();
                
                if (data.success) {
                    // Reload page to update cart display
                    location.reload();
                } else {
                    showNotification(data.message || 'Failed to remove item', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('An error occurred', 'error');
            }
        }

        async function clearCart() {
            if (!confirm('Are you sure you want to clear your entire cart?')) {
                return;
            }

            try {
                const response = await fetch('/cart/clear', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();
                
                if (data.success) {
                    location.reload();
                } else {
                    showNotification(data.message || 'Failed to clear cart', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('An error occurred', 'error');
            }
        }

        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-xl shadow-2xl transform transition-all duration-300 ${
                type === 'success' 
                    ? 'bg-gradient-to-r from-green-500 to-emerald-500 text-white' 
                    : 'bg-gradient-to-r from-red-500 to-pink-500 text-white'
            }`;
            notification.innerHTML = `
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        ${type === 'success' 
                            ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />'
                            : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />'
                        }
                    </svg>
                    <span class="font-semibold">${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }
    </script>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</x-main-layout>

