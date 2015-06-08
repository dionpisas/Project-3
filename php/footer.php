<?php
// inladen van footer.html
$footer = new TemplatePower("template/files/footer.tpl");
$footer->prepare();



// Op het einde van de code zorg ik ervoor dat alles uit word geprint
$header->printToScreen();
$errors->printToScreen();
$content->printToScreen();
$footer->printToScreen();