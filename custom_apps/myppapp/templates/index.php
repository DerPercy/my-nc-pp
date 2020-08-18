<?php
script('myppapp', 'script');
style('myppapp', 'style');
?>

<div id="app">
	<?php print_unescaped($this->inc('content/index')); ?>
</div>
