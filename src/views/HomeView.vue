<template>
  <div class="view">
    <div class="msg-container msg-container-styck">
      <transition>
        <p v-show="msg.error" class="msg msg-styck">{{ msg.mensagem }}</p>
      </transition>
    </div>

    <div class="header">
      <h2>Home</h2>
    </div>

    <div class="busca">
      <div class="container-input">
        <label for="busca" class="espaco">Buscar vagas</label>
        <input type="text" name="busca" class="input" v-model="pesquisa.empNome" @keyup="pesquisaVagas" placeholder="nome da empresa"/>
      </div>
      <div class="container-input">
        <label for="area">Área de atuação</label>
        <select name="area" id="area" class="input" v-model="pesquisa.area" @change="pesquisaVagas">
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

    <div class="container" v-if="vagas != null">
      <div v-for="vaga in vagas" :key="vaga.id_vaga" class="field">
        <div class="header sub-header">
            <h2>Vaga</h2>
          </div>
          <div class="flex">
            <div class="flex-1">
              <h3>Sobre a empresa</h3>
              <div class="text">
                <p>Empresa: <i>{{vaga.nome}}</i></p>
                <p>Email: <i>{{vaga.email}}</i></p>
                <p>Telefone: <i>{{vaga.phone}}</i></p>
              </div>
            </div>
            <div class="flex-2">
              <h3>Endereço</h3>
              <div class="text">
                <div>
                  <p>Logradouro: <i>{{vaga.logradouro}}</i></p>
                  <p>Complemento: <i>{{vaga.complemento}}</i></p>
                  <p>Bairro: <i>{{vaga.bairro}}</i></p>
                </div>
                <div>
                  <p>Cidade: <i>{{vaga.cidade}}</i></p>
                  <p>Estado: <i>{{vaga.estado}}</i></p>
                </div>
              </div>
            </div>
            <div class="flex-3">
              <h3>Sobre a vaga</h3>
              <div>
                <div class="text">
                  <p>Área: <i>{{vaga.area}}</i></p>
                  <p>Vaga: <i>{{vaga.vaga}}</i></p>
                  <p>Salário: <i>{{vaga.salario}}</i></p>
                </div> 
                <h3 class="flex3">Descrição</h3>
                <p>{{vaga.descricao}}</p>
                <h3 class="flex3">Requisitos</h3>
                <p>{{vaga.requisitos}}</p>
              </div> 
            </div> 
          </div>

        <button type="button" class="btn" @click="sendCurriculo(vaga.id_vaga)">Enviar currículo</button>
      </div>
    </div>
    <div v-else class="preenche"></div>
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
.view {
  height: auto;
}

.busca {
  display: flex;
  padding: 15px;
}

.preenche{
  height: 82vh;
}

.container-input label {
  margin-right: 3px;
}

.input{
  width: 200px;
  margin-right: 10px;
}

.container {
  color: #303133;
  margin-left: 15px;
  margin-top: 15px;
  flex-direction: column;
  padding-right: 15px;
}

.container .field{
  margin-bottom: 15px;
  width: 100%;
}

.container .field .flex{
  display: flex;
  flex-direction: column;
  width: 100%;
  gap: 15px;
  padding-top: 5px;
}

.container .field .flex > div{
  padding-left: 10px;
}

.container .field .flex .text{
  display: flex;
  justify-content: space-evenly ;
  height: auto;
}

.container .field .flex div h3{
  font-size: 16px;
  font-family: Arial, Helvetica, sans-serif;
  border-bottom: 3px solid #303133;
  margin-bottom: 5px;
  margin-top: 10px;
}

.container .field .flex div .flex3 {
  margin-top: 10px;
  margin-bottom: 2px;
}

.container .field .flex div p {
  font-family: Arial, Helvetica, sans-serif;
  font-weight: 500;
  color: #303133;
  font-size: 18px;  
}



.container .field .flex div p i {
  color: #4a4b4f;
}

.container .field .btn {
  margin-top: 15px;
}

.warning {
  width: 100%;
  height: 80vh;
}

.sub-header {
  margin-bottom: 10px;
}

.sub-header h2 {
  font-size: 28px;
  text-align: left;
  margin-left: 0;
}

.msg-styck {
  top: 50px;
}

.field:hover {
  transform: scale(1);
}

/*** MEDIA QUERIES ***/
@media screen and (max-width: 800px) {
  .view {
    height: auto;
  }

  .busca {
    display: block;
  }

  .espaco {
    margin-right: 22px;
  }

  .container .field .flex {
    display: block;
  }

  .container .field .flex div{
    width: 100%;  
  }

  .container .field .flex div p {
    margin-bottom: 5px;
  }

  .container .field .flex .text{
    display: block;
  }

  .warning {
    height: 85vh;
  }
}
</style>
