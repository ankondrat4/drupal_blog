<?php

namespace Drupal\dlog_hero\Plugin\DlogHero\Path;

use Drupal\dlog_hero\Plugin\DlogHero\DlogHeroPluginBase;

/**
 * The base for DlogHero path plugin type.
 */
abstract  class DlogHeroPathPluginBase extends DlogHeroPluginBase implements DlogHeroPathInterface {

  /**
   * @inheritdoc
   */
  public function getMatchPath() {
    return $this->pluginDefinition['match_path'];
  }

  /**
   * @inheritdoc
   */
  public function getMatchType() {
    return $this->pluginDefinition['match_type'];
  }
}
