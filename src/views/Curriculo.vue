<template>
  <div class="view">
    <div v-if="endereco == false" class="warning">
      <div class="header">
        <h2>AVISO</h2>
      </div>
      <div>
        <p>Você ainda não cadastrou seu endereço.</p>
        <p>Vá para perfil e cadestre-o!</p>
      </div>
    </div>
    <div v-else>
      <div class="header">
        <h2>Meu curriculo</h2>
      </div>
      <div class="container">
        <DevData :user="userData"/>

        <div class="field">
          <div class="container-input">
            <label for="formação">Formação</label>
            <textarea class="input" name="formação" v-model="curriculo.formacao" placeholder="Descreva sua formação acadêmica"></textarea>
          </div>

          <div class="container-input">
            <label for="experiência">Experiência</label>
            <textarea class="input" name="experiência"  v-model="curriculo.experiencia" placeholder="Descreva sua experiência de trabalho"></textarea>
          </div>

          <div class="container-input">
            <label for="idiomas">Idiomas</label>
            <input type="text" class="input" name="idiomas" v-model="curriculo.idiomas" placeholder="Informe quais linguas você é fluente">
          </div>

          <div class="container-input">
            <label for="github">GitHub</label>
            <input type="text" class="input" name="github" v-model="curriculo.github" placeholder="Link do seu GitHub">
          </div>

          <div class="msg-container">
            <transition>
              <p v-show="msg.error" class="msg">{{ msg.mensagem }}</p>
            </transition>
          </div>
          <button type="button" class="btn" @click="updateCurriculo">Atualizar dados</button>
        </div> 
      </div>
    </div>
  </div>
    
</template>

<script>
import DevData from '../components/DevData.vue';

export default {
  components: {
    DevData
  },

  data() {
    return {
      chave: null,
      endereco: false,
      cadastrado: false,
      msg: {error: false, mensagem: ""},
      userData: {
        id: "",
        nome: "",
        email: "",
        phone: "",
        dt_nasc: "",
        cidade: "",
        estado: ""
      },
      curriculo: {
        formacao: "",
        experiencia: "",
        idiomas: "",
        github: ""
      }
    }
  },

  methods: {
    updateCurriculo() { 
      let formData = new FormData();
      formData.append("id", this.userData.id);
      formData.append("cadastrado", this.cadastrado);
      formData.append("formacao", this.curriculo.formacao);
      formData.append("experiencia", this.curriculo.experiencia);
      formData.append("idiomas", this.curriculo.idiomas);
      formData.append("github", this.curriculo.github);

      fetch("https://localhost/vue-php-project/backEnd/crontoler.php?action=updateCurriculo", {
        body: formData,
        method: "post",
      })
      .then((resp) => resp.json())
      .then((data) => {
        this.msg.error = true; 
        this.msg.mensagem = data.message;
        this.buscaDev();
      });
      setTimeout(() => {
        (this.msg.error = false), (this.msg.mensagem = "");
      }, 2000);
    },

    buscaDev() {
      let formData = new FormData();
      formData.append("chave", this.chave);

      fetch("https://localhost/vue-php-project/backEnd/crontoler.php?action=buscaDev", {
        body: formData,
        method: "post",
      })
      .then((resp) => resp.json())
      .then((data) => { 
        if(data.error) { 
          this.endereco = false;
        } else {
          this.endereco = true;
          this.userData.id = data.user.id_dev;
          this.userData.nome = data.user.nome;
          this.userData.email = data.user.email;
          this.userData.phone = data.user.phone;
          this.userData.dt_nasc = data.user.data_nasc;
          this.userData.cidade = data.user.cidade;
          this.userData.estado = data.user.estado; 

          let formData = new FormData();
          formData.append("id", this.userData.id);
          fetch("https://localhost/vue-php-project/backEnd/crontoler.php?action=buscaCurriculo", {
            body: formData,
            method: "post",
          })
          .then((resp) => resp.json())
          .then((data) => { 
            if(data.error) { 
              this.cadastrado = false;
            } else {
              this.cadastrado = true;
              this.curriculo.formacao = data.curriculo.formacao
              this.curriculo.experiencia = data.curriculo.experiencia
              this.curriculo.idiomas = data.curriculo.idiomas
              this.curriculo.github = data.curriculo.github
            }
          });
        }
      });
    },

    checaVariaveis(){
      this.chave = localStorage.getItem("chave");
    }
  },

  created() {
    this.checaVariaveis();
    this.buscaDev();
  },
}
</script>

<style>
.view {
  height: 96vh;
}

.container {
  color: #303133;
  margin-left: 15px;
  margin-top: 15px;
}

.field {
  margin-right: 15px;
  margin-bottom: 15px;
  width: 60%;
}

.btn:hover{
  background-color: #4a4b4f;
  color: #9af995;
}

.msg{
  top: -15px;
}

@media screen and (max-width: 800px) {
  .view {
    height: auto;
  }

  .container {
    display: block;
    gap: 300px;
    padding-right: 15px;
  }

  .field {    
    width: 100%;
  }
}
</style>

