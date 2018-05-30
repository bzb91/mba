<?php
/**
 * Created by PhpStorm.
 * User: Burak
 * Date: 2.5.2018
 * Time: 08:20
 */
header("Access-Control-Allow-Origin: *");

switch ($method) {
    case "postOgrenci":
        $param1 = $_REQUEST['param1'];
        $param2 = $_REQUEST['param2'];
        $param3 = $_REQUEST['param3'];
        $param4 = $_REQUEST['param4'];

        $sql_layer = "insert into ogrenci(ogrenci_ad,ogrenci_okul,ogrenci_sinif,ogrenci_no,tarih)
                                   values('" . $param1 . "','" . $param2 . "','" . $param3 . "','" . $param4 . "',now())";

        //echo $sql_layer;

        $q_layers = mysqli_query($conn, $sql_layer) or die("Connect failed: %s\n" . $conn->error);
        $id = mysqli_insert_id($conn);
        $rows = mysqli_affected_rows($conn);
        mysqli_close($conn);
        echo '[{ "postsuccess": "' . $rows . '","ogrenci_id": "' . $id . '"}]';;
        break;

    case "getBostaVideoGetir":
        $param1 = $_REQUEST['param1'];
        $param2 = $_REQUEST['param2']; //Monitör'e giden video aktif
        if($param2 == "") {
            $sql_layer = "select * from video where video_bostami = 1";
        }
        else{
            $sql_layer = "select * from video where video_bostami = 1 and ogrenci_id <>0";
        }
        $q_layers = mysqli_query($conn, $sql_layer) or die("Connect failed: %s\n" . $conn->error);
        $resultArray = array();
        $temp = array();
        $control = 0;
        $control_videoid = 0;
        $localIP = $_SERVER['REMOTE_ADDR'];

        if ($localIP == "192.168.0.126") {
            $control_videoid = 8;
        } else if ($localIP == "192.168.0.172") {
            $control_videoid = 6;
        } else if ($localIP == "192.168.0.205") {
            $control_videoid = 4;
        }

        while ($row = mysqli_fetch_array($q_layers, MYSQLI_ASSOC)) {
            $video_izlendimi = 0;
            //print($control_videoid." ".$row["video_id"]);
            if ($control_videoid != $row["video_id"] && $param2 != 1) {
                $sql_sub = "select * from videoizlenme where ogrenci_id = '" . $param1 . "'";
                $q_sub = mysqli_query($conn, $sql_sub) or die("Connect failed: %s\n" . $conn->error);
                //print json_encode($row);
                while ($row_sub = mysqli_fetch_array($q_sub, MYSQLI_ASSOC)) {
                    $control = 1;
                    //print $row["video_id"]." ".$row_sub["video_id"]." ".$video_izlendimi."<br/>";
                    if ($row["video_id"] == $row_sub["video_id"]) {
                        $video_izlendimi = 1;
                        //print "video izlendi <br/>";
                        break;
                    } else if ($row["video_id"] != $row_sub["video_id"] && $video_izlendimi == 0) {
                        //print "video izlenmedi ".$row["video_id"]."<br/>";
                        //print "sizeof ".sizeof($resultArray)."<br/>";
                        if (sizeof($temp) == 0) {

                            $video_id = $row["video_id"];
                            $temp[] = $row;
                            //print "asdas eklendi ".json_encode($temp)."<br/>";
                            //print "videoid ".$row["video_baslik"]."<br/>";

                        }
                    }
                    //print "sizeof123 ".sizeof($resultArray)."<br/>";
                }
            } else if ($control_videoid == $row["video_id"] && $param2 == 1) {
                $video_id = $row["video_id"];
                $temp[] = $row;
            }
            //print sizeof($temp)."<br/>";
            //print "video_izlendimi ".$video_izlendimi."<br/>";
            if ($video_izlendimi == 1) { // Eğer video izlenmişse boşalt
                $temp = [];
                $video_id = "";
            } else if (sizeof($resultArray) == 0 && sizeof($temp) > 0) {
                $video_id = $temp[0]["video_id"];
                $resultArray = $temp;
                break;
                //print "arrayaaaaa eklendi ".$video_id."<br/>";
            }
            //print $video_id;
            //print "arraya eklendi ".json_encode($temp)."<br/>";
            /*            if($control == 0 && sizeof($resultArray) == 0){
                            $video_id = $row["video_id"];
                            $resultArray[] = $row;
                        }*/
        }
        /*
                print json_encode($row);
                if($control == 0 && sizeof($resultArray) == 0 && $param2 == 1){ //Eğer monitör'den istek geldiyse
                    print $row["video_id"];
                    $video_id = $row["video_id"];
                    $resultArray[] = $row;
                }*/
        //print $video_id."<br/>";*/
        //echo "gfdgfdg";
        /*Video boşta değil*/

        //mysqli_close($conn);

        if ($video_id != "" && $video_id != 0 && $param1 == "") {
            $sql_layer = "update video set video_bostami = 0 where video_id=" . $video_id;
            $q_layers = mysqli_query($conn, $sql_layer) or die("Connect failed: %s\n" . $conn->error);
        }
        //print "fggfd".$video_id;
        mysqli_close($conn);
        print json_encode($resultArray);
        break;

    case "getUnicCode":
        $param1 = $_REQUEST['param1'];
        $sql_layer = "select * from video where video_id =" . $param1;
        $q_layers = mysqli_query($conn, $sql_layer) or die("Connect failed: %s\n" . $conn->error);
        $resultArray = array();

        while ($row = mysqli_fetch_array($q_layers, MYSQLI_ASSOC)) {
            $resultArray[] = $row;
        }

        mysqli_close($conn);
        print json_encode($resultArray);
        break;

    case "getVideoyuSifirla":

        $localIP = $_SERVER['REMOTE_ADDR'];

        if ($localIP == "192.168.0.126") {
            $control_videoid = 8;
        } else if ($localIP == "192.168.0.172") {
            $control_videoid = 6;
        } else if ($localIP == "192.168.0.205") {
            $control_videoid = 4;
        }

        $param1 = $_REQUEST['param1'];
        $param2 = $_REQUEST['param2'];
        if($param1 == "") $param1 = $control_videoid;
        $sql_layer = "update video set video_bostami = 1, unic_code='" . $param2 . "',ogrenci_id=0 where video_id=" . $param1;
        $q_layers = mysqli_query($conn, $sql_layer) or die("Connect failed: %s\n" . $conn->error);
        $rows = mysqli_affected_rows($conn);
        mysqli_close($conn);
        echo '[{ "postsuccess": "' . $param1 . '"}]';
        break;

    case "postVideoIzlenme":
        $param1 = $_REQUEST['param1'];
        $param2 = $_REQUEST['param2'];


        $sql_layer = "insert into videoizlenme(ogrenci_id,video_id,tarih)
                                   values('" . $param1 . "','" . $param2 . "',now())";


        $q_layers = mysqli_query($conn, $sql_layer) or die("Connect failed: %s\n" . $conn->error);
        //$id = mysqli_insert_id($conn);
        $rows = mysqli_affected_rows($conn);
        mysqli_close($conn);
        echo '[{ "postsuccess": "' . $rows . '"}]';;
        break;

    case "TumVideolarIzlendimi":
        $param1 = $_REQUEST['param1'];
        $sql_sub = "select * from videoizlenme where ogrenci_id = '" . $param1 . "'";
        $q_sub = mysqli_query($conn, $sql_sub) or die("Connect failed: %s\n" . $conn->error);
        $temp = array();
        while ($row_sub = mysqli_fetch_array($q_sub, MYSQLI_ASSOC)) {
            $temp[] = $row_sub;
        }

        $izlendi = 0;
        if (sizeof($temp) == 9) {
            $izlendi = 1;
        }

        echo '[{ "postsuccess": "' . $izlendi . '"}]';
        break;

    case "getVideoyuSifirlaTum":
        $sql_layer = "update video set video_bostami = 1, unic_code = '',ogrenci_id = 0";
        $q_layers = mysqli_query($conn, $sql_layer) or die("Connect failed: %s\n" . $conn->error);
        $rows = mysqli_affected_rows($conn);
        mysqli_close($conn);
        echo '[{ "postsuccess": "' . $rows . '"}]';
        break;

    case "postVideoIzlemeAktif":
        $param1 = $_REQUEST['param1'];
        $param2 = $_REQUEST['param2'];


        $sql_sub = "select * from videoizlenme vi
                    inner join video v on vi.video_id = v.video_id
                    where vi.ogrenci_id = '" . $param2 . "' and v.unic_code = '" . $param1 . "'";
        $q_sub = mysqli_query($conn, $sql_sub) or die("Connect failed: %s\n" . $conn->error);
        $temp = array();
        while ($row_sub = mysqli_fetch_array($q_sub, MYSQLI_ASSOC)) {
            $temp[] = $row_sub;
        }
        $izlendi = 0;
        if (sizeof($temp) == 1) {
            $izlendi = 1;
        }


        if ($izlendi == 0) {
            $sql_layer = "update video set unic_code = '0000',ogrenci_id='" . $param2 . "' where unic_code = '" . $param1 . "'";
            $q_layers = mysqli_query($conn, $sql_layer) or die("Connect failed: %s\n" . $conn->error);
            $rows = mysqli_affected_rows($conn);
            mysqli_close($conn);
        }
        echo '[{ "postsuccess": "' . $rows . '"}]';
        break;

    case "getVideoVoices":
        $param1 = $_REQUEST['param1'];
        $sql_layer = "select * from video where unic_code ='0000' and ogrenci_id =".$param1;
        $q_layers = mysqli_query($conn,$sql_layer) or die("Connect failed: %s\n". $conn -> error);
        $resultArray = array();

        while ($row = mysqli_fetch_array($q_layers,MYSQLI_ASSOC)) {
            $resultArray[] = $row;
        }

        mysqli_close($conn);
        print json_encode($resultArray);
        break;
}