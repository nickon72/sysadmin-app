<?php
snmp_set_quick_print(true);
$result = snmpget("192.168.13.10", "public", "1.3.6.1.2.1.1.1.0", 2000);
echo $result ? "SNMP РАБОТАЕТ: $result" : "Ошибка SNMP";