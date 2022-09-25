<template>
  <div class="view">
    <div class="header">
      <h2>Perfil</h2>
    </div>
    <div class="container">
      <div class="field">
        <div class="container-img">
          <label for="imagem">
            <img :src="local" alt="Imagem de perfil" id="fotoPerfil" />
            <i class="fa-solid fa-camera camera"></i>
          </label>
          <input type="file" name="imagem" id="imagem" @change="updateFoto">
           <transition>
            <p v-show="msg3.error" class="msg">{{ msg3.mensagem }}</p>
          </transition>
        </div>
        <div class="inputs">
          <div class="container-input">
            <label for="nome">Nome</label>
            <input type="text" class="input" name="nome" v-model="user.nome">
          </div>

          <div class="container-input">
            <label for="email">Email</label>
            <input type="text" class="input" name="email" v-model="user.email">
          </div>

          <div class="container-input">
            <label for="telefone">Telefone</label>
            <input type="text" class="input" name="telefone" v-model="user.phone">
          </div>

          <div v-if="dev == 'true'">
            <div class="container-input">
              <label for="data de nascimento">Nascimento</label>
              <input type="date" class="input" name="data de nascimento" v-model="user.dtNasc">
            </div>
          </div>
          <div v-else>
            <div class="container-input">
              <label for="cnpj">CNPJ</label>
              <input type="text" class="input" name="cnpj" v-model="user.cnpj">
            </div>
          </div>
          <div class="msg-container">
          <transition>
            <p v-show="msg2.error" class="msg msg-2">{{ msg2.mensagem }}</p>
          </transition>
        </div>
          <button type="button" class="btn" @click="updateUser">Atualizar dados</button>
        </div>
      </div>

      <div class="field">
        <div class="header sub-header">
          <h2>Endereço</h2>
        </div>
        
        <div>
          <div class="msg-container">
            <transition>
              <p v-show="msg.error" class="msg">{{ msg.mensagem }}</p>
            </transition>
          </div>
          <div class="container-input">
            <label for="cep">CEP: </label>
            <input type="text" class="input input-cep" name="cep" v-model="cep" placeholder="Digite seu CEP"/>
            <button class="btn btn-cep" @click="updateEndereco">Enviar</button>
          </div>
          <div v-if="localCadastrado" class="local">
            <p v-show="dev == 'false'">Logradouro: {{endereco.logradouro}}</p>
            <p v-show="dev == 'false'">Complemento: {{endereco.complemento}}</p>
            <p v-show="dev == 'false'">Bairro: {{endereco.bairro}}</p>
            <p>Cidade: {{endereco.cidade}}</p>
            <p>Estado: {{endereco.estado}}</p>
          </div>
          <div v-else class="local">
            <p>Você ainda não registrou seu endereço</p>
            <p>Para faze-lo insira seu CEP e click em enviar</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      cep: "",
      dev: null,
      chave: null,
      localCadastrado: false,
      endereco: null,
      msg: {error: false, mensagem: ""},
      msg2: {error: false, mensagem: ""},
      msg3: {error: false, mensagem: ""},
      user: { 
        id: "",
        nome: "",
        email: "",
        phone: "",
        dtNasc: "",
        cnpj: "",
        foto: "",
        chave: ""
      },
      selectedFile: null,
      local: null,
    };
  },

  methods: {
    carregaPerfil() {
      let formData = new FormData();
      formData.append("chave", this.chave);
      formData.append("dev", this.dev);
      
      fetch("https://localhost/vue-php-project/backEnd/crontoler.php?action=buscaPerfil", {
        body: formData,
        method: "post",
      })
      .then((resp) => resp.json())
      .then((data) => {
        this.user.id = data.user.id
        this.user.nome = data.user.nome
        this.user.email = data.user.email
        this.user.phone = data.user.phone
        this.user.dtNasc = data.user.data_nasc
        this.user.cnpj = data.user.cnpj 
        this.user.foto = data.user.foto
        this.user.chave = data.user.chave
        this.local = "http://localhost/vue-php-project/backEnd/imagens/"+data.user.foto
      })
    },

    updateEndereco(){
      fetch("https://viacep.com.br/ws/"+this.cep+"/json/")
      .then((resp) => resp.json())
      .then((data) => {
        this.endereco = data;
        if (this.endereco.complemento == "" ) {
          this.endereco.complemento = "Sem complemento";
        }

        let formData = new FormData();
        formData.append("localCadastrado", this.localCadastrado);
        formData.append('id', this.user.id)
        formData.append("dev", this.dev);
        formData.append("cidade", data.localidade);
        formData.append("estado", data.uf);
        if(this.dev != 'true') {
          formData.append("logradouro", data.logradouro);
          formData.append("bairro", data.bairro);
          formData.append("complemento", this.endereco.complemento);
        }

        fetch("https://localhost/vue-php-project/backEnd/crontoler.php?action=updateEndereco", {
        body: formData,
        method: "post",
        })
        .then((resp) => resp.json())
        .then((data) => { 
          this.msg.error = true;
          this.msg.mensagem = data.message;
          this.carregaEndereco();
        })
      })
      .catch((error) => {
        this.msg.error = true;
        this.msg.mensagem = "CEP inválido";
        console.log(error);
      });

      setTimeout(() => {
        (this.msg.error = false), (this.msg.mensagem = "");
      }, 2800);
    },

    updateUser() {
      let formData = new FormData();
      formData.append("id", this.user.id);
      formData.append("dev", this.dev);
      formData.append("nome", this.user.nome);
      formData.append("email", this.user.email);
      formData.append("phone", this.user.phone);
      if(this.dev == "true") {
        formData.append("data_nascimento", this.user.dtNasc);
      } else {
        formData.append("cnpj", this.user.cnpj);
      }

      fetch("https://localhost/vue-php-project/backEnd/crontoler.php?action=updateUser", {
        body: formData,
        method: "post",
      })
      .then((resp) => resp.json())
      .then((data) => {
        this.msg2.error = true;
        this.msg2.mensagem = data.message;
        if (data.error == false) {
          this.carregaPerfil();
        }
      });
      setTimeout(() => {
        (this.msg2.error = false), (this.msg2.mensagem = "");
      }, 2000);
    },

    updateFoto(event) {
      var extensoesPermitidas = /(.jpg|.jpeg|.png)$/i;
      this.selectedFile = event.target.files[0];
      
      if(!extensoesPermitidas.exec(this.selectedFile.name)){
        this.msg3.error = true;
        this.msg3.mensagem = "Somente arquivos .jpeg ou .png"
      } else {
        let formData = new FormData();
        formData.append("dev", this.dev);
        formData.append("chave", this.chave);
        formData.append("id", this.user.id);
        formData.append("nomeAntigo", this.user.foto);
        formData.append("imagem", this.selectedFile);
        
        fetch("https://localhost/vue-php-project/backEnd/crontoler.php?action=updateImage", {
        body: formData,
        method: "post",
        })
        .then((resp) => resp.json())
        .then((data) => {
          this.msg3.error = true;
          this.msg3.mensagem = data.message;
          if(data.error == false) {
            this.local = "";
            this.local = "http://localhost/vue-php-project/backEnd/imagens/userNoImage.png";
          }
        });
       
      }

      setTimeout(() => {
        (this.msg3.error = false), (this.msg3.mensagem = "");
      }, 2000);
      
    },

    carregaEndereco() {
      let formData = new FormData();
      formData.append("chave", this.chave);
      formData.append("dev", this.dev);
      
      fetch("https://localhost/vue-php-project/backEnd/crontoler.php?action=buscaEndereco", {
        body: formData,
        method: "post",
      })
      .then((resp) => resp.json())
      .then((data) => {
        if(data.error) {
          this.localCadastrado = false;
        } else {
          this.localCadastrado = true;
          this.endereco = data.data;
        }
      })
    },

    checaVariaveis() {
      this.chave = localStorage.getItem("chave");
      this.dev = localStorage.getItem("dev");
    }
  },

  created() {
    this.checaVariaveis();
  },

  mounted() {
    this.carregaPerfil();
    this.carregaEndereco();
  }
};
</script>

<style scoped>
.view {
  height: 96vh;
}
.container {
  color: #303133;
  margin-left: 15px;
  margin-top: 15px;
}

.field {
  display: flex;
  flex-direction: column;

  margin-right: 15px;
  margin-bottom: 15px;
  width: 50%;
}

.btn:hover{
  background-color: #4a4b4f;
  color: #9af995;
}

/*** INPUTS ***/
.container-input {
  display: flex;
}

.container-input label{
  margin-top: 13px;
  margin-right: 5px;
}

/** IMAGEM DE PERFIL ***/
.container-img {
  width: 100%;
  display: flex;
  justify-content: center;  
}

.container-img #fotoPerfil {
  width: 100px;
  height: 100px;
  border-radius: 50%;
}

.container-img #imagem {
  position: fixed;
  opacity: 0;
  visibility: hidden;
}

.container-img label {
  position: relative;
  font-size: 20px;
  transition: all .5s ease;
}

.container-img .camera {
  position: absolute;
  bottom: 0;
  right: 10px;
}

.container-img #fotoPerfil:hover,
.container-img label:hover {
  cursor: pointer;
  transform: scale(1.05);
}



/*** ENDEREÇO ***/
.input-cep {
  margin-right: 10px;
}

.btn-cep {
  height: 38px;
  margin-top: 5px;
}

.local {
  margin-top: 10px;
}

.local p {
  font-family: Arial, Helvetica, sans-serif;
  font-weight: 500;
  color: #4a4b4f;
  font-size: 18px;
  margin-bottom: 5px;
}

/*** ***/
.msg {
  top: 50px;
}

.msg-2 {
  top: -12px;
}

/*** MEDIA QUERIES ***/
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


