<?php
include_once __DIR__."./tools/Message.addon.php";
$message = new \aaa\Message();
$message->success("成");
$message->warning("警");
$message->error("错");
$message->info("信");