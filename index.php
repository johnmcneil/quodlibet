<?php // header
 $pageTitle = "The Quodlibet of Messmore Breamworthy";
 include('includes/header.php');
?>

<?php // database connection and variables and html output
require_once 'includes/connection.php';

$conn = dbConnect('read');

$sql = "SELECT * FROM quotation WHERE Display = True ORDER BY Theme";

$result = $conn->query($sql);
if(!$result) {
    $error = $conn->error;
} else { // get the result into some useful variables.
    //create an array to hold the quotations
    $quotations = array ();
    //fetch each row as an associative array and put it into the multidimensional array $quotations
    while($row = $result->fetch_assoc()) {
        array_push($quotations, $row);
    }
    
    //count the rows in $quotations for use in the loop    
    $count = count($quotations);

    /* not working
    $themes = array($quotations[0]['theme']);
    for($x=1; $x <= $count-1; $x++)
      if($quotations[$x]['theme'] !== $quotations[$x-1]['theme']) {
        $themes[] = $quotations[$x]['theme'];
      } else {}
    */
}

if (isset($error)) {
    echo "<p>$error</p>";
} else { // html/php to display quotations goes here ?>  
    <div class="row">
    <div class="all-themes col-xs-10 col-sm-10"> <!-- this column goes around all the themes -->   
    
    <?php //this loop outputs the themes and quotations
    for($x = 0; $x<=$count-1; $x++) { 
        //open the first row and the first <div class="theme">
        if ($x == 0) { ?>
            <div id="<?= $quotations[0]['Theme']; ?>" class="theme col-xs-12 col-sm-6">
            <h2 class="theme-title"><?= $quotations[0]['Theme']; ?></h2>
        <?php } ?>

        <?php // open the <div class="theme"> if this quotation has a different theme than the previous one
        if ($x > 0 && $quotations[$x]['Theme'] !== $quotations[$x-1]['Theme']) { ?>
            <div class="theme col-xs-12 col-sm-6" id="<?= $quotations[$x]['Theme']; ?>">
            <h2 class="theme-title"><?= $quotations[$x]['Theme']; ?></h2>
        <?php } else {} ?> 

        <div class="quotation">
            <blockquote>
                <p><?= $quotations[$x]['Quotation']; ?></p>
                <footer class="attribution">
                    <span class="short-attribution full-attribution-click-me"><?= $quotations[$x]['ShortAttrib']; ?></span>
                    <p class="full-attribution"><?= $quotations[$x]['Attribution']; ?></p>
                </footer>
            </blockquote>
        </div>
        
        <?php  // close the <div class="theme" if we're on the last one, or if the one we're on has a different theme than the next one.
        if ($x == $count-1 || $quotations[$x]['Theme'] !== $quotations[$x+1]['Theme']) { ?>
            </div>
        <?php } else {} ?>
    <?php } ?>
    
    <?php //close connection
        mysqli_close($conn); ?>

    </div> <!--end col around themes -->
<?php } ?>


<?php //this loop gets the names of the themes for the nav sidebar
function getThemesForNav($lfirst, $numberOfLetters) {
    global $count, $quotations;
    $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $alphabetPiece = substr($alphabet, strpos($alphabet, $lfirst), $numberOfLetters);
    for($x = 0; $x<= $count-1; $x++) {
	$themeFirstL = substr($quotations[$x]['Theme'], 0, 1);
	if (strpos($alphabetPiece, $themeFirstL) !== false) {
	    if ($x == 0 || ($x > 0 && $quotations[$x]['Theme'] !== $quotations[$x-1]['Theme'])) { ?>
		<li class="">
		    <a href="#<?= $quotations[$x]['Theme']; ?>">
			<?= $quotations[$x]['Theme']; ?>
		    </a>
		</li>
<?php }}}} ?>

<?php
function getNextLetterInAlphabet($l) {
    $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $lposition = strpos($alphabet, $l);
    $lpositionNext = $lposition + 1;
    $nextLetter = substr($alphabet, $lpositionNext, 1);
    echo $nextLetter;
}
?>

<?php
function getHrefForLetterRange($lfirst) {
    global $count, $quotations;
    for($x=0; $x<= $count-1; $x++) {
	$themeFirstL = substr($quotations[$x]['Theme'], 0, 1);
	if ($themeFirstL == $lfirst) {
	    if ($x == 0 || substr($quotations[$x-1]['Theme'], 0, 1) !== substr($quotations[$x]['Theme'], 0,1)) {
		echo ('#' . $quotations[$x]['Theme']);
	    }
	} else {
	    if (getNextLetterInAlphabet($themeFirstL) == $lfirst) {
		if ($x == 0 || substr($quotations[$x-1]['Theme'], 0, 1) !== substr($quotations[$x]['Theme'], 0,1)) {
		echo ('#' . $quotations[$x]['Theme']);
		}
	    }
	    
	}
    }
}
?>

<nav class="col-xs-2 col-sm-2 bs-docs-sidebar">
<ul id="sidebar" class="nav nav-stacked">
    <li>
	<a class="letter-range" id="letter-range1" href="">A-E</a>
	<ul class="nav nav-stacked">
	    <?php getThemesForNav("A", "5"); ?>
	</ul>
    </li>	 
    <li>
	<a class="letter-range" id="letter-range2" href="">F-J</a>
	<ul class="nav nav-stacked">
	    <?php getThemesForNav("F", "5"); ?>
	</ul>
    </li>
    <li>
	<a class="letter-range" id="letter-range3" href="">K-O</a>
	<ul class="nav nav-stacked">
	    <?php getThemesForNav("K", "5"); ?>
	</ul>
    </li>
    <li>
	<a class="letter-range" id="letter-range4" href="">P-T</a>
	<ul class="nav nav-stacked">
	    <?php getThemesForNav("P", "5"); ?>
	</ul>
    </li>
    <li>
	<a class="letter-range" id="letter-range5" href="">U-Z</a>
	<ul class="nav nav-stacked">
	    <?php getThemesForNav("U", "6"); ?>
	</ul>
    </li>
</ul>
</nav>

</div> <!--end row-->


<?php include('includes/footer.php'); ?>