<?php

namespace App;

class PlayFab
{
  private $playfab_uri = 'https://8C68.playfabapi.com/';
  private $playfab_id = '8C68';
  private $secret_key = 'YT8WJQASU5XCZEQWMKQSX5NPBGSPNOPHCFBGIBXQGMOCJICZIQ';
  private $publisher_id = 'F81C550E73BEB380';
  private $session_key = '';
  private $currency = 'VC';

  public $photon_id = '4e4d14f8-9970-433c-88ae-21a9d32f6955';
  public $player_combined_info = [
    'InfoRequestParameters'=>[
      'GetUserAccountInfo'=>TRUE,
      'GetUserInventory'=>TRUE,
      'GetUserVirtualCurrency'=>TRUE,
      'GetUserData'=>TRUE,
      'UserDataKeys'=>TRUE,
      'GetUserReadOnlyData'=>FALSE,
      'GetCharacterInventories'=>FALSE,
      'GetCharacterList'=>FALSE,
      'GetTitleData'=>TRUE,
      'GetPlayerStatistics'=>TRUE
    ]
  ];

  public function getSession() {
    $this->session_key = session('playfab_session_id');
  }

  public function setSession($session_key) {
    $this->session_key = $session_key;
    // session(['playfab_session_id' => $session_key]);
  }

  public function getId() {
    return '8C68';
  }

  public function getSecretKey() {
    return $this->secret_key;
  }

  public function getChip() {
    return $this->currency;
  }

  public function request($url = '', $payload = [], $headers = []) {
    $defheaders = ['Content-Type: application/json'];
    $ch = curl_init($this->playfab_uri . $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge($defheaders, $headers));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    return json_decode($result);
  }

  public function client($url = '', $payload = []) {
    return $this->request("Client/{$url}", $payload, [
      "X-Authentication:{$this->session_key}"
    ]);
  }

  public function server($url = '', $payload = []) {
    return $this->request("Server/{$url}", $payload, [
      "X-SecretKey:{$this->secret_key}"
    ]);
  }

  public function admin($url = '', $payload = []) {
    return $this->request("Admin/{$url}", $payload, [
      "X-SecretKey:{$this->secret_key}"
    ]);
  }

}
