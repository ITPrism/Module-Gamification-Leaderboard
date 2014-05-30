<?php
/**
 * @package      Gamification Platform
 * @subpackage   Modules
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die;
?>
<div class="gfy-modlb<?php echo $moduleclass_sfx;?>">

<?php for ($i = 0; $i < $numberItems; $i++) {

    // Point Type
    switch($pointsType) {
        case "abbr":
            $pointsTypeClean = htmlspecialchars($leaderboard[$i]->abbr, ENT_QUOTES, "UTF-8");
            break;

        case "title":
            $pointsTypeClean = JString::strtolower(htmlspecialchars($leaderboard[$i]->title, ENT_QUOTES, "UTF-8"));
            break;

        default:
            $pointsTypeClean = "";
            break;
    }

    // Social Profile
    if (!empty($socialProfiles)) {

        // Get avatar
        $avatar = $socialProfiles->getAvatar($leaderboard[$i]->user_id, $avatarSize);
        if (!$avatar) {
            $avatar = '<img class="media-object" src="media/com_gamification/images/no_picture.png">';
        } else {
            $avatar = '<img class="media-object" src="'.$avatar.'" width="'.$avatarSize.'" height="'.$avatarSize.'">';
        }

        $link   =  $socialProfiles->getLink($leaderboard[$i]->user_id);

    } else {
        $avatar = '<img class="media-object" src="media/com_gamification/images/no_picture.png" width="'.$avatarSize.'" height="'.$avatarSize.'">';
        $link   = 'javascript: void(0);';
    }

    if (!$nameLinkable) {
        $name = htmlspecialchars($leaderboard[$i]->name, ENT_QUOTES, "UTF-8");
    } else {
        $name = '<a href="'.$link.'">'.htmlspecialchars($leaderboard[$i]->name, ENT_QUOTES, "UTF-8").'</a>';
    }
    ?>
    <div class="media">
    <?php if ($displayNumber) {?>
        <div class="pull-left gfy-modlb-number"><?php echo $i + 1;?></div>
    <?php } ?>

        <a class="pull-left" href="<?php echo $link;?>">
        <?php echo $avatar; ?>
        </a>

        <div class="media-body">
            <h5 class="media-heading"><?php echo $name;?></h5>
            <p class="gfy-media-points"><?php echo (int)$leaderboard[$i]->points." ".$pointsTypeClean;?></p>
        </div>

    </div>
    <div class="clearfix"></div>
<?php }?>
</div>
