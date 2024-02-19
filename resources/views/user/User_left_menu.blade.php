<?php
$page = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'],"/")+1);
?>
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <div class="app-brand demo">
    <a href="{{ route('index')}}" class="app-brand-link">
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
    <li class="menu-item {{ request()->is('index*') ? 'active' : '' }}">
      <a href="{{ route('index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home-circle"></i>
        <div data-i18n="Analytics">Home</div>
      </a>
    </li>
    <li class="menu-item {{ request()->is('search*') ? 'active' : '' }}">
      <a href="{{ route('search') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-gift"></i>
        <div data-i18n="Basic">Food</div>
      </a>
    </li>
    @if (session('username'))
    <li class="menu-item {{ request()->is('donate*') ? 'active' : '' }}">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-donate-blood"></i>
        <div data-i18n="Account Settings">Donate Food</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item {{ request()->is('donate/add-donated-food') ? 'active' : '' }}">
          <a href="{{ route('add_donated_food') }}" class="menu-link">
            <div data-i18n="Connections">Add Donated Food</div>
          </a>
        </li>
        <li class="menu-item {{ request()->is('donate/manage-list') ? 'active' : '' }}">
          <a href="{{ route('show_donate_list') }}" class="menu-link">
            <div data-i18n="Account">Manage List Food</div>
          </a>
        </li>
      </ul>
    </li>
    <li class="menu-item {{ request()->is('receiving*') ? 'active' : '' }}">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-history"></i>
        <div data-i18n="Misc">Receiving History</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item {{ request()->is('receiving/list') ? 'active' : '' }}">
          <a href="{{ route('received_list') }}" class="menu-link">
            <div data-i18n="Error">Received List</div>
          </a>
        </li>
      </ul>
    </li>
    <li class="menu-item {{ request()->is('message*') ? 'active' : '' }}">
      <a href="" class="menu-link">
        <i class="menu-icon tf-icons bx bx-message"></i>
        <div data-i18n="Basic">Messages</div>
      </a>
    </li>
    <!-- User interface -->
    @endif
    <!-- <li class="menu-item">
      <a href="icons-boxicons.html" class="menu-link">
        <i class="menu-icon tf-icons bx bx-location-plus"></i>
        <div data-i18n="Boxicons">Emergency Points</div>
      </a>
    </li>
    <li class="menu-item">
      <a href="icons-boxicons.html" class="menu-link">
        <i class="menu-icon tf-icons bx bx-support"></i>
        <div data-i18n="Boxicons">Reports and Support</div>
      </a>
    </li> -->
  </ul>
</aside>
