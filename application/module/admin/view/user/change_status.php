<?php
/*
 * for ajax
 */
if ($changed) {
    $arr = array('changed' => true);
} else {
    $arr = array('changed' => false);
}
echo json_encode($arr);