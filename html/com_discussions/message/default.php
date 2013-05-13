<?php
/**
 * @package		Codingfish Discussions
 * @subpackage	com_discussions
 * @copyright	Copyright (C) 2010-2013 Codingfish (Achim Fischer). All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://www.codingfish.com
 */


// no direct access
defined('_JEXEC') or die('Restricted access');

JHTML::_('stylesheet', 'discussions.css', 'components/com_discussions/assets/');

JHtml::_('behavior.formvalidation');

require_once(JPATH_COMPONENT.DS.'classes/user.php');
require_once(JPATH_COMPONENT.DS.'classes/helper.php');




echo "<script type='text/javascript'>";

	echo "function confirmdelete() { ";
 		echo "return confirm('" . JText::_( 'COFI_MESSAGES_CONFIRM_DELETE' ) . "');";
	echo "}";

echo "</script>";


echo "<script type='text/javascript'>";

	echo "Joomla.submitbutton = function confirmnotices(pressbutton) { ";

        echo "var form = document.getElementById('postform');";

        echo "if (form.postReceiver.value == '') { ";
            echo "alert( '" . JText::_('COFI_MESSAGES_MESSAGE_MUST_HAVE_RECIPIENT') . "');";
            echo "return false;";
        echo "}";

        echo "if (form.postSubject.value == '') { ";
            echo "alert( '" . JText::_('COFI_MESSAGES_MESSAGE_MUST_HAVE_SUBJECT') . "');";
            echo "return false;";
        echo "}";

        echo "form.submit();";

	echo "}";

echo "</script>";

?>

<article>        
<div class="codingfish">

<?php

$app = JFactory::getApplication();

$user =& JFactory::getUser();
$logUser = new CofiUser( $user->id);


// get parameters
$params = JComponentHelper::getParams('com_discussions');


// website root directory
$_root = JURI::root();

$cHelper = new CofiHelper();



// check if this is the owner of this message

if ( $this->task == "msg_new") {
	// do nothing for now
}
else {
	if ( $this->user_id != $user->id) {
		// redirect	link
		switch( $this->type) {
			case "outbox": {
				$redirectLink = JRoute::_( "index.php?option=com_discussions&view=outbox&task=outbox");
				break;
			}
			
			default: {
				$redirectLink = JRoute::_( "index.php?option=com_discussions&view=inbox&task=inbox");
				break;
			}
		}
		
		// if user is not the owner, kick him back to index page
		$app->redirect( $redirectLink);
	}
}
?>



<!-- HTML Box Top -->
<?php
$htmlBoxMessageTop = $params->get('htmlBoxMessageTop', '');

if ( $htmlBoxMessageTop != "") {
	echo "<div class='cofiHtmlBoxMessageTop'>";
		echo $htmlBoxMessageTop;
	echo "</div>";
}
?>
<!-- HTML Box Top -->



<?php
include( 'components/com_discussions/includes/topmenu.php');
?>



<!-- Box name and description -->
<table width="100%" style="margin-bottom:10px;" class="noborder">
    <tr>

        <!-- box name and description -->
        <td align="left" class="noborder">
            <?php
            echo "<h3 style='padding-left: 0px;'>";
                        
			switch( $this->type) {
				case "outbox": {
				
							$_username = $cHelper->getUsernameById( $this->user_to_id);
							

					        	echo JText::_( 'COFI_MESSAGES_MESSAGE_TO' ) . ": ";
					        	echo "<br />";
					        	echo "<br />";
					        	
                    			$_avatar   = $cHelper->getAvatarById( $this->user_to_id);


				                echo "<table width='100%' cellspacing='0' cellpadding='0' border='0' class='noborder'>";
				                    echo "<tr>";
				

                                        echo "<td width='32' align='left' class='noborder'>";

                                            echo "<div class='cofiMessagesAvatarBox'>";
                                                if ( $_avatar == "") { // display default avatar
                                                    echo "<img src='" . $_root . "components/com_discussions/assets/users/user.png' width='32px' height='32px' class='cofiCategoryDefaultAvatar' alt='$_username' title='$_username' />";
                                                }
                                                else { // display uploaded avatar
                                                    echo "<img src='" . $_root . "images/discussions/users/" . $this->user_to_id . "/small/".$_avatar."' width='32px' height='32px' class='cofiCategoryAvatar' alt='$_username' title='$_username' />";
                                                }
                                            echo "</div>";

                                        echo "</td>";
				

				                        echo "<td align='left' valign='center' class='noborder' style='padding-left: 5px;'>";

					                    	echo $_username;
				
				                        echo "</td>";
				                    echo "</tr>";
				                echo "</table>";
					        	

					break;
										
				}
				
				default: {
				
					switch ( $this->task) {
					
						case "msg_new": {
							echo JText::_( 'COFI_MESSAGES_NEW_MESSAGE' );
							break;
						}

						case "msg_reply": {
							echo JText::_( 'COFI_MESSAGES_REPLY_MESSAGE' );
				        	$_user_from = $cHelper->getUsernameById( $this->user_from_id);
							break;
						}

						case "msg_quote": {
							echo JText::_( 'COFI_MESSAGES_QUOTE_MESSAGE' );
				        	$_user_from = $cHelper->getUsernameById( $this->user_from_id);
							break;
						}
					
						default: { // show message	
						
							$_username = $cHelper->getUsernameById( $this->user_from_id);
							

					        	echo JText::_( 'COFI_MESSAGES_MESSAGE_FROM' ) . ": ";
					        	echo "<br />";
					        	echo "<br />";
					        	
                    			$_avatar   = $cHelper->getAvatarById( $this->user_from_id);


				                echo "<table width='100%' cellspacing='0' cellpadding='0' border='0' class='noborder'>";
				                    echo "<tr>";

                                        echo "<td width='32' align='left' class='noborder'>";

                                            echo "<div class='cofiMessagesAvatarBox'>";
                                                if ( $_avatar == "") { // display default avatar
                                                    echo "<img src='" . $_root . "components/com_discussions/assets/users/user.png' width='32px' height='32px' class='cofiCategoryDefaultAvatar' alt='$_username' title='$_username' />";
                                                }
                                                else { // display uploaded avatar
                                                    echo "<img src='" . $_root . "images/discussions/users/" . $this->user_from_id . "/small/".$_avatar."' width='32px' height='32px' class='cofiCategoryAvatar' alt='$_username' title='$_username' />";
                                                }
                                            echo "</div>";

                                        echo "</td>";

                                        echo "<td align='left' valign='center' class='noborder' style='padding-left: 5px;'>";

					                    	echo $_username;
				
				                        echo "</td>";

				                    echo "</tr>";
				                echo "</table>";
					        	

				        	break;						
						}					
					
					}
				
								
					break;
				}
			}
            	
            echo "</h3>";
            ?>
        </td>
        <!-- box name and description -->


    </tr>
</table>
<!-- Box name and description -->



<?php

if ( $this->task == "msg_new" || $this->task == "msg_reply" || $this->task == "msg_quote") {
    echo "<form action='' method='post' name='postform' id='postform'>";

    	echo "<table cellspacing='1' cellpadding='0' width='100%' class='noborder'>";


    		echo "<tr>";    		
    			echo "<td style='padding: 5px;' class='noborder'>";

    				echo "<div class='cofiSubjectHeader'>" . JText::_( 'COFI_MESSAGES_RECEIVER' ) . ":</div> ";
   					
   					   					
   						switch ( $this->task) {

							case "msg_new": {
		            			echo "<div class='cofiSubject'>";

		            				if ( $this->receiver_userid > 0) {
		            				
		            					$_receivername = $cHelper->getUsernameById( $this->receiver_userid);
		            				
		            					echo "<input type='text' name='postReceiver' id='postReceiver' size='50' maxlength='80' style='width: 200px;' value='" . $_receivername . "' >";
		            				}
		            				else {
		            					echo "<input type='text' name='postReceiver' id='postReceiver' size='50' maxlength='80' style='width: 200px;'>";
		            				}
		            				
		            				
		            				
		            				
		            				
		            			echo "</div>";		            			
		            			echo "<div class='cofiSubjectFooter'>" . JText::_( 'COFI_MESSAGES_RECEIVER_INFO' ) . "</div> ";
								break;
							}   						
   						

							case "msg_reply":
							case "msg_quote": {
		            			echo "<div class='cofiSubject'>";
		            				echo "<input type='text' name='postReceiver' id='postReceiver' size='50' maxlength='80' style='width: 200px;' value='" . $_user_from . "' readonly>";
		            			echo "</div>";		            			
		            			echo "<div class='cofiSubjectFooter'>" . JText::_( 'COFI_MESSAGES_RECEIVER_INFO' ) . "</div> ";

								break;
							}   						
   						
	   						default: {
	   							break;
	   						}   						
   						}
   					
   					
   					
            		
    			echo "</td>";
    		echo "</tr>";


    		echo "<tr>";    		
    			echo "<td style='padding: 5px;' class='noborder'>";

    				echo "<div class='cofiSubjectHeader'>" . JText::_( 'COFI_MESSAGES_SUBJECT' ) . ":</div> ";
   					
   					
   						switch ( $this->task) {

							case "msg_new": {
		            			echo "<div class='cofiSubject'>";
            						echo "<input type='text' name='postSubject' id='postSubject' size='50' maxlength='80' style='width: 500px;'>";
		            			echo "</div>";		            			
            					echo "<div class='cofiSubjectFooter'>" . JText::_( 'COFI_MESSAGES_SUBJECT_INFO' ) . "</div> ";
								break;
							}   						
   						

							case "msg_reply":
							case "msg_quote": {
		            			echo "<div class='cofiSubject'>";		            			
		            				// shorten subject, remove multiple Re:
		            				$_subject = $this->messageSubject;
		            				$_subject = str_replace( "Re:", "", $_subject);
		            				$_subject = "Re: " . ltrim($_subject);		            			
            						echo "<input type='text' name='postSubject' id='postSubject' size='50' maxlength='80' style='width: 500px;' value='" . $_subject . "' readonly>";
		            			echo "</div>";		            			
            					echo "<div class='cofiSubjectFooter'>" . JText::_( 'COFI_MESSAGES_SUBJECT_INFO' ) . "</div> ";

								break;
							}   						
   						
	   						default: {
	   							break;
	   						}   						
   						}
   					   					            		
    			echo "</td>";
    		echo "</tr>";


    		echo "<tr>";
    			echo "<td align='left' valign='top' style='padding: 5px;' class='noborder'>";
    			
    				echo "<div class='cofiTextHeader'>" . JText::_( 'COFI_MESSAGES_TEXT' ) . ":</div> ";
   					   			
   					echo "<div class='cofiText'>";		
   					
   						if ( $this->task == "msg_quote") {
   							echo "<textarea name='postText' cols='80' rows='20' style='width: 600px;' wrap='VIRTUAL' id='postText'>";
	   							if ( $logUser->getMessagesUseSignature() == 1 && $logUser->getMessagesUseSignatureForReplies() == 1) { // use the users signature for quote
	   								echo "\n\n\n";
	   								echo $logUser->getMessagesSignature();
	   							}     								
	   							echo "\n\n\n";
	   							echo $this->messageText; // text from original message
    						echo "</textarea>";   						
   						}
   						else { // blank textarea
   							echo "<textarea name='postText' cols='80' rows='20' style='width: 600px;' wrap='VIRTUAL' id='postText'>";
   								if ( $this->task == "msg_reply") {
		   							if ( $logUser->getMessagesUseSignature() == 1 && $logUser->getMessagesUseSignatureForReplies() == 1) { // use the users signature for reply
		   								echo "\n\n\n";
		   								echo $logUser->getMessagesSignature();
		   							}     								
   								} 
   								else { // new message
		   							if ( $logUser->getMessagesUseSignature() == 1) { // use the users signature for new message
		   								echo "\n\n\n";
		   								echo $logUser->getMessagesSignature();
		   							}     								   								
   								}					   								
    						echo "</textarea>";
    					}    					
    					
    				echo "</div>";

            		echo "<div class='cofiTextFooter'>" . JText::_( 'COFI_MESSAGES_TEXT_INFO' ) . "</div> ";




            		echo "<div class='cofiTextButton'>";

						echo "<input type='hidden' name='dbmode' value='insert'>";
								
						echo "<input type='hidden' name='task' value='save'>";  			
						echo "<input class='cofiButton' type='submit' name='submit' onclick='return Joomla.submitbutton()' value='" . JText::_( 'COFI_MESSAGES_SEND_MESSAGE' ) ."'>";
					
					echo "</div> ";


    			echo "</td>";
    			    			
    		echo "</tr>";


    	echo "</table>";

    echo "</form>";
    
}
else { // display message

	// set read_flag
	
	if ( $this->type == "inbox") {

		// mark message as read
		$cHelper->setMessageReadFlagById( $this->id);
	
		
		// display reply, quote and delete links	
		echo "<table style='margin:20px 0px 20px 0px;' class='noborder'>";
	    	echo "<tr>";       	
	
	        	echo "<td width='16' align='center' valign='middle' class='noborder'>";
	            	echo "<img src='" . $_root . "/components/com_discussions/assets/messages/reply.gif' style='margin-left: 5px; margin-right: 5px; border:0px;' />";
	        	echo "</td>";
	        	echo "<td align='left' valign='middle' class='noborder'>";
	            	$menuLinkReplyTMP = "index.php?option=com_discussions&view=message&task=msg_reply&id=" . $this->id;
	            	$menuLinkReply = JRoute::_( $menuLinkReplyTMP);
	            	echo "<a href='".$menuLinkReply."'>" . JText::_( 'COFI_MESSAGES_REPLY_MESSAGE' ) . "</a>";
	        	echo "</td>";      


	        	echo "<td class='noborder'>";
	        		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	        	echo "</td>";      


	        	echo "<td width='16' align='center' valign='middle' class='noborder'>";
	            	echo "<img src='" . $_root . "/components/com_discussions/assets/messages/quote.gif' style='margin-left: 5px; margin-right: 5px; border:0px;' />";
	        	echo "</td>";
	        	echo "<td align='left' valign='middle' class='noborder'>";
	            	$menuLinkQuoteTMP = "index.php?option=com_discussions&view=message&task=msg_quote&id=" . $this->id;
	            	$menuLinkQuote = JRoute::_( $menuLinkQuoteTMP);
	            	echo "<a href='".$menuLinkQuote."'>" . JText::_( 'COFI_MESSAGES_QUOTE_MESSAGE' ) . "</a>";
	        	echo "</td>";      


	        	echo "<td class='noborder'>";
	        		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	        	echo "</td>";      
	        	
	        	  
	        	echo "<td width='16' align='center' valign='middle' class='noborder'>";
	            	echo "<img src='" . $_root . "/components/com_discussions/assets/messages/delete.gif' style='margin-left: 5px; margin-right: 5px; border:0px;' />";
	        	echo "</td>";
	        	echo "<td align='left' valign='middle' class='noborder'>";
	            	$menuLinkDeleteTMP = "index.php?option=com_discussions&view=message&task=msg_idelete&id=" . $this->id;
	            	$menuLinkDelete = JRoute::_( $menuLinkDeleteTMP);
	            	echo "<a href='".$menuLinkDelete."' onclick='return confirmdelete();' >" . JText::_( 'COFI_MESSAGES_DELETE_MESSAGE' ) . "</a>";
	        	echo "</td>";      
	        	  	        	
	    	echo "</tr>";
		echo "</table>";	
	
	}
	
	if ( $this->type == "outbox") {
	
		// display delete link	
		echo "<table style='margin:20px 0px 20px 0px;' class='noborder'>";
		
	    	echo "<tr>";       	
		        	        	  
	        	echo "<td width='16' align='center' valign='middle' class='noborder'>";
	            	echo "<img src='" . $_root . "/components/com_discussions/assets/messages/delete.gif' style='margin-left: 5px; margin-right: 5px; border:0px;' />";
	        	echo "</td>";
	        	echo "<td align='left' valign='middle' class='noborder'>";
	            	$menuLinkDeleteTMP = "index.php?option=com_discussions&view=message&task=msg_odelete&id=" . $this->id;
	            	$menuLinkDelete = JRoute::_( $menuLinkDeleteTMP);
	            	echo "<a href='".$menuLinkDelete."' onclick='return confirmdelete();' >" . JText::_( 'COFI_MESSAGES_DELETE_MESSAGE' ) . "</a>";
	        	echo "</td>";      
	        	  	        	
	    	echo "</tr>";
	    	
		echo "</table>";
		// display delete link	
	
	}
	

	?>

	<div class="cofiPostHelp" style="color: #555555; background: #FFFFFF;">
	
	    <div class="cofiTextFormatTimestamp">
	    	<?php 	
		    echo $this->messageDate;
		    echo " ";
		    echo $this->messageTime;
	    	?>
	    </div>

	
	    <div class="cofiTextFormatHeader">
	    	<?php 
	    	echo $this->messageSubject; 
	    	?>
	    </div>
	    				
	    <div class="cofiTextFormat">
	    		
			<table cellspacing="0px" cellpadding="10px" width="100%" class="noborder">
	
				<tr>
					<td class="noborder">
						<?php 
						echo nl2br( $this->messageText); 
						?>					
					</td>
				</tr>
	
					  
			</table>
	    		    				    				
	    </div>
	
	</div>

<?php
}
?>



<!-- HTML Box Bottom -->
<?php
$htmlBoxPostingBottom = $params->get('htmlBoxPostingBottom', '');		

if ( $htmlBoxPostingBottom != "") {
	echo "<div class='cofiHtmlBoxPostingBottom'>";
		echo $htmlBoxPostingBottom;
	echo "</div>";
}
?>
<!-- HTML Box Bottom -->


<?php
include( 'components/com_discussions/includes/footer.php');
?>

</div>
</article>