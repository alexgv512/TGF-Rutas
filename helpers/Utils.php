<?php

class Utils {
    public static function isAdmin() {
        return isset($_SESSION['identity']) && $_SESSION['identity']['rol'] == 'admin';
    }
}
?>