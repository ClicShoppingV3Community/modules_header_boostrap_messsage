<?php
/**
 *
 *  @copyright 2008 - https://www.clicshopping.org
 *  @Brand : ClicShopping(Tm) at Inpi all right Reserved
 *  @Licence GPL 2 & MIT
 *  @licence MIT - Portion of osCommerce 2.4
 *  @Info : https://www.clicshopping.org/forum/trademark/
 *
 */

  use ClicShopping\OM\Registry;
  use ClicShopping\OM\CLICSHOPPING;

  class he_header_boostrap_message {
    public $code;
    public $group;
    public $title;
    public $description;
    public $sort_order;
    public $enabled = false;

    public function __construct() {
      $this->code = get_class($this);
      $this->group = basename(__DIR__);
      $this->title = CLICSHOPPING::getDef('module_header_boostrap_message_title');
      $this->description = CLICSHOPPING::getDef('module_header_boostrap_message_description');

      if (defined('MODULE_HEADER_BOOSTRAP_MESSAGE_STATUS')) {
        $this->sort_order = MODULE_HEADER_BOOSTRAP_MESSAGE_SORT_ORDER;
        $this->enabled = (MODULE_HEADER_BOOSTRAP_MESSAGE_STATUS == 'True');
      }
    }

    public function execute() {
      $CLICSHOPPING_Template = Registry::get('Template');

      $content_width = MODULE_HEADER_BOOSTRAP_MESSAGE_CONTENT_WIDTH;
      $alert = 'alert alert-' . MODULE_HEADER_BOOSTRAP_MESSAGE_ALERT;
      $message = MODULE_HEADER_BOOSTRAP_MESSAGE_TEXT;
      
      $header_template = '<!-- header google search start -->';

      ob_start();

      require_once($CLICSHOPPING_Template->getTemplateModules($this->group . '/content/header_boostrap_message'));
      $header_template .= ob_get_clean();


      $header_template .= '<!-- header google search end -->' . "\n";

      $CLICSHOPPING_Template->addBlock($header_template, $this->group);
    }

    public function isEnabled() {
      return $this->enabled;
    }

    public function check() {
      return defined('MODULE_HEADER_BOOSTRAP_MESSAGE_STATUS');
    }

    public function install() {
      $CLICSHOPPING_Db = Registry::get('Db');


      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Do you want to activate this module ?',
          'configuration_key' => 'MODULE_HEADER_BOOSTRAP_MESSAGE_STATUS',
          'configuration_value' => 'True',
          'configuration_description' => 'You must have a registration on https://cse.google.co.uk/ to use this module',
          'configuration_group_id' => '6',
          'sort_order' => '1',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'True\', \'False\'))',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Indicate the content with',
          'configuration_key' => 'MODULE_HEADER_BOOSTRAP_MESSAGE_CONTENT_WIDTH',
          'configuration_value' => '12',
          'configuration_description' => 'Content width',
          'configuration_group_id' => '6',
          'sort_order' => '2',
          'set_function' => 'clic_cfg_set_content_module_width_pull_down',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Choose alert type',
          'configuration_key' => 'MODULE_HEADER_BOOSTRAP_MESSAGE_ALERT',
          'configuration_value' => 'primary',
          'configuration_description' => 'choose your color to display your message',
          'configuration_group_id' => '6',
          'sort_order' => '0',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'primary\', \'secondary\', \'info\', \'success\', \'danger\', \'warning\', \'light\', \'dark\'))',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Please write your text',
          'configuration_key' => 'MODULE_HEADER_BOOSTRAP_MESSAGE_TEXT',
          'configuration_value' => '',
          'configuration_description' => 'text to display on your catalog (you can include html code)',
          'configuration_group_id' => '6',
          'sort_order' => '0',
          'set_function' => '',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Sort order',
          'configuration_key' => 'MODULE_HEADER_BOOSTRAP_MESSAGE_SORT_ORDER',
          'configuration_value' => '150',
          'configuration_description' => 'Sort order (lower is displaying in first)',
          'configuration_group_id' => '6',
          'sort_order' => '0',
          'set_function' => '',
          'date_added' => 'now()'
        ]
      );
    }

    public function remove() {
      return Registry::get('Db')->exec('delete from :table_configuration where configuration_key in ("' . implode('", "', $this->keys()) . '")');
    }

    public function keys() {
      return array('MODULE_HEADER_BOOSTRAP_MESSAGE_STATUS',
                   'MODULE_HEADER_BOOSTRAP_MESSAGE_CONTENT_WIDTH',
                   'MODULE_HEADER_BOOSTRAP_MESSAGE_ALERT',
                   'MODULE_HEADER_BOOSTRAP_MESSAGE_TEXT',
                   'MODULE_HEADER_BOOSTRAP_MESSAGE_SORT_ORDER'
                  );
    }
  }
