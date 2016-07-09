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

            $rawLog = fopen("C:/Users/innsmaut/Desktop/dhcpd2.txt", "r") or die ("Unable to open");
            $time = microtime(TRUE);

            while (!feof($rawLog)) {

                $curStr = substr(fgets($rawLog), 35);
                $caser = 0;
                if($curStr[0]=='i' && $curStr[5]=='a') { $caser = 1; 
                } elseif ($curStr[2]=='n'){ $caser = 2; 
                } elseif ($curStr[0]=='i' && $curStr[5]=='p') { $caser = 3;
                } elseif ($curStr[4]=='o' && !($curStr[9]=='.' && $curStr[7]=='1') && !($curStr[10]=='2' && $curStr[8]=='9' && $curStr[7]=='1')) { $caser = 4;}

                if ($caser == 1) {
                    $curStr = trim(substr($curStr, 11));
                    $switchIp = $curStr;
                }

                if ($caser == 2) {
                    $curStr = trim(substr($curStr, 10));
                    $a = explode(':', $curStr);
                    for ($j=0; $j<5; $j++) { $a[$j] = (strlen($a[$j]) == 1)?'0'.$a[$j]:$a[$j]; }
                    $clientMac = trim($a[0].':'.$a[1].':'.$a[2].':'.$a[3].':'.$a[4].':'.$a[5]);
                }

                if ($caser == 3) {
                    $curStr = trim(substr($curStr, 10));
                    $switchPort = $curStr;
                }

                if ($caser == 4){
                    $to = strpos($curStr, ' to ');
                    $ipadd = trim(substr($curStr, 7, $to-6));
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
            print_r(array('seconds' => microtime(TRUE) - $time));
        ?>
    </body>
</html>
