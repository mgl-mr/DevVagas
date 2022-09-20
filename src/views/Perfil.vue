<template>
  <div class="container">
    <section class="field">
      <div>
        <img :src="local" alt="logo Dev Vagas" id="logo" />
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
          <p v-show="msg2.error" class="msg">{{ msg2.mensagem }}</p>
        </transition>
      </div>
        <button type="button" class="btn" @click="updateUser">Atualizar dados</button>
      </div>
    </section>

    <section class="field">
      <h1> Endereço </h1>
      
      <div class="msg-container">
        <transition>
          <p v-show="msg.error" class="msg">{{ msg.mensagem }}</p>
        </transition>
      </div>

      <div>
        <label for="cep">CEP: </label>
        <input type="text" name="cep" v-model="cep" placeholder="Digite seu CEP"/>
        <button @click="updateEndereco">Enviar</button>
      </div>
      <div v-if="localCadastrado">
        <p v-show="dev == 'false'">Logradouro: {{endereco.logradouro}}</p>
        <p v-show="dev == 'false'">Complemento: {{endereco.complemento}}</p>
        <p v-show="dev == 'false'">Bairro: {{endereco.bairro}}</p>
        <p>Cidade: {{endereco.cidade}}</p>
        <p>Estado: {{endereco.estado}}</p>
      </div>
      <div v-else>
        <p>Você ainda não registrou seu endereço</p>
        <p>Para faze-lo insira seu CEP e click em enviar</p>
      </div>
    </section>
    
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
      local: "/img/perfil/dev/userNoImage.png",
      /**** ../../backEnd/ ****/
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
      }, 2500);
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
.container {
  width: 100%;
  margin: 0 auto;
  padding: 10px;
  background-color: #3e3f42;
  color: #303133;
}

.field {
  background-color: #DFE2ED;
  padding: 10px;
  margin: 0 10px;
  height: auto;
}

img {
  width: 100px;
  height: 100px;
}

.container-input {
  display: flex;
}

.msg {
  top: -30px;
}

</style>


