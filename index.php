<?php
  include_once($_SERVER['DOCUMENT_ROOT']."/lib/config.php");
  include_once($_SERVER['DOCUMENT_ROOT']."/lib/auth.lib.php");

  global $_CONFIG, $isConnect, $isLocal, $original_user;
  $isLocal = $_SERVER['SERVER_ADDR']=="127.0.0.1" ? true : false;
  $auth_status = auth_get_status();
  if($auth_status[0]==AUTH_LOGGED){
    list($status, $user) = $auth_status;
  } else {
    $status = AUTH_NOT_LOGGED;
    $user = null;
  }
  $isConnect = $status;
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Security-Policy" content="default-src * 'self' 'unsafe-inline' 'unsafe-eval' data: gap: content:">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui, viewport-fit=cover">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="theme-color" content="#2196f3">
    <meta name="format-detection" content="telephone=no">
    <meta name="msapplication-tap-highlight" content="no">

    <title>My App</title>
    <link rel="stylesheet" href="framework7/css/framework7.min.css">
    <link rel="stylesheet" href="css/icons.css">
    <link rel="stylesheet" href="css/my-app.css">
  </head>

  <body>

    <div id="app">

      <div class="statusbar"></div>

      <div class="panel panel-right panel-reveal theme-dark">
        <div class="view">
          <div class="page">
            <div class="navbar">
              <div class="navbar-inner">
                <div class="title">Right Panel</div>
              </div>
            </div>
            <div class="page-content">
              <div class="block"></div>
            </div>
          </div>
        </div>
      </div>

      <div class="view view-main ios-edges">

        <div class="page" data-name="index">

          <!-- Top Navbar -->
          <div class="navbar">
            <div class="navbar-inner">
              <div class="title sliding">My App</div>
              <div class="right">
                <a href="#" class="link icon-only panel-open" data-panel="right">
                  <i class="icon f7-icons ios-only">menu</i>
                  <i class="icon material-icons md-only">menu</i>
                </a>
              </div>
            </div>
          </div>

          <div class="page-content">

            <div class="block content-square-buttons">
              <div class="row">
                <div class="col-50">
                  <a href="/match/" class="button button-raised button-fill"><span>Start Match</span></a>
                </div>
                <div class="col-50">
                  <a href="#" class="button button-raised button-fill"><span>Load Match</span></a>
                </div>
              </div>
              <div class="row">
                <div class="col-50">
                  <a href="#" class="button button-raised button-fill"><span>Player's Statistics</span></a>
                </div>
                <div class="col-50">
                  <a href="#" class="button button-raised button-fill"><span>Team's Statistics</span></a>
                </div>
              </div>
              <div class="row">
                <div class="col-50">
                  <a href="/management/" class="button button-raised button-fill"><span>Team Management</span></a>
                </div>
                <div class="col-50">
                  <a href="/settings/" class="button button-raised button-fill"><span>Settings</span></a>
                </div>
              </div>
            </div>

            <div class="toolbar">
              <div class="toolbar-inner">
                <a href="#" class="link premium">Premium</a>
              </div>
            </div>

          </div> <!-- End .page-content -->

        </div>
      </div>
    </div>

    <script src="framework7/js/framework7.min.js"></script>
    <script src="js/routes.js"></script>
    <script src="js/my-app.js"></script>
  </body>
</html>