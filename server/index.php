<?php

require_once('DB.php');
date_default_timezone_set('America/Sao_Paulo');

$db = new DB();

$temperatures = json_decode($db->getTemperatures());

?>

<html>
    <head>
        <title>Temperaturas</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Dados coletados via Arduino/MQTT">
        <script>            
            setTimeout(() => {
                window.location.reload();
            }, 5000);
                       
        </script>
        <style>
        .background {
            background: #1c92d2;  /* fallback for old browsers */
            background: -webkit-linear-gradient(to bottom, #f2fcfe, #1c92d2);  /* Chrome 10-25, Safari 5.1-6 */
            background: linear-gradient(to bottom, #f2fcfe, #1c92d2); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
        }
        </style>
    </head>
    <body class="container background">      
        <div class="page-header">
            <h1>Temperaturas <small>Dados coletados via MQTT</small></h1>
            <small>Delay: 5 segundos</small>
            
        </div>    
          
        <div class="row">
            <?php foreach($temperatures as $temperature): ?>  
                <div class="col-sm-6 col-md-3">
                    <div class="thumbnail">
                    <h1 align="center"><b><?php echo $temperature->temperature;?>º C</b></h1>    
                    <div class="caption">
                        <h3 align="center"><?php echo date('d/m/Y H:i:s', strtotime($temperature->created_at));?></h3>
                        <p align="center"><?php echo $temperature->id;?></p> 
                        <?php 
                            if ($temperature->source == "A0") {
                                $temperature->source = "Arduino_0";
                            } elseif ($temperature->source == "N0") {
                                $temperature->source = "NodeMCU_0";
                            }
                        ?>
                        <p align="center"><?php echo $temperature->source;?></p>                        
                    </div>
                    </div>
                </div>
            <?php endforeach; ?>   
        </div>
                           
    </body>
</html>