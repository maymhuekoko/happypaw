@extends('master')
@section('title', 'Dashboard')
@section('content')
<style>
    * {
 margin: 0;
 padding: 0;
 box-sizing: border-box;
}
body {
 font-family: cursive;
}
a {
 text-decoration: none;
}
li {
 list-style: none;
}
.navbar {
 display: flex;
 align-items: center;
 justify-content: space-between;
 padding: 20px;
 background-color: #95d7d7;
 color: #fff;
}
.nav-links a {
 color: #fff;
}
/* NAVBAR MENU */
.menu {
 display: flex;
 gap: 1em;
 font-size: 18px;
}
.menu li:hover {
 background-color: #95d7d7;
 border-radius: 5px;
 transition: 0.3s ease;
}
.menu li {
 padding: 5px 14px;
}
/* DROPDOWN MENU */
.services {
 position: relative;
}
.dropdown {
 background-color: #95d7d7;
 padding: 1em 0;
 position: absolute; /*WITH RESPECT TO PARENT*/
 display: none;
 border-radius: 8px;
 top: 35px;
}
.dropdown li + li {
 margin-top: 10px;
}
.dropdown li {
 padding: 0.5em 1em;
 width: 8em;
 text-align: center;
}
.dropdown li:hover {
 background-color: #95d7d7;
}
.services:hover .dropdown {
 display: block;
}
</style>
<div class="content">
    <nav class="navbar">
        <!-- NAVIGATION MENU -->
        <ul class="nav-links">
          <!-- NAVIGATION MENUS -->
          <div class="menu">
            <li><a href="/">Home</a></li>
            <li><a href="/">About</a></li>
            <li class="services">
              <a href="/">Services</a>
              <!-- DROPDOWN MENU -->
              <ul class="dropdown">
                <li><a href="/">Dropdown 1 </a></li>
                <li><a href="/">Dropdown 2</a></li>
                <li><a href="/">Dropdown 2</a></li>
                <li><a href="/">Dropdown 3</a></li>
                <li><a href="/">Dropdown 4</a></li>
              </ul>
            </li>
            <li><a href="/">Pricing</a></li>
            <li><a href="/">Contact</a></li>
          </div>
        </ul>
      </nav>
</div>



@endsection

@section('js')

<script>

</script>

@endsection
