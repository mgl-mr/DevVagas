<template>
  <div class="recebidos">
    <h1>Recebidos</h1>
    <div class="msg-container msg-container-styck">
      <transition>
        <p v-show="msg.error" class="msg msg-styck">{{ msg.mensagem }}</p>
      </transition>
    </div>
    <div v-if="render.error == false">
      <p>{{render.mensagem}}</p>
    </div>
    <div v-else>
      <h2>Currículos</h2>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      chave: "",
      render: {error: false, mensagem: ""},
      msg: {error: false, mensagem: ""},
      curriculos: null,
    }
  },
  
  methods: {
    buscaCurriculos(){
      let formData = new FormData();
      formData.append("chave", this.chave);

      fetch("https://localhost/vue-php-project/backEnd/crontoler.php?action=buscaCurriculos", {
        body: formData,
        method: "post",
      })
      .then((resp) => resp.json())
      .then((data) => { console.log(data);
        if(data.error) {
          this.render.error = false;
          this.render.mensagem = data.message;
          this.curriculos = null;
        } else {
          this.render.error = true;
          /* this.id_curriculo = data.id_curriculo;
          this.vagas = data.vagas; */
        }
      })
    },

    checaVariaveis() {
      this.chave = localStorage.getItem("chave");
    }
  },

  created() {
    this.checaVariaveis();
    this.buscaCurriculos();
  }
}
</script>

<style scoped>
.recebidos {
  width: 100%;
}
</style>