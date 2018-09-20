<?php

namespace Drupal\droopler\Plugin\Droopler\OptionalModule;

use Drupal\Core\Form\FormStateInterface;

/**
 * Google Analytics.
 *
 * @DrooplerOptionalModule(
 *   id = "google_tag",
 *   label = @Translation("Google Tag Manager"),
 *   description = @Translation("Use Google Tag Manager instead of Google Analytics."),
 *   type = "module",
 *   exclusions = {
 *    "google_analytics",
 *   },
 *   standardlyEnabled = 0,
 * )
 */
class GoogleTag extends AbstractOptionalModule {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form = parent::buildForm($form, $form_state);

    $form['google_tag']['container_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Container ID'),
      '#description' => $this->t('The ID assigned by Google Tag Manager (GTM) for this website container. To get a container ID, <a href="https://tagmanager.google.com/">sign up for GTM</a> and create a container for your website.'),
      '#attributes' => ['placeholder' => ['GTM-xxxxxx']],
      '#size' => 12,
      '#maxlength' => 15,
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array $formValues) {

    $this->configFactory->getEditable('google_tag.settings')
      ->set('container_id', (string) $formValues['container_id'])
      ->save(TRUE);
  }

}
