/**
 * @license Copyright (c) 2003-2021, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For complete reference see:
	// https://ckeditor.com/docs/ckeditor4/latest/api/CKEDITOR_config.html

	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbar = [
		{ name: 'document', groups: [ 'document', 'doctools' ], items: [ 'NewPage', 'ExportPdf', '-', 'Templates' ] },
		{ name: 'clipboard', groups: [ 'clipboard', 'undo', ], items: [ 'print', 'save', 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
		{ name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
		{ name: 'insert', items: [ 'Image', 'Flash', 'Table',  'Smiley', 'SpecialChar', 'Iframe', 'HorizontalRule' ] },
		{ name: 'basicstyles', groups: [ 'cleanup' ], items: [ 'RemoveFormat' ] },
		{ name: 'paragraph', groups: [ 'bidi' ], items: [ 'CreateDiv' ] },
		{ name: 'document', groups: [ 'mode' ], items: [ 'Source' ] },
		'/',
		{ name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize', 'spacingsliders' ] },
		{ name: 'others', items: [ 'textindent' ] },
		{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
		{ name: 'insert', items: ['PageBreak' ] },
		{ name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles'], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting' ] },
		{ name: 'colors', items: [ 'TextColor', 'BGColor' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
		{ name: 'about', items: [ 'About' ] },
	    { name: 'others', items: [ '-', ] },
	];
	config.extraPlugins = 'spacingsliders';
	// Remove some buttons provided by the standard plugins, which are
	// not needed in the Standard(s) toolbar.
};
