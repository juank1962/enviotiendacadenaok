<?php
/**
 * Advanced Review  Template
 *
 * @author        Yithemes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<li id="li-answer-<?php echo $answer->ID; ?>" class="answer-container <?php echo $classes; ?>">

	<div class="answer-content">
		<span class="answer"><?php echo $answer->content; ?></span>
	</div>

	<div
		class="answer-owner"><?php echo sprintf( __("%s answered on %s", 'yith-woocommerce-questions-and-answers'), '<span class="answer-author-name">' . $answer->get_author_name() . '</span>',
			'<span class="answer-date">' . $answer->date . '</span>' ); ?>
	</div>


</li>