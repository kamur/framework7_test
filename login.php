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

      <div class="view view-main ios-edges">

        <div class="page" data-name="login">

          <!-- Top Navbar -->
          <div class="navbar">
            <div class="navbar-inner">
              <div class="title sliding">My App</div>
            </div>
          </div>

          <div class="page-content login-screen-content">
            <div class="login-screen-title">Login</div>
            <div class="list">
              <ul>
                <li class="item-content item-input">
                  <div class="item-inner">
                    <div class="item-title item-label">Email</div>
                    <div class="item-input-wrap">
                      <input type="email" name="email" placeholder="Your email" required validate data-error-message="Insert e valid email">
                    </div>
                  </div>
                </li>
                <li class="item-content item-input">
                  <div class="item-inner">
                    <div class="item-title item-label">Password</div>
                    <div class="item-input-wrap">
                      <input type="password" name="password" placeholder="Your password" required validate data-error-message="Insert e password">
                    </div>
                  </div>
                </li>
              </ul>
            </div>
            <div class="list">
              <ul>
                <li>
                  <a href="#" id="login-button" class="item-link list-button">Login</a>
                </li>
              </ul>
              <div class="block-footer">Click <a href="#" class="login-screen-open" data-login-screen="#my-register-screen">here</a> to new registration</div>
            </div>
          </div>

        </div><!-- page -->

      </div><!-- view view-main ios-edges -->

    </div><!-- #app -->

    <div class="login-screen" id="my-register-screen" style="display: none;">
      <div class="view">
        <div class="page">
          <div class="page-content login-screen-content">
            <a class="login-screen-close" href="#" style="position: absolute; top: 20px; right: 20px;"><i class="icon material-icons md-only">clear</i></a>
            <div class="login-screen-title">Register</div>
            <div class="list">
              <ul>
                <li class="item-content item-input">
                  <div class="item-inner">
                    <div class="item-title item-label">Email</div>
                    <div class="item-input-wrap">
                      <input type="text" name="email" placeholder="Your email">
                    </div>
                  </div>
                </li>
                <li class="item-content item-input">
                  <div class="item-inner">
                    <div class="item-title item-label">Password</div>
                    <div class="item-input-wrap">
                      <input type="password" name="password" placeholder="Your password">
                    </div>
                  </div>
                </li>
              </ul>
            </div>
            <div class="list">
              <ul>
                <li>
                  <a href="#" id="register-button" class="item-link list-button">Sign In</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script type="text/javascript">
      window.prefix_url = "<?php if(isset($_SERVER['CONTEXT_PREFIX'])) echo $_SERVER['CONTEXT_PREFIX']; ?>";
    </script>
    <script src="framework7/js/framework7.min.js"></script>
    <script src="js/routes.js"></script>
    <script src="js/my-app.js"></script>

  </body>
</html>