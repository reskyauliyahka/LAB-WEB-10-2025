<ul class="navbar-nav bg-gradient-primary    sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
        <div class="sidebar-brand-icon">
        </div>
        <div class="sidebar-brand-text mx-3">GUDANGKU</div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item">
        <a class="nav-link" href="/">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Manajemen Data
    </div>

    <li class="nav-item">
        {{-- Ganti href /kategori -> /categories --}}
        <a class="nav-link" href="/categories">
            <i class="fas fa-fw fa-table"></i>
            <span>Kategori</span></a>
    </li>

    <li class="nav-item">
        {{-- Ganti href /product -> /products --}}
        <a class="nav-link" href="/products">
            <i class="fas fa-fw fa-box"></i>
            <span>Produk</span></a>
    </li>

    <li class="nav-item">
        {{-- Ganti href /product -> /warehouses --}}
        <a class="nav-link" href="/warehouses">
            <i class="fas fa-fw fa-warehouse"></i>
            <span>Gudang</span></a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Manajemen Stok
    </div>

    <li class="nav-item">
        {{-- Kita pakai rute 'stock.index' dari web.php --}}
        <a class="nav-link" href="{{ route('stock.index') }}">
            <i class="fas fa-fw fa-boxes"></i>
            <span>Stok Gudang</span></a>
    </li>

    <li class="nav-item">
        {{-- Kita pakai rute 'stock.transfer.form' dari web.php --}}
        <a class="nav-link" href="{{ route('stock.transfer.form') }}">
            <i class="fas fa-fw fa-exchange-alt"></i>
            <span>Transfer Stok</span></a>
    </li>


    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
