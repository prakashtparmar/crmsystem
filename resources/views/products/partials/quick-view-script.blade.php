<script>
    document.addEventListener('click', function (e) {
        const trigger = e.target.closest('.view-product');

        if (trigger) {
            const d = trigger.dataset;

            // Basic fields
            pm_name.textContent    = d.name || '—';
            pm_sku.textContent     = d.sku || '—';
            pm_hsn.textContent     = d.hsn || '—';
            pm_price.textContent   = d.price || '—';
            pm_cost.textContent    = d.cost || '—';
            pm_tax.textContent     = d.gst || '—';
            pm_organic.textContent = d.organic || '—';
            pm_active.textContent  = d.active || '—';
            pm_min.textContent     = d.min || '—';
            pm_max.textContent     = d.max || '—';
            pm_shelf.textContent   = d.shelf ? d.shelf + ' days' : '—';
            pm_short.textContent   = d.short || '—';
            pm_desc.textContent    = d.desc || '—';

            // Relation fields (if present in modal)
            if (typeof pm_category !== 'undefined')    pm_category.textContent    = d.category || '—';
            if (typeof pm_subcategory !== 'undefined') pm_subcategory.textContent = d.subcategory || '—';
            if (typeof pm_brand !== 'undefined')       pm_brand.textContent       = d.brand || '—';
            if (typeof pm_unit !== 'undefined')        pm_unit.textContent        = d.unit || '—';
            if (typeof pm_crop !== 'undefined')        pm_crop.textContent        = d.crop || '—';
            if (typeof pm_season !== 'undefined')      pm_season.textContent      = d.season || '—';

            // Image handling
            if (d.image) {
                pm_image.src = d.image;
                pm_image.classList.remove('hidden');
                pm_no_image.classList.add('hidden');
            } else {
                pm_image.src = '';
                pm_image.classList.add('hidden');
                pm_no_image.classList.remove('hidden');
            }

            // Show modal
            productModal.classList.remove('hidden');
            productModal.classList.add('flex');

            requestAnimationFrame(() => {
                productModalCard.classList.remove('scale-95', 'opacity-0');
                productModalCard.classList.add('scale-100', 'opacity-100');
            });

            return;
        }

        // Close modal
        if (
            e.target.id === 'productModal' ||
            e.target.id === 'closeProductModal' ||
            e.target.id === 'closeProductModalBtn'
        ) {
            productModalCard.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                productModal.classList.add('hidden');
                productModal.classList.remove('flex');
            }, 150);
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            productModalCard.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                productModal.classList.add('hidden');
                productModal.classList.remove('flex');
            }, 150);
        }
    });
</script>
