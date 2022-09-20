<template>
  <div>
    <p>Nome: {{emp.nome}}</p>
    <p>Email: {{emp.email}}</p>
    <p>Telefone: {{emp.phone}}</p>
    <p>CNPJ: {{emp.cnpj}}</p>
    <p>Logradouro: {{emp.logradouro}}</p>
    <p>Complemento: {{emp.complemento}}</p>
    <p>Bairro: {{emp.bairro}}</p>
    <p>Cidade: {{emp.cidade}}</p>
    <p>Estado: {{emp.estado}}</p> 
  </div>
</template>

<script>
export default {
  name: "EmpData",
  props: ["chave"],

  data() {
    return {
      emp: { 
        id: "",
        nome: "",
        email: "",
        phone: "",
        cnpj: "",
        logradouro: "",
        complemento: "",
        bairro: "",
        cidade: "",
        estado: ""
      },
    }
  },

  methods: {
    buscaEmp() {
      let formData = new FormData();
      formData.append("chave", this.chave);

      fetch("https://localhost/vue-php-project/backEnd/crontoler.php?action=buscaEmp", {
        body: formData,
        method: "post",
      })
      .then((resp) => resp.json())
      .then((data) => { 
          this.emp.id = data.emp.id_empresa;
          this.emp.nome = data.emp.nome;
          this.emp.email = data.emp.email;
          this.emp.phone = data.emp.phone;
          this.emp.cnpj = data.emp.cnpj;
          this.emp.logradouro = data.emp.logradouro;
          this.emp.complemento = data.emp.complemento;
          this.emp.bairro = data.emp.bairro;
          this.emp.cidade = data.emp.cidade;
          this.emp.estado = data.emp.estado;
      });
    }
  },

  created() {
    this.buscaEmp();
  }
}
</script>