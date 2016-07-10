<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8/Windows-1251" />
        <title></title>
    </head>
    <body>
        <h1>TEST BODY</h1>
        <script>
            <?php
                $urls = array(
                "C:/Users/innsmaut/Desktop/dhcpd2.txt",
                "C:/Users/innsmaut/Desktop/dhcpd1.txt"
                );
                    for ($i=0;$i<count($urls);$i++){
                        $parseRes = dhcpParse($urls[$i]);
                        foreach ($parseRes as $macadd=>$macadd) {
                            $merged[$macadd] = $parseRes[$macadd];
                        }
                    }

                function dhcpParse($urlParse){
                    $rawLog = fopen($urlParse, "r") or die ("Unable to open");

                    while (!feof($rawLog)) {

                        $curStr = substr(fgets($rawLog), 35);
                        $caser = 0;
                        if($curStr[0]=='i' && $curStr[5]=='a') { $caser = 1; 
                        } elseif ($curStr[2]=='n'){ $caser = 2; 
                        } elseif ($curStr[0]=='i' && $curStr[5]=='p') { $caser = 3;
                        } elseif ($curStr[4]=='o' && !($curStr[9]=='.' && $curStr[7]=='1') && !($curStr[9]=='2' && $curStr[8]=='9')) { $caser = 4;}

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

                    fclose($urlParse);
                    return $final;
                }

            foreach ($merged as $macadd=>$macadd) {
                $swapmerge[$merged[$macadd][2]][0] = $merged[$macadd][0];
                $swapmerge[$merged[$macadd][2]][1] = $merged[$macadd][1];
                $swapmerge[$merged[$macadd][2]][2] = $macadd;
                $swapmerge[$merged[$macadd][2]][3] = '';
                $swapmerge[$merged[$macadd][2]][4] = '';
            }


               $urls = "C:/Users/innsmaut/Desktop/client_list.txt";

                    $rawLog = fopen($urls, "r") or die ("Unable to open");

                    while (!feof($rawLog)) {

                        $curBase = fgets($rawLog);
                        $curStr = substr($curBase, 200, 150);
                        $cutedge = strpos($curStr, '</b><br><span > ');
                        if ($cutedge == FALSE) { continue;};
                        $cutedge2 = strlen($curStr) - strpos($curStr, '/32</span><br>');
                        $curIp = trim(substr($curStr, $cutedge + 16, -$cutedge2));
                        
                        if (strpos($curBase, '80.77') && strpos($curBase, '188.247')) {
                        $curIp = substr($curBase, strpos($curBase, '188.247'));
                        $curIp = trim(substr($curIp, 0, -(strlen($curIp) - strpos($curIp, '/'))));  
                        }

                        if ($swapmerge[$curIp]) {

                        $curStr = substr($curBase, 19, 60);
                        $cutedge3 = strpos($curStr, '</');
                        $curLogin = substr($curStr, 0, -(strlen($curStr) - $cutedge3));
                        $cutedge4 = strpos($curStr, '      ');
                        $curAcc = substr($curStr, strlen($curLogin) + 20, -(strlen($curStr) - $cutedge4));
                        } 
                    }

                    fclose($rawLog);

            $filePush = fopen("C:/Users/innsmaut/Desktop/SaltovkaUserList.xml", "w");
            fwrite($filePush, '<?xml version="1.0" encoding="Windows-1251"?>');
            fwrite($filePush, '<userstable>');

            $x = 1;
            foreach ($swapmerge as $macadd=>$macadd) {
                fwrite($filePush, ' <user id="'.$x.'">');
                fwrite($filePush, '  <switch>'.$swapmerge[$macadd][0].'</switch>');
                fwrite($filePush, '  <port>'.$swapmerge[$macadd][1].'</port>');
                fwrite($filePush, '  <usermac>'.$swapmerge[$macadd][2].'</usermac>');
                fwrite($filePush, '  <userip>'.$macadd.'</userip>');
                fwrite($filePush, '  <userlogin>'.$swapmerge[$macadd][3].'</userlogin>');
                fwrite($filePush, '  <useracc>'.$swapmerge[$macadd][4].'</useracc>');
                fwrite($filePush, ' </user>');
                $x++;
            }
            fwrite($filePush, '</userstable>');

            fclose($filePush);

            ?>
        </script>
        <?php
            $x = 1;
            foreach ($swapmerge as $macadd=>$macadd) {
                echo "\n".$x.". ".$swapmerge[$macadd][0]."     p:".$swapmerge[$macadd][1]."    ".$macadd."    ".$swapmerge[$macadd][2]."   ".$swapmerge[$macadd][3]."    ".$swapmerge[$macadd][4]."<br>";
                $x++;
            }
        ?>
    </body>
</html>
