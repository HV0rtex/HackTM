<!doctype html>
<?php
	function no_cache_link($link) {
		echo $link . "?v=" . filemtime($link);
	}
?>