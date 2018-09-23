<?php
header('Content-Type: application/rss+xml');
require_once '../config.php';
require_once '../dbconn.php';
// DB_HOST DB_USER DB_PASS DB_NAME
$result = $conn->query("SELECT * FROM `posts` ORDER BY created DESC;");

echo '<?xml version="1.0" encoding="utf-8"?>'.
	'<rss xmlns:atom="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:media="http://search.yahoo.com/mrss/" version="2.0">'.
	'<channel>'.
		'<title>Unboxablog</title>'.
		'<description>I’m Regan and this is Unboxable.</description>'.
		'<link>https://www.unboxable.com</link>'.
		'<atom:link href="https://www.unboxable.com/blog/feed" rel="self" type="application/rss+xml"/>'.
		'<copyright>© '.date('Y').' Regan Leah Bourland</copyright>'.
		'<language>en</language>';

if ($result->num_rows > 0) {
    for ($i = 0; $i < $result->num_rows; $i++) {
    	$entireRow = mysqli_fetch_object($result);
    	if ($i == 0) {
			echo '<lastBuildDate>'.date("l, d M Y H:i:s",strtotime($entireRow->modified)).' +5000</lastBuildDate>';
    	}
		echo '<item>'.
			'<title>'.
				html_entity_decode(
					strip_tags(
						str_replace(
							array("\n", "\t", "\r"),
							' ',
							$entireRow->title
						)
					)
				).
			'</title>'.
			'<description>'.
				substr(
					html_entity_decode(
						strip_tags(
							str_replace(
								array("\n", "\t", "\r"),
								' ',
								$entireRow->body
							)
						)
					),
					0,
					250
				).'...</description>'.
			'<link>https://www.unboxable.com/blog/post/'.$entireRow->id.'</link>'.
			'<guid isPermaLink="false">'.hash('ripemd160',$entireRow->id).'</guid>'.
			'<pubDate>'.date("l, d M Y H:i:s",strtotime($entireRow->created)).' +5000</pubDate>'.
			'<media:content/>'.
			'<media:thumbnail/>'.
			'<category>Unboxablog</category>'.
			'<media:keywords>'.$entireRow->tags.'</media:keywords>'.
			'<dc:creator>Regan Bourland</dc:creator>'.
			'<dc:modified>'.date("l, d M Y H:i:s",strtotime($entireRow->modified)).'</dc:modified>'.
			'<dc:publisher>Unboxable.com</dc:publisher>'.
			//<media:thumbnail url="https://media.wired.com/photos/5b9c46491e60052cdc38be19/master/pass/CarRoundup-Florence-1031577054.jpg" width="2400" height="1800"/>
		'</item>';
    }
}


echo '</channel>'.
'</rss>';
$conn->close();