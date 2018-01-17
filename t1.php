<?php
    /*
    if (array_key_exists("updata", $_POST)){

        print_r($_POST);
    }*/



    $link = mysqli_connect("localhost","billing","1qaz2wsx", "mysql");// select (location, db name, db password, db table)
    if (mysqli_connect_error()) die ("There was an error connecting to the database");//check db
    //print_r($_POST);
    echo "<br>";

    //creat varble for item, type and cash

    $a = "'".$_POST[ITEM]."'";//variable item
    $b = "'".$_POST[TYPE]."'";//variable type
    $c = $_POST[CASH];//variable cash 

    //insert value to mysql
    if ($_POST[ins_fg] =='Y'){


        $sql = "INSERT INTO billing_table (ITEM, TYPE, CASH) VALUES ($a,$b,$c)";
        //print_r($sql);
        $result = mysqli_query($link, $sql);              
        //header('Location:t1.php');
        echo "<script>window.location.href='t1.php' </script>";
        exit;
    } 
 

    //all data
    $sql ="select * from billing_table";
    $result = mysqli_query($link, $sql);
    $idx=0;
    while ($row = mysqli_fetch_row($result)){
        list($id[$idx],$item[$idx],$type[$idx],$cash[$idx],$insdatetime[$idx],$updatetime[$idx])=$row;
        //print_r($row);
        $idx++;
    }
    mysqli_free_result($result);


    //search data
    $subsql="";   
    if ($_POST[searchitem]){ 
        $search=$_POST[searchitem];      
        $subsql=" where item LIKE '%$search%'";      
        $sql ="select * from billing_table";
        $sql.=$subsql;
        //echo $sql;
        $result = mysqli_query($link, $sql);
        $arr=array();
        while ($row = mysqli_fetch_row($result)){
            $arr[]=$row;
        }
        //print_r($arr);
        mysqli_free_result($result);
    }

    //search data on modal
    $sql_1 = "";
    if ($_GET[edit]){
        $id = $_GET[edit];
        print_r($id);
        $sql_1 = "select * from billing_table WHERE item LIKE '$id'";
        $result1 = mysqli_query($link, $sql_1);
        //echo $sql_1;
        $arr_1=array();
        while ($row_1 = mysqli_fetch_row($result1)){
            $id_1 = $row_1['ID'];
            $insdatetime_1 = $row_1['INPUT_DATETIME'];
        }
        //print_r(arr_1);
        mysqli_free_result($result);
    }
    
    
    
    //updata data    
    if($_POST[update]){
        print_r($_POST);

        $id_1 = $_POST[id];
        $sql = "UPDATE billing_table SET ITEM = $a, TYPE = $b, CASH = $c, UPDATE_DATETIME = CURRENT_TIMESTAMP WHERE ID = $id_1";                
        $result = mysqli_query($link, $sql); //每次有動作一定要新增
        echo "<script>window.location.href='t1.php' </script>";
        echo $sql;
        exit;
    }


    //delete data
    if($_POST[del_index]){
        $sql = "DELETE FROM billing_table where id=$_POST[del_index]";
        echo $sql;
        $result = mysqli_query($link, $sql);
        echo "<script>window.location.href='t1.php' </script>";
        exit;
    }  
    mysqli_close($link);


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Billing Form</title>
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="w3.css"> 
    <script src="https://code.jquery.com/jquery-1.11.3.js"></script>
    <script type="text/javascript">

        //avoid null in column
        function chk(p){
            
            if(p !=1){
                if(document.send.ITEM.value==''){
                    alert('Please check column item!');
                    document.send.ITEM.focus();
                    return false;
                }
                if(document.send.CASH.value==''){
                    alert('Please check column cash!');
                    document.send.CASH.focus();
                    return false;
                } 
                document.send.ins_fg.value="Y";
                document.send.action="t1.php";
                document.send.submit();
            }else{

                document.search.action="t1.php";
                document.search.submit();   
            }

        }


        function dels(d){
            if(confirm("Are you sure to del?")==true){
                document.search.action="t1.php";
                document.search.del_index.value=d;
                document.search.submit();
            }
        }

        //
        function edits(e){
            //alert(e);
            document.getElementById('id01').style.display='block';
            document.search.action="t1.php";
            $.ajax({
                  type: 'GET',      //GET or POST
                  url: "t2.php",  //請求的頁面
                  data: { id: e},
                  cache: false,   //是否使用快取
                  success: function(res){   //處理回傳成功事件，當請求成功後此事件會被呼叫
                    //console.log(res)
                    $("#id02").html(res)
                  },
                  error: function(callback){   //處理回傳錯誤事件，當請求失敗後此事件會被呼叫
                    //console.log(callback)

                  }
            });
        }



        //modal submit button  
        function myUpdate(f){

                                     
                if(document.update.ITEM.value==''){
                    alert('Please check column item!');
                    document.update.ITEM.focus();
                    return false;
                }
                if(document.update.CASH.value==''){
                    alert('Please check column cash!');
                    document.update.CASH.focus();
                    return false;
                } 
                document.update.action="t1.php";
                document.update.submit();   

        }







    </script>

</head>
<body>
    <h1>記帳玩具</h1>
    <h2>新增數據</h2>

        <form action="" method="post" name="send">
            <input type="hidden" name="ins_fg" value="">
            <p>
            	<label for="ITEM">ITEM:</label>
                <input type="text" name="ITEM" id="ITEM">
            </p>
            <p>
            	<label for="TYPE">TYPE:</label>
        	    <select name="TYPE" id="TYPE">
        	　	<option value="income">收入</option>
        	　	<option value="expense">支出</option>
        		</select>
            </p>
            <p>
            	<label for="CASH">CASH:</label>
                <input type="text" name="CASH" id="CASH">
            </p>
            <input type="button" value="Add Records" onclick="chk()">
        </form>
    <br>

    <h2>修改</h2>

    <p><em>search item</em></p>

    <form action="" method="post" name="search">
        <input type="hidden" name="ins_fg" value="">
        <input type="hidden" name="del_index" value="">
        <input type="text" name="searchitem" value="" placeholder="search the item"> 
        <input type="button" value="filiter" onclick="chk(1)">

        <table  border="1">
            <tr>
                <td>項目</td>
                <td>Item</td>
                <td>Cat</td>
                <td>Cash</td>
                <td>新建日期</td>
                <td>修改日期</td>
                <td></td>
            </tr>
            
        <? 
            if(count($arr)>0){
                foreach ($arr as $key => $value) { 
        ?>
            <tr>
                <td><?=$value[0];?></td>
                <td><?=$value[1];?></td>
                <td><?=$value[2];?></td>
                <td><?=$value[3];?></td>
                <td><?=$value[4];?></td>
                <td><?=$value[5];?></td>
                <td>
                    <input class="w3-btn w3-green" type="button" name="edit" value="check it" onclick="edits(<?=$value[0]?>);">
                    <!--<input type="button" name="edit" value="edit" onclick="edits(<?=$value[0]?>);">-->
                    <input class="w3-btn w3-red" type="button" name="del" value="del" onclick="dels(<?=$value[0]?>);">
                </td>               
            </tr>

        <? 
                }
            }else{

                $msg="-- no data --";
            }

        ?>
            
        </table>
        <?=$msg;?>
    </form>


        <!--出現modal-->
    <form action="" method="post" name="update">
        <div  class="w3-modal"  id="id01">
          <span onclick="document.getElementById('id01').style.display='none'" class="w3-closebtn w3-hover-red w3-container w3-padding-16 w3-display-topright w3-xxlarge">×</span>
          <div class="w3-modal-content w3-card-8 w3-animate-zoom" style="max-width:800px">
            <div class="w3-container">
              <div class="w3-section" id="id02">
                <!--table conten-->
            

              </div>
            </div>

            <div class="w3-container w3-border-top w3-padding-16 w3-light-grey w3-centered">
              <input type="submit" name="update" class="w3-btn btn-block w3-green" value="update" onclick="myUpdate()">           
            </div>
          </div>
        </div> 
    </form>   





    <h2>總覽</h2>

    <p><em>All data</em></p>
        <table  border="1">
            <tr>
                <td>項目</td>
                <td>Item</td>
                <td>Cat</td>
                <td>Cash</td>
                <td>新建日期</td>
                <td>修改日期</td>
            </tr>
        <?php
            for ($i=0; $i < $idx; $i++) { 
        ?>
            <tr>
                <td><?php echo $id[$i];?></td>
                <td><?php echo $item[$i];?></td>
                <td><?php echo $type[$i];?></td>
                <td><?php echo $cash[$i];?></td>
                <td><?php echo $insdatetime[$i];?></td>
                <td><?php echo $updatetime[$i];?></td>

            </tr>
        <?php
            }
        ?>
        </table>

</body>
</html>
