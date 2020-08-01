<?php $this->layout='inicio'; ?>
<?php 
	echo $this->Html->css('packages/core/main.css', array('rel' => 'stylesheet', 'media' => 'all'));
	echo $this->Html->css('packages/daygrid/main.css', array('rel' => 'stylesheet', 'media' => 'all'));
	echo $this->Html->css('calendarios/calendar.css', array('rel' => 'stylesheet', 'media' => 'all'));
	echo $this->Html->script('packages/core/main.js');
	echo $this->Html->script('packages/core/locales-all.js');
	echo $this->Html->script('packages/interaction/main.js');
	echo $this->Html->script('packages/daygrid/main.js');
	echo $this->Html->script('calendarios/calendar.js');
	echo $this->Html->script('eventos/eventos.js');
	
?>
<body>
	<div style="margin-top:50px;" class="col-md-1">
	<button id="newEvent" class="btn btn-primary">Crear Evento</button> 
	</div>
	<div id='calendar' style="margin-top:50px;" class="col-md-11"></div>

	<div id='div_eventos'></div>
	  
	<div id='new_event'></div>

</body>
