<?php

namespace Drupal\iq_progressive_decoupler_rest_block\Plugin\Block;

use Drupal\Core\Form\FormStateInterface;
use Drupal\iq_progressive_decoupler\Plugin\Block\DecoupledBlockBase;

/**
 * Base block for decoupling.
 *
 * @Block(
 *   id = "iq_progressive_decoupler_rest_block",
 *   admin_label = @Translation("Decoupled REST Block"),
 * )
 */
class DecoupledRestBlock extends DecoupledBlockBase {

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);
    $form['api_endpoint'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API endpoint'),
      '#default_value' => $this->configuration['api_endpoint'] ?? '',
      '#required' => TRUE,
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = parent::build();
    $build['#attached']['library'][] = 'iq_progressive_decoupler_rest_block/rest-block';
    $build['#attached']['drupalSettings']['progressive_decoupler'][$this->configuration['block_id']]['api_endpoint'] = $this->configuration['api_endpoint'];
    $build['#theme'] = 'iq_progressive_decoupler_rest_block';
    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);
    $this->configuration['api_endpoint'] = $form_state->getValue('api_endpoint');
  }

}
