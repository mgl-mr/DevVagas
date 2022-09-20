<template>
  <div class="home">
    <div class="msg-container msg-container-styck">
      <transition>
        <p v-show="msg.error" class="msg msg-styck">{{ msg.mensagem }}</p>
      </transition>
    </div>
    <h1>HOME</h1>
    <div class="busca">
      <div class="container-input container-busca">
        <label for="busca">Buscar vagas</label>
        <input type="text" name="busca" v-model="pesquisa.empNome" @keyup="pesquisaVagas" placeholder="nome da empresa"/>
      </div>
      <div class="container-input">
        <label for="area">Área de atuação</label>
        <select name="area" id="area" v-model="pesquisa.area" @change="pesquisaVagas">
          <option value="" selected> Qualquer</option>
          <option value="Desenvolvedor Front-End">Desenvolvedor Front-End</option>
          <option value="Desenvolvedor Back-End">Desenvolvedor Back-End</option>
          <option value="Desenvolvedor Full-stack">Desenvolvedor Full-stack</option>
          <option value="Desenvolvedor mobile">Desenvolvedor mobile</option>
          <option value="Cientista de dados">Cientista de dados</option>
          <option value="Técnico de segurança">Técnico de segurança</option>
        </select>
      </div>
    </div>
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

        <button type="button" class="btn" @click="sendCurriculo(vaga.id_vaga)">Enviar currículo</button>
      </div>
    </div>
    
  </div>
</template>

<script>

export default {
  name: 'HomeView',

  data() {
    return {
      chave: "",
      dev: "",
      id_curriculo: null,
      completo: false,
      msg: {error: false, mensagem: ""},
      vagas: null,
      pesquisa: {
        empNome: "",
        area: ""
      },

    }
  },

  methods: {
    pesquisaVagas(){
      let formData = new FormData();
      formData.append("empNome", this.pesquisa.empNome);
      formData.append("area", this.pesquisa.area);

      if(this.pesquisa.empNome != "") {
        fetch("https://localhost/vue-php-project/backEnd/crontoler.php?action=pesquisaVagas", {
        body: formData,
        method: "post",
        })
        .then((resp) => resp.json())
        .then((data) => { 
          if(data.error == false) { 
            this.vagas = data.vagas;
          } else {
            this.vagas = null;
          }
        });
      } else {
        this.vagas = null;
      }
    },

    sendCurriculo(id){
      if(this.dev == "false") {
        this.msg.error = true;
        this.msg.mensagem = "Você não tem permissão";
      } else {
        if(this.completo != true) {
          this.msg.error = true;
          this.msg.mensagem = "Complete seu curriculo";
        } else {
          let formData = new FormData();
          formData.append("id_curriculo", this.id_curriculo);
          formData.append("id_vaga", id);
          
          fetch("https://localhost/vue-php-project/backEnd/crontoler.php?action=sendCurriculo", {
          body: formData,
          method: "post",
          })
          .then((resp) => resp.json())
          .then((data) => { 
            this.msg.error = true;
            this.msg.mensagem = data.message;
          })
        }
      } 
      setTimeout(() => {
        (this.msg.error = false), (this.msg.mensagem = "");
      }, 2000);
    },

    buscaDev(){
      let formData = new FormData();
      formData.append("chave", this.chave);

      fetch("https://localhost/vue-php-project/backEnd/crontoler.php?action=buscaDev", {
        body: formData,
        method: "post",
      })
      .then((resp) => resp.json())
      .then((data) => {  
        if(data.error) {
          console.log(data.message);
        } else { 
          if(data.user.completo == '1'){
            this.completo = true; 
          }
          let formData = new FormData();
          formData.append("id", data.user.id_dev);

          fetch("https://localhost/vue-php-project/backEnd/crontoler.php?action=buscaCurriculo", {
            body: formData,
            method: "post",
          })
          .then((resp) => resp.json())
          .then((data) => { 
            if(data.error) {
              console.log(data.message);
            } else { 
              this.id_curriculo = data.curriculo.id;
            }
          });
        }
      });
    },

    checaVariaveis() {
      this.chave = localStorage.getItem("chave");
      this.dev = localStorage.getItem("dev");
    }
  },

  created() {
    this.checaVariaveis();
    this.buscaDev();
  }
}
</script>

<style scoped>
.home {
  width: 100%;
  background-color: #DFE2ED;
  padding: 10px;
  margin: 0 auto;
}

.busca{
  display: flex;
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
