<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// $apiAccess = TRUE;
// $api_prefix = 'api';
//
// if (php_sapi_name() != 'cli') {
//   switch($_SERVER['SERVER_NAME']) {
//     case 'api.pool4cashapp.com':
//       $api_prefix = '';
//       break;
//     default:
//       $app->get('/', function () use ($app) { return ['status'=>TRUE]; });
//   }
// }

$app->group(['middleware'=>'auth_api', 'namespace'=>'App\Http\Controllers\Api'], function () use ($app) {
    $app->get('/', function() { return ['status' => TRUE, 'version'=>'1.0']; });
    $app->get('docs', function() {
      return view('api.docs');
    });

    //PLAYERS
    $app->get('players', 'PlayerController@index');
    $app->get('player/photon', 'PlayerController@getPhoton'); //get photon token
    $app->post('player/register', 'PlayerController@register');
    $app->post('player/register/fbOrGuest', 'PlayerController@registerWithFbOrGuest');
    $app->post('player/auth', 'PlayerController@auth');
    $app->post('player/eula', 'PlayerController@eula');
    $app->post('player/auth/{type}', 'PlayerController@auth');
    $app->post('player/authentication/guest', 'PlayerController@authGuest');
    $app->post('player/authentication/email', 'PlayerController@authEmail');
    $app->post('player/authentication/guest/linkToEmail', 'PlayerController@linkGuestToEmail');
    $app->post('player/authentication/guest/linkToFb', 'PlayerController@linkGuestToFb');
    $app->post('player/authentication/email/linkToFb', 'PlayerController@linkEmailToFb');
    $app->put('player/{id}','PlayerController@updatePlayer');   
    $app->delete('player/{id}','PlayerController@deletePlayer');


    $app->get('player/lobbies', 'PlayerController@getLobbies');
    $app->get('player/stats', 'PlayerController@getStatus');

    $app->get('player/info', 'PlayerController@getInfo'); //includes player and playfab info and data
    $app->post('player/info', 'PlayerController@postInfo');
    $app->post('player/info/{type}', 'PlayerController@postInfo');
    $app->post('player/avatar', 'PlayerController@postAvatar');

    $app->post('player/report/{playerId}', 'PlayerController@postReport');
    $app->post('player/recover', 'PlayerController@postRecover');

    $app->post('player/push/send', 'PlayerController@sendNotification'); //send notification
    $app->post('player/push/setting', 'PlayerController@setNotification'); //set notification
    $app->post('player/activity', 'PlayerController@activity');
    $app->get('player/{playerId}', 'PlayerController@show');


    //LOBBIES
    $app->get('lobbies', 'LobbyController@index'); // list all available lobbies ordered by number of wins
    $app->get('lobbies/invitations', 'LobbyController@invitations'); //player gets lobbies, where he got invited

    $app->get('lobby/match', 'LobbyController@match'); //show a random match
    $app->get('lobby/{lobbyId}', 'LobbyController@show'); //show the detail of the lobby and the winner or current info

    $app->post('lobby/join', 'LobbyController@join'); //player joins the lobby, returns the lobby id for the player
    $app->post('lobby/{lobbyId}/invite/{playerId}', 'LobbyController@invite'); //invite player to match
    $app->post('lobby/{lobbyId}/accept', 'LobbyController@accept'); //removes acceptor lobby and added to player lobby
    $app->post('lobby/{lobbyId}/start', 'LobbyController@start'); //set start status for player and send push notification
    $app->post('lobby/{lobbyId}/complete', 'LobbyController@complete'); //first to send complete is the winner
    $app->post('lobby/{lobbyId}/leave', 'LobbyController@leave'); //leaves the lobby if engaged in a game, automatically lose the game

    // GAMES
    // $app->get('games', 'GameController@index');
    // $app->get('game/{type}', 'GameController@getData');
    // $app->post('game/{type}', 'GameController@postData');

    // $app->get('game/catalog', 'GameController@catalog');
    // $app->get('game/publisher', 'GameController@publisher');
    // $app->get('game/store', 'GameController@store');
    // $app->get('game/time', 'GameController@time');
    // $app->get('game/title', 'GameController@title');
    // $app->get('game/news', 'GameController@news');

    // FRIENDS
    $app->get('friends', 'FriendController@index'); //list all player friends including playfab friends, including pending
    $app->get('friend/{id}', 'FriendController@show');
    $app->get('friends/leaderboard', 'FriendController@getLeaderboard'); //get friend leaderboard
    $app->get('friends/pending', 'FriendController@getPending');

    $app->post('friend/{playerId}/invite', 'FriendController@invite'); //add friend with pending status
    $app->post('friend/{invitationId}/accept', 'FriendController@accept'); //accept friend
    $app->post('friend/{invitationId}/remove', 'FriendController@destroy'); //remove friend

    // SETTINGS
    $app->get('settings', 'SettingController@index');
    $app->get('setting/{settingId}', 'SettingController@show');
    $app->get('settings/country', 'SettingController@getSupportedCountries');
    $app->get('settings/{type}', 'SettingController@show');

    $app->get('payment/paypal', 'PaymentController@paypal');
    $app->get('payment/skrill', 'PaymentController@skrill');

    //VIRTUAL CURRENCIES
    $app->get('vcs', 'VirtualCurrencyController@index'); //get the player cash and chips data
    $app->get('vcs/{vcsId}', 'VirtualCurrencyController@show');
    $app->post('vcs/cash/add','VirtualCurrencyController@addCash');
    $app->post('vcs/cash/subtract','VirtualCurrencyController@subtractCash');
    $app->post('vcs/cash/reset','VirtualCurrencyController@resetCash');

    $app->post('vcs/chips/add','VirtualCurrencyController@addChips');
    $app->post('vcs/chips/subtract','VirtualCurrencyController@subtractChips');
    $app->post('vcs/chips/reset','VirtualCurrencyController@resetChips');
    $app->post('vcs/add/{amount}', 'VirtualCurrencyController@add');

    //ACTIVITIES
    $app->get('activities', 'ActivityController@index');
    $app->get('activity/{activityId}', 'ActivityController@show');

    //ASYNC
    $app->post('async/match', 'AsyncController@match');
    $app->post('async/abortedMatch', 'AsyncController@abortedMatch');
    $app->get('async/result/match/{matchId}', 'AsyncController@getResultByMatchId');
    $app->get('async/result/player/{playerId}', 'AsyncController@getResultByPlayerId');
    $app->post('async/createMatch', 'AsyncController@createMatch');
    $app->get('async/result/matches', 'AsyncController@getMatches');
    $app->post('async/deleteAll', 'AsyncController@deleteAll');

    //RATINGS
    $app->get('ratings/{playerId}', 'RatingsController@getRatingByPlayerId');
    $app->post('ratings/update', 'RatingsController@updateRating');

  
    //NOTIFICATIONS
    $app->get('notifications', 'NotificationController@index');
    $app->get('notification/{notificationId}', 'NotificationController@show');

    //COUNTRIES
    $app->get('countries', 'CountryController@index');
    $app->get('country/{countryId}', 'CountryController@show');

    //EULAS
    $app->get('eulas', 'EulaController@index');
    $app->get('eula/{eulaId}', 'EulaController@show');
    $app->post('eula/{eulaId}/agree', 'EulaController@agree');

    //USER
    $app->get('users', 'UserController@index');
    $app->get('user/{userId}', 'UserController@show');
    $app->post('authenticate', 'UserController@login');

    //REAL CASH
    $app->post('realCash/deposit/{amount}', 'RealCashController@deposit');
    $app->post('realCash/withrawal/{amount}', 'RealCashController@withraw');
    $app->post('realCash/enterRoom/{roomName}', 'RealCashController@enterRoom');
    $app->post('realCash/leaveRoom/{roomName}', 'RealCashController@leaveRoom');
    $app->post('realCash/matchResult', 'RealCashController@matchResult');
    $app->post('realCash/storeMatch', 'RealCashController@storeMatch');
    $app->post('virtualChips/enterRoom/{roomName}', 'VirtualCurrencyController@enterRoom');

});
