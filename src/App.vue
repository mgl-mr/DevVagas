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
}

.container {
  display: flex;
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
  background-color: #9af995;
  border: 2px solid #dfe2ed;
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
  position: sticky;
  top: 50px;
  left: 50%;
}

.msg-container-styck {
  position: fixed;
  top: 50px;
  left: 50%;
}

.v-enter-active {
  transition: transform 2s cubic-bezier(0, 1, 1, 0);
}

.v-enter-from {
  transform: translateX(10vw);
}

.v-enter-to {
  transform: translateX(-10vw);
}
</style>
