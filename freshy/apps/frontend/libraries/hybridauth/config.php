<?php
/**
 * HybridAuth
 * http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
 * (c) 2009-2014, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
 */

// ----------------------------------------------------------------------------------------
//	HybridAuth Configs file: http://hybridauth.sourceforge.net/userguide/Configuration.html
// ----------------------------------------------------------------------------------------

return
    array(
        "base_url" => "http://".$_SERVER["SERVER_NAME"]."/auth",

        "providers" => array (
            // openid providers
            "OpenID" => array (
                "enabled" => true
            ),

            "Yahoo" => array (
                "enabled" => true,
                "keys"    => array ( "key" => "dj0yJmk9SlkzOTdtYU01Q2lhJmQ9WVdrOVkwTk5NMGRoTkRRbWNHbzlNQS0tJnM9Y29uc3VtZXJzZWNyZXQmeD1iYQ--", "secret" => "568484380c63fb532addf5c987a5158ab829f36e" )
            ),

            "AOL"  => array (
                "enabled" => true
            ),

            "Google" => array (
                "enabled" => true,
                "keys"    => array ( "id" => "727864738971-4jgskl1opnfogk442u3ifi22lpspglo0.apps.googleusercontent.com", "secret" => "lWHmrNde2dchOJXrFwXLoPZd" ),
                "scope"     => "https://www-opensocial.googleusercontent.com/api/people/ "."https://www.googleapis.com/auth/plus.login "."https://www.googleapis.com/auth/plus.me "."https://www.googleapis.com/auth/userinfo.profile "."https://www.googleapis.com/auth/userinfo.email ",
            	"access_type" => "offline",
                "approval_prompt" => 'force'
            ),

            "Facebook" => array (
                "enabled" => true,
                "keys"    => array ( "id" => "366823350155450", "secret" => "eb28329b9b3f0fa7dbf4a7fc0abf72b0" ),
                "scope" => "email, user_about_me, user_birthday, user_hometown, user_location, user_relationships, user_groups, user_likes",
                "trustForwarded" => true
            ),

            "Twitter" => array (
                "enabled" => true,
                "keys"    => array ( "key" => "hiBZ6W903zXjLBoPJi6vPlyGm", "secret" => "Y0xbCHpmKc4S9i5uVqMTVhAr6nZleYSt1CfPZ4lyLtrQHfxpvu" )
            ),

            // windows live
            "Live" => array (
                "enabled" => true,
                "keys"    => array ( "id" => "", "secret" => "" )
            ),

            "LinkedIn" => array (
                "enabled" => true,
                "keys"    => array ( "key" => "77ti1hzjocmhfm", "secret" => "G1ViBYJuzdgl2rPB" )
            ),

            "Foursquare" => array (
                "enabled" => true,
                "keys"    => array ( "id" => "", "secret" => "" )
            ),
        ),

        // If you want to enable logging, set 'debug_mode' to true.
        // You can also set it to
        // - "error" To log only error messages. Useful in production
        // - "info" To log info and error messages (ignore debug messages)
        "debug_mode" => false,

        // Path to file writable by the web server. Required if 'debug_mode' is not false
        "debug_file" => "",
    );
