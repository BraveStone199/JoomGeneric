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

include( 'components/com_discussions/includes/functions.php');

require_once(JPATH_COMPONENT.DS.'classes/helper.php');

$CofiHelper = new CofiHelper();


echo "<script type='text/javascript'>";
	echo "function confirmdelete() { ";
 		echo "return confirm('" . JText::_( 'COFI_MESSAGES_CONFIRM_DELETE_MARKED_MESSAGES' ) . "');";
	echo "}"; 	
echo "</script>";
?>
<article>
<div class="codingfish">

<?php
$app = JFactory::getApplication();

// set page title and description
$document =& JFactory::getDocument(); 

$title = $document->getTitle();
$siteName = $app->getCfg('sitename');

$document->setTitle( $title); 

// get parameters
$params = JComponentHelper::getParams('com_discussions');

// website root directory
$_root = JURI::root();
?>



<!-- HTML Box Top -->
<?php
$_htmlBoxTop = $this->htmlBoxTop;
if ( $_htmlBoxTop != "") {
	echo "<div class='cofiHtmlBoxCategoryTop'>";
		echo $_htmlBoxTop;
	echo "</div>";
}
?>
<!-- HTML Box Top -->



<?php
include( 'components/com_discussions/includes/topmenu.php');
?>


<!-- Box name and description -->
<br />
<table class="noborder">
    <tr>

        <!-- box name and description -->
        <td align="left" valign="center" class="noborder">
            <?php
            echo "<h3 style='margin: 0px; padding: 0px;'>";
            	echo JText::_( "COFI_MESSAGES_INBOX");
            echo "</h3>";
            ?>
        </td>
        <td align="left" valign="center" class="noborder">
            <?php
            $menuLinkOutbox = JRoute::_( 'index.php?option=com_discussions&view=outbox&task=outbox');
            echo "<a href='$menuLinkOutbox' style='margin-left:20px;'>" . JText::_( "COFI_MESSAGES_OUTBOX", true ) . "</a>";
            ?>
        </td>
        <!-- box name and description -->


    </tr>
</table>
<br />
<!-- Box name and description -->



<?php
	echo "<table width='50%'  class='noborder' style='margin:20px 0px 20px 0px; border: 0px;'>";
    	echo "<tr>";       	

        	echo "<td width='16' align='center' valign='middle' class='noborder' style='border: 0px;'>";
            	echo "<img src='" . $_root . "/components/com_discussions/assets/threads/new.png' style='margin-left: 5px; margin-right: 5px; border:0px;' />";
        	echo "</td>";
        	echo "<td align='left' valign='middle' class='noborder' style='border: 0px;'>";
            	$menuLinkNewTMP = "index.php?option=com_discussions&view=message&task=msg_new";
            	$menuLinkNew = JRoute::_( $menuLinkNewTMP);
            	echo "<a href='".$menuLinkNew."'>" . JText::_( 'COFI_MESSAGES_NEW_MESSAGE' ) . "</a>";
        	echo "</td>";        
        	
    	echo "</tr>";
	echo "</table>";
?>



<!-- Pagination Links -->
<div class="pagination" style="border:0px;">

<table width="100%" class="noborder" style="margin-bottom:10px; border: 0px;">
    <tr>
        <td class="noborder" style="border: 0px;">
            <?php
            echo $this->pagination->getPagesLinks();
            ?>
        </td>
        <td class="noborder" style="border: 0px;">
            <p class="counter">
            <?php
            echo $this->pagination->getPagesCounter();
            ?>
            </p>
        </td>

    </tr>
</table>
    
</div>
<!-- Pagination Links -->




<form action="" method="post" name="msgform" id="msgform">

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="noborder">

    <tr> 
    
		<td width="20px"  align="center" class="cofiTableHeader">
		
			<input type="checkbox" name="cball" onclick="checkboxStateAll()" class="inputbox" />
			
		</td>

		<td width="200px" align="left" class="cofiTableHeader"><?php echo JText::_( 'COFI_MESSAGES_FROM' ); ?></td>

        <td align="left" class="cofiTableHeader"><?php echo JText::_( 'COFI_MESSAGES_SUBJECT' ); ?></td>
        
        
		<td width="170px" align="center" class="cofiTableHeader"><?php echo JText::_( 'COFI_MESSAGES_DATE' ); echo " / "; echo JText::_( 'COFI_MESSAGES_TIME' ); ?></td>
						
    </tr> 



	<?php 
	$rowColor = 1;
	
	foreach ( $this->messages as $message ) : ?>

    	<tr> 

			<td align="center" class="cofiIndexTableRow<?php echo $rowColor; ?> cofiIndexTableRowReplies">	
			
				<input type="checkbox" name="cb" value="<?php echo $message->id; ?>" onclick="checkboxState()" class="inputbox" />
				
			</td> 

			<td align="left" class="cofiIndexTableRowAvatar<?php echo $rowColor; ?> cofiIndexTableRowTopic">

				<?php
				
				$_username = $CofiHelper->getUsernameById( $message->user_from_id);
				
                $_avatar   = $CofiHelper->getAvatarById( $message->user_from_id);

                echo "<table width='100%' cellspacing='0' cellpadding='0' border='0' class='noborder'>";
                    echo "<tr>";


                        echo "<td width='32' align='left' class='noborder'>";

                            echo "<div class='cofiMessagesAvatarBox'>";
                                if ( $_avatar == "") { // display default avatar
                                    echo "<img src='" . $_root . "components/com_discussions/assets/users/user.png' width='32px' height='32px' class='cofiCategoryDefaultAvatar' alt='$_username' title='$_username' />";
                                }
                                else { // display uploaded avatar
                                    echo "<img src='" . $_root . "images/discussions/users/".$message->user_from_id."/small/".$_avatar."' width='32px' height='32px' class='cofiCategoryAvatar' alt='$_username' title='$_username' />";
                                }
                            echo "</div>";

                        echo "</td>";


                        echo "<td align='left' valign='center' class='noborder' style='padding-left: 5px;'>";


						if ( $message->flag_read == 0) { // new message
							                        
							echo "<span class='cofiMessagesMessageUnread'>";
	                        	echo $_username;
							echo "</span>";
	                        
						}
						else {

							echo "<span class='cofiMessagesMessageRead'>";
	                        	echo $_username;
							echo "</span>";
						
						}

                        echo "</td>";
                    echo "</tr>";
                echo "</table>";
				
				?>
								
			</td> 


			<td align="left" class="cofiIndexTableRow<?php echo $rowColor; ?> cofiIndexTableRowTopic">

                <?php
                
            	$_hoverSubject = $message->subject;
            	$_hoverSubject = str_replace( '\'', '"', $_hoverSubject);
            	//$_hoverSubject = addslashes($thread->subject);
                                            
                $messageLink = JRoute::_('index.php?option=com_discussions&view=message&task=inbox&id='.$message->id);

				if ( $message->flag_read == 0) {// new message
                	echo "<a href='$messageLink' title='".$_hoverSubject."' class='cofiMessageUnread'>".$message->subject."</a>";					
				}
				else {
                	echo "<a href='$messageLink' title='".$_hoverSubject."' class='cofiMessageRead'>" . $message->subject . "</a>";
                }
					
                ?>

			</td> 

									
			<td align="center" class="cofiIndexTableRow<?php echo $rowColor; ?> cofiIndexTableRowLastPost">

                <?php
				if ( $message->flag_read == 0) {// new message
					echo "<span class='cofiMessageUnread'>";
            			echo $message->msg_date;
					echo "</span>";
				}
				else {
					echo "<span class='cofiMessageRead'>";
            			echo $message->msg_date;
					echo "</span>";
                }                			                			

				echo "&nbsp;";

				if ( $message->flag_read == 0) {// new message
					echo "<span class='cofiMessageUnread'>";
            			echo $message->msg_time;
					echo "</span>";
				}
				else {
					echo "<span class='cofiMessageRead'>";
            			echo $message->msg_time;
					echo "</span>";
                }                			                			
                ?>

			</td> 
		
		
    	</tr> 




		<?php 
		// toggle row color
		if ( $rowColor == 1) {
			$rowColor = 2;
		}
		else {
			$rowColor = 1;
		}

	endforeach; 
	?>

</table>


<div style="text-align: left;">

	<div class="cofiTextButton">
	
		<input type="hidden" name="selectedMessages" value="" />
		
		<input type="submit" name="submit" class="cofiButton" value="<?php echo JText::_( 'COFI_MESSAGES_BUTTON_DELETE' );?>" onclick="return confirmdelete();" />
		&nbsp;
		<input type="submit" name="submit" class="cofiButton" value="<?php echo JText::_( 'COFI_MESSAGES_BUTTON_MARK_READ' );?>" />
		&nbsp;
		<input type="submit" name="submit" class="cofiButton" value="<?php echo JText::_( 'COFI_MESSAGES_BUTTON_MARK_UNREAD' );?>" />
	
	</div>

</div>

</form>



<!-- Pagination Links -->
<div class="pagination" style="border:0px;">

<table width="100%" class="noborder" style="margin-top:10px; border: 0px;">
    <tr> 
        <td class="noborder" style="border: 0px;">
            <?php
            echo $this->pagination->getPagesLinks();
            ?>
        </td>
        <td class="noborder" style="border: 0px;">
            <p class="counter">
            <?php
            echo $this->pagination->getPagesCounter();
            ?>
            </p>
        </td>

    </tr>
</table>

</div>
<!-- Pagination Links -->



<!-- HTML Box Bottom -->
<?php
$_htmlBoxBottom = $this->htmlBoxBottom;

if ( $_htmlBoxBottom != "") {
	echo "<div class='cofiHtmlBoxCategoryBottom'>";
		echo $_htmlBoxBottom;
	echo "</div>";
}
?>
<!-- HTML Box Bottom -->


<br />
<br />
 
<?php
include( 'components/com_discussions/includes/footer.php');
?>

</div>
</article>

