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

require_once( JPATH_COMPONENT.DS.'classes/user.php');
require_once( JPATH_COMPONENT.DS.'classes/helper.php');
?>
<article>
<div class="codingfish">

<?php

echo "<script type='text/javascript'>";
	echo "function confirmdelete() { ";
 		echo "return confirm('" . JText::_( 'COFI_CONFIRM_DELETE' ) . "');";
	echo "}"; 		
echo "</script>";


$app = JFactory::getApplication();


$user =& JFactory::getUser();
$logUser = new CofiUser( $user->id);
$CofiHelper = new CofiHelper();


// set page title and description
$document =& JFactory::getDocument(); 

$title = $document->getTitle();

$title = $this->subject . " - " . $this->categoryName;

$document->setTitle( $title);


$_search = array();
$_search[0] = '/\n/';
$_search[1] = '/\r/';
$_search[2] = '/"/';
$_search[3] = '/</';
$_search[4] = '/>/';
$_search[5] = '/\//';
$_search[6] = '/  /';

$_replace = array();
$_replace[0] = ' ';
$_replace[1] = ' ';
$_replace[2] = ' ';
$_replace[3] = ' ';
$_replace[4] = ' ';
$_replace[5] = ' ';
$_replace[6] = ' ';

$_metaDescription = preg_replace($_search, $_replace, trim($this->metaDescription));

$document->setDescription( substr ( $_metaDescription, 0, 160)); 


if ( $this->metaKeywords == "") {
	// $document->setMetaData( "keywords", "");
}
else { // use the meta keywords configured for this forum
	$document->setMetaData( "keywords", $this->metaKeywords);
}


// get parameters
$params = JComponentHelper::getParams('com_discussions');

// show username / name?
$showUsernameName = $params->get('showUsernameName', 0);

// Display login row?
$_showLoginRow          = $params->get('showLoginRow', 0); // 0 no, 1 yes

$_imagesDisplayMode 	= $params->get( 'imagesDisplayMode', 0); // 0 Browser, 1 Slimbox, 2 RokBox, 3 Widgetkit
$_includeMootoolsJS 	= $params->get( 'includeMootoolsJS', 0); // 0 no, 1 yes
$_includeSlimboxJS  	= $params->get( 'includeSlimboxJS', 0);  // 0 no, 1 yes
$_useMessages 		    = $params->get( 'useMessages', 1);  // 0 no, 1 yes

// Flickr
$_useFlickr 	      	= $params->get( 'useFlickr', 0);  // 0 no, 1 yes
$_flickr_display_mode 	= $params->get( 'flickr_display_mode', 0); // 0 Browser, 1 Slimbox, 2 RokBox, 3 YOOeffects
$_flickr_cache_mode   	= $params->get( 'flickr_cache_mode', 0 ); // 0 = off, 1 = on
$_flickr_cache_time   	= $params->get( 'flickr_cache_time', 7200 ); // default 7200 seconds = 2 hours

// YouTube
$_useYouTube 	      	= $params->get( 'useYouTube', '0');  // 0 no, 1 yes
$_youtube_video_width 	= $params->get( 'youtube_video_width', '640');  // default 640 pixel
$_youtube_video_height	= $params->get( 'youtube_video_height', '385');  // default 385 pixel

// Google Map (static)
$_useThreadMap          = $params->get( 'useThreadMap', '0');  // 0 no, 1 yes
$_thread_map_width 	    = $params->get( 'thread_map_size_width', '600');  // default 600 pixel
$_thread_map_height	    = $params->get( 'thread_map_size_height', '100');   // default 100 pixel
$_thread_map_zoom_level	= $params->get( 'thread_map_zoom_level', '10');   // default 100 pixel


if ( $_useFlickr == 1) {

    $_flickr_apikey     = $params->get( 'flickr_apikey', '');
    require_once( JPATH_COMPONENT.DS.'includes/phpflickr/phpFlickr.php');

    $f = new phpFlickr( $_flickr_apikey);

    if ( $_flickr_cache_mode == 1) {

        $config = new JConfig();

        $_host 	    = $config->host;
        $_db 		= $config->db;
        $_dbprefix  = $config->dbprefix;
        $_user 	    = $config->user;
        $_password  = $config->password;

        $_flickr_connect = "mysql://" . $_user . ":" . $_password . "@" . $_host . "/" . $_db;

        $_flickr_cache_table = $_dbprefix . "discussions_flickr_cache";

        $f->enableCache(
            "db",
            $_flickr_connect,
            $_flickr_cache_time,
            $_flickr_cache_table
        );

    }

}



if ( $_imagesDisplayMode == 1) { // Slimbox
	$assets = JURI::root() . "components/com_discussions/assets";
	$document->addStyleSheet( $assets.'/css/slimbox.css');
}


// website root directory
$_root = JURI::root();
?>


<!-- Javascript functions -->

<?php
if ( $_includeMootoolsJS == 1) { // include Mootools JS
	echo "<script type=\"text/javascript\" src=\"" . $assets . "/js/mootools.js\"></script>";
}	

if ( $_includeSlimboxJS == 1) { // include Slimbox JS
	echo "<script type=\"text/javascript\" src=\"" . $assets . "/js/slimbox.js\"></script>";
}	
?>

<script type="text/javascript">

    function callURL(obj) {

        $catid 		= obj.options[obj.selectedIndex].value;
		var length 	= slugsarray.length;

		for(var k=0; k < slugsarray.length; k++) {
			
			// if selected index found jump to category
			if ( slugsarray[k][0] == $catid) {
         		location.href = slugsarray[k][1];
        	}

		}			

    }

</script>
<!-- Javascript functions -->



<!-- HTML Box Top -->
<?php
$_htmlBoxTop = $this->htmlBoxTop;

if ( $_htmlBoxTop != "") {
	echo "<div class='cofiHtmlBoxThreadTop'>";
		echo $_htmlBoxTop;
	echo "</div>";
}
?>
<!-- HTML Box Top -->



<?php
include( 'components/com_discussions/includes/topmenu.php');
?>



<!-- Category icon, name and description -->
<table width="100%" class="noborder" style="margin-bottom:10px;">
    <tr>

        <!-- category image -->
        <td width="50" class="noborder">
            <?php
			if ( $this->categoryImage == "") {  // show default category image
				echo "<img src='" . $_root . "components/com_discussions/assets/categories/default.png' style='border:0px;margin:5px;' />";
			}
			else {
				echo "<img src='" . $_root . "components/com_discussions/assets/categories/".$this->categoryImage."' style='border:0px;margin:5px;' />";
			}
            ?>
        </td>
        <!-- category image -->

        <!-- category name and description -->
        <td align="left" class="noborder">
            <?php
            echo "<h2 style='padding-left: 0px;'>";
                echo $this->categoryName;
            echo "</h2>";
            echo $this->categoryDescription;
            ?>
        </td>
        <!-- category name and description -->

        <!-- category quick select box -->
        <td align="left" class="noborder">
            <?php
            echo $CofiHelper->getQuickJumpSelectBox( $this->categoryId);
            ?>
        </td>
        <!-- category quick select box -->

    </tr>
</table>
<!-- Category icon, name and description -->




<?php
// Forum specific top banner

if ( $this->forumBannerTop != "") {

	echo "<table width='100%' border='0' class='noborder' style='margin-top:10px;'>";
	
	    echo "<tr>";
	
	    	echo "<td width='100%' align='center' class='noborder'>";
					?>
		
					<script type='text/javascript'>
		
					<?php			
		            echo $this->forumBannerTop;
					?>
		
					</script>
		
					<?php
	    	echo "</td>";
	
	    echo "</tr>";
				
	echo "</table>";

}
			
// Forum specific top banner
?>



<!-- Post, Reply,... Links -->
<?php
if ( $user->guest) { // user is not logged in

    if ( $_showLoginRow == 1) {

        echo "<table width='100%' class='noborder' style='margin:20px 0px 20px 0px;' border='0' >";
            echo "<tr>";
                echo "<td width='100%' align='left' valign='middle' class='noborder'>";
                    $registerURL = "index.php?option=com_users&view=registration";
                    $loginURL    = "index.php?option=com_users&view=login";

                    echo JText::_( 'COFI_NO_PUBLIC_WRITE' );

                    echo "<a href='" . JRoute::_( $loginURL) . "' >" . JText::_( 'COFI_NO_PUBLIC_WRITE_LOGIN' ) . "</a>";
                    echo JText::_( 'COFI_OR' );
                    echo "<a href='" . JRoute::_( $registerURL) . "' >" . JText::_( 'COFI_NO_PUBLIC_WRITE_REGISTER' ) . "</a>";
                echo "</td>";
            echo "</tr>";

        echo "</table>";

    }
        
	echo "<table class='noborder' style='margin:20px 0px 20px 0px;'>";

    	echo "<tr>";
        
        	echo "<td width='16' align='center' valign='middle' class='noborder' style='padding-left: 0px;'>";
            	echo "<img src='" . $_root . "components/com_discussions/assets/system/lastentry.png' style='margin-left: 5px; margin-right: 5px; border:0px;' />";
        	echo "</td>";
        	
        	echo "<td align='left' valign='middle' class='noborder'>";
				$menuLinkLastTMP = "index.php?option=com_discussions&view=thread&catid=" . $this->categorySlug . "&thread=" . $this->threadSlug;
				$menuLinkLastTMP .= $this->lastEntryJumpPoint;
            	$menuLinkLast = JRoute::_( $menuLinkLastTMP);
            	echo "<a href='".$menuLinkLast."'>" . JText::_( 'COFI_GOTO_LAST_ENTRY' ) . "</a>";
        	echo "</td>";        


    	echo "</tr>";
        
        
        
    echo "</table>";
    
}
else { // user is logged in

	echo "<table class='noborder' style='margin:20px 0px 20px 0px;'>";
	
    	echo "<tr>";       	
    	
    		if ( $this->lockedStatus == 0 || $logUser->isModerator()) { // thread is not locked or user is moderator
        		echo "<td width='16' align='center' valign='middle' class='noborder' style='padding-left: 0px;' >";
            		echo "<img src='" . $_root . "components/com_discussions/assets/threads/reply.png' style='margin-left: 15px; margin-right: 5px; border:0px;' />";
        		echo "</td>";
        		echo "<td align='left' valign='middle' class='noborder'>";
            		$menuLinkReplyTMP = "index.php?option=com_discussions&view=posting&task=reply&catid=".$this->categorySlug."&thread=".$this->thread."&parent=".$this->threadId;
            		$menuLinkReply = JRoute::_( $menuLinkReplyTMP);
            		echo "<a href='".$menuLinkReply."'>" . JText::_( 'COFI_REPLY1' ) . "</a>";
        		echo "</td>";
			}

        	echo "<td width='16' align='center' valign='middle' class='noborder' style='padding-left: 20px;'>";
            	echo "<img src='" . $_root . "components/com_discussions/assets/threads/new.png' style='margin-left: 5px; margin-right: 5px; border:0px;' />";
        	echo "</td>";
        	
        	echo "<td align='left' valign='middle' class='noborder'>";
            	$menuLinkNewTMP = "index.php?option=com_discussions&view=posting&task=new&catid=" . $this->categorySlug;
            	$menuLinkNew = JRoute::_( $menuLinkNewTMP);
            	echo "<a href='".$menuLinkNew."'>" . JText::_( 'COFI_NEW_THREAD' ) . "</a>";
        	echo "</td>";                	
        	
        	echo "<td width='16' align='center' valign='middle' class='noborder' style='padding-left: 20px;'>";
            	echo "<img src='" . $_root . "components/com_discussions/assets/system/lastentry.png' style='margin-left: 5px; margin-right: 5px; border:0px;' />";
        	echo "</td>";
        	
        	echo "<td align='left' valign='middle' class='noborder'>";
				$menuLinkLastTMP = "index.php?option=com_discussions&view=thread&catid=" . $this->categorySlug . "&thread=" . $this->threadSlug;
				$menuLinkLastTMP .= $this->lastEntryJumpPoint;
            	$menuLinkLast = JRoute::_( $menuLinkLastTMP);
            	echo "<a href='".$menuLinkLast."'>" . JText::_( 'COFI_GOTO_LAST_ENTRY' ) . "</a>";
        	echo "</td>";        

    	echo "</tr>";
    	
	echo "</table>";
}
?>
<!-- Post, Reply,... Links -->





<!-- Breadcrumb -->
<?php
$showBreadcrumbRow = $params->get('breadcrumb', '0');		

if ( $showBreadcrumbRow == "1") {
	?>

	<table class="noborder" style="margin-top: 5px;">
	    <tr>
	        <td class="noborder">
	            <?php
	            $menuLinkHome     = JRoute::_( 'index.php?option=com_discussions');
				$menuText = $app->getMenu()->getActive()->title;	
	            echo "<a href='$menuLinkHome'>" . $menuText . "</a>";
	            ?>
	        </td>
	        <td class="noborder">
	            <?php
	            $menuLinkCategoryTMP = "index.php?option=com_discussions&view=category&catid=".$this->categorySlug;
	            $menuLinkCategory = JRoute::_( $menuLinkCategoryTMP);
	            echo "&nbsp;&raquo;&nbsp;";
	            echo "<a href='$menuLinkCategory'>".$this->categoryName."</a>";
	            ?>
	        </td>
	        <td class="noborder">
	            <?php
	            echo "&nbsp;&raquo;&nbsp;";
	            echo $this->subject;
	            ?>
	        </td>
	    </tr>
	</table>

	<?php
}
?>
<!-- Breadcrumb -->



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



<table width="100%" border="0" cellspacing="0" cellpadding="5" class="noborder">

	<?php
	$rowColor = 1;
	$counter  = 1;

	foreach ( $this->postings as $posting ) : ?>

    	<tr>

			<td width="100" align="center" valign="top" class="cofiThreadTableRow<?php echo $rowColor; ?> cofiThreadBorder1" >
                <?php

                // show avatar and username
                echo "<div class='cofiAvatarBox'>";
                $CofiUser = new CofiUser( $posting->user_id);

                if ( $showUsernameName == 1) {
                    $opUserUsername = $CofiHelper->getRealnameById( $posting->user_id);
                }
                else {
                    $opUserUsername = $CofiHelper->getUsernameById( $posting->user_id);
                }

                if ( $CofiUser->getAvatar() == "") { // display default avatar
                    echo "<img src='" . $_root . "components/com_discussions/assets/users/user.png' class='cofiAvatar' alt='$opUserUsername' title='$opUserUsername' />";
                }
                else { // display uploaded avatar
                    echo "<img src='" . $_root . "images/discussions/users/".$posting->user_id."/large/".$CofiUser->getAvatar()."' class='cofiAvatar' alt='$opUserUsername' title='$opUserUsername' />";
                }
                echo "</div>";



                // display social media buttons
				$twitter    = $CofiUser->getTwitter();
				$facebook   = $CofiUser->getFacebook();
				$flickr     = $CofiUser->getFlickr();
				$youtube    = $CofiUser->getYoutube();
                $googleplus = $CofiUser->getGoogleplus();

				if( $twitter != "" || $facebook != "" || $googleplus != "" || $flickr != "" || $youtube != "" || $_useMessages == "1") {
				
                	echo "<div class='cofiSocialMediaBox'>";

	                if ( $twitter != "") {
	                	echo "<a href='http://" . $twitter . "' title='" . $opUserUsername . " " . JText::_( 'COFI_ON' ) . " Twitter' target='_blank' >";
						echo "<img src='" . $_root . "components/com_discussions/assets/icons/twitter_16.png' style='margin: 10px 5px 10px 5px;' />";  
						echo "</a>";              
	                }
	
	                if ( $facebook != "") {
	                	echo "<a href='http://" . $facebook . "' title='" . $opUserUsername . " " . JText::_( 'COFI_ON' ) . " Facebook' target='_blank' >";
						echo "<img src='" . $_root . "components/com_discussions/assets/icons/facebook_16.png' style='margin: 10px 5px 10px 5px;' />";
						echo "</a>";              
	                }

                    if ( $googleplus != "") {
                        echo "<a href='http://" . $googleplus . "' title='" . $opUserUsername . " " . JText::_( 'COFI_ON' ) . " Google+' target='_blank' >";
                        echo "<img src='" . $_root . "components/com_discussions/assets/icons/google_plus_16.png' style='margin: 10px 5px 10px 5px;' />";
                        echo "</a>";
                    }

	                if ( $flickr != "") {
	                	echo "<a href='http://" . $flickr . "' title='" . $opUserUsername . " " . JText::_( 'COFI_ON' ) . " Flickr' target='_blank' >";
						echo "<img src='" . $_root . "components/com_discussions/assets/icons/flickr_16.png' style='margin: 10px 5px 10px 5px;' />";
						echo "</a>";              
	                }
	
	                if ( $youtube != "") {
	                	echo "<a href='http://" . $youtube . "' title='" . $opUserUsername . " " . JText::_( 'COFI_ON' ) . " YouTube' target='_blank' >";
						echo "<img src='" . $_root . "components/com_discussions/assets/icons/youtube_16.png' style='margin: 10px 5px 10px 5px;' />";
						echo "</a>";              
	                }


					if ( $user->guest) { // do nothing
						echo "<br />";
					}
					else {
		                if ( $_useMessages == "1" && $opUserUsername != "-") {
		                
	
		                    $_username = strtolower( $opUserUsername);
		                    
		                    
							$linkMessages  = JRoute::_( 'index.php?option=com_discussions&view=message&task=msg_new&userid=' . $posting->user_id);

	            			echo "<a href='" . $linkMessages . "' title='" . JText::_( 'COFI_MESSAGE_TO' ) . " " . $opUserUsername . "' >";
							echo "<img src='" . $_root . "components/com_discussions/assets/icons/pn_16.png' style='margin: 10px 5px 10px 5px;' />";
							echo "</a>";              										
	
		                }
	                }


                	echo "</div>";

				}
				else {
                	echo "<br />";                
				}




                // display username
                echo "<b>";
                	echo $opUserUsername;
                echo "</b>";
                echo "<br />";
                


				echo "<div class='cofiAvatarColumnPosts'>";
					$_posts = $CofiUser->getPosts();
					
					if ( $_posts == 1) {
                		echo $_posts . " " . JText::_( 'COFI_POST' );
					}
					else {
                		echo $_posts . " " . JText::_( 'COFI_POSTSCOUNTER' );
					}					
                echo "</div>";



				// online status
				echo "<div class='cofiAvatarColumnOnlineStatus'>";
				
					if ( $user->guest) { // user is not logged in

						echo "<div class='cofiAvatarColumnOnlineStatusOffline'>";
							echo JText::_( 'COFI_OFFLINE_GUEST' );
						echo "</div>";
					
					}
					else { // user is logged in
							
						if ( $CofiUser->getShowOnlineStatus() && $CofiHelper->isUserOnlineById( $posting->user_id)) {
							echo "<div class='cofiAvatarColumnOnlineStatusOnline'>";
								echo JText::_( 'COFI_ONLINE' );
							echo "</div>";
						}
						else {
							echo "<div class='cofiAvatarColumnOnlineStatusOffline'>";
								echo JText::_( 'COFI_OFFLINE' );
							echo "</div>";
						}
					
					}
				
				echo "</div>";



				// location
				echo "<div class='cofiAvatarColumnLocation'>";
				
				echo JText::_( 'COFI_LOCATION' ) . ":";
                echo "<br />";


                $city    = $CofiUser->getCity();
                $country = $CofiUser->getCountry();

                if ( $city != "" || $country != "") {
                
                	if ( $city != "") {	
						echo $city;
                		if ( $country != "") {	
                			echo "<br />";
                		}
					}
                	if ( $country != "") {	
						echo $country;
					}
					
				}
				else { // nothing set
					echo JText::_( 'COFI_NO_LOCATION' );
				}
				echo "</div>";



                // rank
				echo "<div class='cofiAvatarColumnTitel'>";
                if ( $CofiUser->getTitle() != "") { // display title

                    echo "<i>";
                    echo $CofiUser->getTitle();
                    echo "</i>";
                    echo "<br />";

                    switch ($CofiUser->getTitle()) {
                    
                        case "Community Manager":
                        case "Administrator": {
                            echo "<img src='" . $_root . "components/com_discussions/assets/system/administrator.png' style='width:16px;border:0px;margin:5px;' />";
                            break;
                        }
                        case "Moderator": {
                            echo "<img src='" . $_root . "components/com_discussions/assets/system/moderator.png' style='width:16px;border:0px;margin:5px;' />";
                            break;
                        }
                        default: {
                            break;
                        }
                    }
                    
                }                
                echo "</div>";

                ?>
			</td>

			<td align="left" valign="top" class="cofiThreadTableRow<?php echo $rowColor; ?> cofiThreadBorder2" >

				<?php
				// anchor
				echo "<a name='p" . $posting->id . "'></a>";				

                if ( $_useThreadMap == 1) {

                    // if posting has location -> show link to map
                    if ( $posting->latitude != null && $posting->longitude != null) {
                        echo " <span class='cofiMap'>";
                            $_latitude  = $posting->latitude;
                            $_longitude = $posting->longitude;
                            echo "<img src='http://maps.google.com/maps/api/staticmap?center=" . $_latitude . "," . $_longitude . "&zoom=" . $_thread_map_zoom_level . "&size=" . $_thread_map_width . "x" . $_thread_map_height . "&sensor=false&markers=color:red%7C" . $_latitude . "," . $_longitude ."'>";
                        echo "</span>";
                        echo "<br>";
                        echo "<br>";
                    }

                }

                echo $posting->date;

                // if posting not from web -> show via <source>
                if ( $posting->apikey_id > 0) { // only show via if not from web

                    $_vianame = $CofiHelper->getViaNameById( $posting->apikey_id);
                    $_viaurl  = $CofiHelper->getViaUrlById( $posting->apikey_id);

                    echo " <span class='cofiViaLink'>";
                        if ( $_viaurl != "") {
                            echo "via <a href='$_viaurl' target='_blank' title='$_vianame' style='color: #999999;'>" . $_vianame . "</a>";
                        }
                        else {
                            echo "via " . $_vianame;
                        }
                    echo "</span>";

                }

				$pageOffset = JRequest::getVar('limitstart', 0, '', 'int');
				
				if ( $pageOffset == 0) { // first page off this thread

					if ( $counter == 1) { // h3 and icons in first row only
										
            			echo "<h3 style='font-weight: bold; margin: 3px 0px 1px 0px;'>";
            				echo $posting->subject;
            			echo "</h3>";
				
				
						echo "<div class='cofiSocialMediaButtonRow'>";
						
							echo "<div class='cofiSocialMediaButton1'>";
                                $_socialMediaButton1 = $this->socialMediaButton1;
								echo $_socialMediaButton1;
							echo "</div>";
	
							echo "<div class='cofiSocialMediaButton2'>";
                                $_socialMediaButton2 = $this->socialMediaButton2;
                                echo $_socialMediaButton2;
							echo "</div>";
										
							echo "<div class='cofiSocialMediaButton3'>";
                                $_socialMediaButton3 = $this->socialMediaButton3;
                                echo $_socialMediaButton3;
							echo "</div>";
							
						echo "</div>";

						echo "<div class='clr' style='margin-bottom:5px; clear:left;'></div>";
					
					}
					else {
					
						echo "<div style='margin: 5px 0px 5px 0px;'>";
	                		echo "<h4>";
	                			echo $posting->subject;
	                		echo "</h4>";
						echo "</div>";
						
					}
					
				}
				else { // following pages
					echo "<div style='margin: 5px 0px 5px 0px;'>";
                		echo "<h4>";
                			echo $posting->subject;
                		echo "</h4>";
					echo "</div>";					
				}
					


                echo "<div class='cofiHorizontalRuler'></div>";

                $message = $posting->message;    
                            
                // transfer bbcode into html code
				$message = $CofiHelper->replace_bb_tags( $message);

                // transfer emoticon code into html image code
				$message = $CofiHelper->replace_emoticon_tags( $message);				

                if ( $_useFlickr == 1) {
                    // transfer flickr tags into image code
                    $message = $CofiHelper->replace_flickr_tags( $f, $_flickr_display_mode, $posting->id, $message);
                }


                if ( $_useYouTube == 1) {
                    // transfer youtube tags into inline code
                    $message = $CofiHelper->replace_youtube_tags( $_youtube_video_width, $_youtube_video_height, $message);
                }


				// close html tags
				$message = $CofiHelper->close_html_tags( $message);

				$message = nl2br( $message);


				echo "<span class='cofiMessage'>";
                	echo $message;
				echo "</span>";
                
                echo "<br />";
                echo "<br />";


				// image attachements
				if 	( 	$posting->image1 != "" ||
						$posting->image2 != "" ||
						$posting->image3 != "" ||
						$posting->image4 != "" ||
						$posting->image5 != "" ) { // found attachement(s)
				
					
					echo "<div class='cofiImageAttachmentRow'>";
					

					$_titleprefix = "";
					switch ( $_imagesDisplayMode) { // set rel and target
					
						case 1: { // Slimbox
							$_linktag = " rel='lightbox-" . $posting->id . "' ";
							break;
						}

						case 2: { // RokBox
							$_linktag = " rel='rokbox (" . $posting->id . ")' ";
							$_titleprefix = $posting->subject . " :: ";
							break;
						}

						case 3: { // Widgetkit
                            $_linktag = " data-lightbox='group:" . $posting->id . "' ";
                            break;
                        }

						default: { // Set to Browser display by default
							$_linktag = " target='_blank' ";
							break;
						}

					
					}


				    if ( $posting->image1 != "") { 
   						echo "<div class='cofiImageAttachment1'>";

							echo "<a href='" . $_root . "images/discussions/posts/".$posting->thread."/".$posting->id."/large/".$posting->image1."' " .$_linktag . " title='".$_titleprefix.$posting->image1_description . "' >";
							echo "<img src='" . $_root . "images/discussions/posts/".$posting->thread."/".$posting->id."/small/".$posting->image1."' alt='.".$posting->image1_description."' class='cofiAttachmentImageEdit' />";
							echo "</a>";
					        	
   						echo "</div>";
				    }			    

				    if ( $posting->image2 != "") { 
   						echo "<div class='cofiImageAttachment2'>";

							echo "<a href='" . $_root . "images/discussions/posts/".$posting->thread."/".$posting->id."/large/".$posting->image2."' " . $_linktag . " title='".$_titleprefix.$posting->image2_description . "' >";
							echo "<img src='" . $_root . "images/discussions/posts/".$posting->thread."/".$posting->id."/small/".$posting->image2."' alt='.".$posting->image2_description."' class='cofiAttachmentImageEdit' />";
							echo "</a>";
					        	
   						echo "</div>";
				    }			    

				    if ( $posting->image3 != "") { 
   						echo "<div class='cofiImageAttachment3'>";

							echo "<a href='" . $_root . "images/discussions/posts/".$posting->thread."/".$posting->id."/large/".$posting->image3."' " . $_linktag . " title='".$_titleprefix.$posting->image3_description . "' >";
							echo "<img src='" . $_root . "images/discussions/posts/".$posting->thread."/".$posting->id."/small/".$posting->image3."' alt='.".$posting->image3_description."' class='cofiAttachmentImageEdit' />";
							echo "</a>";
					        	
   						echo "</div>";
				    }			    

				    if ( $posting->image4 != "") { 
   						echo "<div class='cofiImageAttachment4'>";

							echo "<a href='" . $_root . "images/discussions/posts/".$posting->thread."/".$posting->id."/large/".$posting->image4."' " . $_linktag . " title='".$_titleprefix.$posting->image4_description . "' >";
							echo "<img src='" . $_root . "images/discussions/posts/".$posting->thread."/".$posting->id."/small/".$posting->image4."' alt='.".$posting->image4_description."' class='cofiAttachmentImageEdit' />";
							echo "</a>";
					        	
   						echo "</div>";
				    }			    

				    if ( $posting->image5 != "") { 
   						echo "<div class='cofiImageAttachment5'>";

							echo "<a href='" . $_root . "images/discussions/posts/".$posting->thread."/".$posting->id."/large/".$posting->image5."' " . $_linktag . " title='".$_titleprefix.$posting->image5_description . "' >";
							echo "<img src='" . $_root . "images/discussions/posts/".$posting->thread."/".$posting->id."/small/".$posting->image5."' alt='.".$posting->image5_description."' class='cofiAttachmentImageEdit' />";
							echo "</a>";
					        	
   						echo "</div>";
				    }			    

					echo "<div class='clr' style='margin-bottom:5px'></div>";
									
					echo "</div>";
				
				
					echo "<br />";
					echo "<br />";				
				}



                $signature = nl2br($CofiUser->getSignature());
                if ( $signature != "") { // display signature hr if one is present
                    echo "<div class='cofiHorizontalRuler'></div>";
                    echo $signature;
                }

				$website = $CofiUser->getWebsite();
                if ( $website != "") { // display website 
                	echo "<br />";                
                	if ( $signature == "") { // draw hr if no signature is available
                    	echo "<div class='cofiHorizontalRuler'></div>";
                    }
                    echo "<a href='http://" . $website . "' target='_blank' title='" . $website . "' rel='nofollow'>";
                    	echo $website;
                    echo "</a>";
                }



                echo "<br />";
                echo "<br />";





                if ( !$user->guest) { // user is logged in. display menue in post

                	echo "<div class='cofiPostMenu'>";

							if ( $this->lockedStatus == 0 || $logUser->isModerator()) { // thread is not locked or user is moderator
							
					        	// reply to post
								echo "<div class='cofiPostMenuItem'>";

									echo "<div class='cofiPostMenuIcon'>";
									
					            		echo "<img src='" . $_root . "components/com_discussions/assets/threads/reply.png' />";
					            		
					            	echo "</div>";

									echo "<div class='cofiPostMenuText'>";
					            	
						                echo "<div class='cofiPostMenuLinks'>";
						                    $menuLinkReplyTMP = "index.php?option=com_discussions&view=posting&task=reply&catid=".$this->categorySlug."&thread=".$this->thread."&parent=".$posting->id;
						                    $menuLinkReply = JRoute::_( $menuLinkReplyTMP);
						                    echo "<a href='".$menuLinkReply."'>" . JText::_( 'COFI_REPLY2' ) . "</a>";
						                echo "</div>";
						                
						        	echo "</div>";
						                
								echo "</div>";
					
					
					        	// reply to post with quote 
								echo "<div class='cofiPostMenuItem'>";
								
									echo "<div class='cofiPostMenuIcon'>";
									
					            		echo "<img src='" . $_root . "components/com_discussions/assets/threads/quote.png' />";
					            		
					            	echo "</div>";

					            	echo "<div class='cofiPostMenuText'>";
					            	
						            	echo "<div class='cofiPostMenuLinks'>";
						            	
											$menuLinkQuoteTMP = "index.php?option=com_discussions&view=posting&task=quote&catid=".$this->categorySlug."&thread=".$this->thread."&parent=".$posting->id."&id=".$posting->id;
											$menuLinkQuote = JRoute::_( $menuLinkQuoteTMP);
											echo "<a href='".$menuLinkQuote."'>" . JText::_( 'COFI_QUOTE2' ) . "</a>";
						            	echo "</div>";
						            	
						            echo "</div>";	
						            
								echo "</div>";
					
					
					
								// edit post
					        	// check if user is post owner or has moderator rights
					                                
					        	$date = $posting->date;
					        
					        	$day = substr( $date, 0, 2);  // 1 + 2 char
					        	$month = substr( $date, 3, 2);  // 4 + 5 char
					        	$year = substr( $date, 6, 4);  // 7 - 10 char
					        
					        	$hour = substr( $date, 11, 2);  // 12 + 13 char
					        	$minute = substr( $date, 14, 2);  // 15 + 16 char
					        
					
					        	//date_default_timezone_set ( "Europe/Berlin");
					        
					        	$now = time(); // current unixtime
					        
					        	$posttime = mktime( $hour, $minute, 0, $month, $day, $year); // unixtime from post date
					        
					        	$isUserEditable = true;
					        
					        	// get editTime in minutes from global parameters
					        	$editTime = $params->get('editTime', '30');		
								                        
					        	if ( ($now - $posttime) > ( $editTime * 60)) {
					        		$isUserEditable = false;
					       		}
					       		
					       		$editForever = $params->get('editForever', '1');		
					       		if ( $editForever == 1) {
					        		$isUserEditable = true;
					        	}
					       		
					        
					        	if ( $logUser->isModerator() || ( ($logUser->getId() == $CofiUser->getId()) && $isUserEditable == true)) {
					        	
									echo "<div class='cofiPostMenuItem'>";
									
										echo "<div class='cofiPostMenuIcon'>";

						                	echo "<img src='" . $_root . "components/com_discussions/assets/threads/edit.png' />";
						                	
						            	echo "</div>";	

										echo "<div class='cofiPostMenuText'>";

						                	echo "<div class='cofiPostMenuLinks'>";
						                	
												$menuLinkEditTMP = "index.php?option=com_discussions&view=posting&task=edit&catid=".$this->categorySlug."&thread=".$this->thread."&parent=".$posting->id."&id=".$posting->id;
												$menuLinkEdit = JRoute::_( $menuLinkEditTMP);
												echo "<a href='".$menuLinkEdit."'>" . JText::_( 'COFI_EDIT' ) . "</a>";
												
						                	echo "</div>";
						                	
					            		echo "</div>";						                	
						                	
					            	echo "</div>";
					        	}
					
							} // if locked == 0 or user is moderator
							
							else {
									echo "<div class='cofiPostMenuItem'>";

										echo "<div class='cofiPostMenuIcon'>";
									
											echo "<img src='" . $_root . "components/com_discussions/assets/threads/lock.png' />";

					            		echo "</div>";						                	
						
										echo "<div class='cofiPostMenuText'>";

											echo "<div class='cofiPostMenuLinks'>";
											
												echo JText::_( 'COFI_THREAD_IS_LOCKED' );									
												
											echo "</div>";

					            		echo "</div>";						                	
										
									echo "</div>";
							
							}
							
					
							
							// delete post / thread
							if ( $logUser->isModerator()) {
						
									echo "<div class='cofiPostMenuItem'>";
									
										echo "<div class='cofiPostMenuIcon'>";
										
											echo "<img src='" . $_root . "components/com_discussions/assets/threads/delete.png' />";

					            		echo "</div>";						                	

										echo "<div class='cofiPostMenuText'>";
						
											echo "<div class='cofiPostMenuLinks'>";
						
												$menuLinkDeleteTMP = "index.php?option=com_discussions&view=moderation&task=delete&id=".$posting->id;
												$menuLinkDelete = JRoute::_( $menuLinkDeleteTMP);
										
												echo "<a href='".$menuLinkDelete."' onclick='return confirmdelete();'>" . JText::_( 'COFI_DELETE' ) . "</a>";
						
											echo "</div>";

					            		echo "</div>";						                	
										
									echo "</div>";
							}						
							
							
							
							
							// check if it is the first post in a thread (parent_id == 0)
					        if ( $posting->parent_id == 0) {
							
								// check if user has moderator rights
								if ( $logUser->isModerator()) {
							
									// move thread
									echo "<div class='cofiPostMenuItem'>";

										echo "<div class='cofiPostMenuIcon'>";
									
											echo "<img src='" . $_root . "components/com_discussions/assets/threads/move.png' />";

					            		echo "</div>";						                	

										echo "<div class='cofiPostMenuText'>";
						
											echo "<div class='cofiPostMenuLinks'>";
						
												$menuLinkMoveTMP = "index.php?option=com_discussions&view=moderation&task=move&catid=".$this->categorySlug."&thread=".$this->thread;
												$menuLinkMove = JRoute::_( $menuLinkMoveTMP);
											
												echo "<a href='".$menuLinkMove."'>" . JText::_( 'COFI_MOVE' ) . "</a>";
						
											echo "</div>";

					            		echo "</div>";						                	
										
									echo "</div>";
					
					
									// sticky or unsticky thread
									echo "<div class='cofiPostMenuItem'>";

										echo "<div class='cofiPostMenuIcon'>";
									
											echo "<img src='" . $_root . "components/com_discussions/assets/threads/sticky.png' />";

					            		echo "</div>";						                	

										echo "<div class='cofiPostMenuText'>";
						
											echo "<div class='cofiPostMenuLinks'>";
						
												if ( $this->stickyStatus == 0) { // thread is not sticky
													$menuLinkStickyTMP = "index.php?option=com_discussions&view=moderation&task=sticky&catid=".$this->categorySlug."&thread=".$this->thread;
													$menuLinkSticky = JRoute::_( $menuLinkStickyTMP);
													echo "<a href='".$menuLinkSticky."'>" . JText::_( 'COFI_STICKY' ) . "</a>";
												}
												else {
													$menuLinkUnstickyTMP = "index.php?option=com_discussions&view=moderation&task=unsticky&catid=".$this->categorySlug."&thread=".$this->thread;
													$menuLinkUnsticky = JRoute::_( $menuLinkUnstickyTMP);
													echo "<a href='".$menuLinkUnsticky."'>" . JText::_( 'COFI_UNSTICKY' ) . "</a>";
												}
						
											echo "</div>";

					            		echo "</div>";						                	
										
									echo "</div>";
					
					
									// close thread
									echo "<div class='cofiPostMenuItem'>";
									
										echo "<div class='cofiPostMenuIcon'>";
									
											echo "<img src='" . $_root . "components/com_discussions/assets/threads/lock.png' />";

					            		echo "</div>";						                	

										echo "<div class='cofiPostMenuText'>";
										
											echo "<div class='cofiPostMenuLinks'>";
						
												if ( $this->lockedStatus == 0) { // thread is not locked
													$menuLinkLockTMP = "index.php?option=com_discussions&view=moderation&task=lock&catid=".$this->categorySlug."&thread=".$this->thread;
													$menuLinkLock = JRoute::_( $menuLinkLockTMP);
													echo "<a href='".$menuLinkLock."'>" . JText::_( 'COFI_LOCK' ) . "</a>";
												}
												else {
													$menuLinkUnlockTMP = "index.php?option=com_discussions&view=moderation&task=unlock&catid=".$this->categorySlug."&thread=".$this->thread;
													$menuLinkUnlock = JRoute::_( $menuLinkUnlockTMP);
													echo "<a href='".$menuLinkUnlock."'>" . JText::_( 'COFI_UNLOCK' ) . "</a>";
												}
											
											echo "</div>";
										
					            		echo "</div>";						                	
										
									echo "</div>";
								
								}
					        
					        }

                	echo "</div>";
                	
                	echo "<br />";
                	

                }

                ?>

			</td>

    	</tr>

    	<tr>
			<td align="center" class="noborder">
			
			<?php
			
			if ( $counter == 1) {
				// for future use (banner after 1 post)
				echo "&nbsp;";								
			}
			else {
				echo "&nbsp;";
			}
			
			?>
			
			</td>
    	</tr>

		<?php
        $rowColor = 2;

		$counter++;
		
	endforeach;
	?>

</table>



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



<!-- Breadcrumb -->
<?php
if ( $showBreadcrumbRow == "1") {
	?>

	<table class="noborder" style="margin-top: 5px;">
	    <tr>
	        <td class="noborder">
	            <?php
	            echo "<a href='$menuLinkHome'>" . $menuText . "</a>";
	            ?>
	        </td>
	        <td class="noborder">
	            <?php
	            echo "&nbsp;&raquo;&nbsp;";
	            echo "<a href='$menuLinkCategory'>".$this->categoryName."</a>";
	            ?>
	        </td>
	        <td class="noborder">
	            <?php
	            echo "&nbsp;&raquo;&nbsp;";
	            echo $this->subject;
	            ?>
	        </td>
	    </tr>
	</table>

	<?php
}
?>
<!-- Breadcrumb -->



<?php
// Forum specific bottom banner

if ( $this->forumBannerBottom != "") {

	echo "<table width='100%' border='0' class='noborder' style='margin-top:10px;'>";
	
	    echo "<tr>";
	
	    	echo "<td width='100%' align='center' class='noborder'>";
					?>
		
					<script type='text/javascript'>
		
					<?php			
		            echo $this->forumBannerBottom;
					?>
		
					</script>
		
					<?php			
	    	echo "</td>";
	
	    echo "</tr>";
				
	echo "</table>";
	
}

// Forum specific bottom banner
?>



<?php
include( 'components/com_discussions/includes/share.php');
?>


<!-- HTML Box Bottom -->
<?php
$_htmlBoxBottom = $this->htmlBoxBottom;

if ( $_htmlBoxBottom != "") {
	echo "<div class='cofiHtmlBoxThreadBottom'>";
		echo $_htmlBoxBottom;
	echo "</div>";
}
?>
<!-- HTML Box Bottom -->


<?php
include( 'components/com_discussions/includes/footer.php');
?>

</div>
</article>

