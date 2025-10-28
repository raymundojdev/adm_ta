<?php
class ModelHome {

  static public function mdlMostrarHome($tabla, $item, $valor){
    if($item!=null){
      $stmt = conexiondb::conectar()->prepare("SELECT * FROM $tabla WHERE $item=:$item");
      $stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
      $stmt = conexiondb::conectar()->prepare("SELECT * FROM $tabla ORDER BY updated_at DESC");
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
  }

  static public function mdlGuardarHome($tabla, $d){
    $sql="INSERT INTO $tabla(slug,seo_title,seo_desc,og_image,content,is_active)
          VALUES(:slug,:seo_title,:seo_desc,:og_image,:content,:is_active)";
    $s=conexiondb::conectar()->prepare($sql);
    $s->bindParam(":slug",$d["slug"],PDO::PARAM_STR);
    $s->bindParam(":seo_title",$d["seo_title"],PDO::PARAM_STR);
    $s->bindParam(":seo_desc",$d["seo_desc"],PDO::PARAM_STR);
    $s->bindParam(":og_image",$d["og_image"],PDO::PARAM_STR);
    $s->bindParam(":content",$d["content"],PDO::PARAM_STR);
    $s->bindParam(":is_active",$d["is_active"],PDO::PARAM_INT);
    return $s->execute() ? "ok":"error";
  }

  static public function mdlEditarHome($tabla, $d){
    $sql="UPDATE $tabla SET slug=:slug,seo_title=:seo_title,seo_desc=:seo_desc,og_image=:og_image,content=:content,is_active=:is_active WHERE id=:id";
    $s=conexiondb::conectar()->prepare($sql);
    $s->bindParam(":id",$d["id"],PDO::PARAM_INT);
    $s->bindParam(":slug",$d["slug"],PDO::PARAM_STR);
    $s->bindParam(":seo_title",$d["seo_title"],PDO::PARAM_STR);
    $s->bindParam(":seo_desc",$d["seo_desc"],PDO::PARAM_STR);
    $s->bindParam(":og_image",$d["og_image"],PDO::PARAM_STR);
    $s->bindParam(":content",$d["content"],PDO::PARAM_STR);
    $s->bindParam(":is_active",$d["is_active"],PDO::PARAM_INT);
    return $s->execute() ? "ok":"error";
  }

  static public function mdlEliminarHome($tabla, $id){
    $s=conexiondb::conectar()->prepare("DELETE FROM $tabla WHERE id=:id");
    $s->bindParam(":id",$id,PDO::PARAM_INT);
    return $s->execute() ? "ok":"error";
  }

  static public function mdlObtenerPorId($tabla, $id){
    $s=conexiondb::conectar()->prepare("SELECT * FROM $tabla WHERE id=:id LIMIT 1");
    $s->bindParam(":id",$id,PDO::PARAM_INT);
    $s->execute();
    return $s->fetch(PDO::FETCH_ASSOC);
  }
}
