<?php

/*
 * Example PHP implementation used for the index.html example
 */

// DataTables PHP library
include( "lib/DataTables.php" );

// Alias Editor classes so they are easy to use
use
	DataTables\Editor,
	DataTables\Editor\Field,
	DataTables\Editor\Format,
	DataTables\Editor\Join,
	DataTables\Editor\Validate;

// Build our Editor instance and process the data coming from _POST
Editor::inst( $db, 'tb_usuario' )
	->fields(
		Field::inst( 'nome' )->validator( 'Validate::required' ),
		Field::inst( 'celular' )->validator( 'Validate::required' ),
		Field::inst( 'email' )->validator( 'Validate::required' ),
		Field::inst( 'status' )->validator( 'Validate::required' )
	)
	->process( $_POST )
	->json();
