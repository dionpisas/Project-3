<?php
$auto = array("Audi","BMW","Mercedes");
rsort($auto);
$aantal = count($auto);
for ($i = 0; $i < $aantal; $i++){
    echo $auto[$i];
    echo "<br>";
}
?>