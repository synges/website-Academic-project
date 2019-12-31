<?php
    require_once('WebsiteUser.php');
    session_start();
    if(isset($_SESSION['websiteUser'])){
        if($_SESSION['websiteUser']->isAuthenticated()){
            session_write_close();
            header('Location:mailing_list.php');
        }
    }
    $missingpass = false;
    $missinguser = false;
    if(isset($_POST['submit'])){
        if(isset($_POST['username']) && isset($_POST['password'])){
            if($_POST['username'] == "") {
              $missinguser = true;
            }
            if ($_POST['password'] == "") {
              $missingpass = true;
            }
            if ($_POST['username'] !== "" && $_POST['password'] !== "") {
                //All fields set, fields have a value
                $websiteUser = new WebsiteUser();
                if(!$websiteUser->hasDbError()){
                    $username = $_POST['username'];
                    $password = $_POST['password'];
                    $websiteUser->authenticate($username, $password);
                    if($websiteUser->isAuthenticated()){
                        $_SESSION['websiteUser'] = $websiteUser;
                        header('Location:mailing_list.php');
                    }
                }
            }
        }
    }
?>
        <?php
        include ('./header.php');


            //Authentication failed
            if(isset($websiteUser)){
                if(!$websiteUser->isAuthenticated()){
                    echo '<h3 style="color:red;">Login failed. Please try again.</h3>';
                }
            }
        ?>
        <div id="content" class="clearfix">
        <form name="login" id="login" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
        <table>
            <tr>
                <td>Username:</td>
                <td><input type="text" name="username" id="username"></td>
                <?php
                if($missinguser){
                    echo '<td><span style=\'color:red\'>Please enter a username</span></td> ';
                }
                ?>
            </tr>
            <tr>
                <td>Password:</td>
                <td><input type="password" name="password" id="password"></td>
                <?php
                if($missingpass){
                    echo '<td><span style=\'color:red\'>Please enter a password</span></td> ';
                }
                ?>
            </tr>
            <tr>
                <td><input type="submit" name="submit" id="submit" value="Login"></td>
            </tr>
        </table>
        </form>
        </div>
<?php include ('./footer.php'); ?>
