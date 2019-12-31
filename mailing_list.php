<?php
include ('./header.php');
require_once('./dao/customerDao.php');
require_once('WebsiteUser.php');
session_start();
session_regenerate_id(false);

echo '<div id="content" class="clearfix">';
if(isset($_SESSION['websiteUser'])){
    if(!$_SESSION['websiteUser']->isAuthenticated()){
       header('Location:login.php');
    }
} else {
    header('Location:login.php');
}
echo '<p><h5>Session ID: ' . session_id() . '</h5></p>';
echo '<p><h5>Session AdminID: ' . $_SESSION['websiteUser']->getID() . '</h5></p>';
echo '<p><h5>Last login date: ' . $_SESSION['websiteUser']->getDate() . '</h5></p>';
echo("<button onclick=\"location.href='logout.php'\">Logout!</button>");
echo '</div>';

$customerDao = new customerDao;

if((isset($_POST['delete']) || isset($_POST['update']) ) && empty($_POST["chosenCustomer"])){
  echo '<h3><span style=\'color:red\'>You must choose a customer</span></h3>';
}

if (isset($_POST['delete']) && !empty($_POST["chosenCustomer"])){
  $id = $_POST['chosenCustomer'];
  $customerDao->deleteCustomer($id);
  echo '<h3><span style=\'color:red\'>Customer Deleted</span></h3>';
}

if ((empty($_POST["customerName"]) || empty($_POST["phoneNumber"]) || empty($_POST["emailAddress"])) && isset($_POST['updateinfo']) ){
  echo '<h3><span style=\'color:red\'>Could Not update customer: Some fields are missing</span></h3>';

}

if (isset($_POST['updateinfo']) && !empty($_POST["customerName"]) && !empty($_POST["phoneNumber"]) && !empty($_POST["emailAddress"])) {
  $updateid = $_POST['updatecustomerid'];
  $updatedname = $_POST['customerName'];
  $updatedphone= $_POST['phoneNumber'];
  $updatedemail = $_POST['emailAddress'];
  $updatedref = $_POST['referral'];
  $customerDao->editCustomer($updateid, $updatedname, $updatedphone, $updatedemail, $updatedref);
  echo '<h3><span style=\'color:red\'>Customer '. $updatedname . ' updated</span></h3>';
}

$customers=$customerDao->getCustomers();

echo '<div id="content" class="clearfix">';
if ($customers){
    echo '<form name="Delete" method="post" action="mailing_list.php">';

    echo '<table width="100%" height="100%" border="2">';
                echo '<tr><th>Select</th><th>Customer</th> <th>Phone</th> <th>EamilAddress</th> <th>referral</th></tr>';
                $ID = $customerDao->getID();
                $i=0;
                foreach($customers as $customer){
                    echo '<tr>';
                   // echo '<td>' . $ID[$i] . '</td>';
                  //  echo '<td><a href=\'edit_employee.php?employeeId='. $ID . '\'>' . $ID . '</a></td>';


                    echo '<td>' . '<input type="radio" name="chosenCustomer" id="chosenCustomer" value="' . $ID[$i] . '"> '. '</td>';
                    echo '<td>' . $customer->getName() . '</td>';
                    echo '<td>' . $customer->getPhone() . '</td>';
                    echo '<td>' . $customer->getEmail() . '</td>';
                    echo '<td>' . $customer->getReferrer() . '</td>';
                    echo '</tr>';
                    $i++;
                }
                echo '</table>';
                echo '<tr>
                    <input type="submit" name="delete" id="delete" value="Delete Customer">&nbsp;&nbsp;
                    <input type="submit" name="update" id="update" value="Update Customer">&nbsp;&nbsp;
                    <input type="reset" name="btnReset" id="btnReset" value="Reset">
                </tr>';
                echo '</form>';
}else{
    echo '<h3>'.'No mailing exist now'.'</h3>';
}


if (isset($_POST['update']) && !empty($_POST["chosenCustomer"])){
  $id = $_POST['chosenCustomer'];
  $customer=$customerDao->getCustomer($id);
if($customer){
?>

  <form name="UpdateEmp" id="UpdateEmp" method="post" action="mailing_list.php">
      <table>
        <tr>
            <td><input type="hidden" name="updatecustomerid" id="updatecustomerid" value="<?php  echo $id;  ?>" size='40'></td>
        </tr>
        <tr>
            <td>Name:</td>
            <td><input type="text" name="customerName" id="customerName" value="<?php  echo $customer->getName();  ?>" size='40'></td>

        </tr>
        <tr>
            <td>Phone Number:</td>
            <td><input type="text" name="phoneNumber" id="phoneNumber" value="<?php  echo $customer->getPhone();  ?>" size='40'></td>

        </tr>
        <tr>
            <td>Email Address:</td>
            <td><input type="text" name="emailAddress" id="emailAddress"  disabled value="<?php  echo $customer->getEmail();  ?>" size='40'>

        </tr>
        <tr>
            <td>How did you hear<br> about us?</td>
            <td>Newspaper<input type="radio" name="referral" id="referralNewspaper" value="newspaper"   <?php if($customer->getReferrer() == 'newspaper'){echo "checked";}?>>
                Radio<input type="radio" name='referral' id='referralRadio' value='radio' <?php if($customer->getReferrer() == 'radio'){echo "checked";}?>>
                TV<input type='radio' name='referral' id='referralTV' value='TV' <?php if($customer->getReferrer() == 'TV'){echo "checked";}?>>
                Other<input type='radio' name='referral' id='referralOther' value='other' <?php if($customer->getReferrer() == 'other'){echo "checked";}?>>
           </td>
        </tr>
        <tr>
            <td colspan='2'><input type='submit' name='updateinfo' id='updateinfo' value='Update info'></td>
        </tr>
      </table>

<?php }} ?>





<?php


echo '</div>';
include ('./footer.php');

?>
