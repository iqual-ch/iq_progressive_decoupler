<?php

namespace Drupal\iq_progressive_decoupler\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\ui_patterns\UiPatternsManager;
use Drupal\Component\Serialization\Yaml;
use Symfony\Component\Yaml\Yaml as YamlParser;
use Drupal\Component\Serialization\Yaml as YamlSerializer;

/**
 * Base block for decoupling.
 */
class DecoupledBlockBase extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * UI Patterns manager.
   *
   * @var \Drupal\ui_patterns\UiPatternsManager
   */
  protected $patternsManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('plugin.manager.ui_patterns')
    );
  }

  /**
   * Block constructor.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\ui_patterns\UiPatternsManager $patterns_manager
   *   UI Patterns manager.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, UiPatternsManager $patterns_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->patternsManager = $patterns_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    $form['ui_pattern'] = [
      '#type' => 'select',
      '#empty_value' => '_none',
      '#title' => $this->t('Pattern'),
      '#options' => $this->patternsManager->getPatternsOptions(),
      '#default_value' => $this->configuration['ui_pattern'] ?? NULL,
      '#required' => TRUE,
    ];

    $form['field_mapping'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Field mapping'),
      '#default_value' => isset($this->configuration['field_mapping']) ? Yaml::decode($this->configuration['field_mapping']) : NULL,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    $build = [];
    $build['#attached']['library'][] = 'iq_progressive_decoupler/initialize';

    $pattern = $this->patternsManager->getDefinitions()[$this->configuration['ui_pattern']];
    foreach ($pattern->getLibrariesNames() as $library) {
      $build['#attached']['library'][] = $library;
    }

    $build['#attached']['drupalSettings']['progressive_decoupler'][$this->configuration['block_id']] = [
      'template' => \file_get_contents($pattern['base path'] . '/' . $pattern['template'] . '.html.twig'),
      'ui_pattern' => $this->configuration['ui_pattern'],
      'type' => $this->getPluginId(),
    ];

    if (isset($this->configuration['field_mapping'])) {
      $build['#attached']['drupalSettings']['progressive_decoupler'][$this->configuration['block_id']]['field_mapping'] = YamlParser::parse(YamlSerializer::decode($this->configuration['field_mapping']));
    }

    return $build;

  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);
    $this->configuration['ui_pattern'] = $form_state->getValue('ui_pattern');
    $this->configuration['block_id'] = str_replace('_', '-', 'block-' . $form['id']['#default_value']);
    $this->configuration['field_mapping'] = Yaml::encode($form_state->getValue('field_mapping'));
  }

}
