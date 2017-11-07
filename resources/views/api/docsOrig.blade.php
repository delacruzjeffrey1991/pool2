<!DOCTYPE html>

<html>
  <head>
    <meta charset="utf-8">
    <title></title>
    <script src="https://unpkg.com/vue"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.4.0/css/bulma.min.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
  </head>

  <body>

    <nav class="nav has-shadow">
      <div class="container">
        <div class="nav-left">
          <a class="nav-item">
            Pool4Cash API
          </a>
          <a class="nav-item is-tab is-hidden-mobile is-active">Players</a>
          <a class="nav-item is-tab is-hidden-mobile">Lobbies</a>
          <a class="nav-item is-tab is-hidden-mobile">Friends</a>
          <a class="nav-item is-tab is-hidden-mobile">Virtual Currencies</a>
          <a class="nav-item is-tab is-hidden-mobile">Settings</a>
        </div>
        <span class="nav-toggle">
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
        </div>
      </div>
    </nav>

    <div class="container" style="margin-top: 30px" id="main">

      <div class="columns">
        <div class="column is-4">
          <aside class="menu">
            <p class="menu-label">
              Endpoints
            </p>
            <ul class="menu-list">
              <li>
                <a>Players</a>
                <ul>
                  <li><a><span class="tag is-success"></span> api/players</a></li>
                  <li><a><span class="tag is-info"></span> api/player/auth</a></li>
                  <li><a><span class="tag is-info"></span> api/player/register</a></li>
                  <li><a><span class="tag is-success"></span> api/player/photon</a></li>
                  <li><a><span class="tag is-info"></span> api/player/register</a></li>
                  <li><a><span class="tag is-success"></span> api/player/lobbies</a></li>
                  <li><a><span class="tag is-success"></span> api/player/stats</a></li>
                  <li><a><span class="tag is-success"></span> api/player/info</a></li>
                  <li><a><span class="tag is-info"></span> api/player/info</a></li>
                  <li><a><span class="tag is-info"></span> api/player/avatar</a></li>
                  <li><a><span class="tag is-info"></span> api/report/$PLAYER_ID</a></li>
                  <li><a><span class="tag is-info"></span> api/player/recover</a></li>
                  <li><a><span class="tag is-info"></span> api/player/push/send</a></li>
                  <li><a><span class="tag is-info"></span> api/player/push/setting</a></li>
                </ul>
              </li>
              <li>
                <a>Lobbies</a>
                <ul>
                  <li><a><span class="tag is-success"></span> api/lobbies</a></li>
                  <li><a><span class="tag is-success"></span> api/lobbies/invitations</a></li>
                  <li><a><span class="tag is-success"></span> api/lobby/$LOBBY_ID</a></li>
                  <li><a><span class="tag is-info"></span> api/lobby/join</a></li>
                  <li><a><span class="tag is-info"></span> api/lobby/$LOBBY_ID/invite/$PLAYER_ID</a></li>
                  <li><a><span class="tag is-info"></span> api/lobby/$LOBBY_ID/accept</a></li>
                  <li><a><span class="tag is-info"></span> api/lobby/$LOBBY_ID/start</a></li>
                  <li><a><span class="tag is-info"></span> api/lobby/$LOBBY_ID/complete</a></li>
                  <li><a><span class="tag is-info"></span> api/lobby/$LOBBY_ID/leave</a></li>
                </ul>
              </li>
              <li>
                <a>Games</a>
                <ul>
                  <li><a><span class="tag is-success"></span> api/games</a></li>
                  <li><a><span class="tag is-success"></span> api/game/catalog</a></li>
                  <li><a><span class="tag is-success"></span> api/game/publisher</a></li>
                  <li><a><span class="tag is-success"></span> api/game/store</a></li>
                  <li><a><span class="tag is-success"></span> api/game/time</a></li>
                  <li><a><span class="tag is-success"></span> api/game/title</a></li>
                  <li><a><span class="tag is-success"></span> api/game/news</a></li>
                </ul>
              </li>
              <li>
                <a>Friends</a>
                <ul>
                  <li><a><span class="tag is-success"></span> api/friends</a></li>
                  <li><a><span class="tag is-success"></span> api/friends/leaderboard</a></li>
                  <li><a><span class="tag is-success"></span> api/friends/pending</a></li>
                  <li><a><span class="tag is-info"></span> api/friend/$PLAYER_ID/invite</a></li>
                  <li><a><span class="tag is-info"></span> api/friend/$INVITATION_ID/accept</a></li>
                  <li><a><span class="tag is-info"></span> api/friend/$INVITATION_ID/remove</a></li>
                </ul>
              </li>
              <li>
                <a>Virtual Currencies</a>
                <ul>
                  <li><a><span class="tag is-success"></span> api/vcs</a></li>
                  <li><a><span class="tag is-info"></span> api/cash/add</a></li>
                  <li><a><span class="tag is-info"></span> api/cash/subtract</a></li>
                  <li><a><span class="tag is-info"></span> api/cash/reset</a></li>
                  <li><a><span class="tag is-info"></span> api/chips/add</a></li>
                  <li><a><span class="tag is-info"></span> api/chips/subtract</a></li>
                  <li><a><span class="tag is-info"></span> api/chips/reset</a></li>
                </ul>
              </li>
              <li>
                <a>Settings</a>
                <ul>
                  <li><a><span class="tag is-success"></span> settings</a></li>
                  <li><a><span class="tag is-success"></span> payment/paypal</a></li>
                  <li><a><span class="tag is-success"></span> payment/skrill</a></li>
                </ul>
              </li>
            </ul>
          </aside>
        </div>

        <div class="column">

        </div>

      </div>

    </div> <!-- end of container -->


  </body>

  <script type="text/javascript">
  $(function() {
    $('ul.menu-list li span.is-success').text('GET');
    $('ul.menu-list li span.is-info').text('POST');

    var main = new Vue({
      el: '#main',
      methods: {
        viewer: function() {
          alert('test');
        }
      }
    });

  });


  </script>

</html>
