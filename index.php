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
            $rawLog = fopen("C:/Users/innsmaut/Desktop/newbase.txt", "r") or die ("Unable to open");

            while (!feof($rawLog)) {
                $curStr = fgets($rawLog);

                if ($curStr[39]=='o' && !($curStr[44]=='.' && $curStr[42]=='1')){
                    $to = strpos($curStr, ' to ');
                    $ipadd = trim(substr($curStr, 42, $to-41));
                    $macadd = trim(substr($curStr, $to+4, 17));
                    $result[$macadd] = $ipadd;
                }
                if ($curStr[35]=='i' && $curStr[40]=='a') {
                    $length = ($curStr[57]==' ')?11:($curStr[58]==' ')?12:($curStr[59]==' ')?13:14;
                    $curStr = trim(substr($curStr, 46, $length));
                    $switchIp[$i] = $curStr;
                }
                if ($curStr[34]=='l' && $curStr[40]=='M') {
                    $curStr = substr($curStr, 45, 17);
                    $a = explode(':', $curStr);
                    for ($j=0; $j<5; $j++) { $a[$j] = (strlen($a[$j]) == 1)?'0'.$a[$j]:$a[$j]; }
                    $clientMac[$i] = trim($a[0].':'.$a[1].':'.$a[2].':'.$a[3].':'.$a[4].':'.$a[5]);
                    $i++;
                }
                if ($curStr[35]=='i' && $curStr[40]=='p') {
                    $curStr = trim(substr($curStr, 45, 2));
                    $switchPort[$i] = $curStr;
                }




            }
            $p = 0;
            for ($x=0; $x<$i; $x++){
                foreach ($result as $macadd=>$ipadd){
                    if ($macadd == $clientMac[$x]){
                        $final[$p][0] = $switchIp[$x];
                        $final[$p][1] = $switchPort[$x];
                        $final[$p][2] = $clientMac[$x];
                        $final[$p][3] = $ipadd;
                        $p++;
                    }
                }
            }
            fclose($result);
            ?>
        </script>
        <?php
            for ($x=0; $x<$p; $x++){
                echo "\n".$x.". ".$final[$x][0]."     p:".$final[$x][1]."    ".$final[$x][2]."    ".$final[$x][3]."<br>";
            }
        ?>
    </body>
</html>
