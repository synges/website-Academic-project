<?php $page_title = "Contact Us"; ?>


<?php
include ('./header.php');
require_once('./dao/customerDao.php');

   try{
     $customerDao = new customerDao;
     $haserror = false;
     $errormessage = Array();


      if(isset($_POST['customerName'])||
         isset($_POST['phoneNumber'])||
         isset($_POST['emailAddress'])||
         isset($_POST['referral'])){


        if($_POST['customerName']==""){
            $haserror = true;
            $errormessage['customerName'] = "Please enter a name";
        }

        if(!preg_match("/[0-9]{3}-[0-9]{3}-[0-9]{4}$/i", $_POST ['phoneNumber']) ){
            $haserror = true;
            $errormessage['phoneNumber'] = "Please enter an appropriate phone number";
        }

        if (empty($_POST["phoneNumber"])){
            $haserror = true;
            $errormessage['phoneNumber'] = "Please enter a phone number";
        }

        if (!preg_match("/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}$/i",$_POST ['emailAddress'])){
            $haserror = true;
            $errormessage['emailAddress'] = "Please enter a valid email address";
        }



        if (empty($_POST ['emailAddress'])){
            $haserror = true;
            $errormessage['emailAddress'] = "Please enter an email address";
        }



        if($customerDao->duplicateEmail($_POST['emailAddress'])){
            $haserror = true;
            $errormessage['emailAddress'] = "Duplicate Email Address.";
        }

        if (empty($_POST["referral"])) {
            $haserror = true;
            $errormessage['referral'] = "Referral is required";
        }


        if(!$haserror){
            $email = $_POST['emailAddress'];
            $hash = password_hash($email, PASSWORD_DEFAULT);
            $customer = new customer($_POST['customerName'],$_POST['phoneNumber'],$hash,$_POST['referral']);
            $addSuccess = $customerDao->addCustomer($customer);

            if(isset($_FILES['fileUpload'])){
              $fileTmpPath = $_FILES['fileUpload']['tmp_name'];
              $fileName = $_FILES['fileUpload']['name'];
              $fileSize = $_FILES['fileUpload']['size'];
              $fileType = $_FILES['fileUpload']['type'];
              $path = 'files/';
              $upload_file = $path.basename($fileName);

              if(move_uploaded_file($fileTmpPath, $upload_file))
                {
                  $message ='File is successfully uploaded.';
                }

            }
        }
    }


?>
            <div id="content" class="clearfix">
                <aside>
                        <h2>Mailing Address</h2>
                        <h3>1385 Woodroffe Ave<br>
                            Ottawa, ON K4C1A4</h3>
                        <h2>Phone Number</h2>
                        <h3>(613)727-4723</h3>
                        <h2>Fax Number</h2>
                        <h3>(613)555-1212</h3>
                        <h2>Email Address</h2>
                        <h3>info@wpeatery.com</h3>
                </aside>
                <div class="main">
                    <h1>Sign up for our newsletter</h1>
                    <p>Please fill out the following form to be kept up to date with news, specials, and promotions from the WP eatery!</p>
                    <?php
                    if(isset($addSuccess)){
                       echo '<h3><span style=\'color:red\'>'.$addSuccess.'</span></h3>';
                    }
                    if(isset($message)){
                       echo '<h3><span style=\'color:red\'>'.$message.'</span></h3>';
                    }
                    ?>
                    <form name="frmNewsletter" id="frmNewsletter" method="post" action="contact.php" enctype="multipart/form-data">
                        <table>
                            <tr>
                                <td>Name:</td>
                                <td><input type="text" name="customerName" id="customerName" placeholder="Customer Name" size='40'></td>
                                <?php
                               if(isset($errormessage['customerName'])){
                                   echo '<td><span style=\'color:red\'>'.$errormessage['customerName'].'</span></td>';
                               }
                               ?>
                            </tr>
                            <tr>
                                <td>Phone Number:</td>
                                <td><input type="text" name="phoneNumber" id="phoneNumber" placeholder="888-888-8888" size='40'></td>
                                <?php
                                if(isset($errormessage['phoneNumber'])){
                                    echo '<td><span style=\'color:red\'>'.$errormessage['phoneNumber'].'</span></td>';
                                }
                                ?>
                            </tr>
                            <tr>
                                <td>Email Address:</td>
                                <td><input type="text" name="emailAddress" id="emailAddress" placeholder="Example@domain.com" size='40'>
                                <?php
                                if(isset($errormessage['emailAddress'])){
                                    echo '<td><span style=\'color:red\'>'.$errormessage['emailAddress'].'</span></td> ';
                                }
                                ?>
                            </tr>
                            <tr>
                                <td>How did you hear<br> about us?</td>
                                <td>Newspaper<input type="radio" name="referral" id="referralNewspaper" value="newspaper">
                                    Radio<input type="radio" name='referral' id='referralRadio' value='radio'>
                                    TV<input type='radio' name='referral' id='referralTV' value='TV'>
                                    Other<input type='radio' name='referral' id='referralOther' value='other'>
                              <?php
                                if(isset($errormessage['referral'])){
                                echo '<td><span style=\'color:red\'>' . $errormessage['referral'] . '</span></td>';
                              }
                              ?> </td>
                            </tr>

                            <tr><!--File Upload form-->
                                <td>Choose a file to upload:</td>
                                <td><input type="file" name="fileUpload" id="fileUpload">
                            </tr>

                            <tr>
                                <td colspan='2'><input type='submit' name='btnSubmit' id='btnSubmit' value='Sign up!'>&nbsp;&nbsp;<input type='reset' name="btnReset" id="btnReset" value="Reset Form"></td>
                            </tr>

                        </table>
                    </form>
                </div><!-- End Main -->
            </div><!-- End Content -->

<?php

}catch(Exception $e){
echo '<h3>Error on page.</h3>';
echo '<p>' . $e->getMessage() . '</p>';
}

?>
<?php include("footer.php"); ?>
