<?php

	class HelpController{

		public static function show(){
			include "private.php";
			include VIEWS_PATH."private-nav.php";
			include VIEWS_PATH."help.php";
		}

	}

