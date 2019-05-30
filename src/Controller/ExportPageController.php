<?php
namespace Drupal\custom_siteinfo\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controller which generates JSON from the content type Page
 */
class ExportPageController extends ControllerBase {
  /**
   * {@inheritdoc}
   */
  public function data() {
    $json_array = array(
      'data' => array()
    );
    $site_config = $this->config('system.site');
    $siteAPI = $site_config->get('siteapikey');
    if ($siteAPI != '') {
      $nids = \Drupal::entityQuery('node')->condition('type','page')->execute();
      $nodes =  Node::loadMultiple($nids);
      foreach ($nodes as $node) {
        $json_array['data'][] = array(
          'type' => $node->get('type')->target_id,
          'id' => $node->get('nid')->value,
          'attributes' => array(
            'title' =>  $node->get('title')->value,
            'content' => $node->get('body')->value,
          ),
        );
      }
      return new JsonResponse($json_array);
    }
    else {
      return "Access Denied";
    }
  }
}