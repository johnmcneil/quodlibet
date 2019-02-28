<?php
$error ='';
if(isset($_POST['login'])) {
    session_start();
    ob_start();
    $username = $_POST['username'];
    $password = $_POST['pwd'];
    //location of usernames and passwords
    $userlist = '../private/users.csv';
    //location of redirect on success
    $redirect = 'http://qomb.net/admin.php';
    require_once 'includes/authenticate.php';
}


$pageTitle = "Qomb Login";
include('includes/header.php');

if($error) {
    echo "<p>$error</p>";
}
?>

<div id="qomb-login">

<h2>Qomb login:</h2>

<form method="post" action="">
    <p>
        <label for="username">Username:</label>
        <input type="text" name="username" id="username">
    </p>
    <p>
        <label for="pwd">Password:</label>
        <input type="password" name="pwd" id="pwd">
    </p>
    <p>
        <input name="login" type="submit" value="Log in">
    </p>
</form>

</div>

<?php include('includes/footer.php'); ?>