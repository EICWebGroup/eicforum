<?php

	class Common {
		static $HOME = "/";
		static $GUEST_ACCOUNT_ID = 20;
	}

	class Utils {

		public static function HandlePDOException($exception){

           //echo "<p style='color:red;'>".$exception."</p>";
			error_log($exception."\n" ,3,ROOT."config/error.log");

		}

		/**
		 * This method used to generate the validate code that used to active account.
		 * This method must be used when creating new account.
		 */
		public static function generateValidationCode(){
			return dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
		}

        public static function generateSalt(){
            return dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
        }

		public static function encrpytPassword($pwd , $salt){
			$check_password = hash('sha256', $pwd . $salt);
			for($round = 0; $round < getHashKey() ; $round++){
				$check_password = hash('sha256', $check_password . $salt);
			}
			return $check_password;

		}

		public static function getCurrentTime(){
			return date('g:iA',time());
		}

		public static function getCurrentDate(){
			return date('Y/m/d');
		}

		public static function getReadableDateTime($milliseconds){
			$now = new DateTime();
			$dt = new DateTime();
			$dt -> setTimestamp($milliseconds);
			$diff = $dt -> diff(new DateTime());

			if($dt->format("Y") != $now->format("Y")){
				return $dt->format("Y年n月d日");
			}else if($diff -> m > 0){
				return $dt->format("n月d日");

			}else if($diff-> d > 0){
				return $diff->d . "日前";

			}else if($diff->h > 0){
				return $diff-> h. "時間前";

			}else if($diff-> i > 0 ){
				return $diff-> i. "分前";

			}else{
				return "先ほど";
			}

			return $diff->m;
		}

		public static function is_mobile(){
			$useragent=$_SERVER['HTTP_USER_AGENT'];
			return preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4));
		}

			// identify if client is robot
		public static function is_bot() {
			$spiders = array("abot","dbot","ebot","hbot","kbot","lbot","mbot","nbot","obot","pbot","rbot","sbot","tbot","vbot","ybot","zbot","bot.","bot/","_bot",".bot","/bot","-bot",":bot","(bot","crawl","slurp","spider","seek","accoona","acoon","adressendeutschland","ah-ha.com","ahoy","altavista","ananzi","anthill","appie","arachnophilia","arale","araneo","aranha","architext","aretha","arks","asterias","atlocal","atn","atomz","augurfind","backrub","bannana_bot","baypup","bdfetch","big brother","biglotron","bjaaland","blackwidow","blaiz","blog","blo.","bloodhound","boitho","booch","bradley","butterfly","calif","cassandra","ccubee","cfetch","charlotte","churl","cienciaficcion","cmc","collective","comagent","combine","computingsite","csci","curl","cusco","daumoa","deepindex","delorie","depspid","deweb","die blinde kuh","digger","ditto","dmoz","docomo","download express","dtaagent","dwcp","ebiness","ebingbong","e-collector","ejupiter","emacs-w3 search engine","esther","evliya celebi","ezresult","falcon","felix ide","ferret","fetchrover","fido","findlinks","fireball","fish search","fouineur","funnelweb","gazz","gcreep","genieknows","getterroboplus","geturl","glx","goforit","golem","grabber","grapnel","gralon","griffon","gromit","grub","gulliver","hamahakki","harvest","havindex","helix","heritrix","hku www octopus","homerweb","htdig","html index","html_analyzer","htmlgobble","hubater","hyper-decontextualizer","ia_archiver","ibm_planetwide","ichiro","iconsurf","iltrovatore","image.kapsi.net","imagelock","incywincy","indexer","infobee","informant","ingrid","inktomisearch.com","inspector web","intelliagent","internet shinchakubin","ip3000","iron33","israeli-search","ivia","jack","jakarta","javabee","jetbot","jumpstation","katipo","kdd-explorer","kilroy","knowledge","kototoi","kretrieve","labelgrabber","lachesis","larbin","legs","libwww","linkalarm","link validator","linkscan","lockon","lwp","lycos","magpie","mantraagent","mapoftheinternet","marvin/","mattie","mediafox","mediapartners","mercator","merzscope","microsoft url control","minirank","miva","mj12","mnogosearch","moget","monster","moose","motor","multitext","muncher","muscatferret","mwd.search","myweb","najdi","nameprotect","nationaldirectory","nazilla","ncsa beta","nec-meshexplorer","nederland.zoek","netcarta webmap engine","netmechanic","netresearchserver","netscoop","newscan-online","nhse","nokia6682/","nomad","noyona","nutch","nzexplorer","objectssearch","occam","omni","open text","openfind","openintelligencedata","orb search","osis-project","pack rat","pageboy","pagebull","page_verifier","panscient","parasite","partnersite","patric","pear.","pegasus","peregrinator","pgp key agent","phantom","phpdig","picosearch","piltdownman","pimptrain","pinpoint","pioneer","piranha","plumtreewebaccessor","pogodak","poirot","pompos","poppelsdorf","poppi","popular iconoclast","psycheclone","publisher","python","rambler","raven search","roach","road runner","roadhouse","robbie","robofox","robozilla","rules","salty","sbider","scooter","scoutjet","scrubby","search.","searchprocess","semanticdiscovery","senrigan","sg-scout","shai'hulud","shark","shopwiki","sidewinder","sift","silk","simmany","site searcher","site valet","sitetech-rover","skymob.com","sleek","smartwit","sna-","snappy","snooper","sohu","speedfind","sphere","sphider","spinner","spyder","steeler/","suke","suntek","supersnooper","surfnomore","sven","sygol","szukacz","tach black widow","tarantula","templeton","/teoma","t-h-u-n-d-e-r-s-t-o-n-e","theophrastus","titan","titin","tkwww","toutatis","t-rex","tutorgig","twiceler","twisted","ucsd","udmsearch","url check","updated","vagabondo","valkyrie","verticrawl","victoria","vision-search","volcano","voyager/","voyager-hc","w3c_validator","w3m2","w3mir","walker","wallpaper","wanderer","wauuu","wavefire","web core","web hopper","web wombat","webbandit","webcatcher","webcopy","webfoot","weblayers","weblinker","weblog monitor","webmirror","webmonkey","webquest","webreaper","websitepulse","websnarf","webstolperer","webvac","webwalk","webwatch","webwombat","webzinger","wget","whizbang","whowhere","wild ferret","worldlight","wwwc","wwwster","xenu","xget","xift","xirq","yandex","yanga","yeti","yodao","zao/","zippp","zyborg","....");

			foreach($spiders as $spider) {
				//If the spider text is found in the current user agent, then return true
				if ( stripos($_SERVER['HTTP_USER_AGENT'], $spider) !== false ) return true;
			}
			//If it gets this far then no bot was found!
			return false;
		}

		public static function setLoginLog($text){
			error_log($text."\n" ,3,self::getLoginLog());
		}

		public static function getLoginLog(){
			return "/home/edisonthktus/Documents/login_record.log";
		}

		public static function showNoPermissionPage(){
            $content = ROOT . "common/no-permission.php";
            include VIEWS_PATH . "account/public.php";
		}

		public static function filterMalicious($content){
			$content = str_replace("<span>\</span>","\\",$content);

			return $content;
		}

		public static function appletTagFound($subject){

			$html = str_get_html($subject);
			$found = false;

			$content = $html->find("applet",0);
			if($content != null){
				$found = true;
			}

			return $found;
		}

		// convert php file in VIEWS folder to string
		public static function output_view($view_file, $ba372aa41a6c63b059cbf047f37f712a = array()){

			foreach ($ba372aa41a6c63b059cbf047f37f712a as $ba372aa41a6c63b059cbf047f37f712b => $ba372aa41a6c63b059cbf047f37f712c) {
				${$ba372aa41a6c63b059cbf047f37f712b} = $ba372aa41a6c63b059cbf047f37f712c;
			}
			
			$body = "include VIEWS_PATH . $view_file";
			include VIEWS_PATH . "base.php";
		}

		// find if there is unavailable tag on subject
		public static function foundUnavailableTag($subject){
			$tags = array("code","pre","li","ul","strong","a","img","ol","h1","h2","hr","em");

			$html = str_get_html("<html><body>".$subject."</body></html>");
			$found = false;

			foreach($tags as $tag){

				$content = $html->find($tag,0);
				if($content != null){
					$found = true;
					break;
				}
			}
			return $found;
		}

		// calculate then length of string without html code
		// <p>hello</p>  ===> length is 5
		public static function textLength($subject){

			$len = 0;
			$html = str_get_html("<html><body>".$subject."</body></html>");
			foreach($html->find("text",0) as $e){
                if($e instanceof simple_html_dom_node)
                    $len = $len + strlen($e->plaintext);
			}

			return $len;
		}

        public static function mailSend($studentId, $subject, $message){

            $headers = 'From: '.getHostMail()."\r\n" .'X-Mailer: PHP/' . phpversion();

            if(!preg_match("/^[0-9]{2}1[4-9]{1}[0-9]{3}/",$studentId)){
                $studentId = "j" . $studentId;
            }

            if(!mail($studentId."@ed.tus.ac.jp", $subject, $message,$headers)){
                $date = date('Y/m/d');
                $time = date('g:iA',time());
                error_log($date." ".$time." ".getHostMail()."が送信できません！Gmailまで確認お願いします。パスワード:denkikougaku12 \n",3,"/var/www/new_eicforum/config/error.log");
            }

        }
	}

	class Cookie{

		static $PATH = "/";
		static $URL = "eicforum.mydns.jp";

		public static function EXPIRE_DATE(){
			return time() + ( 15 * 24 * 60 * 60);
		}
	}
