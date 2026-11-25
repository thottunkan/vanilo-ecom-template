/**
 * public/js/cartcalc.js
 *
 * Vanilo Cart AJAX calculations
 */

(function () {

    'use strict';

    const CONFIG = {
        updateUrl: '/cart/update',
        removeUrl: '/cart/remove',

        // Must match your Laravel tax calculation
        taxRate: 0.05,

        // Free shipping threshold
        freeShippingThreshold: 1000,

        // Shipping charge when below threshold
        shippingCharge: 50
    };


    /**
     * Get CSRF token
     */
    function getCsrfToken() {

        const token = document.querySelector(
            'meta[name="csrf-token"]'
        );

        return token ? token.getAttribute('content') : '';

    }


    /**
     * Format currency
     */
    function formatCurrency(amount) {

        amount = Number(amount || 0);

        return '₹' + Math.round(amount).toLocaleString('en-IN');

    }


    /**
     * Update summary UI
     */
    function updateSummary(summary) {

        const subtotalElement =
            document.getElementById('cart-subtotal');

        const shippingElement =
            document.getElementById('cart-shipping');

        const taxElement =
            document.getElementById('cart-tax');

        const totalElement =
            document.getElementById('cart-total');


        if (subtotalElement) {

            subtotalElement.textContent =
                formatCurrency(summary.subtotal);

        }


        if (shippingElement) {

            if (Number(summary.shipping) === 0) {

                shippingElement.textContent = 'Free';

                shippingElement.classList.add('free');

            } else {

                shippingElement.textContent =
                    formatCurrency(summary.shipping);

                shippingElement.classList.remove('free');

            }

        }


        if (taxElement) {

            taxElement.textContent =
                formatCurrency(summary.tax);

        }


        if (totalElement) {

            totalElement.textContent =
                formatCurrency(summary.total);

        }

    }


    /**
     * Update quantity
     */
    async function updateQuantity(itemId, quantity) {

        if (quantity < 1) {
            quantity = 1;
        }


        try {

            const response = await fetch(
                CONFIG.updateUrl,
                {
                    method: 'POST',

                    headers: {
                        'Content-Type': 'application/json',

                        'Accept': 'application/json',

                        'X-CSRF-TOKEN': getCsrfToken(),

                        'X-Requested-With': 'XMLHttpRequest'
                    },

                    body: JSON.stringify({
                        item_id: itemId,
                        quantity: quantity
                    })
                }
            );


            const data = await response.json();


            if (!response.ok || !data.success) {

                throw new Error(
                    data.message ||
                    'Unable to update cart.'
                );

            }


            /*
             * Update quantity
             */
            const quantityElement =
                document.querySelector(
                    `[data-quantity="${itemId}"]`
                );


            if (quantityElement) {

                quantityElement.textContent =
                    data.item.quantity;

            }


            /*
             * Update individual item price
             */
            const itemTotalElement =
                document.querySelector(
                    `[data-item-total="${itemId}"]`
                );


            if (itemTotalElement) {

                itemTotalElement.textContent =
                    formatCurrency(data.item.total);

            }


            /*
             * Update order summary
             */
            updateSummary(data.summary);


        } catch (error) {

            console.error(
                'Cart quantity error:',
                error
            );

            alert(error.message);

        }

    }


    /**
     * Remove cart item
     */
    async function removeItem(itemId) {

        try {

            const response = await fetch(
                CONFIG.removeUrl,
                {
                    method: 'POST',

                    headers: {
                        'Content-Type': 'application/json',

                        'Accept': 'application/json',

                        'X-CSRF-TOKEN': getCsrfToken(),

                        'X-Requested-With': 'XMLHttpRequest'
                    },

                    body: JSON.stringify({
                        item_id: itemId
                    })
                }
            );


            const data = await response.json();


            if (!response.ok || !data.success) {

                throw new Error(
                    data.message ||
                    'Unable to remove item.'
                );

            }


            /*
             * Remove item from DOM
             */
            const itemElement =
                document.querySelector(
                    `[data-cart-item="${itemId}"]`
                );


            if (itemElement) {

                itemElement.remove();

            }


            /*
             * Update summary
             */
            updateSummary(data.summary);


            /*
             * If cart becomes empty,
             * reload page so empty-cart UI appears.
             */
            if (Number(data.summary.cart_count) === 0) {

                window.location.reload();

            }


        } catch (error) {

            console.error(
                'Cart remove error:',
                error
            );

            alert(error.message);

        }

    }


    /**
     * Plus button
     */
    document.addEventListener(
        'click',
        function (event) {

            const button =
                event.target.closest('.qty-plus');


            if (!button) {
                return;
            }


            const itemId =
                button.dataset.itemId;


            const quantityElement =
                document.querySelector(
                    `[data-quantity="${itemId}"]`
                );


            if (!quantityElement) {
                return;
            }


            let quantity =
                parseInt(
                    quantityElement.textContent,
                    10
                );


            if (isNaN(quantity)) {
                quantity = 1;
            }


            quantity++;

            updateQuantity(
                itemId,
                quantity
            );

        }
    );


    /**
     * Minus button
     */
    document.addEventListener(
        'click',
        function (event) {

            const button =
                event.target.closest('.qty-minus');


            if (!button) {
                return;
            }


            const itemId =
                button.dataset.itemId;


            const quantityElement =
                document.querySelector(
                    `[data-quantity="${itemId}"]`
                );


            if (!quantityElement) {
                return;
            }


            let quantity =
                parseInt(
                    quantityElement.textContent,
                    10
                );


            if (isNaN(quantity)) {
                quantity = 1;
            }


            quantity--;


            /*
             * Do not allow zero.
             * You can change this to removeItem()
             * if you want zero to remove the product.
             */
            if (quantity < 1) {
                quantity = 1;
            }


            updateQuantity(
                itemId,
                quantity
            );

        }
    );


    /**
     * Remove button
     */
    document.addEventListener(
        'click',
        function (event) {

            const button =
                event.target.closest('.remove-item');


            if (!button) {
                return;
            }


            const itemId =
                button.dataset.itemId;


            removeItem(itemId);

        }
    );


})();