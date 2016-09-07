/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For the complete reference:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config
CKEDITOR.replaceClass = 'ckeditor';
	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbarGroups = [
		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing' },
		{ name: 'links' },
		{ name: 'insert' },
	
		{ name: 'tools' },
		 { name: 'document2', items: ['NewPage', '-', 'Source'] },
		
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph',   groups: [ 'list',  'align', 'bidi' ] },
		{ name: 'styles' },
		{ name: 'colors' ,      groups : [ 'TextColor','BGColor' ] }
	];

	// Remove some buttons, provided by the standard plugins, which we don't
	// need to have in the Standard(s) toolbar.
	config.removeButtons = 'Underline,Subscript,Superscript';
	config.filebrowserUploadUrl = "/includes/ckeditor/ckupload.php";
	// Se the most common block elements.
	config.format_tags = 'p;h1;h2;h3;pre';

	// Make dialogs simpler.
	// config.removeDialogTabs = 'image:advanced;link:advanced';
};
