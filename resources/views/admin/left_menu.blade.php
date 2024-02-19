<?php
$page = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'],"/")+1);
?>
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <div class="app-brand demo">
    <a href="{{ route('show_dashboard')}}" class="app-brand-link">
        <img alt="logo" style="max-width:50px;" src="/assets/img/icons/brands/logo.png">
      <span class="app-brand-text demo menu-text fw-bolder ms-2">FoodShare</span>
    </a>
    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left bx-sm align-middle"></i>
    </a>
  </div>
  <div class="menu-inner-shadow"></div>
  <ul class="menu-inner py-1">
    <!-- Dashboard -->
    <li class="menu-item {{ request()->is('dashboard*') ? 'active' : '' }}">
      <a href="{{ route('show_dashboard') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home-circle"></i>
        <div data-i18n="Analytics">Dashboard</div>
      </a>
    </li>
    <li class="menu-item {{ request()->is('manage/donated*') ? 'active' : '' }}">
      <a href="{{ route('show_manage_donated') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-gift"></i>
        <div data-i18n="Basic">Manage Donated</div>
      </a>
    </li>
    <li class="menu-item {{ request()->is('manage/transactions*') ? 'active' : '' }}">
      <a href="{{route('show_manage_transactions')}}" class="menu-link ">
        <i class="menu-icon tf-icons bx bx-history"></i>
        <div data-i18n="Misc">Manage Transaction</div>
      </a>
    </li>
    <li class="menu-item {{ request()->is('manage/users*') || request()->is('manage/user-role/*') ? 'active' : '' }}">
      <a href="{{route('show_manage_users')}}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-user"></i>
        <div data-i18n="Basic">Manage account</div>
      </a>
    </li>
    <li class="menu-item {{ request()->is('message*') ? 'active' : '' }}">
      <a href="{{route('admin_logout')}}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-power-off"></i>
        <div data-i18n="Basic">Log out</div>
      </a>
    </li>
  </ul>
</aside>