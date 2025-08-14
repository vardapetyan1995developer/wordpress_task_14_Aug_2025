<?php
/**
 * CoverNewws and Blockspare content pattern.
 *
 * @package MoreNews
 */

return array(
	'title'      => __( 'Post Grid', 'morenews' ),
    'categories' => array( 'morenews' ),
	'content'    => '<!-- wp:query {"queryId":20,"query":{"perPage":6,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"exclude","inherit":false},"metadata":{"categories":["morenews"],"patternName":"morenews/theme-block-7","name":"Grid"}} -->
    <div class="wp-block-query"><!-- wp:group {"style":{"spacing":{"blockGap":"20px","margin":{"bottom":"20px"}}},"layout":{"type":"constrained"}} -->
    <div class="wp-block-group" style="margin-bottom:20px"><!-- wp:heading {"align":"wide"} -->
    <h2 class="wp-block-heading alignwide">Post Grid</h2>
    <!-- /wp:heading -->
    
    <!-- wp:post-template {"align":"wide","style":{"spacing":{"blockGap":"20px"}},"layout":{"type":"grid","columnCount":"2"}} -->
    <!-- wp:group {"style":{"spacing":{"padding":{"top":"0px","right":"0px","bottom":"0px","left":"0px"},"margin":{"top":"0","bottom":"0"},"blockGap":"10px"}},"layout":{"inherit":false}} -->
    <div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px"><!-- wp:post-featured-image {"isLink":true,"aspectRatio":"3/2"} /-->
    
    <!-- wp:post-title {"isLink":true,"style":{"elements":{"link":{"color":{"text":"#404040"},":hover":{"color":{"text":"#007ACC"}}}},"color":{"text":"#404040"}},"fontSize":"medium"} /-->
    
    <!-- wp:group {"style":{"spacing":{"blockGap":"15px"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
    <div class="wp-block-group"><!-- wp:post-author-name {"style":{"typography":{"fontSize":"12px"},"elements":{"link":{"color":{"text":"#404040"}}},"color":{"text":"#404040"}}} /-->
    
    <!-- wp:post-date {"style":{"typography":{"fontSize":"12px"},"elements":{"link":{"color":{"text":"#404040"}}},"color":{"text":"#404040"}}} /--></div>
    <!-- /wp:group -->
    
    <!-- wp:post-excerpt {"moreText":"Read More","style":{"elements":{"link":{"color":{"text":"#404040"}}},"color":{"text":"#404040"},"typography":{"fontSize":"16px"}}} /--></div>
    <!-- /wp:group -->
    <!-- /wp:post-template --></div>
    <!-- /wp:group --></div>
    <!-- /wp:query -->',
	
);
