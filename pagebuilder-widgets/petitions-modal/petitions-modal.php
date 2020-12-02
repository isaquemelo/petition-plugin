<?php

/*
  Widget Name: Modal de Petição
  Description: Modal de agradecimento de assinatura da petição
  Author: hacklab/
  Author URI: https://hacklab.com.br/
 */

namespace widgets;

class PetitionsModal extends \SiteOrigin_Widget {

    function __construct() {
        $fields = [
            'title' => [
                'type' => 'text',
                'label' => 'Título do Modal',
                'default' => 'Agradecemos sua participação',
            ],
            'text' => [
                'type' => 'text',
                'label' => 'Texto do Modal',
                'default' => 'Para dar mais coro à nossa ação, compartilhe esta campanha em suas redes e convide seus amigos a se juntarem nessa pressão',
            ],
        ];

        parent::__construct('petitions-modal', 'Modal de Petição', [
            'description' => 'Modal de agradecimento de assinatura da petição'
                ], [], $fields, plugin_dir_path(__FILE__)
        );
    }

    function get_template_name($instance) {
        return 'template';
    }

    function get_style_name($instance) {
        return 'style';
    }

}

Siteorigin_widget_register('petitions-modal', __FILE__, 'widgets\PetitionsModal');
