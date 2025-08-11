<?php
$action = "/"; //
?>

<form role="search" method="get" id="searchform" class="searchform" action="<?php echo $action; ?>">
	<label class="screen-reader-text" for="search">Search for:</label>
	<div class="input-group">
		<input type="text" value="<?php echo (isset($_GET['search'])) ? $_GET['search'] : "" ?>" name="s" id="s" class="form-control">
	  <button type="submit" id="searchsubmit" value="" class="btn btn-dark input-group-text icon-search"></button>
	</div>
</form>