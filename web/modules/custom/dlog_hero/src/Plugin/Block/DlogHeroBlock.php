<?php

namespace Drupal\dlog_hero\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\dlog_hero\Plugin\DlogHeroPluginManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Dlog Hero' block
 *
 * @Block (
 *   id = "dlog_hero",
 *   admin_label = @Translation("Dlog Hero"),
 *   category = @Translation("Custom")
 * )
 */

class DlogHeroBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The plugin manager for dlog hero path plugins.
   *
   * @var \Drupal\dlog_hero\Plugin\DlogHeroPluginManager
   */
  protected DlogHeroPluginManager $dlogHeroPathManager;

  /**
   * The plugin manager for dlog hero entity plugins.
   *
   * @var \Drupal\dlog_hero\Plugin\DlogHeroPluginManager
   */
  protected DlogHeroPluginManager $dlogHeroEntityManager;

  /**
   * Constructs a new DlogHeroBlock instance.
   *
   * @param array $configuration
   * @param $plugin_id
   * @param $plugin_definition
   * @param \Drupal\dlog_hero\Plugin\DlogHeroPluginManager $dlog_hero_entity
   *  The plugin manager for dlog hero entity plugins.
   * @param \Drupal\dlog_hero\Plugin\DlogHeroPluginManager $dlog_hero_path
   *  The plugin manager for dlog hero path plugins.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition,
                  DlogHeroPluginManager $dlog_hero_entity, DlogHeroPluginManager $dlog_hero_path) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->dlogHeroEntityManager = $dlog_hero_entity;
    $this->dlogHeroPathManager = $dlog_hero_path;
  }

  /**
   * @inheritdoc
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('plugin.manager.dlog_hero.entity'),
      $container->get('plugin.manager.dlog_hero.path')
    );
  }

  /**
   * @inheritdoc
   */
  public function build() {
    $entity_plugins = $this->dlogHeroEntityManager->getSuitablePlugins();
    $path_plugins = $this->dlogHeroPathManager->getSuitablePlugins();
    $plugins = $entity_plugins + $path_plugins;
    uasort($plugins,'\Drupal\Component\Utility\SortArray::sortByWeightElement');
    $plugin = end($plugins);

    if ($plugin['plugin_type'] == 'entity') {
      /** @var \Drupal\dlog_hero\Plugin\DlogHero\DlogHeroPluginInterface $instance */
      $instance = $this->dlogHeroEntityManager->createInstance($plugin['id'], ['entity' => $plugin['entity']]);
    }

    if ($plugin['plugin_type'] == 'path') {
      $instance = $this->dlogHeroPathManager->createInstance($plugin['id']);
    }

    $build['content'] = [
      '#theme' => 'dlog_hero',
      '#title' => $instance->getHeroTitle(),
      '#subtitle' => $instance->getHeroSubtitle(),
      '#image' => $instance->getHeroImage(),
      '#video' => $instance->getHeroVideo()
    ];
    return $build;
  }

  /**
   * @inheritdoc
   */
  public function getCacheContexts() {
    return [
      'url.path',
    ];
  }

}
