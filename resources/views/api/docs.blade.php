<!DOCTYPE html>

<html>
<head>
  <meta charset="utf-8">
  <title></title>
  <script src="https://unpkg.com/vue"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.4.0/css/bulma.min.css" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
  <script src="assets/js/neatjson.js"></script>
</head>

<body>

  <nav class="nav has-shadow">
    <div class="container">
      <div class="nav-left">
        <a class="nav-item">
          Pool4Cash API
        </a>
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

          @verbatim
            <ul class="menu-list">
              <li v-for="endpoint in endpoints">
                <p><b>{{ endpoint.name }}</b></p>
                <ul>
                  <li v-for="url in endpoint.urls">
                    <a @click="selectEndpoint(url)" >
                      <span v-if="url.method == 'GET'" class="tag is-success"></span>
                      <span v-else class="tag is-info"></span>
                       {{ url.endpoint }}
                    </a>
                  </li>
                </ul>
              </li>
            </ul>
          @endverbatim

        </aside>
      </div>

      <div class="column">

        @verbatim
          <div>
            <p><b>METHOD:</b> {{ endpoint.method }}</p>
            <p><b>URL:</b> {{ endpoint.endpoint }}</p>
            <p><b>PARAMETERS:</b></p>

            <table class="table  is-narrowed">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Type</th>
                  <th>Required/Optional</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="param in endpoint.parameters">
                  <td>{{ param.name }}</td>
                  <td>{{ param.type }}</td>
                  <td>{{ param.required ? 'required' : 'optional' }}</td>
                </tr>
              </tbody>
            </table>

            <p><b>RESPONSE</b></p>
            <pre>{{ endpoint.response | pretty }}</pre>
          </div>
        @endverbatim

      </div>

    </div>

  </div> <!-- end of container -->


</body>

<script type="text/javascript">
$(function() {
  $('ul.menu-list li span.is-success').text('GET');
  $('ul.menu-list li span.is-info').text('POST');

  $('.tabs .tab-pane').hide();
  $('.tabs .tab-pane:first-child').show();

  var endpoints = [
    { //PLAYERS
      name: 'Players',
      urls: [
        {
          method: 'GET',
          endpoint: 'players',
          parameters: [

          ],
          response: {
            "status":true,"players":[{"id":1,"game_id":"8C68","playfab_id":"E20211185EECC4CB","firstname":"Michael","lastname":"Jordan","username":"michaeljordan","email":"michaeljordan@test.com","session_ticket":"E20211185EECC4CB--CA48A455F2F0B0BD-8C68-8D493F7AB366D37-7B939B1E58838EB1.F7374FBC7DDF5BBE","virtual_chips_total":"0.00","real_cash_total":"0.00","total_won":0,"total_lose":0,"percentage_won":0,"percentage_lose":0,"vc":"chip","last_login":1494017130,"avatar":null,"remember_token":null,"created_at":"2017-05-05 20:45:30","updated_at":"2017-05-05 20:45:30"},{"id":2,"game_id":"8C68","playfab_id":"35ADA3369B919715","firstname":"Larry ","lastname":"Bird","username":"larrybird","email":"larrybird@test.com","session_ticket":"35ADA3369B919715--CA48A455F2F0B0BD-8C68-8D493F7BEA09FEF-3921C5E94584BCB9.DF3BBBF529EB81E4","virtual_chips_total":"0.00","real_cash_total":"0.00","total_won":0,"total_lose":0,"percentage_won":0,"percentage_lose":0,"vc":"chip","last_login":1494017162,"avatar":null,"remember_token":null,"created_at":"2017-05-05 20:46:02","updated_at":"2017-05-05 20:46:02"},{"id":3,"game_id":"8C68","playfab_id":"F8BCDBE59E0B0516","firstname":"Magic","lastname":"Johnson","username":"magicjohnson","email":"magicjohnson@test.com","session_ticket":"F8BCDBE59E0B0516--CA48A455F2F0B0BD-8C68-8D493F7D2856C7F-57DADB4D19DA1032.C758D752DFD76B2E","virtual_chips_total":"0.00","real_cash_total":"0.00","total_won":0,"total_lose":0,"percentage_won":0,"percentage_lose":0,"vc":"chip","last_login":1494017196,"avatar":null,"remember_token":null,"created_at":"2017-05-05 20:46:36","updated_at":"2017-05-05 20:46:36"},{"id":4,"game_id":"8C68","playfab_id":"80A07BDC02EDD42B","firstname":"New","lastname":"User","username":"mckinley1","email":"mckinley1@gmail.com","session_ticket":"80A07BDC02EDD42B-0-0-8C68-8D4A26CD2FB5E4D-14133A69B5A940CD.F592A51F5BA73584","virtual_chips_total":"0.00","real_cash_total":"0.00","total_won":0,"total_lose":0,"percentage_won":0,"percentage_lose":0,"vc":"chip","last_login":1495606764,"avatar":null,"remember_token":null,"created_at":"2017-05-24 05:34:33","updated_at":"2017-05-24 06:19:25"},{"id":5,"game_id":"8C68","playfab_id":"514EAF849A241FC0","firstname":"","lastname":"","username":"514EAF849A241FC0","email":"testbanned@gmail.com","session_ticket":"514EAF849A241FC0-0-0-8C68-8D4A2736D838050-811137D6A5174CD8.942B8B90DD6FE8A0","virtual_chips_total":"0.00","real_cash_total":"0.00","total_won":0,"total_lose":0,"percentage_won":0,"percentage_lose":0,"vc":"chip","last_login":1495609601,"avatar":null,"remember_token":null,"created_at":"2017-05-24 07:06:41","updated_at":"2017-05-24 07:06:41"},{"id":6,"game_id":"8C68","playfab_id":"E69E630074DCDA2","firstname":"","lastname":"","username":"E69E630074DCDA2","email":"test@gmail.com","session_ticket":"E69E630074DCDA2-0-0-8C68-8D4A273CD5F0CBE-2DB379A97F8489A3.C356076E726C1C1C","virtual_chips_total":"5.00","real_cash_total":"3.00","total_won":0,"total_lose":0,"percentage_won":0,"percentage_lose":0,"vc":"chip","last_login":1495609761,"avatar":null,"remember_token":null,"created_at":"2017-05-24 07:07:21","updated_at":"2017-05-24 07:43:04"}]
          }
        },
        {
          method: 'GET',
          endpoint: 'player/$PLAYER_ID',
          parameters: [

          ],
          response: {
            "status":true,"player":{"id":1,"game_id":"8C68","playfab_id":"6A1CC5B327779E4","firstname":"New","lastname":"User","username":"sample3","email":"sample3@pool4cashapp.com","session_ticket":"6A1CC5B327779E4-0-0-8C68-8D4A804381A2220-DF08A5C7A594AF20.8DA104F22F0A45CA","virtual_chips_total":"24.00","real_cash_total":"114.00","total_won":0,"total_lose":0,"percentage_won":0,"percentage_lose":0,"vc":"chip","last_login":1496221544,"avatar":null,"eula_accepted":0,"eula_id":null,"remember_token":null,"created_at":"2017-05-31 06:56:30","updated_at":"2017-05-31 09:05:44"}
          }
        },
        {
          method: 'POST',
          endpoint: 'player/auth',
          parameters: [
            {
              name: 'email',
              type: 'String',
              required: true
            },
            {
              name: 'password',
              type: 'String',
              required: true
            }
          ],
          response: {
            "status":true,"player":{"id":13,"game_id":"8C68","playfab_id":"CFC78765AC137EC6","firstname":"","lastname":"","username":"CFC78765AC137EC6","email":"james1@test.com","session_ticket":"CFC78765AC137EC6-0-0-8C68-8D4A257E9413B3F-5E2728E52C4516D4.F4719300505BC4E","virtual_chips_total":"0.00","real_cash_total":"0.00","total_won":0,"total_lose":0,"percentage_won":0,"percentage_lose":0,"vc":"chip","last_login":1495597783,"avatar":null,"remember_token":null,"created_at":"2017-05-05 14:01:50","updated_at":"2017-05-24 03:49:43"},"token":"a5319e8e6839dcab90b84a401ac711e76e1c1f5b","type":"email"
          }
        },
        {
          method: 'POST',
          endpoint: 'player/register',
          parameters: [

            {
              name: 'username',
              type: 'String',
              required: true
            },
            {
              name: 'email',
              type: 'String',
              required: true
            },
            {
              name: 'password',
              type: 'String',
              required: true
            }
          ],
          response: {
            "status":true,"player":{"username":"mckinley","playfab_id":"D7FE64DCC24E720F","game_id":"8C68","email":"mckinley@gmail.com","updated_at":"2017-05-24 03:54:11","created_at":"2017-05-24 03:54:11","id":17,"firstname":"New","lastname":"User","vc":"chip","session_ticket":"D7FE64DCC24E720F--CA48A455F2F0B0BD-8C68-8D4A25888EFFB53-799FAB6564D59BFD.7EA856BD7F92D51B","last_login":1495598051},"token":"53a378d70fefb6730ea9c2e392947a5f5402af7d"
          }
        },
        {
          method: 'GET',
          endpoint: 'player/photon',
          parameters: [
            {
              name: 'photon_id',
              type: 'String',
              required: true
            }
          ],
          response: {
            "status": true,
            "photon": "4wguwfrd6jyg8qgtqte3ypjn5zfrh6cs4b4quuh3qaciyh4yx4"
          }
        },
        {
          method: 'GET',
          endpoint: 'player/lobbies',
          parameters: [


          ],
          response: {
            "status":true,"lobbies":[{"id":8,"player_id":13,"lobby_id":7,"is_winner":0,"lobby_meta":null,"created_at":"2017-05-05 14:14:06","updated_at":"2017-05-05 14:14:06","lobby":{"id":7,"uri":null,"name":"New Game","region":"","amount":"0.00","vc":"chip","currency":"USD","winner_id":null,"loser_id":null,"has_player_accept":0,"player_accepted_at":null,"has_opponent_accept":0,"opponent_accepted_at":null,"is_active":1,"is_user_created":1,"created_by":13,"status":"created","started_at":null,"completed_at":null,"archived_at":null,"created_at":"2017-05-05 14:14:06","updated_at":"2017-05-05 14:14:06"}}]
          }
        },
        {
          method: 'GET',
          endpoint: 'player/stats',
          parameters: [

          ],
          response: {
            status: true,
            data: []
          }
        },
        {
          method: 'GET',
          endpoint: 'player/info',
          parameters: [

          ],
          response: {
            "status":true,"player":{"id":13,"game_id":"8C68","playfab_id":"CFC78765AC137EC6","firstname":"","lastname":"","username":"CFC78765AC137EC6","email":"james1@test.com","session_ticket":"CFC78765AC137EC6-0-0-8C68-8D4A258FEE0CF82-72BD9A5C4ECFB3BE.C881BA9F21B0EF7","virtual_chips_total":"0.00","real_cash_total":"0.00","total_won":0,"total_lose":0,"percentage_won":0,"percentage_lose":0,"vc":"chip","last_login":1495598248,"avatar":null,"remember_token":null,"created_at":"2017-05-05 14:01:50","updated_at":"2017-05-24 03:57:28"},"ip":"49.147.138.164","request":false,"info":{"PlayFabId":"CFC78765AC137EC6","Created":"2017-05-05T13:53:54.327Z","Username":"james1","TitleInfo":{"Origination":"Organic","Created":"2017-05-05T13:53:54.34Z","LastLogin":"2017-05-24T03:57:28.289Z","FirstLogin":"2017-05-05T13:53:54.34Z","isBanned":false},"PrivateInfo":{"Email":"james1@test.com"}},"combined":{"PlayFabId":"CFC78765AC137EC6","InfoResultPayload":{"UserDataVersion":0,"UserReadOnlyDataVersion":0}},"user":{},"publisher":{}
          }
        },
        {
          method: 'POST',
          endpoint: 'player/info',
          parameters: [
            {
              name: 'email',
              type: 'String',
              required: true
            },
            {
              name: 'password',
              type: 'String',
              required: true
            }
          ],
          response: {
            "status":true,"res":{"code":200,"status":"OK","data":{"DataVersion":1}}
          }
        },
        {
          method: 'POST',
          endpoint: 'player/avatar',
          parameters: [
            {
              name: 'avatar',
              type: 'String',
              required: true
            }
          ],
          response: {
            "status":true,"player":{"id":13,"game_id":"8C68","playfab_id":"CFC78765AC137EC6","firstname":"","lastname":"","username":"CFC78765AC137EC6","email":"james1@test.com","session_ticket":"CFC78765AC137EC6-0-0-8C68-8D4A258FEE0CF82-72BD9A5C4ECFB3BE.C881BA9F21B0EF7","virtual_chips_total":"0.00","real_cash_total":"0.00","total_won":0,"total_lose":0,"percentage_won":0,"percentage_lose":0,"vc":"chip","last_login":1495598248,"avatar":"https:\/\/www.smashingmagazine.com\/wp-content\/uploads\/2015\/06\/10-dithering-opt.jpg","remember_token":null,"created_at":"2017-05-05 14:01:50","updated_at":"2017-05-24 05:28:12"}
          }
        },
        {
          method: 'POST',
          endpoint: 'player/report/$PLAYER_ID',
          parameters: [
            {
              name: 'playfab_id',
              type: 'String',
              required: true
            },
            {
              name: 'comment',
              type: 'String',
              required: true
            }
          ],
          response: {
            status: true,
          }
        },
        {
          method: 'POST',
          endpoint: 'player/recover',
          parameters: [
            {
              name: 'email',
              type: 'String',
              required: true
            },
            {
              name: 'game_id',
              type: 'String',
              required: true
            }
          ],
          response: {
            status: true,
            data: []
          }
        },
        {
          method: 'POST',
          endpoint: 'player/push/send',
          parameters: [
            {
              name: 'playfab_id',
              type: 'String',
              required: true
            },
            {
              name: 'message',
              type: 'String',
              required: true
            }
          ],
          response: {
            status: true,
            data: []
          }
        },
        {
          method: 'POST',
          endpoint: 'player/push/setting',
          parameters: [
            {
              name: 'device_token',
              type: 'String',
              required: true
            }
          ],
          response: {
            status: true,
            data: []
          }
        },
        {
          method: 'GET',
          endpoint: 'player/eula',
          parameters: [

          ],
          response: {
            status: true,
            data: []
          }
        }
      ]
    },

    { //LOBBIES
      name: 'Lobbies',
      urls: [
        {
          method: 'GET',
          endpoint: 'lobbies',
          parameters: [

          ],
          response: {
            "status":true,"lobbies":[{"id":1,"uri":null,"name":"New Game","region":"EastAsia","amount":"10200.00","vc":"chip","currency":"USD","winner_id":null,"loser_id":null,"has_player_accept":0,"player_accepted_at":null,"has_opponent_accept":0,"opponent_accepted_at":null,"is_active":1,"is_user_created":1,"created_by":2,"status":"created","started_at":null,"completed_at":null,"archived_at":null,"created_at":"2017-05-05 21:04:56","updated_at":"2017-05-05 21:04:56","creator":{"id":2,"playfab_id":"35ADA3369B919715","firstname":"Larry ","lastname":"Bird","username":"larrybird","total_won":0,"total_lose":0,"percentage_won":0,"percentage_lose":0,"avatar":null,"virtual_chips_total":"0.00","real_cash_total":"0.00","last_login":1494017162},"players":[{"id":1,"player_id":2,"lobby_id":1,"is_winner":0,"created_at":"2017-05-05 21:04:56"}]},{"id":2,"uri":null,"name":"New Game","region":"USCentral","amount":"90000.00","vc":"chip","currency":"USD","winner_id":null,"loser_id":null,"has_player_accept":0,"player_accepted_at":null,"has_opponent_accept":0,"opponent_accepted_at":null,"is_active":1,"is_user_created":1,"created_by":1,"status":"created","started_at":null,"completed_at":null,"archived_at":null,"created_at":"2017-05-05 21:42:11","updated_at":"2017-05-05 22:23:39","creator":{"id":1,"playfab_id":"E20211185EECC4CB","firstname":"Michael","lastname":"Jordan","username":"michaeljordan","total_won":0,"total_lose":0,"percentage_won":0,"percentage_lose":0,"avatar":null,"virtual_chips_total":"0.00","real_cash_total":"0.00","last_login":1494017130},"players":[{"id":2,"player_id":1,"lobby_id":2,"is_winner":0,"created_at":"2017-05-05 21:42:11"}]}]
          }
        },
        {
          method: 'GET',
          endpoint: 'lobbies/invitations',
          parameters: [],
          response: {
            status: true,
            data: []
          }
        },
        {
          method: 'GET',
          endpoint: 'lobby/$LOBBY_ID',
          parameters: [],
          response: {
            "status":true,"lobby":{"id":1,"uri":null,"name":"New Game","region":"GB","amount":"10.00","vc":"chip","currency":"USD","winner_id":null,"loser_id":null,"has_player_accept":0,"player_accepted_at":null,"has_opponent_accept":0,"opponent_accepted_at":null,"is_active":1,"is_user_created":1,"created_by":6,"status":"created","started_at":null,"completed_at":null,"archived_at":null,"created_at":"2017-04-26 10:02:26","updated_at":"2017-04-26 10:02:26"}
          }
        },
        {
          method: 'GET',
          endpoint: 'lobby/match',
          parameters: [],
          response: {
            "status":true,"match":{"id":2,"uri":null,"name":"New Game","region":"USCentral","amount":"90000.00","vc":"chip","currency":"USD","winner_id":null,"loser_id":null,"has_player_accept":0,"player_accepted_at":null,"has_opponent_accept":0,"opponent_accepted_at":null,"is_active":1,"is_user_created":1,"created_by":1,"status":"created","started_at":null,"completed_at":null,"archived_at":null,"created_at":"2017-05-05 21:42:11","updated_at":"2017-05-05 22:23:39"}
          }
        },
        {
          method: 'POST',
          endpoint: 'lobby/$LOBBY_ID/join',
          parameters: [],
          response: {
            "status":true,"lobby":{"name":"New Game","region":"GB","amount":10,"vc":"chip","currency":"USD","status":"created","created_by":4,"updated_at":"2017-05-24 06:42:18","created_at":"2017-05-24 06:42:18","id":3},"player":{"lobby_id":3,"player_id":4,"updated_at":"2017-05-24 06:42:18","created_at":"2017-05-24 06:42:18","id":3},"newly_created":true,"match":{"id":2,"uri":null,"name":"New Game","region":"USCentral","amount":"90000.00","vc":"chip","currency":"USD","winner_id":null,"loser_id":null,"has_player_accept":0,"player_accepted_at":null,"has_opponent_accept":0,"opponent_accepted_at":null,"is_active":1,"is_user_created":1,"created_by":1,"status":"created","started_at":null,"completed_at":null,"archived_at":null,"created_at":"2017-05-05 21:42:11","updated_at":"2017-05-05 22:23:39"}
          }
        },
        {
          method: 'POST',
          endpoint: 'lobby/$LOBBY_ID/invite/$PLAYER_ID',
          parameters: [],
          response: {
            "status":true,"lobby":{"id":3,"uri":null,"name":"New Game","region":"GB","amount":"10.00","vc":"chip","currency":"USD","winner_id":null,"loser_id":null,"has_player_accept":0,"player_accepted_at":null,"has_opponent_accept":0,"opponent_accepted_at":null,"is_active":1,"is_user_created":1,"created_by":4,"status":"created","started_at":null,"completed_at":null,"archived_at":null,"created_at":"2017-05-24 06:42:18","updated_at":"2017-05-24 06:42:18"},"players":[{"id":3,"player_id":4,"lobby_id":3,"is_winner":0,"lobby_meta":null,"created_at":"2017-05-24 06:42:18","updated_at":"2017-05-24 06:42:18"}]
          }
        },
        {
          method: 'POST',
          endpoint: 'lobby/$LOBBY_ID/leave',
          parameters: [],
          response: {
            "status":true
          }
        },
        {
          method: 'POST',
          endpoint: 'lobby/$LOBBY_ID/accept',
          parameters: [],
          response: {
            "status":true,"lobby":{"id":3,"uri":null,"name":"New Game","region":"GB","amount":"10.00","vc":"chip","currency":"USD","winner_id":null,"loser_id":null,"has_player_accept":1,"player_accepted_at":1495608357,"has_opponent_accept":0,"opponent_accepted_at":null,"is_active":1,"is_user_created":1,"created_by":4,"status":"created","started_at":null,"completed_at":null,"archived_at":null,"created_at":"2017-05-24 06:42:18","updated_at":"2017-05-24 06:45:57"}
          }
        },
        {
          method: 'POST',
          endpoint: 'lobby/$LOBBY_ID/start',
          parameters: [],
          response: {
            "status":true,"lobby":{"id":4,"uri":null,"name":"New Game","region":"GB","amount":"10.00","vc":"chip","currency":"USD","winner_id":null,"loser_id":null,"has_player_accept":1,"player_accepted_at":1495608737,"has_opponent_accept":1,"opponent_accepted_at":1495608957,"is_active":1,"is_user_created":1,"created_by":4,"status":"started","started_at":1495608957,"completed_at":null,"archived_at":null,"created_at":"2017-05-24 06:49:58","updated_at":"2017-05-24 06:55:57"}
          }
        },
        {
          method: 'POST',
          endpoint: 'lobby/$LOBBY_ID/complete',
          parameters: [],
          response: {
            "status":true,"winner":true,"message":"You won this game","lobby":{"id":4,"uri":null,"name":"New Game","region":"GB","amount":"10.00","vc":"chip","currency":"USD","winner_id":4,"loser_id":null,"has_player_accept":1,"player_accepted_at":1495608737,"has_opponent_accept":1,"opponent_accepted_at":1495608957,"is_active":1,"is_user_created":1,"created_by":4,"status":"completed","started_at":1495608957,"completed_at":1495609077,"archived_at":null,"created_at":"2017-05-24 06:49:58","updated_at":"2017-05-24 06:57:57"}
          }
        }
      ]
    },

    { //FRIENDS
      name: 'Friends',
      urls: [
        {
          method: 'GET',
          endpoint: 'friends',
          parameters: [],
          response: {
            "status":true,"friends":[]
          }
        },
        {
          method: 'GET',
          endpoint: 'friend/$ID',
          parameters: [],
          response: {
            "status":true,"friend":{"id":1,"player_id":2,"friend_id":2,"is_accepted":1,"created_at":"2017-05-31 07:54:51","updated_at":"2017-05-31 07:58:57"}          }
        },
        {
          method: 'GET',
          endpoint: 'friends/leaderboard',
          parameters: [],
          response: {
            "status":true
          }
        },
        {
          method: 'GET',
          endpoint: 'friends/pending',
          parameters: [],
          response: {
            "status":true,"pendings":[{"id":1,"player_id":4,"friend_id":6,"is_accepted":0,"created_at":"2017-05-24 07:17:01","updated_at":"2017-05-24 07:17:01","friend":{"id":6,"playfab_id":"E69E630074DCDA2","firstname":"","lastname":"","username":"E69E630074DCDA2","total_won":0,"total_lose":0,"percentage_won":0,"percentage_lose":0,"avatar":null,"virtual_chips_total":"0.00","real_cash_total":"0.00","last_login":1495609761}}]
          }
        },
        {
          method: 'POST',
          endpoint: 'friend/$PLAYER_ID/invite',
          parameters: [],
          response: {
            "status":true,"0":"Invitation Sent"
          }
        },
        {
          method: 'POST',
          endpoint: 'friend/$INVITATION_ID/accept',
          parameters: [],
          response: {
            "status":true
          }
        },
        {
          method: 'POST',
          endpoint: 'friend/$INVITATION_ID/remove',
          parameters: [],
          response: {
            "status": true
          }
        }
      ]
    },

    { //SETTINGS
      name: 'Settings',
      urls: [
        {
          method: 'GET',
          endpoint: 'settings',
          parameters: [],
          response: {
            "status":true,"setting":{"game.vc.amount":"10","game.region":"GB","eula.version":"1","game.difficulty.level":"easy","game.missed":"4","game.over.scratch":"1","game.over.missed":"1","game.wrongball":"4","game.over.wrongball":"1","game.async.status":"active","game.realtime.status":"active","game.rc.amount":"10","game.starter.vc":"1000","game.starter.rc":"0"}
          }
        },
        {
          method: 'GET',
          endpoint: 'setting/$SETTING_ID',
          parameters: [],
          response: {
            "status":true,"setting":{"name":"game.vc.amount","value":"10"}
          }
        },
        {
          method: 'GET',
          endpoint: 'settings/country',
          parameters: [],
          response: {
            "status":true,"countries":[{"id":1,"name":"United States","code":"US","states":{"0":{"name":"Arizona","chip":true,"cash":false},"1":{"name":"Arkansas","chip":true,"cash":false},"2":{"name":"Connecticut","chip":true,"cash":false},"3":{"name":"Delaware","chip":true,"cash":false},"4":{"name":"Florida","chip":true,"cash":false},"5":{"name":"Illinois","chip":true,"cash":false},"6":{"name":"Louisiana","chip":true,"cash":false},"7":{"name":"Maryland","chip":true,"cash":false},"8":{"name":"Montana","chip":true,"cash":false},"9":{"name":"South Carolina","chip":true,"cash":false},"10":{"name":"South Dakota","chip":true,"cash":false},"11":{"name":"Tennessee","chip":true,"cash":false}},"cash_supported":1,"chip_supported":1,"created_at":null,"updated_at":null},{"id":2,"name":"Afghanistan","code":"AF","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":3,"name":"Belarus","code":"BY","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":4,"name":"Bulgaria","code":"BG","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":5,"name":"Democratic Republic of Congo","code":"CD","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":6,"name":"Cote d'Ivoire","code":"CI","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":7,"name":"China","code":"CN","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":8,"name":"Cuba","code":"CU","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":9,"name":"Egypt","code":"EG","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":10,"name":"France","code":"FR","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":11,"name":"French Guiana","code":"GF","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":12,"name":"French Polynesia","code":"PF","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":13,"name":"French Southern Territories","code":"TF","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":14,"name":"Greece","code":"GR","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":15,"name":"Indonesia","code":"ID","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":16,"name":"Iran","code":"IR","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":17,"name":"Iraq","code":"IQ","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":18,"name":"North Korea","code":"KP","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":19,"name":"Lithuania","code":"LT","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":20,"name":"Macedonia","code":"MK","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":21,"name":"Malaysia","code":"MY","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":22,"name":"Myanmar","code":"MM","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":23,"name":"Nigeria","code":"NG","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":24,"name":"Pakistan","code":"PK","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":25,"name":"Romania","code":"RO","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":26,"name":"Russia","code":"RU","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":27,"name":"Sudan","code":"SD","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":28,"name":"Syria","code":"SY","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":29,"name":"Turkey","code":"TR","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":30,"name":"Ukraine","code":"UA","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":31,"name":"Vietnam","code":"VN","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":32,"name":"Zimbabwe","code":"ZW","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":33,"name":"Croatia","code":"HR","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":34,"name":"Slovenia","code":"SI","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":35,"name":"Bosnia and Herzegovina","code":"BA","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":36,"name":"Serbia","code":"RS","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":37,"name":"Montenegro","code":"ME","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":38,"name":"Kosovo","code":"XK","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null}],"supported":true,"modes":["cash","chip"]
          }
        },
        {
          method: 'GET',
          endpoint: 'settings/{type}',
          parameters: [],
          response: {
            "status":true
          }
        }
      ]
    },

    { //VCS
      name: 'Virtual Currencies',
      urls: [
        {
          method: 'GET',
          endpoint: 'vcs',
          parameters: [],
          response: {
            "status":true,"virtualCurrencies":[{"id":1,"mode":null,"amount":1,"player_id":650,"payment_type":null,"payment_number":null,"payment_meta":null,"is_added":1,"is_available":1,"is_credited":0,"creditor_id":null,"created_at":"2017-05-31 08:21:04","updated_at":"2017-05-31 08:21:04"},{"id":2,"mode":null,"amount":1,"player_id":2,"payment_type":null,"payment_number":null,"payment_meta":null,"is_added":1,"is_available":1,"is_credited":0,"creditor_id":null,"created_at":"2017-05-31 08:23:14","updated_at":"2017-05-31 08:23:14"},{"id":3,"mode":null,"amount":57,"player_id":2,"payment_type":null,"payment_number":null,"payment_meta":null,"is_added":1,"is_available":1,"is_credited":0,"creditor_id":null,"created_at":"2017-05-31 08:25:57","updated_at":"2017-05-31 08:25:57"},{"id":4,"mode":null,"amount":59,"player_id":2,"payment_type":null,"payment_number":null,"payment_meta":null,"is_added":1,"is_available":1,"is_credited":0,"creditor_id":null,"created_at":"2017-05-31 08:27:32","updated_at":"2017-05-31 08:27:32"},{"id":5,"mode":null,"amount":1,"player_id":1,"payment_type":null,"payment_number":null,"payment_meta":null,"is_added":1,"is_available":1,"is_credited":0,"creditor_id":null,"created_at":"2017-05-31 08:33:48","updated_at":"2017-05-31 08:33:48"},{"id":6,"mode":null,"amount":2,"player_id":2,"payment_type":null,"payment_number":null,"payment_meta":null,"is_added":1,"is_available":1,"is_credited":0,"creditor_id":null,"created_at":"2017-05-31 00:46:04","updated_at":"2017-05-31 00:46:04"},{"id":7,"mode":null,"amount":313,"player_id":2,"payment_type":null,"payment_number":null,"payment_meta":null,"is_added":1,"is_available":1,"is_credited":0,"creditor_id":null,"created_at":"2017-05-31 00:46:13","updated_at":"2017-05-31 00:46:13"},{"id":8,"mode":null,"amount":20,"player_id":2,"payment_type":null,"payment_number":null,"payment_meta":null,"is_added":1,"is_available":1,"is_credited":0,"creditor_id":null,"created_at":"2017-05-31 00:52:43","updated_at":"2017-05-31 00:52:43"},{"id":9,"mode":null,"amount":12,"player_id":1,"payment_type":null,"payment_number":null,"payment_meta":null,"is_added":1,"is_available":1,"is_credited":0,"creditor_id":null,"created_at":null,"updated_at":null},{"id":10,"mode":null,"amount":12,"player_id":1,"payment_type":null,"payment_number":null,"payment_meta":null,"is_added":1,"is_available":1,"is_credited":0,"creditor_id":null,"created_at":null,"updated_at":null},{"id":11,"mode":null,"amount":13,"player_id":1,"payment_type":null,"payment_number":null,"payment_meta":null,"is_added":1,"is_available":1,"is_credited":0,"creditor_id":null,"created_at":"2017-06-04 19:48:24","updated_at":"2017-06-04 19:48:24"}]
          }
        },
        {
          method: 'GET',
          endpoint: 'vcs/$VCS_ID',
          parameters: [],
          response: {
            "status":true,"virtualCurrency":{"id":1,"mode":null,"amount":1,"player_id":650,"payment_type":null,"payment_number":null,"payment_meta":null,"is_added":1,"is_available":1,"is_credited":0,"creditor_id":null,"created_at":"2017-05-31 08:21:04","updated_at":"2017-05-31 08:21:04"}
          }
        },
        {
          method: 'POST',
          endpoint: 'vcs/cash/add',
          parameters: [
            {
              name: 'amount',
              type: 'String',
              required: true
            }
          ],
          response: {
            "status":true,"data":{"PlayFabId":"E69E630074DCDA2","VirtualCurrency":"RC","BalanceChange":100,"Balance":100}
          }
        },
        {
          method: 'POST',
          endpoint: 'vcs/cash/subtract',
          parameters: [
            {
              name: 'amount',
              type: 'String',
              required: true
            }
          ],
          response: {
            "status":true,"data":{"PlayFabId":"E69E630074DCDA2","VirtualCurrency":"RC","BalanceChange":-100,"Balance":3}
          }
        },
        {
          method: 'POST',
          endpoint: 'vcs/cash/reset',
          parameters: [],
          response: {
            "status":true,"data":{"PlayFabId":"E69E630074DCDA2","VirtualCurrency":"RC","BalanceChange":-3,"Balance":3}
          }
        },
        {
          method: 'POST',
          endpoint: 'vcs/chips/add',
          parameters: [
            {
              name: 'amount',
              type: 'String',
              required: true
            }
          ],
          response: {
            "status":true,"data":{"PlayFabId":"E69E630074DCDA2","VirtualCurrency":"VC","BalanceChange":100,"Balance":105}
          }
        },
        {
          method: 'POST',
          endpoint: 'vcs/chips/subtract',
          parameters: [
            {
              name: 'amount',
              type: 'String',
              required: true
            }
          ],
          response: {
            "status":true,"data":{"PlayFabId":"E69E630074DCDA2","VirtualCurrency":"VC","BalanceChange":-100,"Balance":5}
          }
        },
        {
          method: 'POST',
          endpoint: 'vcs/chips/reset',
          parameters: [],
          response: {
            "status":true,"data":{"PlayFabId":"6A1CC5B327779E4","VirtualCurrency":"VC","BalanceChange":-24,"Balance":5137}
          }
        }
      ]
    },

    { //ACTIVITIES
      name: 'Activities',
      urls: [
        {
          method: 'GET',
          endpoint: 'activities',
          parameters: [],
          response: {
            "status":true,"activities":[{"id":1,"player_id":2,"lobby_id":2,"pocketed_balls":10,"duration":10000,"created_at":"2017-06-04 19:46:05","updated_at":"2017-06-04 19:46:05"},{"id":2,"player_id":2,"lobby_id":1,"pocketed_balls":12,"duration":5000,"created_at":"2017-06-05 02:54:15","updated_at":"2017-06-05 02:54:15"}]          }
        },
        {
          method: 'GET',
          endpoint: 'activity/$ACTIVITY_ID',
          parameters: [],
          response: {
              "status":true,"activity":{"id":1,"player_id":2,"lobby_id":2,"pocketed_balls":10,"duration":10000,"created_at":"2017-06-04 19:46:05","updated_at":"2017-06-04 19:46:05"}          }
        },
      ]
    },

    { //NOTIFICATIONS
      name: 'Notifications',
      urls: [
        {
          method: 'GET',
          endpoint: 'notifications',
          parameters: [],
          response: {
            "status":true,"notifications":[{"id":1,"message":"You use hackable tools in the game. Your account will be banned permanently.","player_id":1,"device":null,"created_at":"2017-06-04 19:46:56","updated_at":"2017-06-04 19:47:23"},{"id":2,"message":"You use hackable tools in the game. Your account will be banned permanently.","player_id":2,"device":"356938035643809","created_at":"2017-06-05 03:06:01","updated_at":"2017-06-05 03:06:01"}]
          }
        },
        {
          method: 'GET',
          endpoint: 'notification/$ACTIVITY_ID',
          parameters: [],
          response: {
            "status":true,"notification":{"id":1,"message":"You use hackable tools in the game. Your account will be banned permanently.","player_id":1,"device":null,"created_at":"2017-06-04 19:46:56","updated_at":"2017-06-04 19:47:23"}            }
          },
      ]
    },

    { //COUNTRIES
      name: 'Countries',
      urls: [
        {
          method: 'GET',
          endpoint: 'countries',
          parameters: [],
          response: {
            "status":true,"countries":[{"id":1,"name":"United States","code":"US","states":{"0":{"name":"Arizona","chip":true,"cash":false},"1":{"name":"Arkansas","chip":true,"cash":false},"2":{"name":"Connecticut","chip":true,"cash":false},"3":{"name":"Delaware","chip":true,"cash":false},"4":{"name":"Florida","chip":true,"cash":false},"5":{"name":"Illinois","chip":true,"cash":false},"6":{"name":"Louisiana","chip":true,"cash":false},"7":{"name":"Maryland","chip":true,"cash":false},"8":{"name":"Montana","chip":true,"cash":false},"9":{"name":"South Carolina","chip":true,"cash":false},"10":{"name":"South Dakota","chip":true,"cash":false},"11":{"name":"Tennessee","chip":true,"cash":false}},"cash_supported":1,"chip_supported":1,"created_at":null,"updated_at":null},{"id":2,"name":"Afghanistan","code":"AF","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":3,"name":"Belarus","code":"BY","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":4,"name":"Bulgaria","code":"BG","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":5,"name":"Democratic Republic of Congo","code":"CD","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":6,"name":"Cote d'Ivoire","code":"CI","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":7,"name":"China","code":"CN","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":8,"name":"Cuba","code":"CU","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":9,"name":"Egypt","code":"EG","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":10,"name":"France","code":"FR","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":11,"name":"French Guiana","code":"GF","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":12,"name":"French Polynesia","code":"PF","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":13,"name":"French Southern Territories","code":"TF","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":14,"name":"Greece","code":"GR","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":15,"name":"Indonesia","code":"ID","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":16,"name":"Iran","code":"IR","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":17,"name":"Iraq","code":"IQ","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":18,"name":"North Korea","code":"KP","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":19,"name":"Lithuania","code":"LT","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":20,"name":"Macedonia","code":"MK","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":21,"name":"Malaysia","code":"MY","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":22,"name":"Myanmar","code":"MM","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":23,"name":"Nigeria","code":"NG","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":24,"name":"Pakistan","code":"PK","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":25,"name":"Romania","code":"RO","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":26,"name":"Russia","code":"RU","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":27,"name":"Sudan","code":"SD","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":28,"name":"Syria","code":"SY","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":29,"name":"Turkey","code":"TR","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":30,"name":"Ukraine","code":"UA","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":31,"name":"Vietnam","code":"VN","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":32,"name":"Zimbabwe","code":"ZW","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":33,"name":"Croatia","code":"HR","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":34,"name":"Slovenia","code":"SI","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":35,"name":"Bosnia and Herzegovina","code":"BA","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":36,"name":"Serbia","code":"RS","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":37,"name":"Montenegro","code":"ME","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null},{"id":38,"name":"Kosovo","code":"XK","states":{},"cash_supported":0,"chip_supported":1,"created_at":null,"updated_at":null}]
          }
        },
        {
          method: 'GET',
          endpoint: 'country/$COUNTRY_ID',
          parameters: [],
          response: {
            "status":true,"country":{"id":1,"name":"United States","code":"US","states":{"0":{"name":"Arizona","chip":true,"cash":false},"1":{"name":"Arkansas","chip":true,"cash":false},"2":{"name":"Connecticut","chip":true,"cash":false},"3":{"name":"Delaware","chip":true,"cash":false},"4":{"name":"Florida","chip":true,"cash":false},"5":{"name":"Illinois","chip":true,"cash":false},"6":{"name":"Louisiana","chip":true,"cash":false},"7":{"name":"Maryland","chip":true,"cash":false},"8":{"name":"Montana","chip":true,"cash":false},"9":{"name":"South Carolina","chip":true,"cash":false},"10":{"name":"South Dakota","chip":true,"cash":false},"11":{"name":"Tennessee","chip":true,"cash":false}},"cash_supported":1,"chip_supported":1,"created_at":null,"updated_at":null}
          }
        },
      ]
    },

    { //EULAS
      name: 'Eulas',
      urls: [
        {
          method: 'GET',
          endpoint: 'eulas',
          parameters: [],
          response: {
            // "status":true,"eulas":[{"id":1,"version":"1.0","text":"
            // Quis ut dolorum magnam vel voluptatem amet. Dolorem maxime at est officiis id dolor.<\/p>","country_id":"1","is_default":0,"created_at":null,"updated_at":"2017-06-04 19:38:10"},{"id":2,"version":"1.1","text":"Officiis quaerat ducimus corrupti eos. Exercitationem dolores magni illo est. Laborum earum voluptatem minima ut qui temporibus. Nostrum quae dolor ipsa pariatur mollitia.","country_id":"19","is_default":0,"created_at":null,"updated_at":null},{"id":3,"version":"1.2","text":"Sed quasi facilis omnis. Amet natus optio molestias consectetur consequuntur qui vitae. Eaque adipisci accusantium ducimus aut quam sit.","country_id":"22","is_default":0,"created_at":null,"updated_at":null},{"id":4,"version":"1.3","text":"Aut est ad vitae ducimus tenetur qui omnis dolorum. Velit et eos dolorem delectus ut. Illo distinctio dolorem magni sint eum. Dolores non est ut facilis ipsam repudiandae.","country_id":"32","is_default":0,"created_at":null,"updated_at":null},{"id":5,"version":"1.4","text":"Iste aut quibusdam fugit est. Ea doloribus nihil quis beatae accusamus praesentium. Ad quasi voluptates ipsam nemo architecto nulla dolores nesciunt.","country_id":"21","is_default":0,"created_at":null,"updated_at":null},{"id":6,"version":"1.5","text":"Consequatur quo accusantium vel illo inventore molestias. Aut culpa vitae est voluptas voluptatum. Odit aut at fugit enim libero porro natus provident.","country_id":"13","is_default":0,"created_at":null,"updated_at":null}]
          }
        },
        {
          method: 'GET',
          endpoint: 'eula/$EULA_ID',
          parameters: [],
          response: {
            // "status":true,"eula":{"id":1,"version":"1.0","text":"
            // Quis ut dolorum magnam vel voluptatem amet. Dolorem maxime at est officiis id dolor.<\/p>","country_id":"1","is_default":0,"created_at":null,"updated_at":"2017-06-04 19:38:10"}
          }
        },
      ]
    },

    { //USERS
      name: 'Users',
      urls: [
        {
          method: 'GET',
          endpoint: 'users',
          parameters: [],
          response: {
            "status":true,"users":[{"id":1,"name":"administrator","email":"admin@pool4cashapp.com","remember_token":"CIjw0SZe2ibJSIvVElGp2qCIGfbtV0dTaU6uAxo2kFY578NwajNZArXw6lTc","created_at":null,"updated_at":null},{"id":2,"name":"owner","email":"owner@pool4cashapp.com","remember_token":null,"created_at":"2017-06-05 03:27:06","updated_at":"2017-06-05 03:27:06"}]
          }
        },
        {
          method: 'GET',
          endpoint: 'user/$USER_ID',
          parameters: [],
          response: {
            "status":true,"user":{"id":1,"name":"administrator","email":"admin@pool4cashapp.com","remember_token":"CIjw0SZe2ibJSIvVElGp2qCIGfbtV0dTaU6uAxo2kFY578NwajNZArXw6lTc","created_at":null,"updated_at":null}
          }
        },
      ]
    },
  ];

  var main = new Vue({
    el: '#main',
    data: {
      endpoints: endpoints,
      endpoint: {}
    },
    methods: {
      viewer: function() {
        alert('test');
      },
      selectEndpoint: function(endpoint) {
        this.endpoint = endpoint;
      }
    },
    filters: {
      pretty: function(value) {
        return JSON.stringify(value, null, 2);
      }
    }
  });

});


</script>

</html>
