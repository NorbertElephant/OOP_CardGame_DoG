<?php 

/**
 * --------------------------------------------------
 * CORE PREDEFINED CONSTANTS
 * http://php.net/manual/fr/reserved.constants.php
 * --------------------------------------------------
**/
if( strtoupper( substr( PHP_OS, 0, 3 ) ) == 'WIN' ) : // If the version of the operating system (provided by the pre-defined constants PHP_OS) corresponds to a Windows kernel,
    if( !defined( 'PHP_EOL') ) : define( 'PHP_EOL', "\r\n" ); endif;
    if( !defined( 'DIRECTORY_SEPARATOR') ) : define( 'DIRECTORY_SEPARATOR', "\\" ); endif;
else :
    if( !defined( 'PHP_EOL') ) : define( 'PHP_EOL', "\n" ); endif;
    if( !defined( 'DIRECTORY_SEPARATOR') ) : define( 'DIRECTORY_SEPARATOR', "/" ); endif;
endif;
/**
 * --------------------------------------------------
 * SESSIONS
 * --------------------------------------------------
**/
session_start(); // Starts a new session or take over an existing session
if( !defined( 'APP_TAG' ) )
    define( 'APP_TAG', 'DuelOfGiants' );
/**
 * --------------------------------------------------
 * APP GENERALS
 * --------------------------------------------------
**/
// if( !defined( 'DOMAIN' ) )
//     define( 'DOMAIN', ( isset( $_SERVER['REQUEST_SCHEME'] ) ? $_SERVER['REQUEST_SCHEME'] : 'http' ) . '://' . $_SERVER['SERVER_NAME'] . dirname( $_SERVER['PHP_SELF'] ) . '/' );
// if( !defined( 'ASSETSURL' ) )
//     define( 'ASSETSURL', DOMAIN . 'assets/' ); // Defines the path to the folder containing the files to include
/**
 * --------------------------------------------------
 * DB
 * --------------------------------------------------
**/
if( !defined( 'DB_HOST' ) )
    define( 'DB_HOST', 'localhost' );
if( !defined( 'DB_NAME' ) )
    define( 'DB_NAME', 'DuelofGiants' );
if( !defined( 'DB_LOGIN' ) )
    define( 'DB_LOGIN', 'root' );
if( !defined( 'DB_PWD' ) )
    define( 'DB_PWD', '' );
/**
 * --------------------------------------------------
 * PATHS
 * --------------------------------------------------
**/
if( !defined( 'DS' ) )
    define( 'DS', DIRECTORY_SEPARATOR ); // Defines the folder separator connected to the system
if( !defined( 'ABSPATH' ) )
    define( 'ABSPATH', __DIR__ . DS ); // Defines the root folder
if( !defined( 'ASSETSPATH' ) )
    define( 'ASSETSPATH', ABSPATH . 'assets' . DS ); // Defines the path to the folder containing the assets files
if( !defined( 'CLASSES' ) )
    define( 'INCPATH', ABSPATH . 'classes' . DS ); // Defines the path to the folder containing the files to include
