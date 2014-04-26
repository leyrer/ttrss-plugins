<?php
class Delicious extends Plugin {
	private $host;

	function init($host) {
		$this->host = $host;

		$host->add_hook($host::HOOK_ARTICLE_BUTTON, $this);
	}

	function about() {
		return array(1.1,
			"Share article on Delicious",
			"leyrer",
			false);
	}

	function get_js() {
		return file_get_contents(dirname(__FILE__) . "/delicious.js");
	}

	function hook_article_button($line) {
		$article_id = $line["id"];

		$rv = "<img src=\"plugins/delicious/delicious.png\"
			class='tagsPic' style=\"cursor : pointer\"
			onclick=\"deliciousArticle($article_id)\"
			title='".__('Share on Delicious')."'>";

		return $rv;
	}

	function getInfo() {
		$id = db_escape_string($_REQUEST['id']);

		$result = db_query("
				SELECT title, link, content
				FROM ttrss_entries, ttrss_user_entries
				WHERE id = '$id' AND ref_id = id AND owner_uid = " . 
				$_SESSION['uid']
		);

		if (db_num_rows($result) != 0) {
			$title = strip_tags(db_fetch_result($result, 0, 'title'));
			$article_link = db_fetch_result($result, 0, 'link');
			$content = truncate_string(strip_tags(db_fetch_result($result, 0, 'content')), 954, " ..."); 
			/*  strip_tags, as delicious doesnt't like html in the note field
				limit to 998 characters, as delicious limits the note field to 1000 characters
			*/ 
		}

		print json_encode( 
				array(
					"title"		=>	$title, 
					"link"		=>	$article_link,
					"content"	=>	$content,
					"id"		=>	$id
				)
			);
	}

	function api_version() {
		return 2;
	}

}
?>
