<?
    print_r($_POST[update]);
    //print_r($_GET);//確認t1傳送得值有收到
    $link = mysqli_connect("localhost","billing","1qaz2wsx", "mysql");// select (location, db name, db password, db table)
    if (mysqli_connect_error()) die ("There was an error connecting to the database");//check db
    
    $a = "'".$_POST[ITEM]."'";//variable item
    $b = "'".$_POST[TYPE]."'";//variable type
    $c = $_POST[CASH];//variable cash
    //print_r($_GET['id_1']);
    //fetch row 
    //print_r(array($_GET['id']));   
    $id_1 = $_GET['id'];
    $sql = "select * from billing_table WHERE id = $id_1";
    $result = mysqli_query($link, $sql);
    //echo $sql;
    $arr=array();
    while ($row = mysqli_fetch_row($result)){
        list($id,$item,$type,$cash,$ins_datetime,$upd_datime)=$row;
    }


?>
<!--<form action="" method="post" name="search">-->
<form action="/t1.php" name="update1" method="get">
     <table  border="1">
        <tr>
            <td>項目</td>
            <td>Item</td>
            <td>Cat</td>
            <td>Cash</td>
            <td>新建日期</td>
            <td>修改日期</td>
        </tr>
        <tr>
            <td name="id"><?=$id_1;?>
                <input type="hidden" name="id" value="<?=$id_1;?>">
            </td>

            <td><input class="w3-input w3-border w3-hover-border-black w3-margin-bottom" type="text" name="ITEM" placeholder="<?=$item?>"></td>
            <td>
                <select name="TYPE" id="TYPE">
            　   <option value="income">收入</option>
            　   <option value="expense">支出</option>
            </td>
            <td>
                <input class="w3-input w3-border w3-hover-border-black w3-margin-bottom" type="text" name="CASH" placeholder="<?=$cash;?>"></td>
            <!--<td><?//=$value[4];?></td>-->
            <td><?=$ins_datetime;?></td>
            <td>Now</td>
        </tr>
    </table>
</form>
