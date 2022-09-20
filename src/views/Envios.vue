<template>
  <div class="envio">
    <h1>Meus envios</h1>
    <div class="msg-container msg-container-styck">
      <transition>
        <p v-show="msg.error" class="msg msg-styck">{{ msg.mensagem }}</p>
      </transition>
    </div>
    <div v-if="render.error == false">
      <p>{{render.mensagem}}</p>
    </div>
    <div v-else>
      <h2>Vagas</h2>
      <div class="lista-vaga" v-show="vagas != null">
        <div v-for="vaga in vagas" :key="vaga.id_vaga" class="row-vaga">
          <p>Empresa: {{vaga.nome}}</p>

          <p>Email: {{vaga.email}}</p>
          <p>Telefone: {{vaga.phone}}</p>

          <p>Logradouro: {{vaga.logradouro}}</p>
          <p>Complemento: {{vaga.complemento}}</p>
          <p>Bairro: {{vaga.bairro}}</p>
          <p>Cidade: {{vaga.cidade}}</p>
          <p>Estado: {{vaga.estado}}</p>

          <p>Área: {{vaga.area}}</p>
          <p>Vaga: {{vaga.vaga}}</p>
          <p>Salário: {{vaga.salario}}</p>
          <p>Descrição: {{vaga.descricao}}</p>
          <p>Requisitos: {{vaga.requisitos}}</p>
          <button type="button" class="btn" @click="deleteEnvio(vaga.id_vaga)">Cancelar envio</button>
        </div> 
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      chave: "",
      id_curriculo: null,
      render: {error: false, mensagem: ""},
      msg: {error: false, mensagem: ""},
      vagas: null,
    }
  },

  methods: {
    buscaEnvios(){
      let formData = new FormData();
      formData.append("chave", this.chave);

      fetch("https://localhost/vue-php-project/backEnd/crontoler.php?action=buscaEnvios", {
        body: formData,
        method: "post",
      })
      .then((resp) => resp.json())
      .then((data) => { 
        if(data.error) {
          this.render.error = false;
          this.render.mensagem = data.message;
          this.vagas = null;
        } else {
          this.render.error = true;
          this.id_curriculo = data.id_curriculo;
          this.vagas = data.vagas;
        }
      })
    },

    deleteEnvio(id){
      let formData = new FormData();
      formData.append("id_curriculo", this.id_curriculo);
      formData.append("id_vaga", id);

      fetch("https://localhost/vue-php-project/backEnd/crontoler.php?action=deleteEnvio", {
        body: formData,
        method: "post",
      })
      .then((resp) => resp.json())
      .then((data) => { 
        this.msg.error = true;
        this.msg.mensagem = data.message;
        if(data.error == false) {
          for(let i=0; i<this.vagas.length; i++) {
            if(this.vagas[i].id_vaga == id) {
              this.vagas.splice(i, 1);
            }
          }
          if(this.vagas == "") {
            this.render.error = false;
            this.render.mensagem = "Nenhum curriculo enviado";
          }
        }
      });
      setTimeout(() => {
        (this.msg.error = false), (this.msg.mensagem = "");
      }, 2000); 
    },

    checaVariaveis() {
      this.chave = localStorage.getItem("chave");
    }
  },

  created() {
    this.checaVariaveis();
    this.buscaEnvios();
  }
}
</script>

<style scoped>
.envio {
  padding: 10px;
  width: 100%;
}



.lista-vaga{
  width: 100%;
}

.row-vaga {
  background-color: #4a4b4f;
  padding: 10px;
  margin-bottom: 10px;
}

.row-vaga p {
  color: #dfe2ed;
}
</style>