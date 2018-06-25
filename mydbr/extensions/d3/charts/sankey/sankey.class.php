<?php

class Ext_d3Sankey extends Ext_d3js {
    public $nodes_cache = array();
    public $nodes = array();
    public $links = array();
    
    
    function __construct($id, $options,  $dataIn, $colInfo)
    {
        $this->id = $id;
        $this->options = $options;  
        $this->dataIn = $dataIn;
        $this->colInfo = $colInfo;
    }
    
    function node_add($node_id, $node_data) {
        if (($id = array_search($node_id, $this->nodes_cache))===false) {
            $this->nodes[] = array('name' => $node_data, 'id' => $node_id);
            $this->nodes_cache[] = $node_id;
            $id = sizeof($this->nodes)-1;
        }
        return $id;
    }
    
    function node_get()
    {
        return $this->nodes;
    }

    function link_add( $source, $target, $value, $url) {
        $this->links[] = array('source' => $source, 'target' => $target, 'value' => $value, 'url' => $url);
        return sizeof($this->links)-1;
    }
    
    function link_get()
    {
        return $this->links;
    }    
    
    function create()
    {
      $title = isset($this->options['dbr.d3']['title']) ? $this->options['dbr.d3']['title'] : '';
      $width = isset($this->options['dbr.d3']['x']) ? $this->options['dbr.d3']['x'] : '860';
      $height = isset($this->options['dbr.d3']['y']) ? $this->options['dbr.d3']['y'] : '400';
      
      $obj_id = 'chart'.$this->id;
  	/* This will add a drill menu in cases where multiple linked reports need a menu */

      $number_format = isset($this->options['dbr.d3.sankey_format']['number']) ? $this->options['dbr.d3.sankey_format']['number'] : ',.d';
      $number_suffix = isset($this->options['dbr.d3.sankey_format']['suffix']) ? $this->options['dbr.d3.sankey_format']['suffix'] : '';
      // Get ID's
      
      
      foreach ($this->dataIn as $data) {
        if (sizeof($data) < 4) {
            $source_id = $source_data = $data[0];
            $target_id = $target_data = $data[1];
            $data_value = $data[2];
        } else {
            $source_id = $data[0];
            $source_data = $data[1];
            $target_id = $data[2];
            $target_data = $data[3];
            $data_value = $data[4];
        }
        $this->sources[ $source_id ] = $this->node_add( $source_id, $source_data );
        $this->targets[ $target_id  ] = $this->node_add( $target_id, $target_data );
        
        if ($this->sources[ $source_id ] === $this->targets[ $target_id ]) {
            echo '<div class="comment">Circular reference with '.$source_data.'&rarr;'.$target_data.' '.$data_value.'</div>';
        } else {
            $url = Extension::get_report_links_js($data);
            $this->link_add( $this->sources[ $source_id ], $this->targets[ $target_id ], $data_value, $url );
        }
      }
      $colors = array();
      
      $ids = array_merge($this->sources, $this->targets);
      for ($i=0; $i < sizeof(array_unique($ids)); $i++) { 
         $colors[] = Extension::get_js_color();
      }
      Extension::add_drill_menu();
        echo '<div'.Extension::resultclass(true, 'sankey', true).' id="'.$obj_id.'"></div>';
    ?>
<script>
var sankey_data = {
data: <?php echo json_encode(array("nodes" => $this->node_get(), "links" => $this->link_get() )); ?>,
object: <?php echo json_encode($obj_id); ?>,
title:  <?php echo json_encode($title); ?>,
size: {x: <?php echo $width; ?>, y: <?php echo $height; ?> },
colors: <?php echo json_encode($colors); ?>,
label_colors: <?php echo json_encode(Extension::get_label_colors()); ?>,
format: {number: <?php echo json_encode($number_format); ?>, suffix: <?php echo json_encode($number_suffix); ?>}
};
if (typeof sankey_datas == "undefined") {
    sankey_datas = [];
}
sankey_datas.push(sankey_data);
</script>
<script src="<?php echo Extension::getBaseURL();  ?>extensions/d3/charts/sankey/sankey_load.js"></script>
<?php
    }
}


?>