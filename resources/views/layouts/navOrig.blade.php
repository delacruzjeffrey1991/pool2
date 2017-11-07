<nav class="nav has-shadow">
  <div class="container">
    <a class="nav-item" href="{{ url('admin')}}">
      Pool4Cash
    </a>
    <div class="nav-left">
      <a class="nav-item is-tab is-hidden-mobile" href="{{ url('admin/players')}}">Players</a>
      <a class="nav-item is-tab is-hidden-mobile" href="{{ url('admin/games')}}">Games</a>
      <a class="nav-item is-tab is-hidden-mobile" href="{{ url('admin/lobbies')}}">Lobbies</a>
      <a class="nav-item is-tab is-hidden-mobile" href="{{ url('admin/vcs')}}">Virtual Currencies</a>
      <a class="nav-item is-tab is-hidden-mobile" href="{{ url('admin/users')}}">User</a>
      <a class="nav-item is-tab is-hidden-mobile" href="{{ url('admin/settings')}}">Settings</a>
    </div>
    {{-- <span class="nav-toggle">
      <span></span>
      <span></span>
      <span></span>
    </span>
    <div class="nav-right nav-menu">
      <a class="nav-item is-tab is-hidden-tablet is-active">Home</a>
      <a class="nav-item is-tab is-hidden-tablet">Features</a>
      <a class="nav-item is-tab is-hidden-tablet">Pricing</a>
      <a class="nav-item is-tab is-hidden-tablet">About</a>
      <a class="nav-item is-tab">
        <figure class="image is-16x16" style="margin-right: 8px;">
          <img src="http://bulma.io/images/jgthms.png">
        </figure>
        Profile
      </a>
      <a class="nav-item is-tab">Log out</a>
    </div> --}}
  </div>
</nav>
