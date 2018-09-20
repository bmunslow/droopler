<?php

namespace Drupal\droopler\Installer\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\droopler\OptionalModulesManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides the site configuration form.
 */
class ModuleConfigureForm extends ConfigFormBase {

  /**
   * The plugin manager.
   *
   * @var \Drupal\droopler\OptionalModulesManager
   */
  protected $optionalModulesManager;

  /**
   * Constructs a \Drupal\system\ConfigFormBase object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\droopler\OptionalModulesManager $optionalModulesManager
   *   The factory for configuration objects.
   */
  public function __construct(ConfigFactoryInterface $config_factory, OptionalModulesManager $optionalModulesManager) {

    parent::__construct($config_factory);

    $this->optionalModulesManager = $optionalModulesManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('plugin.manager.droopler.optional_modules')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'droopler_module_configure_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {

    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    
    $form['description'] = [
      '#type' => 'item',
      '#markup' => $this->t('Keep calm. You can install all the modules later, too.'),
    ];

    $form['install_modules'] = [
      '#type' => 'container',
    ];

    $providers = $this->optionalModulesManager->getDefinitions();

    static::sortByWeights($providers);

    foreach ($providers as $provider) {
      $instance = $this->optionalModulesManager->createInstance($provider['id']);

      $form['install_modules_' . $provider['id']] = [
        '#type' => 'checkbox',
        '#title' => $provider['label'],
        '#description' => isset($provider['description']) ? $provider['description'] : '',
        '#default_value' => isset($provider['standardlyEnabled']) ? $provider['standardlyEnabled'] : 0,
      ];

      if (isset($provider['dependencies'])) {
        $this->addDependencies($form, $provider);
      }
      if (isset($provider['exclusions'])) {
        $this->addExclusions($form, $provider);
      }

      $form = $instance->buildForm($form, $form_state);

    }
    $form['#title'] = $this->t('Install & configure modules');

    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['save'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save and continue'),
      '#button_type' => 'primary',
      '#submit' => ['::submitForm'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $installModules = [];

    foreach ($form_state->getValues() as $key => $value) {

      if (strpos($key, 'install_modules') !== FALSE && $value) {
        preg_match('/install_modules_(?P<name>\w+)/', $key, $values);
        $installModules[] = $values['name'];
      }
    }

    $buildInfo = $form_state->getBuildInfo();

    $install_state = $buildInfo['args'];

    $install_state[0]['thunder_additional_modules'] = $installModules;
    $install_state[0]['form_state_values'] = $form_state->getValues();

    $buildInfo['args'] = $install_state;

    $form_state->setBuildInfo($buildInfo);

  }

  /**
   * Adds dependencies from annotations, to make one optional module dependent on the another.
   *
   * @param array $form
   *   Form to add exclusions.
   * @param array $provider
   *   Provider info from annotations.
   */
  private function addDependencies(array &$form, array $provider) {
    $form['install_modules_' . $provider['id']]['#disabled'] = TRUE;
    $form['install_modules_' . $provider['id']]['#title_display'] = FALSE;
    $form['install_modules_' . $provider['id']]['#attributes']['class'][] = 'hidden';
    foreach ($provider['dependencies'] as $depency) {
      $form['install_modules_' . $provider['id']]['#states']['checked'][] = [
        'input[name="install_modules_' . $depency . '"]' => ['checked' => TRUE],
      ];
    }
  }

  /**
   * Adds exclusions from annotations, to prevent enabling conflicting modules.
   *
   * @param array $form
   *   Form to add exclusions.
   * @param array $provider
   *   Provider info from annotations.
   */
  private function addExclusions(array &$form, array $provider) {
    foreach ($provider['exclusions'] as $exclusion) {
      $form['install_modules_' . $provider['id']]['#states']['disabled'][] = [
        'input[name="install_modules_' . $exclusion . '"]' => ['checked' => TRUE],
      ];
    }
  }

  /**
   * Returns a sorting function to sort an array by weights.
   *
   * If an array element doesn't provide a weight, it will be set to 0.
   * If two elements have the same weight, they are sorted by label.
   *
   * @param array $array
   *   The array to be sorted.
   */
  private static function sortByWeights(array &$array) {
    uasort($array, function ($a, $b) {
      $a_weight = isset($a['weight']) ? $a['weight'] : 0;
      $b_weight = isset($b['weight']) ? $b['weight'] : 0;

      if ($a_weight == $b_weight) {
        return ($a['label'] > $b['label']) ? 1 : -1;
      }
      return ($a_weight > $b_weight) ? 1 : -1;
    });
  }

}
