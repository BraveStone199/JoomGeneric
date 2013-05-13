<?php
/**
 * @package		Joomla.Site
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

/*
 * For Use with the Minecraft themed modules
 */
function modChrome_craft($module, &$params, &$attribs)
{		
	if (!empty ($module->content)) : ?>
		<div class="moduletable<?php echo htmlspecialchars($params->get('moduleclass_sfx')); ?>">
			<div class="widget">
				<?php if ($module->showtitle != 0) : ?>
					<div class="widget-title">
        			<h4><?php echo $module->title; ?></h4>
                    </div>
				<?php endif; ?>
        	<div class="widget-content">
			<?php echo $module->content; ?>
		</div>
        </div>
	<?php endif;
}