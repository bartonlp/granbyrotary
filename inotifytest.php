<?php
echo "HELLO version 1<br>";

if(function_exists('inotify_init')) {
  echo "found init<br>";
} else {
  echo "init not found<br>";
}

if(function_exists('inotify_add_watch')) {
  echo "found watch<br>";
} else {
  echo "inotify_add_watch not found<br>";
}

if(!extension_loaded('inotify')) {
  echo "inotify not loaded<br>";
}

exit();
$fd = inotify_init();

while(true) {
  inotify_add_watch($fd, '/home/barton11/testinotify.txt', IN_MODIFY);
  inotify_read($fd); //block until file modified
  echo file_get_contents("/home/barton11/testinotify.txt");
}
?>
  