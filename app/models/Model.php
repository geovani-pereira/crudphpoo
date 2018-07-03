<?php

namespace App\Models;
use Config\Db;
class Model
{
	static public function all()
	{
		$obj = new static;

		$conn = Db::conexao();
		$sql = "select * from ".$obj->table;
		$ret = $conn->query($sql);
		$compras = $ret->fetchAll();

		return $compras;
	} 

	static public function find(int $id)
	{
		$obj = new static;

		$conn = Db::conexao();
		$sql = "select * from ".$obj->table." where ".$obj->primary_key." =:id limit 1";
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(':id',$id);
		$stmt->execute();
		$objAux = $stmt->fetch(\PDO::FETCH_OBJ);

		foreach ($objAux as $key => $value) {
			$obj->{$key} = $value;
		}

		return $obj;
	} 

	public function save()
	{	
		$atributos = get_object_vars($this);
		unset($atributos['table']);
		unset($atributos['primary_key']);


		if(isset($this->{$this->primary_key})){
			unset($atributos[$this->primary_key]);

			//atualizar o registro
			$coluna = "";
			$aux = true;
		
			foreach ($atributos as $key => $value) {
				if($aux){
				$aux 	 = false;
				$coluna .= "`$key` = :$key";
			
				}else{
					$coluna .= ", `$key` = :$key";
				}

			}
			$update = "update `".$this->table."` set ".$coluna." where `".$this->primary_key."`=:id;";

			$conn = Db::conexao();
			$stmt = $conn->prepare($update);
			foreach ($atributos as $key => $val) {
			//$stmt->bindParam(':'.$key,$atributos[$key]);
			$stmt->bindValue(':'.$key,$value);
		}
			$stmt->bindValue(':id',$this->{$this->primary_key});
			$stmt->execute();
			return $this;
		}

		//criar registro	
		
		$coluna = "(";
		$val  = "(";
		$aux = true;
		foreach($atributos as $key =>$value){
			if($aux){
				$aux 	 = false;
				$coluna .= "`$key`";
				$val 	.=":$key";
			}else{
				$coluna .= ",`$key`";
				$val 	.=",:$key";
			}
		}

		$coluna .= ")";
		$val  .= ")";

		$insert = "insert into `".$this->table."` ".$coluna." values ".$val;
		$conn = Db::conexao();
		$stmt = $conn->prepare($insert);
		foreach ($atributos as $key => $val) {
			//$stmt->bindParam(':'.$key,$atributos[$key]);
			$stmt->bindValue(':'.$key,$val);
		}
		$stmt->execute();
		return $this::find($conn->lastInsertId());
		

	}

	public function delete()
	{
		$conn = Db::conexao();

		$delete = "delete from `".$this->table."` where `".$this->primary_key."`=:id;";
		$stmt = $conn->prepare($delete);
		$stmt->bindValue(':id',$this->{$this->primary_key});
		

		return $stmt->execute();

	}
}