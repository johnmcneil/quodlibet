<?php // database connection
require_once 'includes/connection.php';
//initialize flags
$OK = false;
$done = false;
//create database connection
$conn = dbConnect('write');
// initialize statement
$stmt = $conn->stmt_init();
// get details of selected record
if (isset($_GET['QuotationID']) && !$_POST) {
    // prepare SQL query
    $sql =  'SELECT *
            FROM quotation WHERE QuotationID = ?';
    if ($stmt->prepare($sql)) {
        // bind the query parameter
        $stmt->bind_param('i', $_GET['QuotationID']);
        // execute the query and fetch the result
        $OK = $stmt->execute();
        // bind the results to variables
        $stmt->bind_result($QuotationID, $AuthorFirst, $AuthorLast, $Quotation, $Attribution,
                           $Subject1, $Subject2, $Subject3, $DisplayOrder, $Display, $Theme, $ShortAttrib);
        $stmt->fetch();
    }
}

// if form has been submitted, update record
if (isset($_POST['update'])) {
    // prepare update query
    $sql = 'UPDATE quotation
            SET AuthorFirst = ?,
                AuthorLast = ?,
                Quotation = ?,
                Attribution = ?,
                Subject1 = ?,
                Subject2 = ?,
                Subject3 = ?,
                DisplayOrder = ?,
                Display = ?,
                Theme = ?,
                ShortAttrib = ?
            WHERE QuotationID = ?';
    if ($stmt->prepare($sql)) {
        $stmt->bind_param('sssssssiissi',
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
            $_POST['ShortAttrib'],
            $_POST['QuotationID']);
        $done = $stmt->execute();
        }
    }


// redirect to homepage if $_GET['article_id] not defined
if (!isset($_GET['QuotationID'])) {
    header('Location: http://qomb.net/');
    exit;
}

// redirect to admin page if edit was successsful
if ($done) {
    header('Location: http://qomb.net/admin.php');
    exit;
}
// get error message if query fails
if (isset($stmt) && !$OK && !$done) {
    $error = $stmt->error;
}
?>


<?php // header
$pageTitle = "Qomb edit";
include('includes/header.php');
?>


<div id="edit-page">

<h2>Edit Quotation</h2>

<?php if (isset($error)) {
    echo "<p class='warning'>Error: $error</p>";
}
if($QuotationID == 0) { ?>
    <p class="warning">Invalid request: record does not exist.</p>
<?php } else { ?>
    <form name="from1" method="post" action="">
        <input name="QuotationID" type="hidden" value="<?= $QuotationID; ?>" />
        <p>
            <label for="AuthorFirst">Author First Name:</label>
            <input name="AuthorFirst" type="text"
                   id="AuthorFirst" size="20" value="<?= htmlentities($AuthorFirst);?>" />
        </p>
        <p>
            <label for="AuthorLast">Author Last Name:</label>
            <input name="AuthorLast" id="AuthorLast" size="20" value="<?= htmlentities($AuthorLast);?>" />
        </p>   
        <p>
            <label for="Quotation">Quotation:</label>
            <textarea name="Quotation" id="Quotation" rows="10" cols="75" ><?= htmlentities($Quotation);?></textarea>
        </p>
        <p>
            <label for="Attribution">Attribution:</label>
            <input name="Attribution" type="text" id="Attribution" rows="3" cols="75" value="<?= htmlentities($Attribution)?>" />
        </p>
        <p>
            <label for="Subject1">Subject1:</label>
            <input name="Subject1" type="text" id="Subject1" size="20" value="<?= htmlentities($Subject1); ?>" />
        </p>
        <p>
            <label for="Subject2">Subject2:</label>
            <input name="Subject2" type="text" id="Subject2" size="20" value="<?= htmlentities($Subject2); ?>" />
        </p>
        <p>
            <label for="Subject3">Subject3:</label>
            <input name="Subject3" type="text" id="Subject3" size="20" value="<?= htmlentities($Subject3); ?>" />
        </p>
        <p>
            <label for="DisplayOrder">Display Order:</label>
            <input name="DisplayOrder" type="number" id="DisplayOrder" min="1" max="10" value="<?= htmlentities($DisplayOrder); ?>" />
        </p>
        <p>
            <label for="Display">Display:</label>
            <input name="Display" id="Display-true" type="radio" value="1" <?php if ($Display == 1) {echo ' checked';} else {} ?> />True
            <input name="Display" id="Display-false" type="radio" value="0" <?php if ($Display == 0) {echo ' checked';} else {} ?> />False
        </p>
        <p>
            <label for="Theme">Theme:</label>
            <input name="Theme" type="text" id="Theme" size="20" value="<?= htmlentities($Theme); ?>" />
        </p>
        <p>
            <label for="ShortAttrib">Short Attribution:</label>
            <input name="ShortAttrib" type="text" id="ShortAttrib" size="60" value="<?= htmlentities($ShortAttrib); ?>" />
        </p>
        <p>
            <input type="submit" name="update" value="Update" id="update" />
        </p>
    </form>
    <a href="admin.php">Back to main admin page</a>
<?php } ?>

</div>

<?php include('includes/footer.php'); ?>