<?php
require_once "actions/cfg.php"; 

?>

<html lang="fr">
  <head>
        <meta charset="utf-8">
         
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Rajdhani&family=Raleway:wght@200;400&family=Sofia+Sans+Condensed:wght@300&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@200;400&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
        
        <!-- CSS -->
        <link type="text/css" rel="stylesheet" href="css/materialize.css?v=<?php echo rand(0,99999); ?>"  media="screen,projection"/>
        <link rel="stylesheet" href="css/styles.css?v=<?php echo rand(0,99999); ?>">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

  </head>
  <body>

        <h1 class='light-title'> Users management <a onclick="darkmode()"> <i class="material-icons right" id="icon_darkmode">bedtime</i></a></h1>
        <h1 class='dark-title'> Que la Force soit avec Toi ! <a onclick="darkmode()"> <i class="material-icons right" id="icon_darkmode">bedtime</i></a></h1>
    
        <!-- Add Users -->

        <h5 class='user-title'> Add user </h5>
        <form method="post" action="actions/add_users.php">
            <div class="row">
                <div class="input-field col l4">
                    <div>
                        <label for="UID" class='uid' >UID</label>
                        <input id="UID" name="UID">
                    </div>
                    <div>
                        <label for="Access" class='access'>Access</label>
                        <input id="Access" name="Access">
                    </div>
                    <div class="input-field">
                        <button class="btn modal-close" id='button'>Send</button>
                    </div>
                </div>
            </div>
        </form>

        <?php
        $sql = "SELECT * FROM users"; 
        $pre = $pdo->prepare($sql);
        $pre->execute();
        $data = $pre->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <!-- Switch Access-->
        <div class='users'>
            <h5 class='user-title'> Existing users </h5>
            <div class="row">
                <?php foreach($data as $users){ ?>
                <div class="col m2">
                    <div class="card">
                        <div class="card-content white-text">
                            <h6 class='center'><?php echo $users['UID']; print(" ")?></h6>
                            <?php if ($users['Access']==0){?>
                                <h6 class='center'>access denied</h6>
                            <?php }else{?>
                                <h6 class='center'>access granted</h6>
                            <?php } ?>
                            <div class='row'>
                                <div class='col m6'>
                                    <form action="actions/switch_access.php" method="post" class='change'>
                                        <input type="hidden" name="UID" value="<?php echo $users['UID'] ?>">
                                        <button type="submit" name="button" class='btn post'>Switch</button>
                                    </form>
                                </div>
                                <div class='col m6'>
                                    <form action="actions/delete_user.php" method="post" class='change'>
                                        <input type="hidden" name="ID" value="<?php echo $users['ID'] ?>">
                                        <button type="submit" name="button" class='btn post'>Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
            
        </div>
        

        <!--JavaScript at end of body for optimized loading-->
        <script src="js/jquery.min.js?v=<?php echo rand(0,99999); ?>" charset="utf-8"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script type="text/javascript" src="js/materialize.min.js?v=<?php echo rand(0,99999); ?>"></script>
        <script src="js/script.js?v=<?php echo rand(0,99999); ?>" charset="utf-8"></script>
    </body>
 </html>