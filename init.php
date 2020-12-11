<?php
class Enclosure_List extends Plugin {

	private $host;

	function about() {
		return array(1.0,
			"Display enclosures as unordered lists",
			"johslarsen");
	}

	function init($host) {
		$this->host = $host;

		$host->add_hook($host::HOOK_FORMAT_ENCLOSURES, $this);
	}

    function hook_format_enclosures($rv, $result, $id, $always_display_enclosures, $article_content, $hide_images) {
        if (!empty($result)) {
			if ($_SESSION['uid'] && !get_pref("STRIP_IMAGES") && !$_SESSION["bw_limit"]) {
				if ($always_display_enclosures || !preg_match("/<img/i", $article_content)) {
					foreach ($result as $entry) {
						if (preg_match("/image/", $entry["content_type"]) || preg_match("/\.(jpg|png|gif|bmp)/i", $entry["filename"])) {
                            if ($hide_images) {
                                continue;
                            }
                            if ($entry['height'] > 0)
                                $encsize .= ' height="' . intval($entry['width']) . '"';
                            if ($entry['width'] > 0)
                                $encsize .= ' width="' . intval($entry['height']) . '"';

                            $url = htmlspecialchars($entry["content_url"]);
                            $alt = htmlspecialchars($entry["filename"]);
                            $title = !empty($entry["title"]) ? htmlspecialchars($entry["title"]) : $alt;

                            $rv .= "<p>";
                            $rv .=  "<a target=\"_blank\" href=\"$url\" title=\"$title\">";
                            $rv .=   "<img src=\"$url\" alt=\"$alt\" $encsize/>";
                            $rv .=  "</a>";
                            $rv .= "</p>";
						}
					}
				}
			}

            $rv .= "<h5>Attachments:</h5>";
            $rv .= "<ul>";

            foreach ($result as $entry) {
                $url = htmlspecialchars($entry["content_url"]);
                $mime = htmlspecialchars($entry["content_type"]);
                $title = htmlspecialchars($entry["title"]);

                $rv .= "<li>";
                $rv .=  "<a target=\"_blank\" href=\"$url\" title=\"$title\">";
                $rv .=   $url;
                $rv .=  "</a> ($mime)";
                $rv .= "</li>";
            }

            $rv .= "</ul>";
        }
        return $rv;
    }

	function api_version() {
		return 2;
	}

}
?>
