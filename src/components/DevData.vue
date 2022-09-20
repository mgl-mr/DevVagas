<template>
  <div class="field">
    <div class="header sub-header">
      <h2>Dados gerais</h2>
    </div>
    <p>Nome: {{user.nome}}</p>
    <p>Email: {{user.email}}</p>
    <p>Telefone: {{user.phone}}</p>
    <p>Data de nascimento: {{user.dt_nasc}}</p>
    <p>Cidade: {{user.cidade}}</p>
    <p>Estado: {{user.estado}}</p>
  </div>
</template>

<script>
export default {
  name: "DevData",

  props: ["user"]
}
</script>

<style scoped>
.header {
  margin-bottom: 10px;
}

.header h2 {
  font-size: 28px;
  text-align: left;
  margin-left: 0;
}

p {
  font-family: Arial, Helvetica, sans-serif;
  font-weight: 500;
  color: #4a4b4f;
  font-size: 18px;
  margin-bottom: 20px;
  border-bottom: 2px solid #dfe2ed;
  padding-bottom: 3px;
}
</style>