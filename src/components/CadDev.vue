<template>
  <form action="#" class="cad-ed" @submit="valida($event)">
    <div class="container-input">
      <label for="nome">Nome</label>
      <input
        type="text"
        class="input"
        name="nome"
        v-model="dev.nome"
        placeholder="Nome completo"
      />
    </div>

    <div class="container-input">
      <label for="email">Email</label>
      <input
        type="text"
        class="input"
        name="email"
        v-model="dev.email"
        placeholder="Digite seu email"
      />
    </div>

    <div class="container-input">
      <label for="telefone">Telefone</label>
      <input
        type="text"
        class="input"
        name="telefone"
        v-model="dev.phone"
        placeholder="Digite seu número de celular"
        @keydown="verificaNumero"
      />
    </div>

    <div class="container-input">
      <label for="senha">Senha</label>
      <input
        type="password"
        class="input"
        name="senha"
        v-model="dev.senha"
        placeholder="Digite sua senha"
      />
    </div>

    <div class="container-input">
      <label for="confirmar senha">Confirmar senha</label>
      <input
        type="password"
        class="input"
        name="confirmar senha"
        v-model="dev.confirmaSenha"
        placeholder="Confirme sua senha"
      />
    </div>

    <div class="container-input data">
      <label for="data de nascimento">Data de nascimento</label>
      <input
        type="date"
        class="input"
        name="data de nascimento"
        v-model="dev.dtNasc"
      />
    </div>

    <div class="container-btn-ed">
      <transition>
        <p v-show="msg.error" class="msg">{{ msg.mensagem }}</p>
      </transition>
    </div>

    <button type="submit" class="btn">Cadastrar-se</button>
  </form>
</template>

<script>
export default {
  name: "CadDev",

  data() {
    return {
      msg: { error: false, mensagem: "" },
      dev: {
        nome: "",
        email: "",
        phone: "",
        senha: "",
        confirmaSenha: "",
        dtNasc: "",
      }
    };
  },

  methods: {
    valida(e) {
      e.preventDefault();
      var submit = true;
      const obj = this.dev;

      for (const campo in obj) {
        if (obj[campo] == "") {
          this.msg.error = true;
          this.msg.mensagem = "Preencher Todos os campos";
          submit = false;
        }
      }

      if (submit == true) {
        const usuario = obj.email.substring(0, obj.email.indexOf("@"));
        const dominio = obj.email.substring(
          obj.email.indexOf("@") + 1,
          obj.email.length
        );

        if (
          usuario.length >= 1 &&
          dominio.length >= 3 &&
          usuario.search("@") == -1 &&
          dominio.search("@") == -1 &&
          usuario.search(" ") == -1 &&
          dominio.search(" ") == -1 &&
          dominio.search(".") != -1 &&
          dominio.indexOf(".") >= 1 &&
          dominio.lastIndexOf(".") < dominio.length - 1
        ) {
        } else {
          this.msg.error = true;
          this.msg.mensagem = "Email inválido";
          submit = false;
        }
      }

      if (submit == true) {
        if (this.dev.phone.length != 15) {
          this.msg.error = true;
          this.msg.mensagem = "Telefone inválido";
          submit = false;
        }
      }

      if (submit == true) {
        if (this.dev.senha.length < 5 || this.dev.senha.length > 15) {
          this.msg.error = true;
          this.msg.mensagem = "Senha entre 5 a 15 caracteres";
          submit = false;
        }
      }

      if (submit == true) {
        if (this.dev.confirmaSenha != this.dev.senha) {
          this.msg.error = true;
          this.msg.mensagem = "Senhas diferente";
          submit = false;
        }
      }

      setTimeout(() => {
        (this.msg.error = false), (this.msg.mensagem = "");
      }, 2000);

      if (submit == true) {
        this.inserirDev();
      }
    },

    verificaNumero(e) {
      const tecla = e.keyCode;
      if ((tecla < 47 || tecla > 58) && tecla != 40 && tecla != 41 && tecla != 45 && tecla != 8) {
        e.preventDefault();
      }
      if (this.dev.phone.length > 14 && tecla != 8) {
        e.preventDefault();
      }
      this.dev.phone = this.dev.phone.replace(/^(\d{2})(\d)/g, "($1) $2");
      this.dev.phone = this.dev.phone.replace(/(\d{4})(\d{4})/g, "$1-$2");
    },

    inserirDev() {
      let formData = new FormData();
      formData.append("nome", this.dev.nome);
      formData.append("email", this.dev.email);
      formData.append("phone", this.dev.phone);
      formData.append("senha", this.dev.senha);
      formData.append("dtNasc", this.dev.dtNasc);

      fetch("https://localhost/vue-php-project/backEnd/crontoler.php?action=inserirDev", {
        body: formData,
        method: "post",
      })
      .then((resp) => resp.json())
      .then((data) => {
        this.msg.error = true;
        this.msg.mensagem = data.message;
        if(!data.error) {
          const obj = this.dev;
          for (const campo in obj){
            obj[campo] = "";
          }
          setTimeout(() => {
            (this.msg.error = true), (this.msg.mensagem = "Verifique seu email");
          }, 2000);
          setTimeout(() => {
            (this.msg.error = false), (this.msg.mensagem = "");
          }, 4000);
        }
      })
      setTimeout(() => {
        (this.msg.error = false), (this.msg.mensagem = "");
      }, 2000);
    }
  }
};
</script>

<style scoped>
.btn {
  margin-top: 15px;
}

.msg {
  top: -5px;
}
</style>