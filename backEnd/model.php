<?php

function fazconexao() {
  try {
    $conexao = new PDO("mysql:host=localhost; dbname=dev_vagas; charset=utf8", "root", "");
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return ($conexao);
  } 
  catch (PDOException $ex) {
    die($ex->getMessage());
  }
}

function ConsultaSelectAll($sql, $parametros = array()) {
  try {
    $conexaoBD = fazConexao();
    $consulta = $conexaoBD->prepare($sql);

    if (sizeof($parametros) > 0) {
      $consulta->execute($parametros);
    } else {
      $consulta->execute();
    }
    $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
    return ($resultados);

  } catch (PDOException $e) {
    echo $e;
  }
}

function ConsultaSelect($sql, $parametros = array()) {
  try {
    $conexaoBD = fazConexao();
    $consulta = $conexaoBD->prepare($sql);

    if (sizeof($parametros) > 0) {
      $consulta->execute($parametros);
    } else {
      $consulta->execute();
    }
    $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
    return ($resultado);

  } catch (PDOException $e) {
    echo $e;
  }
}

function insUpDel($sql, $parametros = array()) {
  try {
    $conexaoBD = fazConexao();
    $consulta = $conexaoBD->prepare($sql);

    $resultado = $consulta->execute($parametros);
    return $resultado;

  } catch (PDOException $e) {
    return false;
  }
}
