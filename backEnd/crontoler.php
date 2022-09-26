<?php
header('Access-Control-Allow-Origin: *');

include("model.php");
include("email/envia_email.php");

$result = array('error'=>false);
$action = "";

if(isset($_GET['action'])) {
  $action = $_GET['action'];
}

if($action == "inserirDev") {
  $nome = $_POST['nome'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $senha=password_hash($_POST['senha'], PASSWORD_DEFAULT);
  $dtNasc = $_POST['dtNasc'];
  $chave = rand(100000000000000000, 999999999999999999);

  $array = array($nome, $email, $phone, $senha, $dtNasc, $chave);
  $query = "INSERT INTO devs(nome, email, phone, senha, data_nasc, chave) VALUES(?, ?, ?, ?, ?, ?)";

  $resp = insUpDel($query,$array);

  if($resp) {
    $result['message'] = "Cadastrado com sucesso";

    $hash=md5($email);
    $link="<a href='https://localhost/vue-php-project/backEnd/crontoler.php?dev=".$hash."'> Clique aqui para confirmar seu cadastro </a>";
    $mensagem="<tr><td style='padding: 10px 0 10px 0;' align='center' bgcolor='#669999'>";

    $mensagem.="Email de Confirmação <br>".$link."</td></tr>";
    $assunto="Confirme seu cadastro";

    enviaEmail($email, $mensagem, $assunto);
  } else {
    $result['error'] = true;
    $result['message'] = "Falha ao cadastrar";
  }
}

if($action == "inserirEmp") {
  $nome = $_POST['nome'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $senha=password_hash($_POST['senha'], PASSWORD_DEFAULT);
  $cnpj = $_POST['cnpj'];
  $chave = rand(10000000000000000, 99999999999999999);

  $array = array($nome, $email, $phone, $senha, $cnpj, $chave);
  $query = "INSERT INTO empresas(nome, email, phone, senha, cnpj, chave) VALUES(?, ?, ?, ?, ?, ?)";

  $resp = insUpDel($query,$array);

  if($resp) {
    $result['message'] = "Cadastrado com sucesso";

    $hash=md5($email);
    $link="<a href='https://localhost/vue-php-project/backEnd/crontoler.php?emp=".$hash."'> Clique aqui para confirmar seu cadastro </a>";
    $mensagem="<tr><td style='padding: 10px 0 10px 0;' align='center' bgcolor='#669999'>";

    $mensagem.="Email de Confirmação <br>".$link."</td></tr>";
    $assunto="Confirme seu cadastro";

    enviaEmail($email, $mensagem, $assunto);    
  } else {
    $result['error'] = true;
    $result['message'] = "Falha ao cadastrar";
  }
}

if($action == "logar") {
  $email = $_POST['email'];
  $senha = $_POST['senha'];

  $array = array($email);
  $query = "SELECT * from devs WHERE email = ?";

  $resp = ConsultaSelect($query, $array);

  if($resp) {
    if($resp['status']) {
      if(password_verify($senha,$resp['senha'])){
        $result['message'] = "Bem vindo ".$resp['nome'];
        $result['dev'] = true;
        $result['chave'] = $resp['chave'];
      } else {
        $result['error'] = true;
        $result['message'] = "Senha incorreta";
      }
    } else {
      $result['error'] = true;
      $result['message'] = "Você ainda não verificou seu email";
    }
  } else {
    $query = "SELECT * FROM empresas WHERE email = ?";
    $resp = ConsultaSelect($query, $array);

    if($resp) {
      if($resp['status']) {
        if(password_verify($senha,$resp['senha'])){
          $result['message'] = "Bem vindo ".$resp['nome'];
          $result['dev'] = false;
          $result['chave'] = $resp['chave'];
        } else {
          $result['error'] = true;
          $result['message'] = "Senha incorreta";
        }
      } else {
        $result['error'] = true;
        $result['message'] = "Você ainda não verificou seu email";
      }
    } else {
      $result['error'] = true;
      $result['message'] = "Login inválido";
    }
  }
}

if($action == "buscaPerfil") {
  $chave = $_POST['chave'];
  $dev = $_POST['dev'];

  $array = array($chave);
  if($dev == "true") {
    $query = "SELECT * from devs WHERE chave = ?";
  } else {
    $query = "SELECT * from empresas WHERE chave = ?";
  }
  
  $resp = ConsultaSelect($query, $array);

  if($resp) {
    $result['user'] = $resp;
  } else {
    $result['error'] = true;
    $result['message'] = "error";
  } 
}

if($action == "buscaEndereco") {
  $chave = $_POST['chave'];
  $dev = $_POST['dev'];

  $array = array($chave);
  if($dev == "true") {
    $query = "SELECT * FROM endereco_dev JOIN devs ON(devs.id = endereco_dev.id_dev) WHERE devs.chave=?";
  } else {
    $query = "SELECT * FROM endereco_empresa JOIN empresas ON(empresas.id = endereco_empresa.id_empresa) WHERE empresas.chave=?";
  }
  
  $resp = ConsultaSelect($query, $array);

  if($resp) {
    $result['data'] = $resp;
  } else {
    $result['error'] = true;
    $result['message'] = "error";
  } 
}

if($action == "updateEndereco") {
  $localCadastrado = $_POST['localCadastrado'];
  $dev = $_POST['dev'];
  $id = $_POST['id'];
  $estado = $_POST['estado'];
  $cidade = $_POST['cidade'];
  
  if($dev == "true") {
    $array = array($cidade, $estado, $id); 
    
    if($localCadastrado == "false") {
      $query = "INSERT INTO endereco_dev(cidade, estado, id_dev) VALUES(?, ?, ?)";
    } else {
      $query = "UPDATE endereco_dev SET cidade=?, estado =?  WHERE id_dev=?";
    }
  } else { 
    $bairro = $_POST['bairro'];
    $complemento = $_POST['complemento'];
    $logradouro= $_POST['logradouro']; 

    $array = array($logradouro, $complemento, $bairro, $cidade, $estado, $id);

    if($localCadastrado == "false") { 
      $query = "INSERT INTO endereco_empresa(logradouro, complemento, bairro, cidade, estado, id_empresa) VALUES(?, ?, ?, ?, ?, ?)";
    } else { 
      $query = "UPDATE endereco_empresa SET logradouro=?, complemento=?, bairro=?, cidade=?, estado=? WHERE id_empresa=?";
    }
  }

  $resp = insUpDel($query,$array);
  
  if($resp) { 
    $result['message'] = "Endereço atualizado";
    if(substr($query, 0, 28) == "INSERT INTO endereco_empresa") {
      $array = array($id);
      $query = "UPDATE empresas SET completo = true WHERE id = ?";
      insUpDel($query, $array);
    }
  } else {
    $result['error'] = true;
    $result['message'] = "Falha ao atualizar endereço";
  }
}

if($action == "updateUser") {
  $id = $_POST['id'];
  $dev = $_POST['dev'];
  $nome = $_POST['nome'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];

  $array = array($nome, $email, $phone);
  
  if($dev == "true") {
    $data_nascimento = $_POST['data_nascimento'];
    array_push($array, $data_nascimento);
    $query = "UPDATE devs SET nome=?, email=?, phone=?, data_nasc=? WHERE id = ?";
  } else {
    $cnpj = $_POST['cnpj'];
    array_push($array, $cnpj);
    $query = "UPDATE empresas SET nome=?, email=?, phone=?, cnpj=? WHERE id = ?";
  }

  array_push($array, $id);
  $resp = insUpDel($query,$array);
  
  if($resp) {
    $result['message'] = "Dados atualizados";
  } else {
    $result['error'] = true;
    $result['message'] = "Falha ao atualizar dados";
  }
}

if($action == "buscaDev") {
  $chave = $_POST['chave'];

  $array = array($chave);
  $query = "SELECT * FROM devs JOIN endereco_dev ON(endereco_dev.id_dev = devs.id) WHERE devs.chave=?";

  $resp = ConsultaSelect($query, $array);

  if($resp) {
    $result['user'] = $resp;
  } else {
    $result['error'] = true;
    $result['message'] = "error";
  }  
}

if($action == "buscaEmp") {
  $chave = $_POST['chave'];
  
  $array = array($chave);
  $query = "SELECT * FROM empresas JOIN endereco_empresa ON(endereco_empresa.id_empresa = empresas.id) WHERE empresas.chave=?";

  $resp = ConsultaSelect($query, $array);

  if($resp) {
    $result['emp'] = $resp;
  } else {
    $result['error'] = true;
    $result['message'] = "error";
  }  
}

if($action == "buscaCurriculo") {
  $id = $_POST['id'];

  $array = array($id);
  $query = "SELECT * FROM curriculo WHERE id_dev = ?";
  $result['array'] = $array;
  $resp = ConsultaSelect($query, $array);

  if($resp) {
    $result['curriculo'] = $resp;
  } else {
    $result['error'] = true;
    $result['message'] = "error";
  }  
}

if($action == "updateCurriculo") {
  $id = $_POST['id'];
  $cadastrado = $_POST['cadastrado'];
  $formacao = $_POST['formacao'];
  $experiencia = $_POST['experiencia'];
  $idiomas = $_POST['idiomas'];
  $github = $_POST['github'];

  $array = array($formacao, $experiencia, $idiomas, $github, $id);
  
  if($cadastrado == 'false') {
    $query = "INSERT INTO curriculo(formacao, experiencia, idiomas, github, id_dev) Values(? ,? ,? ,?, ?)";
  } else {
    $query = "UPDATE curriculo SET formacao=?, experiencia=?, idiomas=?, github=? WHERE id_dev=?";
  }

  $resp = insUpDel($query,$array);
  
  if($resp) {
    $result['message'] = "Dados atualizados";
    if(substr($query, 0, 6) == "INSERT") {
      $array = array($id);
      $query = "UPDATE devs SET completo = true WHERE id = ?";
      insUpDel($query, $array);
    }
  } else {
    $result['error'] = true;
    $result['message'] = "Falha ao atualizar dados";
  } 
}

if($action == "inserirVaga") {
  $id = $_POST['id'];
  $area = $_POST['area'];
  $vaga = $_POST['vaga'];
  $descricao = $_POST['descricao'];
  $requisitos = $_POST['requisitos'];
  $salario = $_POST['salario'];

  $array = array($area, $vaga, $descricao, $requisitos, $salario, $id);
  $query = "INSERT INTO vagas(area, vaga, descricao, requisitos, salario, id_empresa) Values(?,?,?,?,?,?)";
  $resp = insUpDel($query,$array);

  if($resp) {
    $result['message'] = "Vaga cadastrada";
  } else {
    $result['error'] = true;
    $result['message'] = "Falha ao vadastrar vaga";
  } 
}

if($action == "buscaVagas") {
  $id = $_POST['id'];

  $array = array($id);
  $query = "SELECT * FROM vagas WHERE id_empresa = ?";
  $resp = ConsultaSelectAll($query, $array);

  if($resp) {
    $result['vagas'] = $resp;
  } else {
    $result['error'] = true;
    $result['message'] = "error";
  }  
}

if($action == "updateVaga") {
  $id = $_POST['id'];
  $area = $_POST['area'];
  $vaga = $_POST['vaga'];
  $descricao = $_POST['descricao'];
  $requisitos = $_POST['requisitos'];
  $salario = $_POST['salario'];
  
  $array = array($area, $vaga, $descricao, $requisitos, $salario, $id);
  $query = "UPDATE vagas SET area=?, vaga=?, descricao=?, requisitos=?, salario=? WHERE id=?";

  $resp = insUpDel($query,$array);

  if($resp) {
    $result['message'] = "Alterado com sucesso";
  } else {
    $result['error'] = true;
    $result['message'] = "Falha ao alterar vaga";
  } 
}

if($action == "deleteVaga") {
  $id = $_POST['id'];
  
  $array = array($id);
  $query = "DELETE FROM vagas WHERE id=?";

  $resp = insUpDel($query,$array);

  if($resp) {
    $result['message'] = "Deletado com sucesso";
  } else {
    $result['error'] = true;
    $result['message'] = "Falha ao deletar vaga";
  } 
}

if($action == "pesquisaVagas") {
  $empNome = "%".$_POST['empNome']."%";
  $area = $_POST['area'];

  $array = array($empNome);
  if($area == "") {
    $query = "SELECT vagas.id AS id_vaga, vagas.*, empresas.*, endereco_empresa.* FROM vagas JOIN empresas ON(empresas.id=vagas.id_empresa) JOIN endereco_empresa ON(endereco_empresa.id_empresa=vagas.id_empresa) WHERE empresas.nome LIKE ?";
  } else {
    $array = array($empNome, $area);
    $query = "SELECT vagas.id AS id_vaga, vagas.*, empresas.*, endereco_empresa.* FROM vagas JOIN empresas ON(empresas.id=vagas.id_empresa) JOIN endereco_empresa ON(endereco_empresa.id_empresa=vagas.id_empresa) WHERE empresas.nome LIKE ? AND vagas.area = ?";
  }
  $resp = ConsultaSelectAll($query, $array);
  
  if($resp) {
    $result['vagas'] = $resp;
  } else {
    $result['error'] = true;
    $result['message'] = "error";
  }  
}

if($action == "sendCurriculo") {
  $id_curriculo = $_POST['id_curriculo'];
  $id_vaga = $_POST['id_vaga'];
  
  $array = array($id_curriculo, $id_vaga);
  $query = "SELECT * FROM vaga_curriculo WHERE id_curriculo=? AND id_vaga = ?";

  $resp = ConsultaSelect($query,$array); 

  if($resp) {
    $result['error'] = true;
    $result['message'] = "Você já envio currículo pra esta vaga";
  } else {
    $query = "INSERT INTO vaga_curriculo(id_curriculo, id_vaga) VALUES(?, ?)";
    $resp = insUpDel($query,$array);

    if($resp) {
      $result['message'] = "Enviado com sucesso";
    } else {
      $result['error'] = true;
      $result['message'] = "Falha ao enviar";
    } 
  } 
}

if($action == "buscaEnvios") {
  $chave = $_POST['chave'];

  $array = array($chave);
  $query = "SELECT curriculo.id FROM curriculo JOIN devs ON(devs.id=curriculo.id_dev ) WHERE devs.chave=?";
  $id_curriculo = ConsultaSelect($query, $array);
  
  if($id_curriculo != false) {
    $result['id_curriculo'] = $id_curriculo['id'];  

    $array = array($id_curriculo['id']); 
    $query = "SELECT vaga_curriculo.id_vaga FROM vaga_curriculo JOIN curriculo ON(curriculo.id = vaga_curriculo.id_curriculo) WHERE curriculo.id=?";

    $id_vagas = ConsultaSelectAll($query, $array);
    if($id_vagas != false) {
      $vagas = [];
      for($i = 0; $i < count($id_vagas); $i++){
        $array = array($id_vagas[$i]['id_vaga']);
        $query = "SELECT vagas.id AS id_vaga, vagas.*, empresas.*, endereco_empresa.* FROM vagas JOIN empresas ON(empresas.id=vagas.id_empresa) JOIN endereco_empresa ON(endereco_empresa.id_empresa=empresas.id) WHERE vagas.id IN(?)";

        $resp = ConsultaSelect($query, $array);
        $vagas[$i] = $resp;
      }
      $result['vagas'] = $vagas;
    } else {
      $result['error'] = true;
      $result['message'] = "Nenhum curriculo enviado";
    }
  } else {
    $result['error'] = true;
    $result['message'] = "você ainda não cadastrou seu curriculo";
  }
}

if($action == "deleteEnvio") {
  $id_curriculo = $_POST['id_curriculo'];
  $id_vaga = $_POST['id_vaga'];

  $array = array($id_curriculo, $id_vaga);
  $query = "DELETE FROM vaga_curriculo WHERE id_curriculo=? AND id_vaga=?";

  $resp = insUpDel($query,$array);

  if($resp) {
    $result['message'] = "Deletado com sucesso";
  } else {
    $result['error'] = true;
    $result['message'] = "Falha ao deletar vaga";
  } 
}

if($action == "buscaCurriculos"){
  $id_vaga = $_POST['id_vaga'];

  $array = array($id_vaga);
  $query = "SELECT id_curriculo FROM vaga_curriculo WHERE id_vaga = ?";
  $id_curriculos = ConsultaSelectAll($query, $array);

  if($id_curriculos != false) {
    $curriculos = [];
    for($i = 0; $i < count($id_curriculos); $i++){
      $array =  array($id_curriculos[$i]['id_curriculo']);
      $query = "SELECT * FROM devs JOIN endereco_dev ON(endereco_dev.id_dev = devs.id) JOIN curriculo ON(curriculo.id_dev = devs.id) WHERE curriculo.id = ?";

      $resp = ConsultaSelect($query, $array);
      $curriculos[$i] = $resp;
    }
    $result['curriculos'] = $curriculos;
  } else {
    $result['error'] = true;
    $result['message'] = "Nenhum currículo recebido";
  }
}

if($action == "updateImage"){
  $id = $_POST['id'];
  $chave = $_POST['chave'];
  $dev = $_POST['dev'];
  $nomeAntigo = $_POST['nomeAntigo'];
  $arquivo_temporario = $_FILES['imagem']['tmp_name'];

  $nomeImagem = $chave.".jpg";

  if($nomeAntigo == "userNoImage.png"){
    $array = array($nomeImagem, $id);
    if($dev == "true") {
      $query = "UPDATE devs SET foto=? WHERE id = ?";
    } else {
      $query = "UPDATE empresas SET foto=? WHERE id = ?";
    }
    insUpDel($query,$array);
  }
  
  if(move_uploaded_file($arquivo_temporario, "imagens/$nomeImagem")) {
    $result['message'] = "Imagem alterada";
  } else {
    $result['error'] = true;
    $result['message'] = "Falha ao atualizar";
  } 
}

if(isset($_GET['dev'])){
  $h=$_GET['dev'];

  $array = array($h);
  $query = "SELECT * FROM devs WHERE md5(email)=?";
  $resp=ConsultaSelect($query, $array);

  if($resp){
      $array=array($resp['id']);
      $query = "UPDATE devs SET status = true WHERE id=?";
      insUpDel($query, $array);
      header('location:http://localhost:8080');
  }
}

if(isset($_GET['emp'])){
  $h=$_GET['emp'];

  $array = array($h);
  $query = "SELECT * FROM empresas WHERE md5(email)=?";
  $resp=ConsultaSelect($query, $array);

  if($resp){
      $array=array($resp['id']);
      $query = "UPDATE empresas SET status = true WHERE id=?";
      insUpDel($query, $array);
      header('location:http://localhost:8080');
  }
}

echo(json_encode($result)); 