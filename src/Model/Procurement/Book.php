<?php
namespace App\Model\Procurement;

use App\Database\DbProcurement;

class Book extends DbProcurement {
    public function InsertBook($book) {
        $sql = "
            INSERT INTO tb_book (
                id, 
                date_add, 
                bookId_recive, 
                bookId, 
                bookId_num, 
                bookNum, 
                bookName, 
                bookDate, 
                departmentForm_id, 
                departTo_id,
                year
            ) VALUES (
                NULL, 
                :date_add, 
                :bookId_recive, 
                :bookId, 
                :bookId_num, 
                :bookNum, 
                :bookName, 
                :bookDate, 
                :departmentForm_id, 
                :departTo_id,
                :year
            );
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($book);
        return $this->pdo->lastInsertId();

    }
    public function getBookId($year) {
        $sql="
            select 
                bookId 
            from 
                tb_book 
            where
                year='".$year."'
            order by
                bookId
            desc
        ";  
        $stmt = $this->pdo->query($sql);
        // $stmt->execute($year);
        $data = $stmt->fetchAll();
        $row = $stmt->rowCount();
        
        if($row == 0){
            $num=1;
            return $num;
        }else{
            // $num['bookId']=intval($data[0])+1;
            $num=intval($data[0]['bookId'])+1;
            return $num;
        }
        // return $row;
    }
    public function getBookIdByDate($date) {
        $sql="
            select 
                * 
            from 
                tb_book  
            where 
                date_add = :date_add AND
                year = :year
            order by
                bookId
            desc
        ";  
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($date);
        $data =$stmt->fetchAll();
        $row = $stmt->rowCount();
        
        // $num['bookId_num']=intval($data[0]['bookId_num'])+1;
        if($row == 0){
            $num['bookId']=1;
            $num['bookId_num']=1;
            return $num;
        }else{
            $num['bookId']=intval($data[0]['bookId']);
            $num['bookId_num']=intval($data[0]['bookId_num'])+1;
            return $num;
        }
        // return $row;
    }
    public function getBookByDate($date) {
        $sql="
            select 
                b.*,df.*,dt.*
            from 
                tb_book AS b
                LEFT JOIN tb_departmentf AS df ON b.departmentForm_id = df.id
                LEFT JOIN tb_departmentt AS dt ON b.departTo_id = dt.id
            where 
                b.bookRegis_date = :bookRegis_date AND
                b.year = :year
            order by
                b.bookId
        ";  
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($date);
        $data =$stmt->fetchAll();
        return $data;
    }

}