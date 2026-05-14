@extends('layouts.app')

@section('title', 'Articulos - VeteHub')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold">Articulos</h1>
                <p class="text-gray-600 mt-2">Productos registrados en el sistema</p>
            </div>
            <div class="flex flex-wrap gap-2">
                @if(Route::has('products.create'))
                    <a href="{{ route('products.create') }}" class="bg-white text-blue-700 px-4 py-2 rounded-lg border border-blue-200 hover:bg-blue-50">
                        Agregar articulo
                    </a>
                @endif
                @if(Route::has('products.edit') || Route::has('products.destroy'))
                    <button type="button" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 dark:bg-blue-500/70 dark:hover:bg-blue-500" id="edit-toggle">
                        Editar articulos
                    </button>
                @endif
                @if(Route::has('sales.report'))
                    <a href="{{ route('sales.report') }}" class="bg-white text-blue-700 px-4 py-2 rounded-lg border border-blue-200 hover:bg-blue-50">
                        Reporte de ventas
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold">Buscar articulo</h3>
            <button type="button" class="p-2 text-blue-600 hover:text-blue-700" id="search-toggle" aria-label="Mostrar buscador">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" id="search-toggle-icon">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
        </div>
        <div class="mt-4 hidden transition-all duration-300 ease-out opacity-0 scale-95 pointer-events-none overflow-hidden" id="search-panel" data-collapse-panel data-collapse-height>
            <form method="GET" action="{{ route('sales.inventory') }}" class="flex flex-col gap-4 md:flex-row md:items-end">
                <div class="flex-1">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Buscar articulo</label>
                    <input
                        id="search"
                        name="search"
                        type="text"
                        value="{{ request('search') }}"
                        placeholder="Nombre o SKU"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 dark:bg-blue-500/70 dark:hover:bg-blue-500">
                        Buscar
                    </button>
                    <a href="{{ route('sales.inventory') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">
                        Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold">Productos</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"></th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Precio</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase hidden" data-edit-header>Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($products as $product)
                        <tr class="cursor-pointer hover:bg-blue-100 dark:hover:bg-blue-900/30" data-cart-item
                            data-id="{{ $product->id }}"
                            data-name="{{ $product->name }}"
                            data-price="{{ $product->price }}"
                            data-stock="{{ $product->stock }}"
                            data-active="{{ $product->is_active ? '1' : '0' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($product->image_path)
                                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="Imagen del articulo" class="h-10 w-10 rounded object-cover border border-gray-200">
                                @else
                                    <div class="h-10 w-10 rounded bg-gray-200 flex items-center justify-center text-xs text-gray-500">
                                        N/A
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $product->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $product->sku ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">${{ number_format((float) $product->price, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $product->stock }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if(!$product->is_active || $product->stock <= 0)
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">No disponible</span>
                                @elseif($product->is_active)
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Activo</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Inactivo</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right hidden" data-edit-cell>
                                <div class="inline-flex items-center gap-2" data-edit-actions>
                                    @if(Route::has('products.edit'))
                                        <a href="{{ route('products.edit', $product) }}" class="px-3 py-1 text-xs rounded border border-gray-200 text-gray-600 hover:text-blue-600 hover:border-blue-200" title="Editar articulo" aria-label="Editar articulo">
                                            Editar
                                        </a>
                                    @endif
                                    @if(Route::has('products.destroy'))
                                        <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Seguro que deseas eliminar este articulo?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1 text-xs rounded border border-gray-200 text-gray-600 hover:text-red-600 hover:border-red-200" title="Eliminar articulo" aria-label="Eliminar articulo">
                                                Eliminar
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-6 text-center text-sm text-gray-500">
                                No hay productos para mostrar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="mt-4">
                {{ $products->links('pagination.products') }}
            </div>
        @endif
    </div>
</div>

<div class="fixed bottom-6 right-6 z-50">
    <button type="button" class="relative bg-blue-600 text-white h-14 w-14 rounded-full shadow-lg hover:bg-blue-700 dark:bg-blue-500/70 dark:hover:bg-blue-500" id="cart-toggle" aria-label="Abrir carrito">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9h12l-2-9M10 21a1 1 0 11-2 0 1 1 0 012 0zm8 0a1 1 0 11-2 0 1 1 0 012 0z" />
        </svg>
        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs h-6 min-w-6 px-1 rounded-full flex items-center justify-center" id="cart-fab-count">0</span>
    </button>

    <div class="absolute bottom-16 right-0 w-80 sm:w-96 bg-gray-50 border border-gray-200 rounded-lg shadow-xl p-4 hidden transition-all duration-200 ease-out opacity-0 scale-95 pointer-events-none" id="cart-panel" data-collapse-panel>
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold">Carrito</h3>
            <span class="text-xs text-gray-500" id="cart-count">0 articulos</span>
        </div>
        <div class="mt-4 space-y-3 max-h-80 overflow-y-auto" id="cart-items">
            <p class="text-sm text-gray-500" id="cart-empty">Aun no has agregado articulos.</p>
        </div>
        <div class="mt-6 border-t border-gray-200 pt-4">
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-600">Subtotal</span>
                <span class="font-semibold" id="cart-subtotal">$0.00</span>
            </div>
            <div class="mt-4 flex flex-col gap-2">
                <button type="button" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 hidden" id="cart-checkout">
                    Proceder al pago
                </button>
                <button type="button" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300" id="cart-clear">
                    Limpiar carrito
                </button>
            </div>
        </div>
    </div>
</div>

<div class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50" id="checkout-backdrop" aria-hidden="true">
    <div class="bg-white text-gray-900 w-full max-w-2xl mx-4 rounded-lg shadow-xl">
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
            <h3 class="text-lg font-semibold text-gray-900">Resumen de venta</h3>
            <button type="button" class="text-gray-500 hover:text-gray-700" id="checkout-close" aria-label="Cerrar resumen">
                ✕
            </button>
        </div>
        <div class="px-6 py-4">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-gray-900">
                    <thead class="text-gray-700">
                        <tr>
                            <th class="text-left py-2">Articulo</th>
                            <th class="text-right py-2">Cantidad</th>
                            <th class="text-right py-2">Precio unitario</th>
                            <th class="text-right py-2">Total</th>
                        </tr>
                    </thead>
                    <tbody id="checkout-items"></tbody>
                </table>
            </div>

            <div class="mt-4 flex items-center justify-between text-sm text-gray-900">
                <span class="text-gray-900">Total a pagar</span>
                <span class="text-lg font-semibold" id="checkout-total">$0.00</span>
            </div>

            <div class="mt-4">
                <label for="payment-method" class="block text-sm font-medium text-gray-900 mb-2">
                    Forma de pago
                </label>
                <select id="payment-method" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white text-gray-900">
                    <option value="cash">Efectivo</option>
                    <option value="debit_card">Tarjeta de debito</option>
                    <option value="credit_card">Tarjeta de credito</option>
                    <option value="spei">Transferencia SPEI</option>
                </select>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row sm:justify-end gap-2 border-t border-gray-200 px-6 py-4">
            <button type="button" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300" id="checkout-cancel">
                Cancelar
            </button>
            <button type="button" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700" id="checkout-confirm">
                Confirmar venta
            </button>
        </div>
    </div>
</div>

<script>
    const cartState = new Map();
    const currency = (value) => `$${Number(value).toFixed(2)}`;
    const cartStorageKey = 'salesCart';

    const cartItems = document.getElementById('cart-items');
    const cartEmpty = document.getElementById('cart-empty');
    const cartCount = document.getElementById('cart-count');
    const cartSubtotal = document.getElementById('cart-subtotal');
    const cartClear = document.getElementById('cart-clear');
    const cartCheckout = document.getElementById('cart-checkout');
    const cartToggle = document.getElementById('cart-toggle');
    const cartPanel = document.getElementById('cart-panel');
    const cartFabCount = document.getElementById('cart-fab-count');
    const searchToggle = document.getElementById('search-toggle');
    const searchPanel = document.getElementById('search-panel');
    const searchToggleIcon = document.getElementById('search-toggle-icon');

    const animatePanel = (panel, show) => {
        if (!panel) {
            return;
        }

        const useHeight = panel.hasAttribute('data-collapse-height');

        if (show) {
            panel.classList.remove('hidden');
            if (useHeight) {
                panel.style.maxHeight = '0px';
            }
            requestAnimationFrame(() => {
                if (useHeight) {
                    panel.style.maxHeight = `${panel.scrollHeight}px`;
                }
                panel.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
            });
            return;
        }

        if (useHeight) {
            panel.style.maxHeight = `${panel.scrollHeight}px`;
        }
        panel.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
        if (useHeight) {
            requestAnimationFrame(() => {
                panel.style.maxHeight = '0px';
            });
        }
        const onEnd = () => {
            panel.classList.add('hidden');
            if (useHeight) {
                panel.style.maxHeight = '';
            }
            panel.removeEventListener('transitionend', onEnd);
        };
        panel.addEventListener('transitionend', onEnd);
    };
    const productRows = Array.from(document.querySelectorAll('[data-cart-item]'));
    const productIndex = new Map(
        productRows.map((row) => [
            row.dataset.id,
            {
                id: row.dataset.id,
                name: row.dataset.name,
                price: Number(row.dataset.price),
                stock: Number(row.dataset.stock),
                isActive: row.dataset.active === '1',
            },
        ])
    );

    const checkoutBackdrop = document.getElementById('checkout-backdrop');
    const checkoutItems = document.getElementById('checkout-items');
    const checkoutTotal = document.getElementById('checkout-total');
    const checkoutClose = document.getElementById('checkout-close');
    const checkoutCancel = document.getElementById('checkout-cancel');
    const checkoutConfirm = document.getElementById('checkout-confirm');
    const paymentMethod = document.getElementById('payment-method');

    const saveCart = () => {
        const payload = Array.from(cartState.values()).map((item) => ({
            id: item.id,
            quantity: item.quantity,
        }));
        try {
            localStorage.setItem(cartStorageKey, JSON.stringify(payload));
        } catch (error) {
            // Ignore storage failures (private mode, quotas, etc.).
        }
    };

    const loadCart = () => {
        try {
            const stored = JSON.parse(localStorage.getItem(cartStorageKey) || '[]');
            if (!Array.isArray(stored)) {
                return;
            }
            stored.forEach((entry) => {
                const product = productIndex.get(String(entry.id));
                if (!product || !product.isActive || product.stock <= 0) {
                    return;
                }
                const quantity = Math.max(1, Math.floor(Number(entry.quantity) || 0));
                if (quantity <= 0) {
                    return;
                }
                cartState.set(product.id, {
                    ...product,
                    quantity: Math.min(quantity, product.stock),
                });
            });
        } catch (error) {
            // Ignore storage failures or corrupted data.
        }
    };

    const updateCartTotals = () => {
        let totalItems = 0;
        let totalAmount = 0;

        cartState.forEach((item) => {
            totalItems += item.quantity;
            totalAmount += item.quantity * item.price;
        });

        cartCount.textContent = `${totalItems} articulo${totalItems === 1 ? '' : 's'}`;
        cartSubtotal.textContent = currency(totalAmount);
        cartFabCount.textContent = totalItems;
        cartCheckout.classList.toggle('hidden', totalItems === 0);
    };

    const renderCart = () => {
        cartItems.innerHTML = '';

        if (cartState.size === 0) {
            cartEmpty.classList.remove('hidden');
            cartItems.appendChild(cartEmpty);
            updateCartTotals();
            saveCart();
            return;
        }

        cartEmpty.classList.add('hidden');
        cartItems.appendChild(cartEmpty);

        cartState.forEach((item) => {
            const row = document.createElement('div');
            row.className = 'bg-white border border-gray-200 rounded-lg p-3';
            row.innerHTML = `
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-semibold">${item.name}</div>
                        <div class="text-xs text-gray-500">${currency(item.price)} c/u</div>
                    </div>
                    <div class="text-sm font-semibold">${currency(item.price * item.quantity)}</div>
                </div>
                <div class="mt-3 flex items-center gap-2">
                    <button type="button" class="px-2 py-1 text-xs rounded border border-gray-200 text-gray-600 hover:text-green-600 hover:border-green-200" data-cart-qty-add data-id="${item.id}">+</button>
                    <input type="number" min="0" class="w-16 px-2 py-1 text-xs border border-gray-200 rounded" value="${item.quantity}" data-cart-qty-input data-id="${item.id}">
                    <button type="button" class="px-2 py-1 text-xs rounded border border-gray-200 text-gray-600 hover:text-orange-600 hover:border-orange-200" data-cart-qty-sub data-id="${item.id}">-</button>
                </div>
            `;
            cartItems.appendChild(row);
        });

        updateCartTotals();
        saveCart();
    };

    const renderCheckout = () => {
        checkoutItems.innerHTML = '';
        let totalAmount = 0;

        cartState.forEach((item) => {
            const lineTotal = item.quantity * item.price;
            totalAmount += lineTotal;
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="py-2">${item.name}</td>
                <td class="py-2 text-right">${item.quantity}</td>
                <td class="py-2 text-right">${currency(item.price)}</td>
                <td class="py-2 text-right">${currency(lineTotal)}</td>
            `;
            checkoutItems.appendChild(row);
        });

        checkoutTotal.textContent = currency(totalAmount);
    };

    const toggleCheckout = (show) => {
        checkoutBackdrop.classList.toggle('hidden', !show);
        checkoutBackdrop.classList.toggle('flex', show);
        if (show) {
            renderCheckout();
        }
    };

    const updateQuantity = (id, delta) => {
        const item = cartState.get(id);
        if (!item) {
            return;
        }

        item.quantity = Math.max(0, item.quantity + delta);
        if (item.quantity === 0) {
            cartState.delete(id);
        }
        renderCart();
    };

    const setQuantity = (id, value) => {
        const item = cartState.get(id);
        if (!item) {
            return;
        }
        const nextValue = Number.isFinite(value) ? Math.max(0, Math.floor(value)) : item.quantity;
        if (nextValue === 0) {
            cartState.delete(id);
        } else {
            item.quantity = nextValue;
        }
        renderCart();
    };

    const addToCart = (data) => {
        if (!data.isActive || data.stock <= 0) {
            return;
        }
        const current = cartState.get(data.id);
        const nextQuantity = current ? current.quantity + 1 : 1;
        const maxQuantity = Number.isFinite(data.stock) ? data.stock : nextQuantity;
        cartState.set(data.id, {
            ...data,
            quantity: Math.min(nextQuantity, maxQuantity),
        });
        renderCart();
    };

    productRows.forEach((row) => {
        row.addEventListener('click', (event) => {
            if (event.target.closest('[data-edit-actions]')) {
                return;
            }
            addToCart({
                id: row.dataset.id,
                name: row.dataset.name,
                price: Number(row.dataset.price),
                stock: Number(row.dataset.stock),
                isActive: row.dataset.active === '1',
            });
        });

    });

    cartItems.addEventListener('click', (event) => {
        const addButton = event.target.closest('[data-cart-qty-add]');
        const subButton = event.target.closest('[data-cart-qty-sub]');
        if (addButton) {
            updateQuantity(addButton.dataset.id, 1);
            return;
        }
        if (subButton) {
            updateQuantity(subButton.dataset.id, -1);
        }
    });

    cartItems.addEventListener('input', (event) => {
        const input = event.target.closest('[data-cart-qty-input]');
        if (!input) {
            return;
        }
        setQuantity(input.dataset.id, Number(input.value));
    });

    cartClear.addEventListener('click', () => {
        cartState.clear();
        renderCart();
    });

    cartToggle.addEventListener('click', () => {
        const willShow = cartPanel.classList.contains('hidden');
        animatePanel(cartPanel, willShow);
    });

    if (searchToggle && searchPanel) {
        searchToggle.addEventListener('click', () => {
            const willShow = searchPanel.classList.contains('hidden');
            animatePanel(searchPanel, willShow);
            searchToggle.setAttribute('aria-label', willShow ? 'Ocultar buscador' : 'Mostrar buscador');
            if (searchToggleIcon) {
                searchToggleIcon.classList.toggle('rotate-180', willShow);
            }
        });
    }

    cartCheckout.addEventListener('click', () => {
        if (cartState.size === 0) {
            return;
        }
        toggleCheckout(true);
    });

    checkoutClose.addEventListener('click', () => toggleCheckout(false));
    checkoutCancel.addEventListener('click', () => toggleCheckout(false));
    checkoutBackdrop.addEventListener('click', (event) => {
        if (event.target === checkoutBackdrop) {
            toggleCheckout(false);
        }
    });

    checkoutConfirm.addEventListener('click', async () => {
        if (cartState.size === 0) {
            return;
        }
        const payload = {
            payment_method: paymentMethod.value,
            items: Array.from(cartState.values()).map((item) => ({
                product_id: item.id,
                quantity: item.quantity,
                unit_price: item.price,
            })),
        };

        checkoutConfirm.disabled = true;
        checkoutConfirm.classList.add('opacity-70', 'cursor-not-allowed');

        try {
            const response = await fetch("{{ route('sales.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Accept': 'application/json',
                },
                body: JSON.stringify(payload),
            });

            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'No se pudo registrar la venta.');
            }

            cartState.clear();
            renderCart();
            toggleCheckout(false);
            alert('Venta registrada correctamente.');
        } catch (error) {
            alert(error.message);
        } finally {
            checkoutConfirm.disabled = false;
            checkoutConfirm.classList.remove('opacity-70', 'cursor-not-allowed');
        }
    });

    const editToggle = document.getElementById('edit-toggle');
    if (editToggle) {
        editToggle.addEventListener('click', () => {
            document.querySelectorAll('[data-edit-header]').forEach((header) => {
                header.classList.toggle('hidden');
            });
            document.querySelectorAll('[data-edit-cell]').forEach((cell) => {
                cell.classList.toggle('hidden');
            });
        });
    }

    loadCart();
    renderCart();
</script>
@endsection
