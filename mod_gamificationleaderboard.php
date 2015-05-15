<?php
/**
 * @package      Gamification Platform
 * @subpackage   Modules
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// no direct access
defined( "_JEXEC" ) or die;

jimport("prism.init");
jimport("gamification.init");

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

// Get component parameters
$componentParams = JComponentHelper::getParams("com_gamification");

$imagePath       = $componentParams->get("images_directory", "images/gamification");

$leaderboard = array();

$layout  =  $params->get('layout', 'default');

switch($layout) {
    
    case "levels":
        
        $displayLevelTitle  = $params->get("levels_display_title", 1);
        $displayLevelRank   = $params->get("levels_display_rank", 0);
        
        $options = array(
            "group_id" => $params->get("levels_group_id"),
            "sort_direction" => "DESC",
            "limit"          => $params->get("results_number", 10)
        );

        /** @var $leaderboard Gamification\Leaderboard\Levels */
        $leaderboard    = new Gamification\Leaderboard\Levels(JFactory::getDbo());
        $leaderboard->load($options);

        break;
        
    default: // Points
        
        $pointsType     = $params->get("points_display_type", "abbr");
        
        $options = array(
            "points_id" => $params->get("points_id"),
            "sort_direction" => "DESC",
            "limit"          => $params->get("results_number", 10)
        );

        /** @var $leaderboard Gamification\Leaderboard\Points */
        $leaderboard    = new Gamification\Leaderboard\Points(JFactory::getDbo());
        $leaderboard->load($options);

        break;
}

$displayNumber  = $params->get("display_number", 1);
$nameLinkable   = $params->get("name_linkable", 1);
$socialPlatform = $componentParams->get("integration_social_platform");
$avatarSize     = $componentParams->get("integration_avatars_size");

$socialProfiles = null;
$numberItems    = count($leaderboard);

if (!empty($numberItems)) {
    if ($socialPlatform) {

        foreach ($leaderboard as $item) {
            $usersIds[] = $item->user_id;
        }
        $usersIds = array_unique($usersIds);

        JLoader::register("Prism\\Integration\\Profiles\\Builder", PRISM_PATH_LIBRARY . "/integration/profiles/builder.php");

        $config = array(
            "social_platform" => $socialPlatform,
            "users_ids" => $usersIds
        );

        $socialProfilesBuilder = new Prism\Integration\Profiles\Builder($config);
        $socialProfilesBuilder->build();
        $socialProfiles = $socialProfilesBuilder->getProfiles();

    }

    require JModuleHelper::getLayoutPath('mod_gamificationleaderboard', $params->get('layout', 'default'));
}
