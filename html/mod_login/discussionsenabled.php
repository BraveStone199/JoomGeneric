<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_login
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
?>
<div id="login">

<?php if ($type == 'logout') : ?>
<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" class="clearfix" id="login-form">

	<h5 style="margin: 0px 0px 10px 0px;">User</h5>

	<?php if ($params->get('greeting')) : ?>

		<div class="login-greeting" style="margin-bottom: 10px;">
			<?php if($params->get('name') == 0) : {
				echo JText::sprintf('MOD_LOGIN_HINAME', htmlspecialchars($user->get('name')));
			} else : {
				echo JText::sprintf('MOD_LOGIN_HINAME', htmlspecialchars($user->get('username')));
			} endif; ?>
		</div>
	
	<?php endif; ?>


	<!-- codingfish code -->
	<div style="margin: 0px 0px 20px 0px;">
	
		<?php 
		
		// get # of new messages
				
		$user =& JFactory::getUser();
		$_user_id = $user->id;
				
		$db		  =& JFactory::getDBO();
		
		$_newMessages 	= 0;
				
		$_sql = "SELECT count(*) FROM #__discussions_messages_inbox WHERE user_id='" . $_user_id . "' AND flag_read='0' AND flag_deleted='0'";		
		$db->setQuery($_sql);
		$_newMessages = $db->loadResult();
			
		if (!$_newMessages) {
			$_newMessages = 0;
		}		
	
	
		// get Discussions itemid
		$_sql = "SELECT extension_id FROM " . $db->nameQuote('#__extensions') . " WHERE " . $db->nameQuote('element') . "='com_discussions' AND " . $db->nameQuote('type') . "='component'";
		$db->setQuery( $_sql);
		$componentid = $db->loadResult();
		
		if ( !$componentid) {
			$itemid = 0;	
		}
		else {
			$_sql = "SELECT id FROM " . $db->nameQuote('#__menu') . 
					" WHERE " . $db->nameQuote('component_id') . "='" . $componentid . "' AND parent_id='1' AND published='1' ";
					
			$db->setQuery( $_sql);
			$itemid = $db->loadResult();
		
			if ( !$itemid) {
				$itemid = 0;
			}
		}
		
		if ( $itemid == 0) { // got no itemid
			$_linkMailbox   = JRoute::_( 'index.php?option=com_discussions&view=inbox&task=inbox');
			$_linkProfile   = JRoute::_( 'index.php?option=com_discussions&view=profile&task=profile');
			$_linkMyAccount = JRoute::_( 'index.php?option=com_users&view=profile&layout=edit');
		}
		else {
			$_linkMailbox   = JRoute::_( 'index.php?option=com_discussions&view=inbox&task=inbox&Itemid=' . $itemid);
			$_linkProfile   = JRoute::_( 'index.php?option=com_discussions&view=profile&task=profile&Itemid=' . $itemid);
		}	
			
		$_linkMyAccount = JRoute::_( 'index.php?option=com_users&view=profile&layout=edit');			
		// Would like to have a nicer link? Create a (hidden) menu entry for link above and set an alias e.g. my-account. Then use this link.
		//$_linkMyAccount = "/my-account";
			
			
		?>
	 	
	    <ul class="nav nav-list">
	
	    	<li>
	    		<?php
	    		if ( $_newMessages > 0) {
	    			?>	    
		    		<a href="<?php echo $_linkMailbox; ?>"><i class="icon-envelope"></i> Mailbox&nbsp;&nbsp;<span class="badge badge-warning"><?php echo $_newMessages; ?></span></a>		    	
		    		<?php
		    	}
		    	else { // 0 new messages
		    	
		    		?>		    	
		    		<a href="<?php echo $_linkMailbox; ?>"><i class="icon-envelope"></i> Mailbox</a>
		    		<?php
		    	}
		    	?>
		    	
			</li>

	    	<li>
		    	<a href="<?php echo $_linkProfile; ?>">
			    	<i class="icon-user"></i>
			    	Profile
			    </a>
			</li>

	    	<li>
		    	<a href="<?php echo $_linkMyAccount; ?>">
			    	<i class="icon-wrench"></i>
			    	My Account
			    </a>
			</li>

	
		</ul>	 	
	 	
	 
	</div>
	<!-- codingfish code -->



	<div class="logout-button">
	
		<input type="submit" name="Submit" class="button" value="<?php echo JText::_('JLOGOUT'); ?>" />
		
		<input type="hidden" name="option" value="com_users" />
		<input type="hidden" name="task" value="user.logout" />
		<input type="hidden" name="return" value="<?php echo $return; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
	
	
</form>


<?php else : ?>


<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" class="clearfix" id="login-form" >

	<h5 style="margin: 0px 0px 10px 0px;">Login</h5>

	<?php if ($params->get('pretext')): ?>
		<div class="pretext">
		<p><?php echo $params->get('pretext'); ?></p>
		</div>
	<?php endif; ?>
	
	
	<fieldset class="userdata">
	
	<p id="form-login-username">
		<input id="modlgn-username" type="text" name="username" class="span2" size="18" placeholder="Username" />
	</p>
	
	
	<p id="form-login-password">
		<input id="modlgn-passwd" type="password" name="password" class="span2" size="18" placeholder="Password" />
	</p>
	
	
	<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
	<p id="form-login-remember">		
		<label class="checkbox">
			<input type="checkbox" class="inputbox" value="yes" id="modlgn-remember" name="remember"> <?php echo JText::_('MOD_LOGIN_REMEMBER_ME') ?>
		</label>						
	</p>		
	<?php endif; ?>
		
		<input type="submit" name="Submit" class="loginbtn" value="<?php echo JText::_('JLOGIN') ?>" />
	
    	<?php
		$usersConfig = JComponentHelper::getParams('com_users');
		if ($usersConfig->get('allowUserRegistration')) : ?>
		or 
				<a href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>">
	    		<?php echo JText::_('MOD_LOGIN_REGISTER'); ?>
				</a>	    	
		<?php endif; ?>
	
	<input type="hidden" name="option" value="com_users" />
	<input type="hidden" name="task" value="user.login" />
	<input type="hidden" name="return" value="<?php echo $return; ?>" />
	<?php echo JHtml::_('form.token'); ?>
	</fieldset>
	
    <div style="margin-top: 10px;">
<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">
			<?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_PASSWORD'); ?></a>
			<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
			<?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_USERNAME'); ?></a>
		<?php
		$usersConfig = JComponentHelper::getParams('com_users');
		if ($usersConfig->get('allowUserRegistration')) : ?>
			<a href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>">
			<?php echo JText::_('MOD_LOGIN_REGISTER'); ?></a>
		<?php endif; ?>
	<?php if ($params->get('posttext')): ?>
		<div class="posttext">
		<p><?php echo $params->get('posttext'); ?></p>
		</div>
	<?php endif; ?>
		
</form>

<?php endif; ?>
</div>