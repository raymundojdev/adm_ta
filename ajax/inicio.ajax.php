<?php
require_once "../models/conexion.php";
require_once "../controllers/home.controller.php";
require_once "../models/home.model.php";

class AjaxHome {
  public $idHome;

  public function ajaxObtener(){
    $r = ModelHome::mdlObtenerPorId("home_content", (int)$this->idHome);
    $out = $r ?: [];

    // Desglosar content a arrays
    $c = [];
    if(!empty($r["content"])){
      $c = json_decode($r["content"], true);
      if(json_last_error()!==JSON_ERROR_NONE) $c = [];
    }
    $out["hero_titulo"]        = $c["hero"]["titulo"] ?? "";
    $out["hero_subtitulo"]     = $c["hero"]["subtitulo"] ?? "";
    $out["hero_imagen"]        = $c["hero"]["imagen_fondo"] ?? "";
    $out["hero_cta1_texto"]    = $c["hero"]["cta1"]["texto"] ?? "";
    $out["hero_cta1_url"]      = $c["hero"]["cta1"]["url"] ?? "";
    $out["hero_cta2_texto"]    = $c["hero"]["cta2"]["texto"] ?? "";
    $out["hero_cta2_url"]      = $c["hero"]["cta2"]["url"] ?? "";

    $out["about_badge"]        = $c["about"]["badge"] ?? "";
    $out["about_titulo"]       = $c["about"]["titulo"] ?? "";
    $out["about_texto"]        = $c["about"]["texto"] ?? "";
    $out["about_imagen"]       = $c["about"]["imagen"] ?? "";

    $out["features"]           = $c["features"] ?? [];
    $out["metrics"]            = $c["metrics"] ?? [];
    $out["services"]           = $c["services"] ?? [];
    $out["testimonials"]       = $c["testimonials"] ?? [];

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($out, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
  }
}

if(isset($_POST["idHome"])){
  $a = new AjaxHome();
  $a->idHome = $_POST["idHome"];
  $a->ajaxObtener();
}
