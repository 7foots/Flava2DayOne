<?php
    
    include("PlistParser.inc");

    $db = new SQLite3('flava.db');
    $results = $db->query('SELECT * FROM notes WHERE 1');
    $entries = 0;
    $photos = 0;

    while ($row = $results->fetchArray())
    {
        $date = (int)$row['created_date'];
        $datetime = system('date -r "'.$date.'"');
        $title = $row['title'];
        $contents = $row['contents'];
        $user_latitude = $row['user_latitude'];
        $user_longitude = $row['user_longitude'];
        $text_tags = $row['text_tags'];
        $photo_file = $row['photo_file'];

        if (substr($contents, 0, strlen($title)) != $title)
        {
            $contents = $title."\n\n".$contents;
        }

        echo "\033[47m\n\033[0m\n\n";
        echo "\033[31m".$row['idx']."\033[0m\n";
        echo "\033[32m".$datetime."\033[0m\n";
        echo "\033[30m".$contents."\033[0m\n";
        if ($user_latitude != 360 && $user_longitude != 360)
        {
            echo "\033[33m".$user_latitude.', '.$user_longitude."\033[0m\n";
        }
        echo "\033[34m".$text_tags."\033[0m\n";
        echo "\033[35m".$photo_file."\033[0m\n";

        $options = ' -d="'.$datetime.'"';

        if ($photo_file != '')
        {
            $photo_file_path = dirname(__FILE__).'/Photo/'.$photo_file;
            $options .= ' -p="'.$photo_file_path.'"';
        }

        $newentry = system('echo "'.$contents.'" | dayone '.$options.' new');
        $newentry = str_replace('New entry : ~/', '', $newentry);
        $newentry = system('ls ~/"'.$newentry.'"');

        $parser = new plistParser();
        $plist = $parser->parseFile($newentry);
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
    <key>Creation Date</key>
    <date>'.$plist['Creation Date'].'</date>
    <key>Entry Text</key>
    <string>'.$plist['Entry Text'].'</string>
    <key>Starred</key>
    <false/>
    <key>Tags</key>
    <array>'.Tags($text_tags).'</array>
    '.Location($user_latitude, $user_longitude).'
    <key>UUID</key>
    <string>'.$plist['UUID'].'</string>
</dict>
</plist>';
        
        file_put_contents($newentry, $xml);

        $entries++;
        if ($photo_file != '')
        {
            $photos++;
        }
    }

    echo "\033[1;32m\033[40m\nComplete!\nExported: ".$entries.' notes and '.$photos." photos \033[0m\n";

function Tags($tags)
{
    $tags = trim($tags);

    if ($tags == '')
    {
        return '';
    }

    $res = explode('|', $tags);

    $return = '';
    foreach ($res as $key => $value) {

        $return .= "\t\t\t<string>".$value."</string>\n";
    }

    return $return;
}

function Location($user_latitude, $user_longitude)
{
    if ($user_latitude == 360 && $user_longitude == 360)
    {
        return '';
    }

    return '<key>Location</key>
    <dict>
        <key>Latitude</key>
        <real>'.$user_latitude.'</real>
        <key>Longitude</key>
        <real>'.$user_longitude.'</real>
    </dict>';
}

?>