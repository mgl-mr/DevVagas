<template>
  <div>
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