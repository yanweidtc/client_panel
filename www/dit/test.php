<?php

print preg_match('/^[0-9-]*$/', '123.99')."\n"; 
print preg_match('/^[0-9-]*$/', '1231-asd22')."\n"; 
print preg_match('/^[0-9-]*$/', '12323123')."\n"; 

?>
