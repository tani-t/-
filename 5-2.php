<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
    
    
    <?php
    
    $dsn = 'データベース名';
    $user = 'ユーザ名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    $sql = "CREATE TABLE IF NOT EXISTS tbtest"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "date char(20),"  //日付を入れる。文字列、半角英数で20文字
    . "pass TEXT"       //パスワードを入れる。文字列、長めの文章も入る
    .");";
    $stmt = $pdo->query($sql);
    
    $str= $_POST["text"];
    $str2= $_POST["name"];
    $deletenum= $_POST["num"];
    $editnum = $_POST["editnum"];
    $pass1= $_POST["pass1"];
    $pass2= $_POST["pass2"];
    $pass3= $_POST["pass3"];
    $c=$_POST["fire"];
     
     
     //投稿フォーム
    if($deletenum==null && $str!=null && $str2!=null && $editnum==null && $pass1!=null){
         //新規投稿の場合
         if($c==null){
             $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
             $sql -> bindParam(':name', $str2, PDO::PARAM_STR);
             $sql -> bindParam(':comment', $str, PDO::PARAM_STR);
             $sql -> bindParam(':date', $date, PDO::PARAM_STR);
             $sql -> bindParam(':pass', $pass1, PDO::PARAM_STR);
             $date = date("Y/m/d H:i:s");
             $sql -> execute();
             
         }
         //編集で投稿の場合
         else{
             $date = date("Y/m/d H:i:s");
             $sql = 'UPDATE tbtest SET name=:name,comment=:comment,date=:date,pass=:pass WHERE id=:id';
             $stmt = $pdo->prepare($sql);
             $stmt->bindParam(':name', $str2, PDO::PARAM_STR);
             $stmt->bindParam(':comment', $str, PDO::PARAM_STR);
             $stmt->bindParam(':date', $date, PDO::PARAM_STR);
             $stmt->bindParam(':pass', $pass1, PDO::PARAM_STR);
             $stmt->bindParam(':id', $c, PDO::PARAM_INT);
             $stmt->execute();
               
             $c=null;//編集したのでリセット
             }
        
    }
    //削除するとき
     elseif($str==null && $str2==null && $editnum==null && $deletenum!=null && $pass2!=null){
         $id = $deletenum;
         $sql = 'SELECT pass FROM tbtest where id=:id';
         $stmt = $pdo->prepare($sql); 
         $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
         $stmt->execute();                             // ←SQLを実行する。
         $results = $stmt->fetch();
        
        //パスワードが正しいなら削除する
        if($results['pass']==$pass2){
        $sql = 'delete from tbtest WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
        else
        echo "パスワードが違います<br>";
     }
     //編集するとき
     elseif($str==null && $str2==null && $editnum!=null && $deletenum==null && $pass3!=null){
        $id = $editnum ; // idがこの値のデータだけを抽出したい、とする
        $sql = 'SELECT pass FROM tbtest where id=:id';
        $stmt = $pdo->prepare($sql); 
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
        $stmt->execute();                             // ←SQLを実行する。
        $results = $stmt->fetch();
        
        //パスワードが正しいなら編集処理する
        if($results['pass']==$pass3){
            echo "新しい内容で送信してください。<br>";
            $sql = 'SELECT * FROM tbtest WHERE id=:id ';
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            $results = $stmt->fetchAll(); 
    
        foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            $c= $row['id'];
            $a= $row['name'];
            $b= $row['comment'];
            $d=$row['pass'];
            
            
        }
        }
        else
        echo "パスワードが違います<br>";
 
         
     
     
     }
     
    
    ?>
    <form action="" method="post">
        <input type="text" name="name" placeholder="名前" value="<?php echo $a; ?>">
        <input type="text" name="text" placeholder="コメント" value="<?php echo $b; ?>">
        <input type="hidden" name="fire" placeholder="みえなくするよ" value="<?php echo $c; ?>">
        <input type="password" name="pass1" placeholder="パスワード"autocomplete="new-password" value="<?php echo $d; ?>">
        <input type="submit" name="submit">
        <br>
        <input type="num" name="num" placeholder="削除したい投稿番号（半角）">
        <input type="password" name="pass2" placeholder="パスワード"autocomplete="new-password">
        <input type="submit" name="delete" value="削除">
        <br>
        <input type="num" name="editnum" placeholder="編集したい投稿番号（半角）">
        <input type="password" name="pass3" placeholder="パスワード"autocomplete="new-password">
        <input type="submit" name="edit" value="編集">
    </form>
    
    <?php
    //テーブル内すべて表示
    $sql = 'SELECT * FROM tbtest';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'];
        echo $row['date'].'<br>';
        echo "<hr>";
        
    }
    
    ?>
    
    
</body>
</html>