<template>
  <Login v-if="logado == 'false'" @login="logar" :msg="msg"/>
  <div v-else class="principal"> 
    <Menu @sair="exit"/>
    <router-view/>
  </div>
</template>

<script>
  import Menu from './components/Menu';
  import Login from './components/Login';

  export default {
    name: "App",  

    components: {
      Menu,
      Login
    },

    data() {
      return {
        logado: 'false',
        dev: null,
        chave: null,
        msg: {error: false, mensagem: ""}
      }
    },

    methods: {
      logar(email, senha) {
        let formData = new FormData();
        formData.append("email", email);
        formData.append("senha", senha);
        
        fetch("https://localhost/vue-php-project/backEnd/crontoler.php?action=logar", {
          body: formData,
          method: "post",
        })
        .then((resp) => resp.json())
        .then((data) => {
          if(data.error) {
            this.msg.error = true;
            this.msg.mensagem = data.message;
          } else {
            localStorage.setItem('logado', 'true');
            localStorage.setItem('chave', data.chave);
            this.logado = 'true';
            this.chave = data.chave;
            if(data.dev) {
              localStorage.setItem('dev', 'true');
              this.dev = 'true';
            } else {
              localStorage.setItem('dev', 'false');
              this.dev = 'false';
            }
          }
        });
        setTimeout(() => {
          (this.msg.error = false), (this.msg.mensagem = "");
        }, 2000);

        this.checaVariaveis()
      },

      exit() {
        localStorage.clear();
        this.checaVariaveis();
      },

      checaVariaveis() {
        if (localStorage.logado) {
          this.logado = localStorage.getItem('logado');
        } else {
          this.logado = 'false'
        }
        if (localStorage.chave) {
          this.chave = localStorage.getItem('chave');
        } else {
          this.chave = null
        }
        if (localStorage.dev) {
          this.dev = localStorage.getItem('dev');
        } else {
          this.dev = null
        }
      }
    },

    created() {
      this.checaVariaveis();
    }
  }
</script>

<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box; 
}

body {
  background-color: #3e3f42;
}

.principal {
  display: flex;
  width: 100%;
  justify-content: flex-end;
}

.container {
  display: flex;
}

.field {
  background-color: #9af995;
  padding: 10px;
  border-radius: 15px;
  box-shadow: rgba(50, 50, 93, 0.25) 0px 30px 60px -12px inset, rgba(0, 0, 0, 0.3) 0px 18px 36px -18px inset;
  transition: all .5s ease;
}

.field:hover {
  transform: scale(1.01);
}

/** VIEWS ***/
.view {
  width: 75%;
  background-color: #dfe2ed;
  border-radius: 15px;
  margin: 10px;
}

/*** HEADER ***/
.header{
  border-bottom: 1px solid #111;
  height: auto;
  padding: 10px 0;
  font-family: Arial, Helvetica, sans-serif;
  color: #3e3f42;
  font-size: 30px;
  text-shadow: 4px 3px 0px #9af995, 9px 8px 0px rgba(0,0,0,0.15);
  letter-spacing: 2px;
}

.header h2 {
  margin-left: 15px;
}

.sub-header {
  font-size: 24px;
  text-shadow: 4px 3px 0px #dfe2ed, 9px 8px 0px rgba(0,0,0,0.15);
  text-align: center;
}

/*** AVISO ***/
.warning {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  justify-content: center;
  background-color: #dfe2ed;
  border-radius: 15px;
  width: 100%;
  height: 96vh;
}

.warning .header {
  margin-bottom: 30px;
}

.warning p{
  font-family: Arial, Helvetica, sans-serif;
  font-weight: 500;
  color: #4a4b4f;
  font-size: 18px;
  margin-bottom: 5px;
  text-align: center;
}

/*** CADS ***/
.cad-ed {
  position: absolute;
  top: 5px;
  margin-bottom: 15px;
  width: 300px;
  background-color: #dfe2ed;
  border-radius: 15px;
  padding: 15px;
  font-family: Arial;
  height: auto;
}

.container-input {
  color: #303133;
}

.cad-ed .data {
  display: flex;
}

.cad-ed .data label {
  margin-top: 5px;
}

.container-input label {
  font-weight: bold;
}

.input {
  width: 100%;
  padding: 10px;
  text-align: center;
  border-radius: 5px;
  border: 2px solid #303133;
  margin-top: 5px;
  margin-bottom: 10px;
  font-size: 12px;
  transition: all 0.5s ease-out;
  outline: 0;
}

.input:hover {
  border: 2px solid #fff;
  background-color: #fff;
}

.input:focus {
  border: 2px solid #9af995;
  background-color: #4a4b4f;
  color: #dfe2ed;
}

.btn {
  width: 100%;
  height: 30px;
  border-radius: 5px;
  border: 2px solid #303133;
  background-color: #dfe2ed;
  color: #303133;
  font-weight: bold;
  text-transform: uppercase;
  font-family: sans-serif;
  font-size: 16px;
  transition: all 0.5s ease-out;
}

.btn:hover {
  background-color: #4a4b4f;
  color: #9af995;
  cursor: pointer;
}

.cad-ed .container-btn-ed {
  position: relative;
}

.msg-ed {
  position: absolute;
  color: red;
  width: 100%;
  text-align: center;
  top: -5px;
}

/*** MENSAGEM PADRÂO ***/
.msg-container {
  position: relative;
}

.msg {
  position: absolute;
  color: red;
  width: 100%;
  text-align: center;
}

.msg-styck {
  position: fixed;
  border: 1px solid #4a4b4f;
  border-radius: 15px;
  color: red;
  font-family: Arial, Helvetica, sans-serif;
  letter-spacing: 2px;
  background-color:#dfe2ed;
  padding-top: 15px;
  width: 400px;
  height: 50px;
  margin: 0 auto;
}

.v-enter-active {
  transition: transform 1s ease;
}

.v-enter-from {
  transform: translateX(100vw);
}

.v-enter-to {
  transform: translateX(0vw);
}

/*** MEDIA QUERIES ***/
@media screen and (max-width: 800px) {
  .view {
    width: 100%;
    background-color: #dfe2ed;
    border-radius: 15px;
    margin: 10px;
    margin-left: 80px;
  }

  .msg-styck {
    letter-spacing: 0px;
    width: 300px;
    font-size: 14px;
  }
}
</style>
