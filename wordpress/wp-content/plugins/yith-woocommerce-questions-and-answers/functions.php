<?php
if ( ! defined( 'YWQA_CUSTOM_POST_TYPE_NAME' ) ) {
	define( 'YWQA_CUSTOM_POST_TYPE_NAME', 'question_answer' );
}


//region    ***************  METAKEY name definition    *******************
if ( ! defined( 'YWQA_METAKEY_PRODUCT_ID' ) ) {
	define( 'YWQA_METAKEY_PRODUCT_ID', '_ywqa_product_id' );
}

if ( ! defined( 'YWQA_METAKEY_DISCUSSION_TYPE' ) ) {
	define( 'YWQA_METAKEY_DISCUSSION_TYPE', '_ywqa_type' );
}

if ( ! defined( 'YWQA_METAKEY_APPROVED' ) ) {
	define( 'YWQA_METAKEY_APPROVED', '_ywqa_approved' );
}
//endregion

if (!function_exists('ywqa_strip_trim_text')) {
	/**
	 * Strip html tags from a text and trim to fixed length
	 * @param $text
	 * @param $chars
	 */
	function ywqa_strip_trim_text($text, $chars = 50)
	{
		return wc_trim_string(wp_strip_all_tags($text), $chars);
	}
}
