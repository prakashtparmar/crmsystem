<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<script>
let index = 1;

function showToast(message) {
    const container = document.getElementById('toastContainer');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className =
        'pointer-events-auto bg-green-600 text-white text-sm px-4 py-2 rounded-lg shadow-lg ' +
        'transform transition-all duration-300 opacity-0 translate-y-2';

    toast.textContent = message;
    container.appendChild(toast);

    requestAnimationFrame(() => {
        toast.classList.remove('opacity-0', 'translate-y-2');
        toast.classList.add('opacity-100', 'translate-y-0');
    });

    setTimeout(() => {
        toast.classList.remove('opacity-100', 'translate-y-0');
        toast.classList.add('opacity-0', 'translate-y-2');
        setTimeout(() => toast.remove(), 300);
    }, 2500);
}

function recalc() {
    let sub = 0, tax = 0;

    document.querySelectorAll('.item-row').forEach(row => {
        const price = parseFloat(row.querySelector('.price').value || 0);
        const qty = parseFloat(row.querySelector('.qty').value || 0);
        const taxRate = parseFloat(row.querySelector('.tax').dataset.rate || 0);

        const line = price * qty;
        const lineTax = (line * taxRate) / 100;

        row.querySelector('.total').value = (line + lineTax).toFixed(2);

        sub += line;
        tax += lineTax;
    });

    const discountEl = document.getElementById('discount_amount');
    let discount = parseFloat(discountEl?.value || 0);
    const max = sub + tax;

    if (discount > max) {
        discount = max;
        discountEl.value = max.toFixed(2);
    }

    document.getElementById('sub_total').value = sub.toFixed(2);
    document.getElementById('tax_amount').value = tax.toFixed(2);
    document.getElementById('grand_total').value =
        Math.max(0, (sub + tax - discount)).toFixed(2);
}

document.addEventListener('change', function(e) {
    if (e.target.classList.contains('product-select')) {
        const opt = e.target.selectedOptions[0];
        const row = e.target.closest('.item-row');

        row.querySelector('.price').value = opt.dataset.price || 0;

        const taxInput = row.querySelector('.tax');
        taxInput.dataset.rate = opt.dataset.tax || 0;

        recalc();
    }
});

document.addEventListener('input', function(e) {
    if (e.target.classList.contains('qty') || e.target.id === 'discount_amount') {
        recalc();
    }
});

document.getElementById('addRow')?.addEventListener('click', function() {
    const base = document.querySelector('.item-row');
    const clone = base.cloneNode(true);

    clone.querySelectorAll('input,select').forEach(el => {
        el.value = el.classList.contains('qty') ? 1 : '';
        el.name = el.name.replace(/\d+/, index);
    });

    const taxInput = clone.querySelector('.tax');
    if (taxInput) {
        taxInput.dataset.rate = 0;
    }

    document.getElementById('items').appendChild(clone);
    index++;
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('qty-plus')) {
        const input = e.target.closest('.item-row').querySelector('.qty');
        input.value = (parseInt(input.value || 1) + 1);
        recalc();
    }

    if (e.target.classList.contains('qty-minus')) {
        const input = e.target.closest('.item-row').querySelector('.qty');
        input.value = Math.max(1, parseInt(input.value || 1) - 1);
        recalc();
    }

    if (e.target.classList.contains('remove-row')) {
        const rows = document.querySelectorAll('.item-row');
        if (rows.length > 1) {
            e.target.closest('.item-row').remove();
            recalc();
        }
    }
});

// Product search
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('product-search')) {
        const term = e.target.value.toLowerCase();
        const row = e.target.closest('.item-row');
        const select = row.querySelector('.product-select');

        let firstVisible = null;

        Array.from(select.options).forEach(opt => {
            if (!opt.value) return;

            const name = opt.textContent.toLowerCase();
            const sku = (opt.dataset.sku || '').toLowerCase();

            const match = name.startsWith(term) || sku.startsWith(term);

            opt.hidden = !match;

            if (match && !firstVisible) {
                firstVisible = opt;
            }
        });

        if (firstVisible) {
            select.value = firstVisible.value;
            select.dispatchEvent(new Event('change', { bubbles: true }));
        }
    }
});



// Keyboard navigation for product search (↑ ↓ Enter)
document.addEventListener('keydown', function(e) {
    if (!e.target.classList.contains('product-search')) return;

    const row = e.target.closest('.item-row');
    const select = row.querySelector('.product-select');

    const visibleOptions = Array.from(select.options)
        .filter(o => o.value && !o.hidden);

    if (!visibleOptions.length) return;

    let currentIndex = visibleOptions.findIndex(o => o.selected);

    if (e.key === 'ArrowDown') {
        e.preventDefault();
        const next = visibleOptions[currentIndex + 1] || visibleOptions[0];
        select.value = next.value;
        select.dispatchEvent(new Event('change', { bubbles: true }));
    }

    if (e.key === 'ArrowUp') {
        e.preventDefault();
        const prev = visibleOptions[currentIndex - 1] || visibleOptions[visibleOptions.length - 1];
        select.value = prev.value;
        select.dispatchEvent(new Event('change', { bubbles: true }));
    }

    if (e.key === 'Enter') {
        e.preventDefault();
        const chosen = visibleOptions[currentIndex] || visibleOptions[0];
        if (chosen) {
            select.value = chosen.value;
            select.dispatchEvent(new Event('change', { bubbles: true }));
            row.querySelector('.qty').focus();
        }
    }
});

// Products table
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('productsTable')) {
        $('#productsTable').DataTable({
            dom: 'lfrtip',
            pageLength: 5,
            lengthMenu: [[5, 10, 25, 50], [5, 10, 25, 50]],
            autoWidth: false,
            scrollX: true,
            responsive: false,
        });
    }
});

// Add product from Products table into Quick Order
document.addEventListener('click', function(e) {
    const btn = e.target.closest('.add-product');
    if (!btn) return;

    const id = btn.dataset.id;
    const price = btn.dataset.price || 0;
    const tax = btn.dataset.tax || 0;

    const wrap = btn.closest('td');
    const qtyInput = wrap.querySelector('.prod-qty');
    const qty = parseInt(qtyInput?.value || 1);

    let rows = document.querySelectorAll('.item-row');
    let row = rows[rows.length - 1];

    const select = row.querySelector('.product-select');
    if (select.value) {
        const clone = row.cloneNode(true);

        clone.querySelectorAll('input,select').forEach(el => {
            el.value = el.classList.contains('qty') ? 1 : '';
            el.name = el.name.replace(/\d+/, index);
        });

        const taxInput = clone.querySelector('.tax');
        if (taxInput) {
            taxInput.dataset.rate = 0;
        }

        document.getElementById('items').appendChild(clone);
        row = clone;
        index++;
    }

    const productSelect = row.querySelector('.product-select');
    const priceInput = row.querySelector('.price');
    const taxInput = row.querySelector('.tax');
    const qtyField = row.querySelector('.qty');

    const option = Array.from(productSelect.options).find(o => o.value == id);
    if (option) {
        productSelect.value = option.value;
    }

    priceInput.value = price;
    if (taxInput) {
        taxInput.dataset.rate = tax;
    }
    qtyField.value = qty;

    recalc();

    const name = btn.dataset.name || 'Product';
    showToast(`${name} added (Qty: ${qty})`);
});


    setupToggle('toggleCustomerInfo', 'customerDetails', true, 'Show Details', 'Hide Details');
    setupToggle('toggleQuickOrder', 'quickOrderSection', false, 'Show Quick Order', 'Hide Quick Order');
    setupToggle('toggleProducts', 'productsSection', false, 'Show Products', 'Hide Products');
    setupToggle('toggleOrders', 'ordersSection', true, 'Show Orders', 'Hide Orders');


// Toggle helper (ADDED)
function setupToggle(buttonId, sectionId, hiddenByDefault = false, showText = 'Show', hideText = 'Hide') {
    const btn = document.getElementById(buttonId);
    const section = document.getElementById(sectionId);
    if (!btn || !section) return;

    if (hiddenByDefault) {
        section.classList.add('hidden');
        btn.textContent = showText;
    } else {
        btn.textContent = hideText;
    }

    btn.addEventListener('click', () => {
        const isHidden = section.classList.toggle('hidden');
        btn.textContent = isHidden ? showText : hideText;
    });
}

// Init after load (ADDED)

</script>
