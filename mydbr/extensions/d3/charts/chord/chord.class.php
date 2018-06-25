<?php

class Ext_d3Chord extends Ext_d3js {
    function __construct($id, $options,  $dataIn, $colInfo)
    {
        $this->id = $id;
        $this->options = $options;  
        $this->dataIn = $dataIn;
        $this->colInfo = $colInfo;
    }
    
    function create()
    {
        $title = isset($this->options['dbr.d3']['title']) ? $this->options['dbr.d3']['title'] : '';
        $width = isset($this->options['dbr.d3']['x']) ? $this->options['dbr.d3']['x'] : '500';
        $height = isset($this->options['dbr.d3']['y']) ? $this->options['dbr.d3']['y'] : '500';
        $zoom = isset($this->options['dbr.d3.zoom']['value'])  ? intval($this->options['dbr.d3.zoom']['value']) : 0;
        
        $obj_id = 'chart'.$this->id;
        
        $from = array();
        $to = array();
        $value = array();
        
        $datas = array();
        $distinct_keys = array();
        $distinct_keys2 = array();
        
        $colors = array();
        // Get from items
        foreach ($this->dataIn as $data) {
            if (!in_array($data[0], $distinct_keys)) {
                $distinct_keys[] = $data[0];
            }
        }
        // Get to items which are not among from items so we get a color for it
        foreach ($this->dataIn as $data) {
            if (!in_array($data[1], $distinct_keys)) {
                $this->dataIn[] = array($data[1], $distinct_keys[0], 0);
                $distinct_keys[] = $data[1];
            }
        }
        foreach ($this->dataIn as $data) {
            $datas[] = array("from" => $data[0], "to" => $data[1], "value" => $data[2]);
        }
        
        for ($i=0; $i < sizeof($distinct_keys); $i++) { 
            $colors[] = Extension::get_js_color();
        }
        
        echo '<div'.Extension::resultclass(true, 'chord').Extension::keepwithnext().' id="'.$obj_id.'"></div>';
        echo '<div id="chord_tooltip"></div>';
        ?>
    <script>
        var chord_data = {
        data: <?php echo json_encode($datas); ?>,
        object: <?php echo json_encode($obj_id); ?>,
        title:  <?php echo json_encode($title); ?>,
        size: {x: <?php echo $width; ?>, y: <?php echo $height; ?> },
        colors: <?php echo json_encode($colors); ?>,
        zoom: <?php echo json_encode($zoom); ?>,
        };
        if (typeof chord_datas == "undefined") {
            chord_datas = [];
        }
        chord_datas.push(chord_data);
    </script>
<?php 
    }
}
