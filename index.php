<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title></title>
    </head>
    <body>
        <h1>TEST BODY</h1>
        <script>
            <?php
            $i = 0;
            $rawLog = fopen("C:/Users/innsmaut/Desktop/dhcpd1.txt", "r") or die ("Unable to open");

            while (!feof($rawLog)) {
                $curStr = fgets($rawLog);
                if ($curStr[35]=='i' && $curStr[40]=='a') {
                    $length = ($curStr[57]==' ')?11:($curStr[58]==' ')?12:($curStr[59]==' ')?13:14;
                    $curStr = trim(substr($curStr, 46, $length));
                    $switchIp = $curStr;
                }
                if ($curStr[34]=='l' && $curStr[40]=='M') {
                    $curStr = substr($curStr, 45, 17);
                    $a = explode(':', $curStr);
                    for ($j=0; $j<5; $j++) { $a[$j] = (strlen($a[$j]) == 1)?'0'.$a[$j]:$a[$j]; }
                    $clientMac = trim($a[0].':'.$a[1].':'.$a[2].':'.$a[3].':'.$a[4].':'.$a[5]);
                }
                if ($curStr[35]=='i' && $curStr[40]=='p') {
                    $curStr = trim(substr($curStr, 45, 2));
                    $switchPort = $curStr;
                }

                if ($curStr[39]=='o' && !($curStr[44]=='.' && $curStr[42]=='1') && !($curStr[45]=='2' && $curStr[43]=='9' && $curStr[42]=='1')){
                    $to = strpos($curStr, ' to ');
                    $ipadd = trim(substr($curStr, 42, $to-41));
                    $macadd = trim(substr($curStr, $to+4, 17));
                }

                if ( $switchIp !== NULL && 
                $switchPort !== NULL && 
                $clientMac !== NULL &&
                $ipadd !== NULL &&
                $macadd !== NULL &&
                $clientMac == $macadd) {
                    $final[$macadd][0] = $switchIp;
                    $final[$macadd][1] = $switchPort;
                    $final[$macadd][2] = $ipadd;
                    $switchIp = NULL;
                    $switchPort = NULL;
                    $clientMac = NULL;
                    $ipadd = NULL;
                    $macadd = NULL;
                }

            }
            fclose($result);
            ?>
        </script>
        <?php
            $x = 1;
            foreach ($final as $macadd=>$macadd) {
                echo "\n".$x.". ".$final[$macadd][0]."     p:".$final[$macadd][1]."    ".$macadd."    ".$final[$macadd][2]."<br>";
                $x++;
            }
        ?>
    </body>
</html>
