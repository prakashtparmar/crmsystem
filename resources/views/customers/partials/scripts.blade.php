<script>
    function tagInput(boxId, inputId, hiddenId) {
        const box = document.getElementById(boxId);
        const input = document.getElementById(inputId);
        const hidden = document.getElementById(hiddenId);

        if (!box || !input || !hidden) return;

        let items = hidden.value ? hidden.value.split(',').filter(Boolean) : [];

        function render() {
            box.querySelectorAll('.tag').forEach(t => t.remove());

            items.forEach((c, i) => {
                const tag = document.createElement('span');
                tag.className =
                    'tag px-2 py-0.5 text-xs rounded bg-emerald-100 text-emerald-800 ' +
                    'dark:bg-emerald-900 dark:text-emerald-200 flex items-center gap-1';

                tag.innerHTML = `${c} <button type="button" data-i="${i}" class="font-bold">×</button>`;
                box.insertBefore(tag, input);
            });

            hidden.value = items.join(',');
        }

        function addItem(value) {
            const v = value.trim();
            if (!v) return;

            if (!items.includes(v)) {
                items.push(v);
                render();
            }

            input.value = '';
        }

        // Add on Enter
        input.addEventListener('keydown', e => {
            if (e.key === 'Enter') {
                e.preventDefault();
                addItem(input.value);
            }
        });

        // Add when user selects from datalist (mouse / touch)
        input.addEventListener('change', () => {
            addItem(input.value);
        });

        // Remove tag
        box.addEventListener('click', e => {
            if (e.target.tagName === 'BUTTON') {
                items.splice(e.target.dataset.i, 1);
                render();
            }
        });

        render();
    }

    tagInput('primary-box', 'primary-input', 'primary-hidden');
    tagInput('secondary-box', 'secondary-input', 'secondary-hidden');
</script>

<!-- Display Name Auto Generator -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const first  = document.querySelector('input[name="first_name"]');
        const middle = document.querySelector('input[name="middle_name"]');
        const last   = document.querySelector('input[name="last_name"]');
        const display = document.querySelector('input[name="display_name"]');

        if (!first || !middle || !last || !display) return;

        function updateDisplayName() {
            const f = first.value.trim();
            const m = middle.value.trim();
            const l = last.value.trim();

            display.value = [f, m, l].filter(Boolean).join(' ');
        }

        first.addEventListener('input', updateDisplayName);
        middle.addEventListener('input', updateDisplayName);
        last.addEventListener('input', updateDisplayName);

        // Fill once on load (for old() values)
        updateDisplayName();
    });
</script>

<!-- Hide button Script -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[data-toggle]').forEach(btn => {
            const targetId = btn.getAttribute('data-toggle');
            const section = document.getElementById(targetId);
            if (!section) return;

            btn.addEventListener('click', () => {
                const hidden = section.classList.toggle('hidden');
                btn.textContent = hidden ? 'Show' : 'Hide';
            });
        });
    });
</script>















<script>
document.addEventListener('DOMContentLoaded', () => {
    const fields = {
        pincode: document.getElementById('pincode'),
        post_office: document.getElementById('post_office'),
        village: document.getElementById('village'),
        taluka: document.getElementById('taluka'),
        district: document.getElementById('district'),
        state: document.getElementById('state'),
    };

    if (!fields.pincode) return;

    // Create datalists
    Object.entries(fields).forEach(([k, el]) => {
        if (!el) return;
        const dl = document.createElement('datalist');
        dl.id = k + '_list';
        document.body.appendChild(dl);
        el.setAttribute('list', dl.id);
    });

    let controller;
    let cache = [];

    async function fetchData(params = {}) {
        if (controller) controller.abort();
        controller = new AbortController();

        const q = new URLSearchParams(params).toString();
        const res = await fetch(`/address-lookup?${q}`, { signal: controller.signal });
        return await res.json();
    }

    // PINCODE → initial load
    fields.pincode.addEventListener('input', async () => {
        const pin = fields.pincode.value.replace(/\D/g, '').slice(0, 6);
        fields.pincode.value = pin;
        if (pin.length < 3) return;

        cache = await fetchData({ pincode: pin });

        const dl = document.getElementById('post_office_list');
        dl.innerHTML = '';
        [...new Set(cache.map(r => r.post_so_name).filter(Boolean))]
            .forEach(v => {
                const opt = document.createElement('option');
                opt.value = v;
                dl.appendChild(opt);
            });
    });

    // Live refinement by Post Office / Village / Taluka / District
    ['post_office', 'village', 'taluka', 'district'].forEach(name => {
        if (!fields[name]) return;

        fields[name].addEventListener('input', async () => {
            const data = await fetchData({
                pincode: fields.pincode.value,
                post_office: fields.post_office.value,
                village: fields.village.value,
                taluka: fields.taluka.value,
                district: fields.district.value,
            });

            cache = data;

            const map = {
                post_office: 'post_so_name',
                village: 'village_name',
                taluka: 'taluka_name',
                district: 'District_name'
            };

            Object.entries(map).forEach(([f, col]) => {
                const dl = document.getElementById(f + '_list');
                if (!dl) return;

                dl.innerHTML = '';
                [...new Set(data.map(r => r[col]).filter(Boolean))]
                    .forEach(v => {
                        const o = document.createElement('option');
                        o.value = v;
                        dl.appendChild(o);
                    });
            });

            // Auto-fill when only one record matches
            if (data.length === 1) {
                const row = data[0];
                fields.post_office.value = row.post_so_name  || '';
                fields.village.value     = row.village_name  || '';
                fields.taluka.value      = row.taluka_name   || '';
                fields.district.value    = row.District_name || '';
                fields.state.value       = row.state_name    || '';
                fields.pincode.value     = row.pincode       || '';
            }
        });
    });

    // Enforce dropdown-only values
    function enforceFromDatalist(id) {
        const input = document.getElementById(id);
        const list  = document.getElementById(id + '_list');
        if (!input || !list) return;

        input.addEventListener('blur', () => {
            const val = input.value.trim();
            if (!val) return;

            const ok = [...list.options].some(o => o.value === val);
            if (!ok) input.value = '';
        });
    }

    ['village', 'taluka', 'district', 'state', 'post_office'].forEach(enforceFromDatalist);
});

// Global – used by your “Clear” button
window.clearAddressFields = function () {
    const ids = [
        'address_line1',
        'address_line2',
        'village',
        'taluka',
        'district',
        'state',
        'pincode',
        'post_office'
    ];

    ids.forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = '';
    });
};
</script>





