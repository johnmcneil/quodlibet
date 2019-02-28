<?php // redirect if not authenticated
session_start();
// if session variable not set, redirect to login page
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] != 'messmorebreamworthy') {
    header('Location: http://qomb.net/login.php');
    exit;
}
?>


<?php // insert new record when form submitted.
require_once 'includes/connection.php';
// initialize flags

if (isset($_POST['insert'])) {
    $OK = false;
    $done = false;
    // create database connection
    $conn = dbConnect('write');
    //initialize statement
    $stmt = $conn->stmt_init();
    
    //create SQL stating the column names, but the values are question marks for now.
    $sql = 'INSERT INTO quotation (AuthorFirst, AuthorLast, Quotation, Attribution, Subject1, Subject2, Subject3, DisplayOrder, Display, Theme, ShortAttrib)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                    
    //add sql to the prepared statement
    if ($stmt->prepare($sql)) {
    //bind parameters and execute statement
    //first value matches data types
    $stmt->bind_param('sssssssiiss',
        $_POST['AuthorFirst'],
        $_POST['AuthorLast'],
        $_POST['Quotation'],
        $_POST['Attribution'],
        $_POST['Subject1'],
        $_POST['Subject2'],
        $_POST['Subject3'],
        $_POST['DisplayOrder'],
        $_POST['Display'],
        $_POST['Theme'],
        $_POST['ShortAttrib']);
        $stmt->execute();
        if ($stmt->affected_rows>0) {
            //set flag to true and page is secure from SQL injection
            $OK = true;
        }
    }
    
    //redirect if successful or display error
    if ($OK) {
        header('Location: http://www.qomb.net/index.php');
        exit;
    } else {
        $error = $stmt->error;
        echo $error;
    }
}
?>

<?php
    $pageTitle = "Qomb Admin";
    include('includes/header.php');
?>

<div id="admin-page">

<h1 id="admin-title">Qomb Admin</h1>

<input type="button" value="Insert new quotation" id="insert-button" />

<?php include('includes/logout.php'); ?>

<div class="container">  

<div id="insert" class="row" ng-app="">
    <form id="insert" method="post" action="admin.php" class="col-xs-12 col-sm-6">
        <h2>Insert new quotation:</h2>
        <p>
            <label for="AuthorFirst">Author First Name:</label>
            <input name="AuthorFirst" type="text" id="AuthorFirst" size="20" ng-model="AuthorFirst" />
        </p>
        <p>
            <label for="AuthorLast">Author Last Name:</label>
            <input name="AuthorLast" type="text" id=AuthorLast" size="20" ng-model="AuthorLast" />
        </p>
        <p>
            <label for="Quotation">Quotation:</label>
            <textarea name="Quotation" id="Quotation" rows="10" cols="75" ng-model="Quotation"></textarea>
        </p>
        <p>
            <label for="Attribution">Attribution:</label>
            <textarea name="Attribution" id="Attribution" rows="3" cols="75" ng-model="Attribution"></textarea>
        </p>
        <p>
            <label for="Subject1">Subject1:</label>
            <input name="Subject1" type="text" id="Subject1" size="20" ng-model="Subject1" />
        </p>
        <p>
            <label for="Subject2">Subject2:</label>
            <input name="Subject2" type="text" id="Subject2" size="20" ng-model="Subject2" />
        </p>
        <p>
            <label for="Subject3">Subject3:</label>
            <input name="Subject3" type="text" id="Subject3" size="20" ng-model="Subject3" />
        </p>
        <p>
            <label for="DisplayOrder">Display Order:</label>
            <input name="DisplayOrder" type="number" id="DisplayOrder" min="1" max="10" ng-model="DisplayOrder" />
        </p>
        <p>
            <label for="Display">Display:</label>
            <input name="Display" id="Display-true" type="radio" value="1" checked/>True
            <input name="Display" id="Display-false" type="radio" value="0" />False
        </p>
        <p>
            <label for="Theme">Theme:</label>
            <input name="Theme" type="text" id="Theme" size="20" ng-model="Theme" />
        </p>
        <p>
            <label for="ShortAttrib">Short Attribution:</label>
            <input name="ShortAttrib" type="text" id="ShortAttrib" size="60" ng-model="ShortAttrib" />
        </p>
        <p>
            <input type="submit" id="insert-submit" name="insert" value="Submit" />
        </p>
    </form>
    <div id="preview" class="col-xs-12 col-sm-6">
        <div id="preview2">
            <h2 id="preview-title">Preview</h2>
            <h2 ng-bind="Theme" class="theme-title"></h2>
            <blockquote>
                <p ng-bind="Quotation"></p>
                <footer class="attribution">
                    <span ng-bind="ShortAttrib" class="short-attribution full-attribution-click-me"></span>
                    <p ng-bind="Attribution" class="full-attribution"></p>
                </footer>
            </blockquote>
        </div>
    </div>
</div>

</div> <!--end bootstrap container -->

<div id="edit">

<h2>Edit Panel</h2>
<?php // select data for edit panel.
require_once 'includes/connection.php';

$conn = dbConnect('read');
        
$sql = "SELECT * FROM quotation";
    
$result = $conn->query($sql);
if(!$result) {
    $error = $conn->error;
} else {
    $numRows = $result->num_rows;
}

if (isset($error)) {
    echo "<p>$error</p>";
} else {
    echo "<p>A total of $numRows records were found.</p>";
}
?>


<table id="edit-panel" class="table-striped">
    <tr>
        <th>QuotationID</th>
        <th>AuthorLast</th>
        <th>Quotation</th>
        <th>DisplayOrder</th>
        <th>Display</th>
        <th>Theme</th>
        <th>ShortAttrib</th>
        <th>&nbsp;</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?= $row['QuotationID']; ?></td>
        <td><?= $row['AuthorLast']; ?></td>
        <td><?= substr($row['Quotation'], 0, 20); ?></td>
        <td><?= $row['DisplayOrder']; ?></td>
        <td><?= $row['Display']; ?></td>
        <td><?= $row['Theme']; ?></td>
        <td><?= substr($row['ShortAttrib'], 0, 25); ?></td>
        <td><a href="edit.php?QuotationID=<?= $row['QuotationID']; ?>">Edit</a></td>
    </tr>
    <?php } ?>
</table>

</div> <!-- end div id="edit" -->

</div>

<?php
    include('includes/footer.php');
?>