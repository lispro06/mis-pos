<?php
	session_start();
	session_cache_limiter('no-cache,must-revalidate');
	session_unregister(session_userid);
	session_destroy();
	header("Location:index.php");
?>