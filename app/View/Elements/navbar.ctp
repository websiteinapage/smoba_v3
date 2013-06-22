      <style>
          #navbar {
              background: #f7f7f7;
              width: 100%;
              position: fixed;
              z-index: 5;
              border-bottom: 1px solid #ccc;
          }
          #jMenu li a {
              color: #2a2a2a;
              text-decoration: none;
          }
          .sub-menu li a {
              color: #fff;
          }
          #jMenu li a:hover, .sub-menu li a:hover {
              text-decoration: none;
              color: #fff;
          }
          #jMenu li {
              min-width: 120px;
              background: #f7f7f7;
              text-align: center;
          }
          .sub-menu {
              background: #006699;
          }
          .sub-menu li:hover, #jMenu li:hover {
              background: #006699;
              color: #fff;
              text-decoration: none;
          }
      </style>
      <script>
      $(function() {
          var SubMenuItems = $('.sub-menu li');
          $('#jMenu li a').mouseover(function() {
              $(this).css({
                  color: "#fff"
              });
              SubMenuItems.css({
                 background: "#006699"
              });
              SubMenuItems.find("a").css({
                  color: "#fff"
              })
          })
          .mouseout(function() {
              $(this).css({
                  color: "#2a2a2a"
              })
          });
          SubMenuItems.mouseover(function() {
             var parent = $(this).parent().parent();
             parent.find("a").css({
                 'color': "#fff"
             });
          })
          .mouseout(function() {
             var parent = $(this).parent().parent();
             parent.find("a").css({
                 'color': "#2a2a2a"
             });
          });
      })
      </script>
      <div id="navbar">
        <ul id="jMenu" data-role="none">
            <?php 
            if (preg_match("/index.php/",$_SERVER['PHP_SELF'])) {
                ?>
              <li class="col_1"><a id="ToggleTweets" data-rel="external" data-ajax="false" class="fNiv">TOGGLE TWEETS</a></li>
                    <?php
            } else {
                if (empty($_SESSION['LOCAL_TOKEN'])) {
                    ?>
              <li class="col_1"><a id="ToggleTweets" href="index.php?job=logout" data-rel="external" data-ajax="false" class="fNiv">HOME</a></li>
                        <?php
                } 
            } ?>
            <li class="col_3"><a href="yrbk.php" data-rel="external" data-ajax="false" >YEARBOOK</a></li>
                <?php

            if (!empty($authUser)) {
                ?>
                  <li class="col_2"><a href="activity.php" data-rel="external" data-ajax="false" >ACTIVITY</a></li>
                  <li class="col_4"><a href="#" data-rel="external" data-ajax="false" >SETTINGS</a>
                      <ul class="sub-menu">
                          <li><a href="settings.php" data-rel="external" data-ajax="false">Personal Settings</a></li>
                      </ul>
                  </li>
                <li class="col_4"><?php echo $this->Html->link("LOGOUT", array("controller"=>"users", "action"=>"logout")); ?></li>
                    <?php
            } else {
            ?>
            <li class="col_4"><a href="register.php" data-rel="external" data-ajax="false" >REGISTER</a></li>
          <?php
            }
            ?>
        </ul>
      </div>
