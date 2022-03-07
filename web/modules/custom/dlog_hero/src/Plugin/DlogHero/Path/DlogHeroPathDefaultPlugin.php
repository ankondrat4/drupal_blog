<?php

namespace Drupal\dlog_hero\Plugin\DlogHero\Path;

use Drupal\dlog_hero\Annotation\DlogHeroPath;

/**
 * Default plugin which will be used if non of others met their requirements.
 *
 * @DlogHeroPath(
 *   id = "dlog_hero_path_default",
 *   match_path = {"*"},
 *   weight = -100,
 * )
 */
class DlogHeroPathDefaultPlugin extends DlogHeroPathPluginBase {


}

