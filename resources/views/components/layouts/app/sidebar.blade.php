<aside
    :class="{ 'w-full md:w-64': sidebarOpen, 'w-0 md:w-16 hidden md:block': !sidebarOpen }"
    class="bg-sidebar text-sidebar-foreground border-r border-gray-200 dark:border-gray-700 sidebar-transition overflow-hidden">
    <div class="h-full flex flex-col">

        <!-- Sidebar Menu -->
        <nav class="flex-1 overflow-y-auto custom-scrollbar py-4">
            <ul class="space-y-1 px-2">

                <!-- Dashboard -->
                <x-layouts.sidebar-link
                    href="{{ route('dashboard') }}"
                    icon="fas-tachometer-alt"
                    :active="request()->routeIs('dashboard*')">
                    Dashboard
                </x-layouts.sidebar-link>

               <!-- Commerce -->
<x-layouts.sidebar-two-level-link-parent
    title="Commerce"
    icon="fas-shopping-cart"
    :active="
        request()->routeIs('orders*') ||
        request()->routeIs('customers*') ||
        request()->routeIs('products*') ||
        request()->routeIs('inventory*') ||
        request()->routeIs('categories*') ||
        request()->routeIs('subcategories*') ||
        request()->routeIs('brands*') ||
        request()->routeIs('units*') ||
        request()->routeIs('crops*') ||
        request()->routeIs('seasons*') ||
        request()->routeIs('product-variants*') ||
        request()->routeIs('product-attributes*') ||
        request()->routeIs('product-images*') ||
        request()->routeIs('product-tags*') ||
        request()->routeIs('batch-lots*') ||
        request()->routeIs('expiries*') ||
        request()->routeIs('certifications*')
    ">

    {{-- CUSTOMER --}}
    <div class="px-3 mt-2 text-xs font-semibold uppercase text-gray-400">
        Customer
    </div>

    <x-layouts.sidebar-two-level-link
        href="{{ route('customers.index') }}"
        icon="fas-users"
        :active="request()->routeIs('customers*')">
        Customers
    </x-layouts.sidebar-two-level-link>

    {{-- ORDERS --}}
    <div class="px-3 mt-3 text-xs font-semibold uppercase text-gray-400">
        Orders
    </div>

    <x-layouts.sidebar-two-level-link
        href="{{ route('orders.index') }}"
        icon="fas-receipt"
        :active="request()->routeIs('orders*')">
        Orders
    </x-layouts.sidebar-two-level-link>

    {{-- PRODUCTS --}}
    <div class="px-3 mt-3 text-xs font-semibold uppercase text-gray-400">
        Products
    </div>

    <x-layouts.sidebar-two-level-link
        href="{{ route('products.index') }}"
        icon="fas-box"
        :active="request()->routeIs('products*')">
        Products
    </x-layouts.sidebar-two-level-link>

    <x-layouts.sidebar-two-level-link
        href="{{ route('inventory.index') }}"
        icon="fas-boxes"
        :active="request()->routeIs('inventory*')">
        Inventory
    </x-layouts.sidebar-two-level-link>

    {{-- MASTERS --}}
    <div class="px-3 mt-3 text-xs font-semibold uppercase text-gray-400">
        Masters
    </div>

    <x-layouts.sidebar-two-level-link
        href="{{ route('categories.index') }}"
        icon="fas-tags"
        :active="request()->routeIs('categories*')">
        Categories
    </x-layouts.sidebar-two-level-link>

    <x-layouts.sidebar-two-level-link
        href="{{ route('subcategories.index') }}"
        icon="fas-tag"
        :active="request()->routeIs('subcategories*')">
        Sub Categories
    </x-layouts.sidebar-two-level-link>

    <x-layouts.sidebar-two-level-link
        href="{{ route('brands.index') }}"
        icon="fas-copyright"
        :active="request()->routeIs('brands*')">
        Brands
    </x-layouts.sidebar-two-level-link>

    <x-layouts.sidebar-two-level-link
        href="{{ route('units.index') }}"
        icon="fas-balance-scale"
        :active="request()->routeIs('units*')">
        Units
    </x-layouts.sidebar-two-level-link>

    <x-layouts.sidebar-two-level-link
        href="{{ route('crops.index') }}"
        icon="fas-seedling"
        :active="request()->routeIs('crops*')">
        Crops
    </x-layouts.sidebar-two-level-link>

    <x-layouts.sidebar-two-level-link
        href="{{ route('seasons.index') }}"
        icon="fas-sun"
        :active="request()->routeIs('seasons*')">
        Seasons
    </x-layouts.sidebar-two-level-link>

    <x-layouts.sidebar-two-level-link
        href="{{ route('product-variants.index') }}"
        icon="fas-layer-group"
        :active="request()->routeIs('product-variants*')">
        Variants
    </x-layouts.sidebar-two-level-link>

    <x-layouts.sidebar-two-level-link
        href="{{ route('product-attributes.index') }}"
        icon="fas-sliders-h"
        :active="request()->routeIs('product-attributes*')">
        Attributes
    </x-layouts.sidebar-two-level-link>

    <x-layouts.sidebar-two-level-link
        href="{{ route('product-images.index') }}"
        icon="fas-image"
        :active="request()->routeIs('product-images*')">
        Images
    </x-layouts.sidebar-two-level-link>

    <x-layouts.sidebar-two-level-link
        href="{{ route('product-tags.index') }}"
        icon="fas-hashtag"
        :active="request()->routeIs('product-tags*')">
        Tags
    </x-layouts.sidebar-two-level-link>

    <x-layouts.sidebar-two-level-link
        href="{{ route('batch-lots.index') }}"
        icon="fas-barcode"
        :active="request()->routeIs('batch-lots*')">
        Batch Lots
    </x-layouts.sidebar-two-level-link>

    <x-layouts.sidebar-two-level-link
        href="{{ route('expiries.index') }}"
        icon="fas-hourglass-end"
        :active="request()->routeIs('expiries*')">
        Expiries
    </x-layouts.sidebar-two-level-link>

    <x-layouts.sidebar-two-level-link
        href="{{ route('certifications.index') }}"
        icon="fas-certificate"
        :active="request()->routeIs('certifications*')">
        Certifications
    </x-layouts.sidebar-two-level-link>

</x-layouts.sidebar-two-level-link-parent>


                <!-- Marketing -->
                <x-layouts.sidebar-two-level-link-parent
                    title="Marketing"
                    icon="fas-bullhorn"
                    :active="request()->routeIs('coupons*') || request()->routeIs('campaigns*')">

                    <x-layouts.sidebar-two-level-link
                        href="{{ route('coupons.index') }}"
                        icon="fas-ticket"
                        :active="request()->routeIs('coupons*')">
                        Coupons
                    </x-layouts.sidebar-two-level-link>

                    <x-layouts.sidebar-two-level-link
                        href="{{ route('campaigns.index') }}"
                        icon="fas-bullhorn"
                        :active="request()->routeIs('campaigns*')">
                        Campaigns
                    </x-layouts.sidebar-two-level-link>

                </x-layouts.sidebar-two-level-link-parent>

                <!-- Reports -->
                <x-layouts.sidebar-two-level-link-parent
                    title="Reports"
                    icon="fas-chart-line"
                    :active="request()->routeIs('reports*')">

                    <x-layouts.sidebar-two-level-link
                        href="{{ route('reports.sales') }}"
                        icon="fas-dollar-sign"
                        :active="request()->routeIs('reports.sales')">
                        Sales
                    </x-layouts.sidebar-two-level-link>

                    <x-layouts.sidebar-two-level-link
                        href="{{ route('reports.customers') }}"
                        icon="fas-user"
                        :active="request()->routeIs('reports.customers')">
                        Customers
                    </x-layouts.sidebar-two-level-link>

                    <x-layouts.sidebar-three-level-parent
                        title="Advanced"
                        icon="fas-angle-right"
                        :active="request()->routeIs('reports.advanced*')">

                        <x-layouts.sidebar-three-level-link
                            href="{{ route('reports.advanced.performance') }}"
                            :active="request()->routeIs('reports.advanced.performance')">
                            Performance
                        </x-layouts.sidebar-three-level-link>

                        <x-layouts.sidebar-three-level-link
                            href="{{ route('reports.advanced.conversion') }}"
                            :active="request()->routeIs('reports.advanced.conversion')">
                            Conversion
                        </x-layouts.sidebar-three-level-link>

                    </x-layouts.sidebar-three-level-parent>

                </x-layouts.sidebar-two-level-link-parent>

                <!-- System -->
                <x-layouts.sidebar-two-level-link-parent
                    title="System"
                    icon="fas-shield-alt"
                    :active="request()->routeIs('users*') || request()->routeIs('roles*')">

                    <x-layouts.sidebar-two-level-link
                        href="{{ route('users.index') }}"
                        icon="fas-user"
                        :active="request()->routeIs('users*')">
                        Users
                    </x-layouts.sidebar-two-level-link>

                    <x-layouts.sidebar-two-level-link
                        href="{{ route('roles.index') }}"
                        icon="fas-id-card"
                        :active="request()->routeIs('roles*')">
                        Roles
                    </x-layouts.sidebar-two-level-link>

                </x-layouts.sidebar-two-level-link-parent>

                <!-- Settings -->
                <x-layouts.sidebar-two-level-link-parent
                    title="Settings"
                    icon="fas-cog"
                    :active="request()->routeIs('settings*')">

                    <x-layouts.sidebar-two-level-link
                        href="{{ route('settings.profile.edit') }}"
                        icon="fas-user-cog"
                        :active="request()->routeIs('settings.profile.*')">
                        Profile
                    </x-layouts.sidebar-two-level-link>

                    <x-layouts.sidebar-two-level-link
                        href="{{ route('settings.password.edit') }}"
                        icon="fas-key"
                        :active="request()->routeIs('settings.password.*')">
                        Password
                    </x-layouts.sidebar-two-level-link>

                    <x-layouts.sidebar-two-level-link
                        href="{{ route('settings.appearance.edit') }}"
                        icon="fas-adjust"
                        :active="request()->routeIs('settings.appearance.*')">
                        Appearance
                    </x-layouts.sidebar-two-level-link>

                </x-layouts.sidebar-two-level-link-parent>

            </ul>
        </nav>
    </div>
</aside>
