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
                $curStr = substr($curStr, 42);
                if ($curStr[0]=='8'){
                    echo $curStr;
                    $to = ($curStr[10]==' ')?10:($curStr[11]==' ')?11:12;
                    $ipadd = substr($curStr, 0, $to);
                    $macadd = substr($curStr, $to+4, 17);
                    $result[$ipadd] = $macadd;
                    
                $i++;
                }

            }
            fclose($result);
            ?>
        </script>
        <?php
            foreach ($result as $ipadd=>$macadd) {
                echo $ipadd."  ".$macadd."<br>";
            }
        ?>
    </body>
</html>
