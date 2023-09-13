<?php
class Database {
    function DBConnect($server, $db, $user, $pass, $DBname = 'MySQL')
	{
		if (!defined('DATABASE_NAME') || $DBname != DATABASE_NAME) {
			define('DATABASE_NAME', $DBname);
		}
		$this->Server = $server;
		$this->DB = $db;
		$this->User = $user;
		$this->Password = $pass;
        switch (DATABASE_NAME) {
			case 'MySQL':
				@mysql_connect($this->Server, $this->User, $this->Password) or die(mysql_error());
				@mysql_select_db($this->DB) or die(mysql_error());
				break;
		}
	}

	function DBFetch($query) {
		return @mysql_fetch_array(mysql_query($query));
	}
}
?>