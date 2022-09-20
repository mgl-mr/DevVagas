<template>
  <div>
    <div class="banner">
      <img src="/img/logo.png" alt="logo Dev Vagas" id="logo" />
      <h1>Dev Vagas</h1>
    </div>

    <div class="cadastro" v-if=cadastrar>
      <div class="cadastro-header">
        <p>Cadastrar: </p> 
        <button class="btn x" @click="cadastrar = false, dev = false, emp = false">
          <i class="fa-solid fa-backward"></i>
        </button>
      </div>
      
      <div>
        <button class="btn" @click="emp = true, dev = false">Sua empresa</button>
        <button class="btn" @click="dev = true, emp = false">Como desenvolvedor</button>
      </div>
    </div>
    <form v-else class="formLogin">
      <div class="container-input">
        <label for="email">Endereço email</label>
        <input type="text" name="email" class="input" v-model="email" placeholder="Digite seu email"/>
        <i class="fa-solid fa-envelope icon"></i>
      </div>

      <div class="container-input">
        <label for="senha">Senha</label>
        <input type="password" class="input" v-model="senha" placeholder="Digite sua senha"/>
        <i class="fa-solid fa-lock icon"></i>
      </div>

      <div class="msg-container">
        <transition>
          <p v-show="msg.error" class="msg">{{ msg.mensagem }}</p>
        </transition>
      </div>
      <button type="submit" class="btn" @click="valida">Logar</button>
      <button class="btn" @click="cadastrar = true">Cadastrar-se</button>
    </form>

    <div class="changes">
      <transition name="dev">
        <CadDev v-show="dev" />
      </transition>

      <transition name="emp">
        <CadEmp v-show="emp" />
      </transition>
    </div>
  </div>
</template>

<script>
import CadDev from './CadDev';
import CadEmp from './CadEmp'

export default {
  name: "Login",

  components: {
    CadDev,
    CadEmp
  },

  props: ["msg"],
  emits: ["login"],

  data() {
    return {
      email: "",
      senha: "",
      cadastrar: false,
      dev: false,
      emp: false 
    };
  },

  methods: {
    valida(e) {
      e.preventDefault();
      if(this.email == ""){
        this.msg.error = true;
        this.msg.mensagem = "Preencher email";

        setTimeout(() => {
            this.msg.error = false,
            this.msg.mensagem = ""
          }, 2000
        );
      } else if(this.senha == ""){
          this.msg.error = true;
          this.msg.mensagem = "Preencher senha";

          setTimeout(() => {
              this.msg.error = false,
              this.msg.mensagem = ""
            }, 2000
          );
        } else {
          this.$emit('login', this.email, this.senha);
        } 
    }
  }
}
</script>
<style scoped>

/*** FORMULÁRIO ***/
.formLogin {
  margin: 30px auto;
  width: 300px;
  background-color: #dfe2ed;
  border-radius: 15px;
  padding: 15px;
  font-family: Arial;
  height: auto;
}

.container-input {
  position: relative;
  height: auto;
  margin-bottom: 30px;
}

.input:focus + .icon {
  color: #9af995;
}

.icon {
  position: absolute;
  top: 34px;
  left: 10px;
}

.btn {
  margin-bottom: 15px;
}

.msg {
  top: -23px;
}

/*** DIV CADASTRO ***/
.cadastro {
  margin: 30px auto;
  margin-bottom: 15px;
  width: 300px;
  height: auto;
  background-color: #dfe2ed;
  border-radius: 15px;
  padding: 5px;
  font-family: Arial;
}

.cadastro p {
  width: 20%;
  margin-left: 10px;
  padding-top: 8px;
  font-size: 18px;
  color: #303133;
  font-weight: bold;
}

.cadastro div {
  display: flex;
  justify-content: space-between;
}

.cadastro div .btn {
  margin: 2px;
  font-size: 12px;
}

.cadastro .x {
  border: 0;
  padding: 0;
  width: 25px;
  font-size: 24px;
  margin-right: 15px;
  margin-top: 4px;
  transition: all .5s ease;
}

.cadastro i {
  font-size: 20px;
  margin-right: 0;
}

.cadastro .x:hover {
  background-color: #dfe2ed;
}

.cadastro .x:hover i {
  color: #9af995;
}

.changes {
  position: relative;
  margin: 0 auto;
  width: 300px;
}

/*** TRANSITIONS ***/
.dev-enter-active,
.dev-leave-active,
.emp-enter-active,
.emp-leave-active {
  transition: transform 1s ease;
}

.dev-enter-from,
.dev-leave-to {
  transform: translateX(100vw);
}

.emp-enter-from,
.emp-leave-to {
  transform: translateX(-100vw);
}

/*** BANNER ***/
.banner {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  background-color: #4a4b4f;
  width: 100%;
}

.banner #logo {
  margin: 0px;
  width: 185px;
}

.banner {
  font-family: helvetica;
  font-size: 18px;
  color: #9af995;
  line-height: 70px;
  text-align: center;
  padding: 10px;
}
</style>